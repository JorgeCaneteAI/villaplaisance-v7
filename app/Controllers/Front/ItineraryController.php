<?php
declare(strict_types=1);

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class ItineraryController extends BaseController
{
    public function show(string $slug): void
    {
        $lang = \LangService::get();

        $itinerary = null;
        try {
            $itinerary = \Database::fetchOne(
                "SELECT * FROM vp_itineraries WHERE slug = ? AND status = 'active'",
                [$slug]
            );
        } catch (\Throwable) {}

        if (!$itinerary) {
            http_response_code(404);
            $seo = \SeoService::forPage('404', $lang, '404 — Itinéraire introuvable', '');
            $jsonLd = [];
            $this->render('front/404', compact('seo', 'jsonLd', 'lang'));
            return;
        }

        $steps = [];
        try {
            $steps = \Database::fetchAll(
                "SELECT * FROM vp_itinerary_steps WHERE itinerary_id = ? ORDER BY position ASC",
                [$itinerary['id']]
            );
        } catch (\Throwable) {}

        // SEO — indexable pour du contenu géolocalisé
        $seoTitle = 'Itinéraire Provence : ' . $itinerary['guest_name'] . ' — Villa Plaisance';
        $seoDesc  = !empty($itinerary['intro_text'])
            ? mb_substr($itinerary['intro_text'], 0, 160)
            : 'Itinéraire de visite en Provence préparé par Villa Plaisance, chambres d\'hôtes à Bédarrides.';

        $seo = [
            'title' => $seoTitle,
            'description' => $seoDesc,
            'canonical' => APP_URL . '/itineraire/' . $slug,
            'og' => [
                'title' => $seoTitle,
                'description' => $seoDesc,
                'image' => APP_URL . '/assets/img/og-default.webp',
                'url' => APP_URL . '/itineraire/' . $slug,
                'type' => 'article',
                'locale' => \SeoService::locale($lang),
            ],
            'hreflang' => [],
        ];

        $jsonLd = [];

        $this->render('front/itinerary', compact('seo', 'itinerary', 'steps', 'jsonLd', 'lang'));
    }
}
