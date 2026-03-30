<?php
declare(strict_types=1);

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class ExterieursController extends BaseController
{
    public function index(): void
    {
        $lang = \LangService::get();
        $seo = \SeoService::forPage('espaces-exterieurs', $lang,
            'Espaces extérieurs — Villa Plaisance, Bédarrides',
            'Piscine privée 12×6m, jardin provençal, terrasses ombragées. Les espaces extérieurs de Villa Plaisance à Bédarrides.'
        );

        $jsonLd = [
            \SeoService::lodgingBusinessJsonLd(),
            \SeoService::breadcrumbJsonLd([
                ['name' => t('nav.home'), 'url' => APP_URL . '/'],
                ['name' => t('nav.exterieurs')],
            ]),
        ];

        $this->render('front/exterieurs', compact('seo', 'jsonLd', 'lang'));
    }
}
