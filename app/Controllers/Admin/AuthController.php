<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class AuthController extends AdminBaseController
{
    public function showLogin(): void
    {
        $csrf = $this->csrf();
        $flash = $this->getFlash();

        // Render login without layout
        require ROOT . '/app/Views/admin/login.php';
    }

    public function login(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/login');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $this->flash('error', 'Veuillez remplir tous les champs.');
            $this->redirect('/admin/login');
            return;
        }

        try {
            $user = \Database::fetchOne("SELECT * FROM vp_users WHERE email = ?", [$email]);
        } catch (\Throwable) {
            $this->flash('error', 'Erreur de connexion à la base de données.');
            $this->redirect('/admin/login');
            return;
        }

        if (!$user || !password_verify($password, $user['password'])) {
            $this->flash('error', 'Email ou mot de passe incorrect.');
            $this->redirect('/admin/login');
            return;
        }

        // Regenerate session
        session_regenerate_id(true);
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['admin_user_id'] = $user['id'];
        $_SESSION['admin_user_name'] = $user['name'];
        $_SESSION['admin_user_email'] = $user['email'];

        $this->redirect('/admin/dashboard');
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /admin/login');
        exit;
    }

    public function showForgotPassword(): void
    {
        $csrf = $this->csrf();
        $flash = $this->getFlash();
        require ROOT . '/app/Views/admin/forgot-password.php';
    }

    public function forgotPassword(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/forgot-password');
            return;
        }

        $email = trim($_POST['email'] ?? '');

        // Toujours afficher le même message (sécurité : ne pas révéler si l'email existe)
        $successMsg = 'Si cette adresse est associée à un compte, un email de réinitialisation a été envoyé.';

        if ($email === '') {
            $this->flash('error', 'Veuillez saisir votre adresse email.');
            $this->redirect('/admin/forgot-password');
            return;
        }

        try {
            $user = \Database::fetchOne("SELECT id, email FROM vp_users WHERE email = ?", [$email]);
        } catch (\Throwable) {
            $this->flash('success', $successMsg);
            $this->redirect('/admin/forgot-password');
            return;
        }

        if (!$user) {
            $this->flash('success', $successMsg);
            $this->redirect('/admin/forgot-password');
            return;
        }

        // Générer un token sécurisé (64 caractères hex)
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        \Database::query(
            "UPDATE vp_users SET reset_token = ?, reset_expires = ? WHERE id = ?",
            [hash('sha256', $token), $expires, $user['id']]
        );

        // Construire le lien de réinitialisation
        $resetUrl = (\defined('APP_URL') ? APP_URL : 'http://localhost:8000') . '/admin/reset-password?token=' . $token;

        // Envoyer l'email
        $to = 'jorge@canete.fr';
        $subject = 'Villa Plaisance — Réinitialisation du mot de passe';
        $body = "Bonjour,\n\n"
            . "Une demande de réinitialisation de mot de passe a été effectuée.\n\n"
            . "Cliquez sur le lien ci-dessous (valable 1 heure) :\n"
            . $resetUrl . "\n\n"
            . "Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.\n\n"
            . "— Villa Plaisance";

        $headers = "From: noreply@villaplaisance.fr\r\n"
            . "Reply-To: noreply@villaplaisance.fr\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n";

        @mail($to, $subject, $body, $headers);

        $this->flash('success', $successMsg);
        $this->redirect('/admin/forgot-password');
    }

    public function showResetPassword(): void
    {
        $token = $_GET['token'] ?? '';

        if ($token === '') {
            $this->flash('error', 'Lien invalide.');
            $this->redirect('/admin/login');
            return;
        }

        // Vérifier que le token existe et n'a pas expiré
        $hashedToken = hash('sha256', $token);
        try {
            $user = \Database::fetchOne(
                "SELECT id FROM vp_users WHERE reset_token = ? AND reset_expires > NOW()",
                [$hashedToken]
            );
        } catch (\Throwable) {
            $user = null;
        }

        if (!$user) {
            $this->flash('error', 'Ce lien a expiré ou est invalide.');
            $this->redirect('/admin/forgot-password');
            return;
        }

        $csrf = $this->csrf();
        $flash = $this->getFlash();
        require ROOT . '/app/Views/admin/reset-password.php';
    }

    public function resetPassword(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/login');
            return;
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if ($token === '' || $password === '') {
            $this->flash('error', 'Données manquantes.');
            $this->redirect('/admin/login');
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->flash('error', 'Les mots de passe ne correspondent pas.');
            $this->redirect('/admin/reset-password?token=' . urlencode($token));
            return;
        }

        if (mb_strlen($password) < 8) {
            $this->flash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            $this->redirect('/admin/reset-password?token=' . urlencode($token));
            return;
        }

        $hashedToken = hash('sha256', $token);

        try {
            $user = \Database::fetchOne(
                "SELECT id FROM vp_users WHERE reset_token = ? AND reset_expires > NOW()",
                [$hashedToken]
            );
        } catch (\Throwable) {
            $this->flash('error', 'Erreur de base de données.');
            $this->redirect('/admin/login');
            return;
        }

        if (!$user) {
            $this->flash('error', 'Ce lien a expiré ou est invalide.');
            $this->redirect('/admin/forgot-password');
            return;
        }

        // Mettre à jour le mot de passe et supprimer le token
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        \Database::query(
            "UPDATE vp_users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?",
            [$hashedPassword, $user['id']]
        );

        $this->flash('success', 'Mot de passe réinitialisé avec succès. Vous pouvez vous connecter.');
        $this->redirect('/admin/login');
    }
}
