<?php
declare(strict_types=1);

/**
 * Seed 032 — Ajouter l'avis d'Elisabeth (Norvège, avril 2026)
 * One-shot — ne pas ré-exécuter si déjà appliqué.
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

$author = 'Elisabeth';
$origin = 'Norvège';

// Vérifier qu'il n'existe pas déjà
$existing = Database::fetchOne(
    "SELECT id FROM vp_reviews WHERE author = ? AND origin = ?",
    [$author, $origin]
);

if ($existing) {
    echo "⚠️  L'avis d'Elisabeth (Norvège) existe déjà (id: {$existing['id']}). Seed annulé.\n";
    exit(0);
}

try {
    Database::insert('vp_reviews', [
        'platform'       => 'airbnb',
        'offer'          => 'bb',
        'author'         => $author,
        'origin'         => $origin,
        'content'        => 'Jeg og to venninner, sammen med hunden min, hadde et helt fortreffelig opphold hos Jorge! Han er en fantastisk vertskap som virkelig bryr seg om gjestene sine. Frokosten var helt utrolig – fersk juice, hjemmelagde marmelader, croissanter og mye mer. Jorge ga oss også gode tips om restauranter og steder å besøke i området. Huset er vakkert, bassenget nydelig, og beliggenheten er perfekt for å utforske Provence. Oppholdet overgikk alle forventninger, faktisk bedre enn å bo på hotell!',
        'rating'         => 5.0,
        'review_date'    => '2026-04-01',
        'featured'       => 1,
        'home_carousel'  => 1,
        'status'         => 'published',
    ]);
    echo "✅  Avis d'Elisabeth (Norvège) ajouté.\n";
} catch (\Throwable $e) {
    echo "❌  Erreur : " . $e->getMessage() . "\n";
}

echo "\n=== Seed 032 terminé ===\n";
