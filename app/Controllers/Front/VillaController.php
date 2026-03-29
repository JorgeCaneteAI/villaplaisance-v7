<?php
declare(strict_types=1);

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class VillaController extends BaseController
{
    public function index(): void
    {
        $lang = \LangService::get();
        $seo = \SeoService::forPage('location-villa-provence', $lang,
            'Location villa Provence — Villa Plaisance, Bédarrides',
            '4 chambres, piscine privée 12×6m, jardin provençal. Villa entière en exclusivité juillet-août, jusqu\'à 10 personnes, entre Avignon et Orange.'
        );

        $jsonLd = [\SeoService::vacationRentalJsonLd()];

        $faqs = [];
        try {
            $faqs = \Database::fetchAll(
                "SELECT question, answer FROM vp_faq WHERE page_slug = 'location-villa-provence' AND lang = ? AND active = 1",
                [$lang]
            );
        } catch (\Throwable) {}
        if (!empty($faqs)) {
            $jsonLd[] = \SeoService::faqJsonLd($faqs);
        }

        $this->render('front/villa', compact('seo', 'jsonLd', 'lang'));
    }
}
