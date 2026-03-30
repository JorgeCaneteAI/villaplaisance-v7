<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class SeoFileController extends AdminBaseController
{
    public function index(): void
    {
        $files = \Database::fetchAll("SELECT * FROM vp_seo_files ORDER BY filename ASC");

        // Add sitemap info (always auto-generated)
        $sitemapInfo = [
            'id' => 0,
            'filename' => 'sitemap.xml',
            'content' => '(Auto-generated from pages and articles)',
            'auto_generate' => 1,
            'is_virtual' => true,
        ];

        $htaccessExists = file_exists(ROOT . '/public/.htaccess');

        $this->render('admin/seo-files/index', compact('files', 'sitemapInfo', 'htaccessExists'));
    }

    public function edit(int $id): void
    {
        $file = \Database::fetchOne("SELECT * FROM vp_seo_files WHERE id = ?", [$id]);
        if (!$file) {
            $this->flash('error', 'Fichier introuvable.');
            $this->redirect('/admin/seo-files');
            return;
        }

        $this->render('admin/seo-files/edit', compact('file'));
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/seo-files');
            return;
        }

        $file = \Database::fetchOne("SELECT * FROM vp_seo_files WHERE id = ?", [$id]);
        if (!$file) {
            $this->flash('error', 'Fichier introuvable.');
            $this->redirect('/admin/seo-files');
            return;
        }

        $content = $_POST['content'] ?? '';

        \Database::update('vp_seo_files', [
            'content' => $content,
        ], 'id = ?', [$id]);

        $this->flash('success', $file['filename'] . ' mis à jour.');
        $this->redirect('/admin/seo-files');
    }

    public function create(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/seo-files');
            return;
        }

        $filename = trim($_POST['filename'] ?? '');
        $content = $_POST['content'] ?? '';

        if ($filename === '') {
            $this->flash('error', 'Le nom de fichier est obligatoire.');
            $this->redirect('/admin/seo-files');
            return;
        }

        // Sanitize filename
        $filename = preg_replace('/[^a-z0-9._-]/i', '', $filename);

        $existing = \Database::fetchOne("SELECT id FROM vp_seo_files WHERE filename = ?", [$filename]);
        if ($existing) {
            $this->flash('error', 'Ce fichier existe déjà.');
            $this->redirect('/admin/seo-files');
            return;
        }

        \Database::query(
            "INSERT INTO vp_seo_files (filename, content, auto_generate) VALUES (?, ?, 0)",
            [$filename, $content]
        );

        $this->flash('success', $filename . ' créé.');
        $this->redirect('/admin/seo-files');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/seo-files');
            return;
        }

        $file = \Database::fetchOne("SELECT * FROM vp_seo_files WHERE id = ?", [$id]);
        if ($file) {
            \Database::delete('vp_seo_files', 'id = ?', [$id]);
            $this->flash('success', $file['filename'] . ' supprimé.');
        }

        $this->redirect('/admin/seo-files');
    }
}
