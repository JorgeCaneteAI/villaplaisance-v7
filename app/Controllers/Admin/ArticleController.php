<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class ArticleController extends AdminBaseController
{
    private const LANGS = ['fr', 'en', 'es'];

    public function index(): void
    {
        $type = $_GET['type'] ?? 'all';
        $articles = [];
        try {
            if ($type === 'all') {
                $articles = \Database::fetchAll(
                    "SELECT * FROM vp_articles WHERE lang = 'fr' ORDER BY type ASC, published_at DESC, created_at DESC"
                );
            } else {
                $articles = \Database::fetchAll(
                    "SELECT * FROM vp_articles WHERE type = ? AND lang = 'fr' ORDER BY published_at DESC, created_at DESC",
                    [$type]
                );
            }
        } catch (\Throwable) {}

        // Count translations per slug
        $translationCounts = [];
        try {
            $counts = \Database::fetchAll(
                "SELECT slug, COUNT(DISTINCT lang) as cnt FROM vp_articles GROUP BY slug"
            );
            foreach ($counts as $c) {
                $translationCounts[$c['slug']] = (int)$c['cnt'];
            }
        } catch (\Throwable) {}

        $csrf = $this->csrf();
        $this->render('admin/articles/index', compact('articles', 'type', 'csrf', 'translationCounts'));
    }

    public function create(): void
    {
        $csrf = $this->csrf();
        $type = $_GET['type'] ?? 'journal';
        $langs = self::LANGS;
        $langLabels = ['fr' => '🇫🇷 Français', 'en' => '🇬🇧 English', 'es' => '🇪🇸 Español'];

        // Empty articles for each lang
        $articlesByLang = [];
        foreach ($langs as $l) {
            $articlesByLang[$l] = [
                'id' => null, 'type' => $type, 'category' => '',
                'slug' => '', 'lang' => $l, 'title' => '', 'excerpt' => '',
                'content' => '[]', 'meta_title' => '', 'meta_desc' => '',
                'meta_keywords' => '', 'gso_desc' => '', 'og_image' => '',
                'status' => 'draft', 'cover_image' => '', 'published_at' => date('Y-m-d'),
            ];
        }

        $isEdit = false;
        $this->render('admin/articles/form', compact('articlesByLang', 'langs', 'langLabels', 'csrf', 'isEdit', 'type'));
    }

    public function edit(int $id): void
    {
        $article = \Database::fetchOne("SELECT * FROM vp_articles WHERE id = ?", [$id]);
        if (!$article) {
            $this->flash('error', 'Article introuvable.');
            $this->redirect('/admin/articles');
            return;
        }

        $slug = $article['slug'];
        $type = $article['type'];
        $langs = self::LANGS;
        $langLabels = ['fr' => '🇫🇷 Français', 'en' => '🇬🇧 English', 'es' => '🇪🇸 Español'];

        // Load all language versions by slug + type
        $articlesByLang = [];
        $rows = \Database::fetchAll(
            "SELECT * FROM vp_articles WHERE slug = ? AND type = ? ORDER BY lang",
            [$slug, $type]
        );
        foreach ($rows as $r) {
            $articlesByLang[$r['lang']] = $r;
        }

        // Fill missing languages with empty defaults
        foreach ($langs as $l) {
            if (!isset($articlesByLang[$l])) {
                $fr = $articlesByLang['fr'] ?? $article;
                $articlesByLang[$l] = [
                    'id' => null, 'type' => $type, 'category' => $fr['category'] ?? '',
                    'slug' => $slug, 'lang' => $l, 'title' => '', 'excerpt' => '',
                    'content' => '[]', 'meta_title' => '', 'meta_desc' => '',
                    'meta_keywords' => '', 'gso_desc' => '', 'og_image' => '',
                    'status' => $fr['status'] ?? 'draft',
                    'cover_image' => $fr['cover_image'] ?? '',
                    'published_at' => $fr['published_at'] ?? date('Y-m-d'),
                ];
            }
        }

        $isEdit = true;
        $csrf = $this->csrf();
        $this->render('admin/articles/form', compact('articlesByLang', 'langs', 'langLabels', 'csrf', 'isEdit', 'type'));
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/articles/create');
            return;
        }

        $slug = trim($_POST['slug'] ?? '');
        $frTitle = trim($_POST['title_fr'] ?? '');
        if ($slug === '' && $frTitle !== '') {
            $slug = $this->slugify($frTitle);
        }

        $type = $_POST['type'] ?? 'journal';
        $category = trim($_POST['category'] ?? '');
        $coverImage = trim($_POST['cover_image'] ?? '');
        $ogImage = trim($_POST['og_image'] ?? '');
        $status = $_POST['status'] ?? 'draft';
        $publishedAt = $_POST['published_at'] ?: date('Y-m-d');

        try {
            foreach (self::LANGS as $l) {
                $data = [
                    'type' => $type,
                    'category' => $category,
                    'slug' => $slug,
                    'lang' => $l,
                    'title' => trim($_POST["title_{$l}"] ?? ''),
                    'excerpt' => trim($_POST["excerpt_{$l}"] ?? ''),
                    'content' => $this->buildContent($l),
                    'meta_title' => trim($_POST["meta_title_{$l}"] ?? ''),
                    'meta_desc' => trim($_POST["meta_desc_{$l}"] ?? ''),
                    'meta_keywords' => trim($_POST["meta_keywords_{$l}"] ?? ''),
                    'gso_desc' => trim($_POST["gso_desc_{$l}"] ?? ''),
                    'og_image' => $ogImage,
                    'cover_image' => $coverImage,
                    'status' => $status,
                    'published_at' => $publishedAt,
                ];
                // Only create if title is not empty (at least FR must have content)
                if ($l === 'fr' || $data['title'] !== '') {
                    \Database::insert('vp_articles', $data);
                }
            }
            $this->flash('success', 'Article créé (FR/EN/ES).');
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

        $article = \Database::fetchOne("SELECT * FROM vp_articles WHERE id = ?", [$id]);
        if (!$article) {
            $this->flash('error', 'Article introuvable.');
            $this->redirect('/admin/articles');
            return;
        }

        $slug = trim($_POST['slug'] ?? '') ?: $article['slug'];
        $type = $_POST['type'] ?? $article['type'];
        $category = trim($_POST['category'] ?? '');
        $coverImage = trim($_POST['cover_image'] ?? '');
        $ogImage = trim($_POST['og_image'] ?? '');
        $status = $_POST['status'] ?? 'draft';
        $publishedAt = $_POST['published_at'] ?: date('Y-m-d');
        $now = date('Y-m-d H:i:s');

        try {
            foreach (self::LANGS as $l) {
                $data = [
                    'type' => $type,
                    'category' => $category,
                    'slug' => $slug,
                    'title' => trim($_POST["title_{$l}"] ?? ''),
                    'excerpt' => trim($_POST["excerpt_{$l}"] ?? ''),
                    'content' => $this->buildContent($l),
                    'meta_title' => trim($_POST["meta_title_{$l}"] ?? ''),
                    'meta_desc' => trim($_POST["meta_desc_{$l}"] ?? ''),
                    'meta_keywords' => trim($_POST["meta_keywords_{$l}"] ?? ''),
                    'gso_desc' => trim($_POST["gso_desc_{$l}"] ?? ''),
                    'og_image' => $ogImage,
                    'cover_image' => $coverImage,
                    'status' => $status,
                    'published_at' => $publishedAt,
                    'updated_at' => $now,
                ];

                // Check if this lang version exists
                $existing = \Database::fetchOne(
                    "SELECT id FROM vp_articles WHERE slug = ? AND type = ? AND lang = ?",
                    [$article['slug'], $article['type'], $l]
                );

                if ($existing) {
                    \Database::update('vp_articles', $data, 'id = ?', [$existing['id']]);
                } elseif ($data['title'] !== '') {
                    $data['lang'] = $l;
                    \Database::insert('vp_articles', $data);
                }
            }
            $this->flash('success', 'Article mis à jour (FR/EN/ES).');
        } catch (\Throwable $e) {
            $this->flash('error', 'Erreur : ' . $e->getMessage());
        }
        $this->redirect("/admin/articles/{$id}/edit");
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/articles');
            return;
        }

        try {
            $article = \Database::fetchOne("SELECT * FROM vp_articles WHERE id = ?", [$id]);
            if ($article) {
                // Delete all language versions
                \Database::query(
                    "DELETE FROM vp_articles WHERE slug = ? AND type = ?",
                    [$article['slug'], $article['type']]
                );
            }
            $this->flash('success', 'Article supprimé (toutes langues).');
        } catch (\Throwable $e) {
            $this->flash('error', 'Erreur : ' . $e->getMessage());
        }
        $this->redirect('/admin/articles');
    }

    private function buildContent(string $lang): string
    {
        $rawContent = trim($_POST["content_raw_{$lang}"] ?? '');
        $contentBlocks = [];
        if ($rawContent !== '') {
            $paragraphs = preg_split('/\n{2,}/', $rawContent);
            foreach ($paragraphs as $p) {
                $p = trim($p);
                if ($p === '') continue;
                if (str_starts_with($p, '## ')) {
                    $contentBlocks[] = ['type' => 'heading', 'text' => substr($p, 3)];
                } elseif (str_starts_with($p, '> ')) {
                    $contentBlocks[] = ['type' => 'quote', 'text' => substr($p, 2)];
                } elseif (str_starts_with($p, '![')) {
                    // Image: ![alt](filename.webp)
                    if (preg_match('/^!\[([^\]]*)\]\(([^)]+)\)(?:\s*(.*))?$/', $p, $m)) {
                        $contentBlocks[] = ['type' => 'image', 'alt' => $m[1], 'src' => $m[2], 'caption' => $m[3] ?? ''];
                    }
                } elseif (str_starts_with($p, '- ') || str_starts_with($p, '* ')) {
                    $items = array_map(fn($line) => trim(ltrim($line, '-* ')), explode("\n", $p));
                    $contentBlocks[] = ['type' => 'list', 'items' => array_filter($items)];
                } else {
                    $contentBlocks[] = ['type' => 'paragraph', 'text' => $p];
                }
            }
        }
        return json_encode($contentBlocks, JSON_UNESCAPED_UNICODE);
    }

    private function slugify(string $text): string
    {
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }
}
