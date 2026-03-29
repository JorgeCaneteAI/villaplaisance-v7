<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class PieceController extends AdminBaseController
{
    public function index(): void
    {
        $pieces = \Database::fetchAll(
            "SELECT * FROM vp_pieces WHERE lang = 'fr' ORDER BY offer ASC, position ASC"
        );
        $csrf = $this->csrf();
        $this->render('admin/pieces/index', compact('pieces', 'csrf'));
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

        $maxPos = \Database::fetchOne("SELECT MAX(position) as m FROM vp_pieces WHERE lang = 'fr'");
        $pos = ($maxPos['m'] ?? 0) + 1;

        \Database::insert('vp_pieces', [
            'name' => 'Nouvelle chambre',
            'offer' => $_POST['offer'] ?? 'bb',
            'type' => $_POST['type'] ?? 'chambre',
            'position' => $pos,
            'lang' => 'fr',
        ]);

        $this->flash('success', 'Chambre/espace ajouté');
        $this->redirect('/admin/pieces');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide');
            $this->redirect('/admin/pieces');
            return;
        }

        \Database::delete('vp_pieces', 'id = ?', [$id]);
        $this->flash('success', 'Supprimé');
        $this->redirect('/admin/pieces');
    }
}
