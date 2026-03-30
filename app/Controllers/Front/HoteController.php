<?php
declare(strict_types=1);

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class HoteController extends BaseController
{
    public function index(): void
    {
        $lang = \LangService::get();

        $profile = \Database::fetchOne(
            "SELECT * FROM vp_host_profile WHERE lang = ? AND active = 1",
            [$lang]
        );

        if (!$profile) {
            $profile = \Database::fetchOne(
                "SELECT * FROM vp_host_profile WHERE lang = 'fr' AND active = 1"
            );
        }

        // Fetch CV blocks
        $blocks = [];
        try {
            $blocks = \Database::fetchAll(
                "SELECT * FROM vp_host_blocks WHERE active = 1 ORDER BY position ASC"
            );
        } catch (\Throwable) {}

        // Fetch reviews that mention Jorge/Georges/George
        $reviews = [];
        try {
            $reviews = \Database::fetchAll(
                "SELECT * FROM vp_reviews WHERE status = 'published' AND (content LIKE '%Jorge%' OR content LIKE '%Georges%' OR content LIKE '%George%') ORDER BY review_date DESC LIMIT 6"
            );
        } catch (\Throwable) {}

        $seo = \SeoService::forPage('votre-hote', $lang,
            ($profile['name'] ?? 'Jorge') . ' — Votre hôte à Villa Plaisance',
            $profile['intro'] ?? 'Découvrez votre hôte à Villa Plaisance, chambres d\'hôtes et villa de charme à Bédarrides.'
        );

        $jsonLd = [];

        $this->render('front/hote', compact('profile', 'blocks', 'reviews', 'seo', 'jsonLd', 'lang'));
    }
}
