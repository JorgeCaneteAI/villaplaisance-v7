<?php
declare(strict_types=1);

/**
 * Seed 031 — Ajout coordonnées GPS aux étapes d'itinéraire + données Elisabeth-J
 * One-shot — ne pas ré-exécuter si déjà appliqué.
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

echo "=== Migration 031 : coordonnées GPS étapes ===\n";

// Ajout colonnes lat/lng
$cols = Database::fetchAll("SHOW COLUMNS FROM vp_itinerary_steps LIKE 'lat'");
if (!empty($cols)) {
    echo "- Colonnes lat/lng existent déjà\n";
} else {
    Database::query("ALTER TABLE vp_itinerary_steps ADD COLUMN lat DECIMAL(10,6) DEFAULT NULL AFTER description");
    Database::query("ALTER TABLE vp_itinerary_steps ADD COLUMN lng DECIMAL(10,6) DEFAULT NULL AFTER lat");
    echo "✅ Colonnes lat/lng ajoutées\n";
}

// Mise à jour coordonnées Elisabeth-J
$itinerary = Database::fetchOne("SELECT id FROM vp_itineraries WHERE slug = 'elisabeth-j'");
if (!$itinerary) {
    echo "- Itinéraire elisabeth-j introuvable, coordonnées non mises à jour\n";
} else {
    $coords = [
        'Départ Villa Plaisance'        => [44.0393, 4.8963],
        'Château La Gardine'            => [44.0533, 4.8361],
        'Pont du Gard'                  => [43.9475, 4.5356],
        'Musée Haribo, Uzès'            => [44.0118, 4.4197],
        'Centre historique d\'Uzès'     => [44.0123, 4.4214],
        'Arrivée à Alès'               => [44.1258, 4.0817],
    ];

    foreach ($coords as $title => $c) {
        Database::query(
            "UPDATE vp_itinerary_steps SET lat = ?, lng = ? WHERE itinerary_id = ? AND title = ?",
            [$c[0], $c[1], $itinerary['id'], $title]
        );
    }
    echo "✅ Coordonnées mises à jour pour " . count($coords) . " étapes\n";
}

echo "=== Migration 031 terminée ===\n";
