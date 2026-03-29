<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class LivretController extends AdminBaseController
{
    public function index(): void
    {
        $type = $_GET['type'] ?? 'bb';
        $sections = [];
        try {
            $sections = \Database::fetchAll(
                "SELECT * FROM vp_livret WHERE type = ? AND lang = 'fr' ORDER BY position ASC",
                [$type]
            );
        } catch (\Throwable) {}

        $csrf = $this->csrf();
        $this->render('admin/livret/index', compact('sections', 'type', 'csrf'));
    }

    public function save(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/livret');
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $data = [
            'section_title' => trim($_POST['section_title'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'type' => $_POST['type'] ?? 'bb',
            'position' => (int)($_POST['position'] ?? 0),
            'active' => isset($_POST['active']) ? 1 : 0,
            'lang' => 'fr',
        ];

        try {
            if ($id > 0) {
                \Database::update('vp_livret', $data, 'id = ?', [$id]);
                $this->flash('success', 'Section mise à jour.');
            } else {
                \Database::insert('vp_livret', $data);
                $this->flash('success', 'Section créée.');
            }
        } catch (\Throwable $e) {
            $this->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->redirect('/admin/livret?type=' . $data['type']);
    }
}
