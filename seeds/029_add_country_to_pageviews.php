<?php
declare(strict_types=1);

/**
 * Seed 029 — Ajout colonne country à vp_pageviews
 * One-shot — ne pas ré-exécuter si déjà appliqué.
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

echo "=== Migration 029 : colonne country ===\n";

// Vérifier si la colonne existe déjà
$cols = Database::fetchAll("SHOW COLUMNS FROM vp_pageviews LIKE 'country'");
if (!empty($cols)) {
    echo "- Colonne country existe déjà, rien à faire.\n";
} else {
    Database::query("ALTER TABLE vp_pageviews ADD COLUMN country VARCHAR(2) DEFAULT NULL AFTER lang");
    Database::query("ALTER TABLE vp_pageviews ADD INDEX idx_country (country)");
    echo "✅ Colonne country ajoutée + index créé\n";
}

echo "=== Migration 029 terminée ===\n";
