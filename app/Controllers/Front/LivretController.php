<?php
declare(strict_types=1);

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class LivretController extends BaseController
{
    public function index(): void
    {
        $lang = \LangService::get();
        $type = $_GET['type'] ?? 'bb';
        if (!in_array($type, ['bb', 'villa'], true)) {
            $type = 'bb';
        }

        $password = '';
        try {
            $row = \Database::fetchOne("SELECT setting_value FROM vp_settings WHERE setting_key = 'livret_password'");
            $password = $row['setting_value'] ?? '';
        } catch (\Throwable) {}

        // Handle POST (password check or message)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->verifyCsrf()) {
                $this->flash('error', 'Token CSRF invalide.');
                $this->redirect(\LangService::url('livret') . '?type=' . $type);
                return;
            }

            // Password submission
            if (isset($_POST['livret_password'])) {
                $submitted = trim($_POST['livret_password']);
                if ($submitted === $password) {
                    $_SESSION['livret_authenticated'] = true;
                } else {
                    $this->flash('error', t('livret.password_error'));
                }
                $this->redirect(\LangService::url('livret') . '?type=' . $type);
                return;
            }

            // Message submission
            if (isset($_POST['message'])) {
                $this->handleMessage($lang, $type);
                return;
            }
        }

        $seo = [
            'title' => t('livret.title') . ' — Villa Plaisance',
            'description' => '',
            'canonical' => '',
            'robots' => 'noindex, nofollow',
            'og' => [],
            'hreflang' => [],
        ];
        $jsonLd = [];
        $flash = $this->getFlash();
        $csrf = $this->csrf();

        // Not authenticated: show password gate
        if (empty($_SESSION['livret_authenticated'])) {
            $this->render('front/livret/password', compact('seo', 'flash', 'csrf', 'jsonLd', 'lang', 'type'));
            return;
        }

        // Authenticated: show booklet
        $sections = [];
        try {
            $sections = \Database::fetchAll(
                "SELECT * FROM vp_livret WHERE type = ? AND lang = ? AND active = 1 ORDER BY position ASC",
                [$type, $lang]
            );
        } catch (\Throwable) {}

        $this->render('front/livret/show', compact('seo', 'flash', 'csrf', 'jsonLd', 'lang', 'type', 'sections'));
    }

    private function handleMessage(string $lang, string $type): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Honeypot
        if (!empty($_POST['website'])) {
            $this->redirect(\LangService::url('livret') . '?type=' . $type);
            return;
        }

        if ($name === '' || $email === '' || $message === '') {
            $this->flash('error', t('contact.form.error'));
            $this->redirect(\LangService::url('livret') . '?type=' . $type);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash('error', t('contact.form.error'));
            $this->redirect(\LangService::url('livret') . '?type=' . $type);
            return;
        }

        try {
            \Database::insert('vp_messages', [
                'name' => $name,
                'email' => $email,
                'subject' => 'Message livret (' . $type . ')',
                'message' => $message,
                'lang' => $lang,
                'source' => 'livret',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
                'created_at' => date('Y-m-d H:i:s'),
                'read_at' => null,
            ]);
            $this->flash('success', t('livret.message_success'));
        } catch (\Throwable) {
            $this->flash('error', t('contact.form.error'));
        }

        $this->redirect(\LangService::url('livret') . '?type=' . $type);
    }
}
