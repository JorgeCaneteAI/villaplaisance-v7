<?php
declare(strict_types=1);

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class ContactController extends BaseController
{
    public function index(): void
    {
        $lang = \LangService::get();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSubmit($lang);
            return;
        }

        $seo = \SeoService::forPage('contact', $lang,
            'Contact — Villa Plaisance, Bédarrides',
            'Contactez Villa Plaisance pour organiser votre séjour en Provence. Chambres d\'hôtes ou villa entière à Bédarrides.'
        );

        $flash = $this->getFlash();
        $csrf = $this->csrf();
        $jsonLd = [
            \SeoService::lodgingBusinessJsonLd(),
            \SeoService::breadcrumbJsonLd([
                ['name' => t('nav.home'), 'url' => APP_URL . '/'],
                ['name' => t('nav.contact')],
            ]),
        ];

        $this->render('front/contact', compact('seo', 'flash', 'csrf', 'jsonLd', 'lang'));
    }

    private function handleSubmit(string $lang): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect(\LangService::url('contact'));
            return;
        }

        // Honeypot
        if (!empty($_POST['website'])) {
            $this->redirect(\LangService::url('contact'));
            return;
        }

        // Rate limiting : 5 messages max par heure
        if (!$this->checkRateLimit('contact', 5, 3600)) {
            $this->flash('error', 'Trop de messages envoyés. Réessayez plus tard.');
            $this->redirect(\LangService::url('contact'));
            return;
        }

        $name    = strip_tags(trim($_POST['name'] ?? ''));
        $email   = trim($_POST['email'] ?? '');
        $subject = strip_tags(trim($_POST['subject'] ?? ''));
        $message = strip_tags(trim($_POST['message'] ?? ''));

        // Anti-spam : bloquer les messages contenant des URLs
        if (preg_match('#https?://#i', $message) || preg_match('#https?://#i', $subject) || preg_match('#https?://#i', $name)) {
            $this->flash('error', 'Les liens ne sont pas autorisés dans le formulaire de contact.');
            $this->redirect(\LangService::url('contact'));
            return;
        }

        if ($name === '' || $email === '' || $message === '') {
            $this->flash('error', 'Veuillez remplir tous les champs obligatoires.');
            $this->redirect(\LangService::url('contact'));
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash('error', 'Adresse email invalide.');
            $this->redirect(\LangService::url('contact'));
            return;
        }

        try {
            \Database::insert('vp_messages', [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'lang' => $lang,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
                'created_at' => date('Y-m-d H:i:s'),
                'read_at' => null,
            ]);

            $this->sendNotification($name, $email, $subject, $message, $lang);

            $this->flash('success', t('contact.form.success'));
        } catch (\Throwable) {
            $this->flash('error', t('contact.form.error'));
        }

        $this->redirect(\LangService::url('contact'));
    }

    private function sendNotification(string $name, string $email, string $subject, string $message, string $lang): void
    {
        $to = 'contact@villaplaisance.fr';
        $cc = 'jorge@canete.fr';

        $cleanSubject = $subject !== '' ? $subject : '(sans objet)';
        $mailSubject = mb_encode_mimeheader('Villa Plaisance — Nouveau message : ' . $cleanSubject, 'UTF-8');

        $body = "Nouveau message reçu via le formulaire de contact.\n\n"
            . "Nom    : " . $name . "\n"
            . "Email  : " . $email . "\n"
            . "Sujet  : " . $cleanSubject . "\n"
            . "Langue : " . $lang . "\n"
            . "IP     : " . ($_SERVER['REMOTE_ADDR'] ?? '') . "\n"
            . "Date   : " . date('Y-m-d H:i:s') . "\n\n"
            . "------------------------------------------------------------\n"
            . $message . "\n"
            . "------------------------------------------------------------\n\n"
            . "Répondre directement à ce mail enverra au visiteur (" . $email . ").\n"
            . "Voir aussi dans l'admin : " . (\defined('APP_URL') ? APP_URL : '') . "/admin/messages";

        $replyToName = preg_replace('/[\r\n]+/', ' ', $name);
        $headers = "From: Villa Plaisance <contact@villaplaisance.fr>\r\n"
            . "Reply-To: " . $replyToName . " <" . $email . ">\r\n"
            . "Cc: " . $cc . "\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n"
            . "X-Mailer: Villa Plaisance Site\r\n";

        @mail($to, $mailSubject, $body, $headers);
    }
}
