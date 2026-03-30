<?php
declare(strict_types=1);

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class SurPlaceController extends BaseController
{
    public function index(): void
    {
        $lang = \LangService::get();
        $seo = \SeoService::forPage('sur-place', $lang,
            'Sur place — Adresses et recommandations autour de Bédarrides',
            'Restaurants, commerces, sites à visiter, activités enfants. Nos recommandations autour de Villa Plaisance à Bédarrides.'
        );

        $articles = [];
        try {
            $articles = \Database::fetchAll(
                "SELECT * FROM vp_articles WHERE type = 'sur-place' AND lang = ? AND status = 'published' ORDER BY published_at DESC",
                [$lang]
            );
        } catch (\Throwable) {}

        $categories = array_unique(array_column($articles, 'category'));
        $jsonLd = [
            \SeoService::breadcrumbJsonLd([
                ['name' => t('nav.home'), 'url' => APP_URL . '/'],
                ['name' => t('nav.surplace')],
            ]),
        ];

        $this->render('front/surplace', compact('seo', 'articles', 'categories', 'jsonLd', 'lang'));
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
            $seo = \SeoService::forPage('404', $lang, '404 — Fiche introuvable', '');
            $jsonLd = [];
            $this->render('front/404', compact('seo', 'jsonLd', 'lang'));
            return;
        }

        $seo = [
            'title' => $article['meta_title'] ?: $article['title'],
            'description' => $article['meta_desc'] ?: ($article['excerpt'] ?? ''),
            'canonical' => \SeoService::canonical('sur-place/' . $slug, $lang),
            'og' => [
                'title' => $article['meta_title'] ?: $article['title'],
                'description' => $article['meta_desc'] ?: ($article['excerpt'] ?? ''),
                'image' => $article['og_image'] ?: (APP_URL . '/assets/img/og-default.webp'),
                'url' => \SeoService::canonical('sur-place/' . $slug, $lang),
                'type' => 'article',
                'locale' => \SeoService::locale($lang),
            ],
            'hreflang' => \SeoService::hreflang('sur-place/' . $slug),
        ];

        $jsonLd = [
            \SeoService::blogPostingJsonLd($article),
            \SeoService::breadcrumbJsonLd([
                ['name' => t('nav.home'), 'url' => APP_URL . '/'],
                ['name' => t('nav.surplace'), 'url' => APP_URL . '/sur-place/'],
                ['name' => $article['title']],
            ]),
        ];

        $contentBlocks = json_decode($article['content'] ?? '[]', true) ?: [];

        $this->render('front/article', compact('seo', 'article', 'contentBlocks', 'jsonLd', 'lang'));
    }
}
