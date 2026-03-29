<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class ArticleController extends AdminBaseController
{
    public function index(): void
    {
        $type = $_GET['type'] ?? 'all';
        $articles = [];
        try {
            if ($type === 'all') {
                $articles = \Database::fetchAll("SELECT * FROM vp_articles ORDER BY published_at DESC, created_at DESC");
            } else {
                $articles = \Database::fetchAll(
                    "SELECT * FROM vp_articles WHERE type = ? ORDER BY published_at DESC, created_at DESC",
                    [$type]
                );
            }
        } catch (\Throwable) {}

        $csrf = $this->csrf();
        $this->render('admin/articles/index', compact('articles', 'type', 'csrf'));
    }

    public function create(): void
    {
        $csrf = $this->csrf();
        $article = [
            'id' => null, 'type' => $_GET['type'] ?? 'journal', 'category' => '',
            'slug' => '', 'lang' => 'fr', 'title' => '', 'excerpt' => '',
            'content' => '', 'meta_title' => '', 'meta_desc' => '',
            'status' => 'draft', 'cover_image' => '', 'published_at' => date('Y-m-d'),
        ];
        $this->render('admin/articles/form', compact('article', 'csrf'));
    }

    public function edit(int $id): void
    {
        $article = \Database::fetchOne("SELECT * FROM vp_articles WHERE id = ?", [$id]);
        if (!$article) {
            $this->flash('error', 'Article introuvable.');
            $this->redirect('/admin/articles');
            return;
        }
        $csrf = $this->csrf();
        $this->render('admin/articles/form', compact('article', 'csrf'));
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/articles/create');
            return;
        }

        $data = $this->getFormData();
        try {
            \Database::insert('vp_articles', $data);
            $this->flash('success', 'Article créé.');
        } catch (\Throwable $e) {
            $this->flash('error', 'Erreur : ' . $e->getMessage());
        }
        $this->redirect('/admin/articles');
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect("/admin/articles/{$id}/edit");
            return;
        }

        $data = $this->getFormData();
        $data['updated_at'] = date('Y-m-d H:i:s');

        try {
            \Database::update('vp_articles', $data, 'id = ?', [$id]);
            $this->flash('success', 'Article mis à jour.');
        } catch (\Throwable $e) {
            $this->flash('error', 'Erreur : ' . $e->getMessage());
        }
        $this->redirect('/admin/articles');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/articles');
            return;
        }

        try {
            \Database::delete('vp_articles', 'id = ?', [$id]);
            $this->flash('success', 'Article supprimé.');
        } catch (\Throwable $e) {
            $this->flash('error', 'Erreur : ' . $e->getMessage());
        }
        $this->redirect('/admin/articles');
    }

    private function getFormData(): array
    {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        if ($slug === '' && $title !== '') {
            $slug = $this->slugify($title);
        }

        // Build content as JSON blocks
        $rawContent = trim($_POST['content_raw'] ?? '');
        $contentBlocks = [];
        if ($rawContent !== '') {
            // Split by double newline for paragraphs
            $paragraphs = preg_split('/\n{2,}/', $rawContent);
            foreach ($paragraphs as $p) {
                $p = trim($p);
                if ($p === '') continue;
                if (str_starts_with($p, '## ')) {
                    $contentBlocks[] = ['type' => 'heading', 'text' => substr($p, 3)];
                } elseif (str_starts_with($p, '> ')) {
                    $contentBlocks[] = ['type' => 'quote', 'text' => substr($p, 2)];
                } else {
                    $contentBlocks[] = ['type' => 'paragraph', 'text' => $p];
                }
            }
        }

        return [
            'type' => $_POST['type'] ?? 'journal',
            'category' => trim($_POST['category'] ?? ''),
            'slug' => $slug,
            'lang' => $_POST['lang'] ?? 'fr',
            'title' => $title,
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'content' => json_encode($contentBlocks, JSON_UNESCAPED_UNICODE),
            'meta_title' => trim($_POST['meta_title'] ?? ''),
            'meta_desc' => trim($_POST['meta_desc'] ?? ''),
            'cover_image' => trim($_POST['cover_image'] ?? ''),
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => $_POST['published_at'] ?: date('Y-m-d'),
        ];
    }

    private function slugify(string $text): string
    {
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }
}
