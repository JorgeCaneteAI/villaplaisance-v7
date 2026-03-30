<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class ProximiteController extends AdminBaseController
{
    public function save(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->back();
            return;
        }

        $fields = [
            'name' => trim($_POST['name'] ?? ''),
            'distance' => trim($_POST['distance'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ];

        // Shared fields only from FR
        if (isset($_POST['category'])) {
            $fields['category'] = $_POST['category'];
        }
        if (isset($_POST['distance_min'])) {
            $fields['distance_min'] = (int)$_POST['distance_min'];
        }

        \Database::update('vp_proximites', $fields, 'id = ?', [$id]);
        $this->flash('success', 'Proximité mise à jour.');
        $this->back();
    }

    public function create(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->back();
            return;
        }

        $max = \Database::fetchOne("SELECT MAX(position) as mx FROM vp_proximites WHERE lang = 'fr'");
        $position = ($max['mx'] ?? 0) + 1;

        foreach (SUPPORTED_LANGS as $lang) {
            \Database::query(
                "INSERT INTO vp_proximites (name, distance, distance_min, description, category, lang, position) VALUES (?, ?, ?, ?, ?, ?, ?)",
                ['', '', 0, '', 'ville', $lang, $position]
            );
        }

        $this->flash('success', 'Proximité ajoutée (FR/EN/ES).');
        $this->back();
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->back();
            return;
        }

        $item = \Database::fetchOne("SELECT * FROM vp_proximites WHERE id = ?", [$id]);
        if ($item) {
            \Database::query("DELETE FROM vp_proximites WHERE position = ?", [$item['position']]);
        }

        $this->flash('success', 'Proximité supprimée (toutes langues).');
        $this->back();
    }

    private function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '/admin/sections';
        $this->redirect(parse_url($ref, PHP_URL_PATH));
    }
}
