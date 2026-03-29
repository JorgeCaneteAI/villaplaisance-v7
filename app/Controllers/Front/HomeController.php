<?php
declare(strict_types=1);

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class HomeController extends BaseController
{
    public function index(): void
    {
        $lang = \LangService::get();
        $seo = \SeoService::forPage('accueil', $lang,
            'Villa Plaisance — Chambres d\'hôtes et villa de charme à Bédarrides, Provence',
            'Chambres d\'hôtes de septembre à juin, villa entière en juillet-août. Piscine privée, 4 chambres, entre Avignon et Orange. Bédarrides, Vaucluse.'
        );

        // JSON-LD
        $jsonLd = [\SeoService::lodgingBusinessJsonLd()];

        // Add FAQ JSON-LD
        $faqs = [];
        try {
            $faqs = \Database::fetchAll(
                "SELECT question, answer FROM vp_faq WHERE page_slug = 'accueil' AND lang = ? AND active = 1",
                [$lang]
            );
        } catch (\Throwable) {}
        if (!empty($faqs)) {
            $jsonLd[] = \SeoService::faqJsonLd($faqs);
        }

        // Add aggregate rating
        $reviews = [];
        try {
            $reviews = \Database::fetchAll(
                "SELECT rating FROM vp_reviews WHERE status = 'published' AND featured = 1"
            );
        } catch (\Throwable) {}
        if (!empty($reviews)) {
            $avgRating = array_sum(array_column($reviews, 'rating')) / count($reviews);
            $jsonLd[0]['aggregateRating'] = \SeoService::aggregateRatingJsonLd($avgRating, count($reviews));
        }

        $this->render('front/home', compact('seo', 'jsonLd', 'lang'));
    }

    public function notFound(): void
    {
        http_response_code(404);
        $lang = \LangService::get();
        $seo = \SeoService::forPage('404', $lang, '404 — Page introuvable', 'Cette page n\'existe pas.');
        $jsonLd = [];
        $this->render('front/404', compact('seo', 'jsonLd', 'lang'));
    }
}
