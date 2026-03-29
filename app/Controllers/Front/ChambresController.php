<?php
declare(strict_types=1);

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class ChambresController extends BaseController
{
    public function index(): void
    {
        $lang = \LangService::get();
        $seo = \SeoService::forPage('chambres-d-hotes', $lang,
            'Chambres d\'hôtes à Bédarrides — Villa Plaisance, Provence',
            'Deux chambres climatisées avec petit-déjeuner maison et piscine partagée. De septembre à juin, entre Avignon et Orange.'
        );

        $jsonLd = [\SeoService::bedAndBreakfastJsonLd()];

        $faqs = [];
        try {
            $faqs = \Database::fetchAll(
                "SELECT question, answer FROM vp_faq WHERE page_slug = 'chambres-d-hotes' AND lang = ? AND active = 1",
                [$lang]
            );
        } catch (\Throwable) {}
        if (!empty($faqs)) {
            $jsonLd[] = \SeoService::faqJsonLd($faqs);
        }

        $this->render('front/chambres', compact('seo', 'jsonLd', 'lang'));
    }
}
