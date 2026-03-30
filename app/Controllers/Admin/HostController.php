<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class HostController extends AdminBaseController
{
    public function index(): void
    {
        $profiles = \Database::fetchAll("SELECT * FROM vp_host_profile ORDER BY FIELD(lang, 'fr', 'en', 'es')");
        $blocks = \Database::fetchAll("SELECT * FROM vp_host_blocks ORDER BY position ASC");
        $csrf = $this->csrf();
        $this->render('admin/host/index', compact('profiles', 'blocks', 'csrf'));
    }

    public function save(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/host');
        }

        $langs = ['fr', 'en', 'es'];
        foreach ($langs as $lang) {
            $data = [
                'name' => trim($_POST["name_{$lang}"] ?? ''),
                'subtitle' => trim($_POST["subtitle_{$lang}"] ?? ''),
                'intro' => trim($_POST["intro_{$lang}"] ?? ''),
                'quote' => trim($_POST["quote_{$lang}"] ?? ''),
            ];

            if (!empty($_POST['photo'])) {
                $data['photo'] = trim($_POST['photo']);
            }

            $existing = \Database::fetchOne("SELECT id FROM vp_host_profile WHERE lang = ?", [$lang]);
            if ($existing) {
                \Database::update('vp_host_profile', $data, 'id = ?', [(int)$existing['id']]);
            } else {
                $data['lang'] = $lang;
                \Database::insert('vp_host_profile', $data);
            }
        }

        $this->flash('success', 'Profil hôte mis à jour.');
        $this->redirect('/admin/host');
    }

    public function createBlock(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/host');
        }

        $maxPos = \Database::fetchOne("SELECT MAX(position) as m FROM vp_host_blocks");
        $pos = ($maxPos['m'] ?? 0) + 1;

        \Database::insert('vp_host_blocks', [
            'title_fr' => trim($_POST['title_fr'] ?? 'Nouveau bloc'),
            'title_en' => trim($_POST['title_en'] ?? 'New block'),
            'title_es' => trim($_POST['title_es'] ?? 'Nuevo bloque'),
            'content_fr' => trim($_POST['content_fr'] ?? ''),
            'content_en' => trim($_POST['content_en'] ?? ''),
            'content_es' => trim($_POST['content_es'] ?? ''),
            'image' => trim($_POST['block_image'] ?? ''),
            'position' => $pos,
        ]);

        $this->flash('success', 'Bloc ajouté.');
        $this->redirect('/admin/host');
    }

    public function updateBlock(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/host');
        }

        \Database::update('vp_host_blocks', [
            'title_fr' => trim($_POST['title_fr'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'title_es' => trim($_POST['title_es'] ?? ''),
            'content_fr' => trim($_POST['content_fr'] ?? ''),
            'content_en' => trim($_POST['content_en'] ?? ''),
            'content_es' => trim($_POST['content_es'] ?? ''),
            'image' => trim($_POST['block_image'] ?? ''),
        ], 'id = ?', [$id]);

        $this->flash('success', 'Bloc mis à jour.');
        $this->redirect('/admin/host');
    }

    public function deleteBlock(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/host');
        }

        \Database::delete('vp_host_blocks', 'id = ?', [$id]);
        $this->flash('success', 'Bloc supprimé.');
        $this->redirect('/admin/host');
    }

    public function moveBlock(int $id): void
    {
        $direction = $_POST['direction'] ?? 'up';
        $block = \Database::fetchOne("SELECT * FROM vp_host_blocks WHERE id = ?", [$id]);
        if (!$block) {
            $this->redirect('/admin/host');
        }

        if ($direction === 'up') {
            $swap = \Database::fetchOne("SELECT * FROM vp_host_blocks WHERE position < ? ORDER BY position DESC LIMIT 1", [$block['position']]);
        } else {
            $swap = \Database::fetchOne("SELECT * FROM vp_host_blocks WHERE position > ? ORDER BY position ASC LIMIT 1", [$block['position']]);
        }

        if ($swap) {
            \Database::update('vp_host_blocks', ['position' => $swap['position']], 'id = ?', [(int)$block['id']]);
            \Database::update('vp_host_blocks', ['position' => $block['position']], 'id = ?', [(int)$swap['id']]);
        }

        $this->redirect('/admin/host');
    }
}
