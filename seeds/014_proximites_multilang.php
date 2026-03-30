<?php
declare(strict_types=1);

/**
 * Add lang column to vp_proximites and duplicate to EN/ES
 * Usage: php seeds/014_proximites_multilang.php
 */

define('ROOT', dirname(__DIR__));
require ROOT . '/config.php';

echo "=== Seed: Multilingual proximites ===\n\n";

// Add lang column if missing
$cols = Database::fetchAll("SHOW COLUMNS FROM vp_proximites LIKE 'lang'");
if (empty($cols)) {
    Database::query("ALTER TABLE vp_proximites ADD COLUMN lang VARCHAR(5) NOT NULL DEFAULT 'fr' AFTER category");
    echo "Added lang column\n";
}

// Add position column if missing
$posCols = Database::fetchAll("SHOW COLUMNS FROM vp_proximites LIKE 'position'");
if (empty($posCols)) {
    Database::query("ALTER TABLE vp_proximites ADD COLUMN position INT NOT NULL DEFAULT 0 AFTER lang");
    echo "Added position column\n";
    // Set positions from distance_min order
    $rows = Database::fetchAll("SELECT id FROM vp_proximites WHERE lang = 'fr' ORDER BY distance_min ASC");
    $pos = 1;
    foreach ($rows as $r) {
        Database::query("UPDATE vp_proximites SET position = ? WHERE id = ?", [$pos, $r['id']]);
        $pos++;
    }
    echo "Set positions from distance order\n";
}

Database::query("UPDATE vp_proximites SET lang = 'fr' WHERE lang = '' OR lang = 'fr'");

$frRows = Database::fetchAll("SELECT * FROM vp_proximites WHERE lang = 'fr' ORDER BY position");
$count = 0;
foreach ($frRows as $r) {
    foreach (['en', 'es'] as $lang) {
        $existing = Database::fetchOne(
            "SELECT id FROM vp_proximites WHERE lang = ? AND position = ?",
            [$lang, $r['position']]
        );
        if ($existing) {
            echo "  SKIP pos{$r['position']}:{$lang}\n";
            continue;
        }
        Database::query(
            "INSERT INTO vp_proximites (name, distance, distance_min, description, category, lang, position) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$r['name'], $r['distance'], $r['distance_min'], '', $r['category'], $lang, $r['position']]
        );
        $count++;
        echo "  + {$r['name']}:{$lang}\n";
    }
}
echo "\nDone: {$count} created.\n";
