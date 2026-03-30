<?php
declare(strict_types=1);

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class LegalController extends BaseController
{
    public function mentions(): void
    {
        $lang = \LangService::get();
        $seo = \SeoService::forPage('mentions-legales', $lang,
            'Mentions légales — Villa Plaisance',
            'Mentions légales du site Villa Plaisance, chambres d\'hôtes et villa à Bédarrides, Provence.'
        );
        $jsonLd = [
            \SeoService::breadcrumbJsonLd([
                ['name' => t('nav.home'), 'url' => APP_URL . '/'],
                ['name' => t('footer.mentions')],
            ]),
        ];
        $this->render('front/mentions-legales', compact('seo', 'jsonLd', 'lang'));
    }

    public function confidentialite(): void
    {
        $lang = \LangService::get();
        $seo = \SeoService::forPage('politique-confidentialite', $lang,
            'Politique de confidentialité — Villa Plaisance',
            'Politique de confidentialité et protection des données personnelles de Villa Plaisance.'
        );
        $jsonLd = [
            \SeoService::breadcrumbJsonLd([
                ['name' => t('nav.home'), 'url' => APP_URL . '/'],
                ['name' => t('footer.confidentialite')],
            ]),
        ];
        $this->render('front/politique-confidentialite', compact('seo', 'jsonLd', 'lang'));
    }

    public function planDuSite(): void
    {
        $lang = \LangService::get();
        $seo = \SeoService::forPage('plan-du-site', $lang,
            'Plan du site — Villa Plaisance',
            'Plan du site Villa Plaisance. Toutes les pages du site.'
        );
        $jsonLd = [
            \SeoService::breadcrumbJsonLd([
                ['name' => t('nav.home'), 'url' => APP_URL . '/'],
                ['name' => t('footer.plan')],
            ]),
        ];
        $this->render('front/plan-du-site', compact('seo', 'jsonLd', 'lang'));
    }
}
