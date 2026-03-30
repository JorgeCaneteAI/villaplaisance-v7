<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class LivretController extends AdminBaseController
{
    public function index(): void
    {
        $type = $_GET['type'] ?? 'bb';
        if (!in_array($type, ['bb', 'villa'], true)) $type = 'bb';

        // Load all 3 languages, grouped by position
        $allSections = [];
        try {
            $rows = \Database::fetchAll(
                "SELECT * FROM vp_livret WHERE type = ? ORDER BY position ASC, FIELD(lang, 'fr', 'en', 'es')",
                [$type]
            );
            foreach ($rows as $row) {
                $allSections[$row['position']][$row['lang']] = $row;
            }
        } catch (\Throwable) {}

        $password = '';
        try {
            $row = \Database::fetchOne("SELECT setting_value FROM vp_settings WHERE setting_key = 'livret_password'");
            $password = $row['setting_value'] ?? '';
        } catch (\Throwable) {}

        $csrf = $this->csrf();
        $this->render('admin/livret/index', compact('allSections', 'type', 'password', 'csrf'));
    }

    public function save(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/livret');
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $type = $_POST['type'] ?? 'bb';
        $lang = $_POST['lang'] ?? 'fr';
        $data = [
            'section_title' => trim($_POST['section_title'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'type' => $type,
            'position' => (int)($_POST['position'] ?? 0),
            'active' => isset($_POST['active']) ? 1 : 0,
            'lang' => $lang,
        ];

        try {
            if ($id > 0) {
                \Database::update('vp_livret', $data, 'id = ?', [$id]);
                $this->flash('success', 'Section mise à jour.');
            } else {
                // New section: create for all 3 languages
                $position = $data['position'];
                if ($position === 0) {
                    $max = \Database::fetchOne(
                        "SELECT MAX(position) as mx FROM vp_livret WHERE type = ?",
                        [$type]
                    );
                    $position = (int)($max['mx'] ?? 0) + 1;
                }

                foreach (['fr', 'en', 'es'] as $l) {
                    \Database::insert('vp_livret', [
                        'type' => $type,
                        'section_title' => $l === $lang ? $data['section_title'] : '',
                        'content' => $l === $lang ? $data['content'] : '',
                        'position' => $position,
                        'active' => $data['active'],
                        'lang' => $l,
                    ]);
                }
                $this->flash('success', 'Section créée (FR/EN/ES).');
            }
        } catch (\Throwable $e) {
            $this->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->redirect('/admin/livret?type=' . $type . '&lang=' . $lang);
    }

    public function savePassword(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/livret');
            return;
        }

        $password = trim($_POST['livret_password'] ?? '');
        $type = $_POST['type'] ?? 'bb';
        $lang = $_POST['lang'] ?? 'fr';

        try {
            $exists = \Database::fetchOne("SELECT id FROM vp_settings WHERE setting_key = 'livret_password'");
            if ($exists) {
                \Database::update('vp_settings', ['setting_value' => $password], "setting_key = 'livret_password'");
            } else {
                \Database::insert('vp_settings', ['setting_key' => 'livret_password', 'setting_value' => $password]);
            }
            $this->flash('success', 'Code d\'accès mis à jour.');
        } catch (\Throwable $e) {
            $this->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->redirect('/admin/livret?type=' . $type . '&lang=' . $lang);
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/livret');
            return;
        }

        try {
            $section = \Database::fetchOne("SELECT type, position FROM vp_livret WHERE id = ?", [$id]);
            if ($section) {
                // Delete across all languages
                \Database::delete('vp_livret', 'type = ? AND position = ?', [$section['type'], $section['position']]);
                $this->flash('success', 'Section supprimée (toutes langues).');
            }
        } catch (\Throwable $e) {
            $this->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $type = $_POST['type'] ?? 'bb';
        $lang = $_POST['lang'] ?? 'fr';
        $this->redirect('/admin/livret?type=' . $type . '&lang=' . $lang);
    }
}
