<?php
declare(strict_types=1);

/**
 * Seed 030 — Ajouter les cover_image aux deux articles Konoha Land
 * Pré-requis : les images doivent être uploadées dans public/uploads/ via cPanel
 * One-shot — ne pas ré-exécuter si déjà appliqué.
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

$covers = [
    'naruto-provence-quand-le-manga-arrive-au-village' => 'konoha-land-vue.webp',
    'parc-spirou-naruto-konoha-land'                   => 'kyubi-unchained.webp',
];

$updated = 0;
$errors  = 0;

foreach ($covers as $slug => $image) {
    try {
        $existing = Database::fetchOne(
            "SELECT id, cover_image FROM vp_articles WHERE slug = ? AND lang = 'fr'",
            [$slug]
        );

        if (!$existing) {
            echo "⚠️  Article introuvable : {$slug}\n";
            $errors++;
            continue;
        }

        Database::query(
            "UPDATE vp_articles SET cover_image = ? WHERE slug = ? AND lang = 'fr'",
            [$image, $slug]
        );

        echo "✅  {$slug} → {$image}\n";
        $updated++;
    } catch (\Throwable $e) {
        echo "❌  {$slug} : " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=== Seed 030 terminé : {$updated} covers mises à jour, {$errors} erreurs ===\n";
