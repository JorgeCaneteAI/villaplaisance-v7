<?php
declare(strict_types=1);

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class JournalController extends BaseController
{
    public function index(): void
    {
        $lang = \LangService::get();
        $seo = \SeoService::forPage('journal', $lang,
            'Journal — Villa Plaisance, Provence',
            'Récits, conseils et regards sur la Provence. Le journal de Villa Plaisance à Bédarrides, entre Avignon et Orange.'
        );

        $articles = [];
        try {
            $articles = \Database::fetchAll(
                "SELECT * FROM vp_articles WHERE type = 'journal' AND lang = ? AND status = 'published' ORDER BY published_at DESC",
                [$lang]
            );
        } catch (\Throwable) {}

        $categories = array_unique(array_column($articles, 'category'));
        $jsonLd = [];

        $this->render('front/journal', compact('seo', 'articles', 'categories', 'jsonLd', 'lang'));
    }

    public function show(string $slug): void
    {
        $lang = \LangService::get();

        $article = null;
        try {
            $article = \Database::fetchOne(
                "SELECT * FROM vp_articles WHERE slug = ? AND lang = ? AND status = 'published'",
                [$slug, $lang]
            );
        } catch (\Throwable) {}

        if (!$article) {
            http_response_code(404);
            $seo = \SeoService::forPage('404', $lang, '404 — Article introuvable', '');
            $jsonLd = [];
            $this->render('front/404', compact('seo', 'jsonLd', 'lang'));
            return;
        }

        $seo = [
            'title' => $article['meta_title'] ?: $article['title'],
            'description' => $article['meta_desc'] ?: ($article['excerpt'] ?? ''),
            'canonical' => \SeoService::canonical('journal/' . $slug, $lang),
            'og' => [
                'title' => $article['meta_title'] ?: $article['title'],
                'description' => $article['meta_desc'] ?: ($article['excerpt'] ?? ''),
                'image' => $article['og_image'] ?: (APP_URL . '/assets/img/og-default.webp'),
                'url' => \SeoService::canonical('journal/' . $slug, $lang),
                'type' => 'article',
                'locale' => \SeoService::locale($lang),
            ],
            'hreflang' => \SeoService::hreflang('journal/' . $slug),
        ];

        $jsonLd = [
            \SeoService::blogPostingJsonLd($article),
            \SeoService::breadcrumbJsonLd([
                ['name' => t('nav.home'), 'url' => APP_URL . '/'],
                ['name' => t('nav.journal'), 'url' => APP_URL . '/journal/'],
                ['name' => $article['title']],
            ]),
        ];

        // Parse content (stored as JSON with blocks)
        $contentBlocks = json_decode($article['content'] ?? '[]', true) ?: [];

        $this->render('front/article', compact('seo', 'article', 'contentBlocks', 'jsonLd', 'lang'));
    }
}
