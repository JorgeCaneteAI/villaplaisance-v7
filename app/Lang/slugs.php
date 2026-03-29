<?php
declare(strict_types=1);

// Maps: page_key => localized slug (used in URLs)
// FR slugs are the canonical reference (used directly in Router)
return [
    'fr' => [
        'accueil' => '/',
        'chambres-d-hotes' => 'chambres-d-hotes',
        'location-villa-provence' => 'location-villa-provence',
        'espaces-exterieurs' => 'espaces-exterieurs',
        'journal' => 'journal',
        'sur-place' => 'sur-place',
        'contact' => 'contact',
        'mentions-legales' => 'mentions-legales',
        'politique-confidentialite' => 'politique-confidentialite',
        'plan-du-site' => 'plan-du-site',
    ],
    'en' => [
        'bed-and-breakfast' => 'chambres-d-hotes',
        'villa-rental-provence' => 'location-villa-provence',
        'outdoor-spaces' => 'espaces-exterieurs',
        'journal' => 'journal',
        'nearby' => 'sur-place',
        'contact' => 'contact',
        'legal-notice' => 'mentions-legales',
        'privacy-policy' => 'politique-confidentialite',
        'sitemap' => 'plan-du-site',
    ],
    'es' => [
        'habitaciones' => 'chambres-d-hotes',
        'villa-provenza' => 'location-villa-provence',
        'espacios-exteriores' => 'espaces-exterieurs',
        'diario' => 'journal',
        'alrededores' => 'sur-place',
        'contacto' => 'contact',
        'aviso-legal' => 'mentions-legales',
        'politica-privacidad' => 'politique-confidentialite',
        'mapa-del-sitio' => 'plan-du-site',
    ],
    'de' => [
        'fruehstueckspension' => 'chambres-d-hotes',
        'villa-provence' => 'location-villa-provence',
        'aussenanlagen' => 'espaces-exterieurs',
        'journal' => 'journal',
        'vor-ort' => 'sur-place',
        'kontakt' => 'contact',
        'impressum' => 'mentions-legales',
        'datenschutz' => 'politique-confidentialite',
        'seitenplan' => 'plan-du-site',
    ],
];
