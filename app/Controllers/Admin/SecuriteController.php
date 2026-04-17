<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

/**
 * Gestion des appareils de confiance (trusted devices) pour l'admin.
 * Permet de lister et révoquer les appareils qui ont coché
 * "Faire confiance à cet appareil" lors de la saisie du PIN.
 */
class SecuriteController extends AdminBaseController
{
    public function index(): void
    {
        $userId = (int) ($_SESSION['admin_user_id'] ?? 0);
        $devices = \Database::fetchAll(
            "SELECT * FROM vp_trusted_devices
             WHERE user_id = ? AND expires_at > NOW()
             ORDER BY last_used DESC",
            [$userId]
        );
        $this->render('admin/securite', ['devices' => $devices]);
    }

    public function revoke(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/securite');
            return;
        }
        $userId = (int) ($_SESSION['admin_user_id'] ?? 0);
        \Database::delete('vp_trusted_devices', 'id = ? AND user_id = ?', [$id, $userId]);
        $this->flash('success', 'Appareil révoqué.');
        $this->redirect('/admin/securite');
    }
}
