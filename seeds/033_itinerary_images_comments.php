<?php
declare(strict_types=1);

/**
 * Seed 033 — Images sur les étapes d'itinéraire + table commentaires
 * One-shot.
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

echo "=== Migration 033 : images étapes + commentaires itinéraire ===\n";

// ── Image sur les étapes ────────────────────────────────────────────────────
$cols = Database::fetchAll("SHOW COLUMNS FROM vp_itinerary_steps LIKE 'image'");
if (!empty($cols)) {
    echo "- Colonne image existe déjà\n";
} else {
    Database::query("ALTER TABLE vp_itinerary_steps ADD COLUMN image VARCHAR(500) DEFAULT NULL AFTER description");
    echo "✅ Colonne image ajoutée sur vp_itinerary_steps\n";
}

// ── Table commentaires itinéraire ───────────────────────────────────────────
Database::query("
    CREATE TABLE IF NOT EXISTS vp_itinerary_comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        itinerary_id INT NOT NULL,
        guest_name VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (itinerary_id) REFERENCES vp_itineraries(id) ON DELETE CASCADE,
        INDEX idx_itinerary_comments (itinerary_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "✅ Table vp_itinerary_comments créée\n";

echo "=== Migration 033 terminée ===\n";
