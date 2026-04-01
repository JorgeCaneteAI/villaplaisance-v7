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

        $comments = [];
        try {
            $comments = \Database::fetchAll(
                "SELECT * FROM vp_itinerary_comments WHERE itinerary_id = ? ORDER BY created_at ASC",
                [$itinerary['id']]
            );
        } catch (\Throwable) {}

        // SEO
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
        $csrf = $this->csrf();

        $this->render('front/itinerary', compact('seo', 'itinerary', 'steps', 'comments', 'jsonLd', 'lang', 'csrf'));
    }

    public function comment(string $slug): void
    {
        $itinerary = \Database::fetchOne(
            "SELECT * FROM vp_itineraries WHERE slug = ? AND status = 'active'",
            [$slug]
        );

        if (!$itinerary || !$this->verifyCsrf()) {
            header('Location: /itineraire/' . $slug);
            exit;
        }

        // Honeypot
        if (!empty($_POST['website'])) {
            header('Location: /itineraire/' . $slug . '#comments');
            exit;
        }

        // Rate limiting : 3 commentaires max par heure
        if (!$this->checkRateLimit('itinerary_comment', 3, 3600)) {
            header('Location: /itineraire/' . $slug . '#comments');
            exit;
        }

        $name    = strip_tags(trim($_POST['guest_name'] ?? ''));
        $message = strip_tags(trim($_POST['message'] ?? ''));

        // Anti-spam : bloquer les URLs
        if (preg_match('#https?://#i', $message) || preg_match('#https?://#i', $name)) {
            header('Location: /itineraire/' . $slug . '#comments');
            exit;
        }

        if ($name !== '' && $message !== '') {
            \Database::insert('vp_itinerary_comments', [
                'itinerary_id' => $itinerary['id'],
                'guest_name'   => mb_substr($name, 0, 200),
                'message'      => mb_substr($message, 0, 2000),
            ]);
        }

        header('Location: /itineraire/' . $slug . '#comments');
        exit;
    }
}
