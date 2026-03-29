<?php
declare(strict_types=1);

class SeoService
{
    public static function forPage(string $slug, string $lang, string $fallbackTitle = '', string $fallbackDesc = ''): array
    {
        // Try to get SEO data from database
        $seo = null;
        try {
            $seo = Database::fetchOne(
                "SELECT * FROM vp_pages WHERE slug = ? AND lang = ?",
                [$slug, $lang]
            );
        } catch (\Throwable $e) {
            // DB not available yet, use fallbacks
        }

        $title = $seo['meta_title'] ?? $fallbackTitle;
        $description = $seo['meta_desc'] ?? $fallbackDesc;
        $canonical = self::canonical($slug, $lang);

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'og' => [
                'title' => $seo['og_title'] ?? $title,
                'description' => $seo['og_desc'] ?? $description,
                'image' => $seo['og_image'] ?? APP_URL . '/assets/img/og-default.webp',
                'url' => $canonical,
                'type' => 'website',
                'locale' => self::locale($lang),
            ],
            'hreflang' => self::hreflang($slug),
            'jsonld' => $seo['jsonld'] ?? null,
        ];
    }

    public static function canonical(string $slug, string $lang): string
    {
        $base = APP_ENV === 'production' ? 'https://v1.villaplaisance.fr' : APP_URL;
        if ($slug === 'accueil' && $lang === 'fr') {
            return $base . '/';
        }
        return $base . LangService::url($slug, $lang);
    }

    public static function hreflang(string $page): array
    {
        $links = [];
        $base = APP_ENV === 'production' ? 'https://v1.villaplaisance.fr' : APP_URL;
        foreach (SUPPORTED_LANGS as $lang) {
            $url = $base . LangService::url($page, $lang);
            $links[] = ['lang' => $lang, 'url' => $url];
        }
        // x-default = fr
        $links[] = ['lang' => 'x-default', 'url' => $base . LangService::url($page, 'fr')];
        return $links;
    }

    public static function locale(string $lang): string
    {
        return match ($lang) {
            'fr' => 'fr_FR',
            'en' => 'en_GB',
            'es' => 'es_ES',
            'de' => 'de_DE',
            default => 'fr_FR',
        };
    }

    public static function lodgingBusinessJsonLd(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LodgingBusiness',
            'name' => 'Villa Plaisance',
            'description' => 'Chambres d\'hôtes et villa de charme à Bédarrides, au cœur du Triangle d\'Or provençal.',
            'url' => APP_URL,
            'telephone' => '+33 6 00 00 00 00',
            'email' => 'contact@villaplaisance.fr',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Bédarrides',
                'addressLocality' => 'Bédarrides',
                'postalCode' => '84370',
                'addressRegion' => 'Vaucluse',
                'addressCountry' => 'FR',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 44.0386,
                'longitude' => 4.8972,
            ],
            'image' => APP_URL . '/assets/img/og-default.webp',
            'priceRange' => '€€',
            'starRating' => [
                '@type' => 'Rating',
                'ratingValue' => '5',
            ],
        ];
    }

    public static function bedAndBreakfastJsonLd(): array
    {
        $base = self::lodgingBusinessJsonLd();
        $base['@type'] = 'BedAndBreakfast';
        $base['amenityFeature'] = [
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Piscine partagée', 'value' => true],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Petit-déjeuner inclus', 'value' => true],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Climatisation', 'value' => true],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'WiFi gratuit', 'value' => true],
        ];
        return $base;
    }

    public static function vacationRentalJsonLd(): array
    {
        $base = self::lodgingBusinessJsonLd();
        $base['@type'] = 'VacationRental';
        $base['numberOfRooms'] = 4;
        $base['occupancy'] = [
            '@type' => 'QuantitativeValue',
            'maxValue' => 10,
        ];
        $base['amenityFeature'] = [
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Piscine privée 12×6m', 'value' => true],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Cuisine équipée', 'value' => true],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Jardin provençal', 'value' => true],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Climatisation', 'value' => true],
        ];
        return $base;
    }

    public static function faqJsonLd(array $faqs): array
    {
        $items = [];
        foreach ($faqs as $faq) {
            $items[] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ];
        }
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $items,
        ];
    }

    public static function blogPostingJsonLd(array $article): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $article['title'],
            'description' => $article['excerpt'] ?? '',
            'author' => [
                '@type' => 'Organization',
                'name' => 'Villa Plaisance',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Villa Plaisance',
                'url' => APP_URL,
            ],
            'datePublished' => $article['published_at'] ?? $article['created_at'] ?? '',
            'dateModified' => $article['updated_at'] ?? $article['created_at'] ?? '',
            'image' => $article['cover_image'] ?? APP_URL . '/assets/img/og-default.webp',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => APP_URL . '/journal/' . ($article['slug'] ?? ''),
            ],
        ];
    }

    public static function breadcrumbJsonLd(array $items): array
    {
        $list = [];
        foreach ($items as $i => $item) {
            $list[] = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ];
        }
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $list,
        ];
    }

    public static function aggregateRatingJsonLd(float $rating, int $count): array
    {
        return [
            '@type' => 'AggregateRating',
            'ratingValue' => number_format($rating, 1),
            'reviewCount' => $count,
            'bestRating' => '5',
        ];
    }
}
