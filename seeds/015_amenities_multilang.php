<?php
declare(strict_types=1);

/**
 * Add lang column to vp_amenities and duplicate to EN/ES
 * Usage: php seeds/015_amenities_multilang.php
 */

define('ROOT', dirname(__DIR__));
require ROOT . '/config.php';

echo "=== Seed: Multilingual amenities ===\n\n";

$cols = Database::fetchAll("SHOW COLUMNS FROM vp_amenities LIKE 'lang'");
if (empty($cols)) {
    Database::query("ALTER TABLE vp_amenities ADD COLUMN lang VARCHAR(5) NOT NULL DEFAULT 'fr' AFTER position");
    echo "Added lang column\n";
}

Database::query("UPDATE vp_amenities SET lang = 'fr' WHERE lang = '' OR lang = 'fr'");

$frRows = Database::fetchAll("SELECT * FROM vp_amenities WHERE lang = 'fr' ORDER BY category, position");
$count = 0;
foreach ($frRows as $r) {
    foreach (['en', 'es'] as $lang) {
        $existing = Database::fetchOne(
            "SELECT id FROM vp_amenities WHERE lang = ? AND category = ? AND position = ?",
            [$lang, $r['category'], $r['position']]
        );
        if ($existing) continue;

        Database::insert('vp_amenities', [
            'category' => $r['category'],
            'name' => '', // to translate
            'description' => '',
            'offer_bb' => $r['offer_bb'],
            'offer_villa' => $r['offer_villa'],
            'position' => $r['position'],
            'lang' => $lang,
            'active' => $r['active'] ?? 1,
        ]);
        $count++;
    }
}
echo "Done: {$count} amenities created.\n";
