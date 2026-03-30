<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class StatController extends AdminBaseController
{
    public function save(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->back();
            return;
        }

        $fields = [
            'value' => trim($_POST['value'] ?? ''),
            'label' => trim($_POST['label'] ?? ''),
            'sublabel' => trim($_POST['sublabel'] ?? ''),
        ];

        \Database::update('vp_stats', $fields, 'id = ?', [$id]);
        $this->flash('success', 'Stat mise à jour.');
        $this->back();
    }

    public function create(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->back();
            return;
        }

        $max = \Database::fetchOne("SELECT MAX(position) as mx FROM vp_stats WHERE lang = 'fr'");
        $position = ($max['mx'] ?? 0) + 1;

        foreach (SUPPORTED_LANGS as $lang) {
            \Database::query(
                "INSERT INTO vp_stats (value, label, sublabel, icon, lang, position) VALUES (?, ?, ?, ?, ?, ?)",
                ['', '', '', '', $lang, $position]
            );
        }

        $this->flash('success', 'Stat ajoutée (FR/EN/ES).');
        $this->back();
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->back();
            return;
        }

        $stat = \Database::fetchOne("SELECT * FROM vp_stats WHERE id = ?", [$id]);
        if ($stat) {
            \Database::query("DELETE FROM vp_stats WHERE position = ?", [$stat['position']]);
        }

        $this->flash('success', 'Stat supprimée (toutes langues).');
        $this->back();
    }

    private function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '/admin/sections';
        $this->redirect(parse_url($ref, PHP_URL_PATH));
    }
}
