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
        'content'        => 'Avec deux amies et mon chien, nous avons passé un séjour absolument merveilleux chez Jorge ! C\'est un hôte fantastique qui se soucie vraiment de ses invités. Le petit-déjeuner était incroyable — jus frais, confitures maison, croissants et bien plus encore. Jorge nous a aussi donné d\'excellents conseils sur les restaurants et les endroits à visiter dans la région. La maison est magnifique, la piscine superbe, et l\'emplacement parfait pour explorer la Provence. Le séjour a dépassé toutes nos attentes, c\'était même mieux que de séjourner à l\'hôtel !',
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
