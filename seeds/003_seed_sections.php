<?php
declare(strict_types=1);

/**
 * Seed sections CMS — Blocs éditables pour chaque page
 * Usage: php seeds/003_seed_sections.php
 */

define('ROOT', dirname(__DIR__));
require ROOT . '/config.php';

echo "=== Seed Sections CMS V7 ===\n\n";

Database::query("DELETE FROM vp_sections");

$sections = [

    // ===== ACCUEIL =====
    ['accueil', 'fr', 'hero', 'Hero accueil', json_encode([
        'title' => 'Villa Plaisance',
        'subtitle' => 'Chambres d\'hôtes et villa de charme à Bédarrides',
        'cta_text' => 'Découvrir la maison',
        'cta_url' => '/chambres-d-hotes',
        'image' => 'hero.webp',
        'image_alt' => 'Villa Plaisance vue piscine et cyprès sous le ciel de Provence',
    ], JSON_UNESCAPED_UNICODE), 1],

    ['accueil', 'fr', 'prose', 'Identité', json_encode([
        'heading' => 'Une maison, deux façons d\'y séjourner',
        'text' => 'Villa Plaisance est une maison provençale ouverte aux voyageurs, à Bédarrides, entre Avignon et Orange. De septembre à juin, nous accueillons en chambres d\'hôtes, avec petit-déjeuner maison et piscine partagée. En juillet et août, la villa entière se loue en exclusivité : quatre chambres, une piscine privée de 12 mètres sur 6, un jardin sous les oliviers. Le lieu est calme. Le village est vivant. La campagne est à pied, le TGV à quinze minutes.',
        'image' => 'identite.webp',
        'image_alt' => 'Reflet de Villa Plaisance dans la piscine',
    ], JSON_UNESCAPED_UNICODE), 2],

    ['accueil', 'fr', 'stats', 'Chiffres clés', '{}', 3],

    ['accueil', 'fr', 'prose', 'Offre Chambres d\'hôtes', json_encode([
        'heading' => 'Chambres d\'hôtes — de septembre à juin',
        'text' => 'Deux chambres climatisées, petit-déjeuner maison avec des produits locaux, piscine partagée avec les hôtes. Un accueil personnel, des conseils sur mesure.',
        'image' => 'chambre-verte.webp',
        'image_alt' => 'Chambre Verte — Villa Plaisance',
    ], JSON_UNESCAPED_UNICODE), 4],

    ['accueil', 'fr', 'prose', 'Offre Villa entière', json_encode([
        'heading' => 'La Villa en exclusivité — juillet et août',
        'text' => 'Quatre chambres, piscine privée 12×6m clôturée, cuisine entièrement équipée, jardin provençal. Jusqu\'à dix personnes, en totale autonomie.',
        'image' => 'piscine.webp',
        'image_alt' => 'Piscine privée 12×6m — Villa Plaisance',
    ], JSON_UNESCAPED_UNICODE), 5],

    ['accueil', 'fr', 'territoire', 'Triangle d\'Or', json_encode([
        'heading' => 'Au cœur du Triangle d\'Or',
    ], JSON_UNESCAPED_UNICODE), 6],

    ['accueil', 'fr', 'avis', 'Avis clients', json_encode([
        'heading' => 'Ce qu\'en disent nos hôtes',
        'limit' => 4,
    ], JSON_UNESCAPED_UNICODE), 7],

    ['accueil', 'fr', 'articles', 'Derniers articles', json_encode([
        'heading' => 'Journal',
        'type' => 'journal',
        'limit' => 3,
    ], JSON_UNESCAPED_UNICODE), 8],

    ['accueil', 'fr', 'faq', 'FAQ Accueil', json_encode([
        'heading' => 'Questions fréquentes',
        'page_slug' => 'accueil',
    ], JSON_UNESCAPED_UNICODE), 9],

    ['accueil', 'fr', 'cta', 'CTA Contact', json_encode([
        'heading' => 'Envie de séjourner chez nous ?',
        'text' => 'Contactez-nous pour organiser votre séjour en Provence.',
        'button_text' => 'Nous écrire',
        'button_url' => '/contact',
        'dark' => true,
    ], JSON_UNESCAPED_UNICODE), 10],

    // ===== CHAMBRES D'HÔTES =====
    ['chambres-d-hotes', 'fr', 'hero', 'Hero Chambres', json_encode([
        'title' => 'Chambres d\'hôtes',
        'subtitle' => 'Deux chambres, de septembre à juin',
        'image' => 'chambres-hero.webp',
        'image_alt' => 'Chambres d\'hôtes Villa Plaisance',
    ], JSON_UNESCAPED_UNICODE), 1],

    ['chambres-d-hotes', 'fr', 'prose', 'Intro Chambres', json_encode([
        'heading' => 'Séjourner en chambres d\'hôtes',
        'text' => 'De septembre à juin, Villa Plaisance ouvre deux chambres aux voyageurs. Le petit-déjeuner est préparé chaque matin avec des produits locaux. La piscine est partagée avec les hôtes. L\'accueil est personnel, les conseils aussi.',
        'lead' => true,
    ], JSON_UNESCAPED_UNICODE), 2],

    ['chambres-d-hotes', 'fr', 'cartes', 'Chambres BB', json_encode([
        'offer' => 'bb',
    ], JSON_UNESCAPED_UNICODE), 3],

    ['chambres-d-hotes', 'fr', 'petit-dejeuner', 'Petit-déjeuner', json_encode([
        'heading' => 'Petit-déjeuner maison',
        'text' => 'Chaque matin, le petit-déjeuner est préparé avec des produits locaux et de saison. Confitures maison, fruits frais, pain de boulanger, fromages et charcuteries du terroir. Servi en terrasse quand le temps le permet.',
        'image' => 'petit-dejeuner.webp',
    ], JSON_UNESCAPED_UNICODE), 4],

    ['chambres-d-hotes', 'fr', 'faq', 'FAQ Chambres', json_encode([
        'heading' => 'Questions fréquentes — Chambres d\'hôtes',
        'page_slug' => 'chambres-d-hotes',
    ], JSON_UNESCAPED_UNICODE), 5],

    ['chambres-d-hotes', 'fr', 'cta', 'CTA Chambres', json_encode([
        'heading' => 'Envie de séjourner chez nous ?',
        'text' => 'Contactez-nous pour organiser votre séjour en Provence.',
        'button_text' => 'Nous écrire',
        'button_url' => '/contact',
        'dark' => true,
    ], JSON_UNESCAPED_UNICODE), 6],

    // ===== VILLA =====
    ['location-villa-provence', 'fr', 'hero', 'Hero Villa', json_encode([
        'title' => 'La Villa en exclusivité',
        'subtitle' => 'Quatre chambres, juillet et août',
        'image' => 'villa-hero.webp',
        'image_alt' => 'Villa Plaisance en exclusivité — piscine privée et jardin',
    ], JSON_UNESCAPED_UNICODE), 1],

    ['location-villa-provence', 'fr', 'prose', 'Intro Villa', json_encode([
        'heading' => 'Toute la maison pour vous',
        'text' => 'En juillet et août, Villa Plaisance se loue en exclusivité. Quatre chambres, une piscine privée clôturée de 12 mètres sur 6, une cuisine entièrement équipée, un jardin provençal. Jusqu\'à dix personnes. La gestion est autonome, les clés sont à vous.',
        'lead' => true,
    ], JSON_UNESCAPED_UNICODE), 2],

    ['location-villa-provence', 'fr', 'cartes', 'Chambres Villa', json_encode([
        'offer' => 'villa',
    ], JSON_UNESCAPED_UNICODE), 3],

    ['location-villa-provence', 'fr', 'piscine', 'Piscine', json_encode([
        'heading' => 'Piscine privée',
        'text' => 'Piscine privée de 12 mètres sur 6, clôturée et sécurisée. Ouverte de mi-mai à fin septembre. Transats, parasols, douche extérieure. La piscine est réservée exclusivement aux locataires de la villa.',
        'image' => 'piscine-villa.webp',
    ], JSON_UNESCAPED_UNICODE), 4],

    ['location-villa-provence', 'fr', 'faq', 'FAQ Villa', json_encode([
        'heading' => 'Questions fréquentes — Villa entière',
        'page_slug' => 'location-villa-provence',
    ], JSON_UNESCAPED_UNICODE), 5],

    ['location-villa-provence', 'fr', 'cta', 'CTA Villa', json_encode([
        'heading' => 'Envie de séjourner chez nous ?',
        'button_text' => 'Nous écrire',
        'button_url' => '/contact',
        'dark' => true,
    ], JSON_UNESCAPED_UNICODE), 6],

    // ===== ESPACES EXTÉRIEURS =====
    ['espaces-exterieurs', 'fr', 'hero', 'Hero Extérieurs', json_encode([
        'title' => 'Espaces extérieurs',
        'subtitle' => 'Jardin, piscine, terrasses',
        'image' => 'exterieurs-hero.webp',
        'image_alt' => 'Jardin provençal de Villa Plaisance',
    ], JSON_UNESCAPED_UNICODE), 1],

    ['espaces-exterieurs', 'fr', 'prose', 'Intro Extérieurs', json_encode([
        'heading' => 'Dehors, ici, c\'est encore chez vous',
        'text' => 'Le jardin de Villa Plaisance est un prolongement naturel de la maison. Oliviers centenaires, lavande, romarin. La piscine de 12 mètres sur 6, les terrasses ombragées, le potager en été. Un espace où l\'on passe plus de temps qu\'à l\'intérieur.',
        'lead' => true,
    ], JSON_UNESCAPED_UNICODE), 2],

    ['espaces-exterieurs', 'fr', 'galerie', 'Galerie Extérieurs', json_encode([
        'images' => [
            ['src' => 'jardin-1.webp', 'alt' => 'Jardin provençal Villa Plaisance'],
            ['src' => 'piscine-2.webp', 'alt' => 'Piscine 12×6m'],
            ['src' => 'terrasse-1.webp', 'alt' => 'Terrasse ombragée'],
            ['src' => 'oliviers-1.webp', 'alt' => 'Oliviers centenaires'],
            ['src' => 'potager-1.webp', 'alt' => 'Potager d\'été'],
            ['src' => 'nuit-1.webp', 'alt' => 'Villa de nuit'],
        ],
    ], JSON_UNESCAPED_UNICODE), 3],

    ['espaces-exterieurs', 'fr', 'cta', 'CTA Extérieurs', json_encode([
        'heading' => 'Envie de séjourner chez nous ?',
        'button_text' => 'Nous écrire',
        'button_url' => '/contact',
        'dark' => true,
    ], JSON_UNESCAPED_UNICODE), 4],

    // ===== CONTACT =====
    ['contact', 'fr', 'hero', 'Hero Contact', json_encode([
        'title' => 'Contact',
        'subtitle' => 'Une question ? Écrivez-nous.',
        'compact' => true,
    ], JSON_UNESCAPED_UNICODE), 1],

    // ===== JOURNAL =====
    ['journal', 'fr', 'hero', 'Hero Journal', json_encode([
        'title' => 'Journal',
        'subtitle' => 'Récits, conseils et regards sur la Provence',
        'compact' => true,
    ], JSON_UNESCAPED_UNICODE), 1],

    // ===== SUR PLACE =====
    ['sur-place', 'fr', 'hero', 'Hero Sur place', json_encode([
        'title' => 'Sur place',
        'subtitle' => 'Nos adresses et recommandations autour de Bédarrides',
        'compact' => true,
    ], JSON_UNESCAPED_UNICODE), 1],
];

foreach ($sections as $s) {
    Database::insert('vp_sections', [
        'page_slug' => $s[0],
        'lang' => $s[1],
        'block_type' => $s[2],
        'title' => $s[3],
        'content' => $s[4],
        'position' => $s[5],
        'active' => 1,
    ]);
}

echo "✅ " . count($sections) . " sections créées\n";
echo "\n=== Seed sections terminé ===\n";
