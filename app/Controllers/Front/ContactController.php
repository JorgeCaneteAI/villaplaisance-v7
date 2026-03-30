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

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Honeypot
        if (!empty($_POST['website'])) {
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
            $this->flash('success', t('contact.form.success'));
        } catch (\Throwable) {
            $this->flash('error', t('contact.form.error'));
        }

        $this->redirect(\LangService::url('contact'));
    }
}
