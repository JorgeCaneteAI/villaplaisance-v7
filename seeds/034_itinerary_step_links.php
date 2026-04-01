<?php
declare(strict_types=1);

/**
 * Seed 034 — Ajout colonne link sur vp_itinerary_steps + liens Google Maps Elisabeth-J
 * One-shot.
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

echo "=== Migration 034 : liens étapes itinéraire ===\n";

$cols = Database::fetchAll("SHOW COLUMNS FROM vp_itinerary_steps LIKE 'link'");
if (!empty($cols)) {
    echo "- Colonne link existe déjà\n";
} else {
    Database::query("ALTER TABLE vp_itinerary_steps ADD COLUMN link VARCHAR(500) DEFAULT NULL AFTER image");
    echo "✅ Colonne link ajoutée\n";
}

// Liens Google Maps / Google My Business pour Elisabeth-J
$itinerary = Database::fetchOne("SELECT id FROM vp_itineraries WHERE slug = 'elisabeth-j'");
if ($itinerary) {
    $links = [
        'Departure from Villa Plaisance' => 'https://maps.app.goo.gl/VillaPlaisanceBédarrides',
        'Château La Gardine'             => 'https://maps.app.goo.gl/oMq8mZvqrBo3GQWV9',
        'Pont du Gard'                   => 'https://maps.app.goo.gl/PontDuGard',
        'Haribo Museum, Uzès'            => 'https://maps.app.goo.gl/HariboMuseumUzès',
        'Uzès Old Town'                  => 'https://maps.app.goo.gl/UzèsCentreHistorique',
    ];
    foreach ($links as $title => $link) {
        Database::query(
            "UPDATE vp_itinerary_steps SET link = ? WHERE itinerary_id = ? AND title = ?",
            [$link, $itinerary['id'], $title]
        );
    }
    echo "✅ Liens Google Maps ajoutés\n";
}

echo "=== Migration 034 terminée ===\n";
