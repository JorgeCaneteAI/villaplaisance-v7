<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class PieceController extends AdminBaseController
{
    public function index(): void
    {
        $langs = SUPPORTED_LANGS;

        // Load pieces for all languages
        $piecesByLang = [];
        foreach ($langs as $l) {
            $piecesByLang[$l] = \Database::fetchAll(
                "SELECT * FROM vp_pieces WHERE lang = ? ORDER BY offer ASC, position ASC",
                [$l]
            );
        }

        // FR pieces are the reference
        $pieces = $piecesByLang['fr'] ?? [];

        // Index by lang + offer + position for matching
        $pieceIndex = [];
        foreach ($langs as $l) {
            foreach ($piecesByLang[$l] ?? [] as $p) {
                $pieceIndex[$l][$p['offer'] . ':' . $p['position']] = $p;
            }
        }

        $csrf = $this->csrf();
        $langLabels = ['fr' => "\u{1F1EB}\u{1F1F7} Français", 'en' => "\u{1F1EC}\u{1F1E7} English", 'es' => "\u{1F1EA}\u{1F1F8} Español"];

        $this->render('admin/pieces/index', compact('pieces', 'piecesByLang', 'pieceIndex', 'langs', 'langLabels', 'csrf'));
    }

    public function save(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide');
            $this->redirect('/admin/pieces');
            return;
        }

        $fields = [
            'name' => trim($_POST['name'] ?? ''),
            'sous_titre' => trim($_POST['sous_titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'equip' => trim($_POST['equip'] ?? ''),
            'note' => trim($_POST['note'] ?? ''),
            'offer' => $_POST['offer'] ?? 'bb',
            'type' => $_POST['type'] ?? 'chambre',
            'image' => trim($_POST['image'] ?? ''),
            'images' => $_POST['images'] ?? null,
        ];

        \Database::update('vp_pieces', $fields, 'id = ?', [$id]);
        $this->flash('success', 'Chambre/espace mis à jour');

        // Redirect back to referrer if from sections page
        $ref = $_SERVER['HTTP_REFERER'] ?? '';
        if (str_contains($ref, '/admin/sections/page/')) {
            $this->redirect(parse_url($ref, PHP_URL_PATH));
        } else {
            $this->redirect('/admin/pieces');
        }
    }

    public function create(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide');
            $this->redirect('/admin/pieces');
            return;
        }

        $offer = $_POST['offer'] ?? 'bb';
        $type = $_POST['type'] ?? 'chambre';
        $maxPos = \Database::fetchOne("SELECT MAX(position) as m FROM vp_pieces WHERE lang = 'fr' AND offer = ?", [$offer]);
        $pos = ($maxPos['m'] ?? 0) + 1;

        foreach (SUPPORTED_LANGS as $lang) {
            \Database::insert('vp_pieces', [
                'name' => 'Nouvelle chambre',
                'offer' => $offer,
                'type' => $type,
                'position' => $pos,
                'lang' => $lang,
            ]);
        }

        $this->flash('success', 'Chambre/espace ajouté (FR/EN/ES)');
        $this->redirect('/admin/pieces');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide');
            $this->redirect('/admin/pieces');
            return;
        }

        $piece = \Database::fetchOne("SELECT * FROM vp_pieces WHERE id = ?", [$id]);
        if ($piece) {
            \Database::query(
                "DELETE FROM vp_pieces WHERE offer = ? AND position = ?",
                [$piece['offer'], $piece['position']]
            );
        }
        $this->flash('success', 'Supprimé (toutes langues)');
        $this->redirect('/admin/pieces');
    }
}
