<?php
declare(strict_types=1);

/**
 * Duplicate pieces & FAQ to EN/ES, add lang column to vp_stats
 * Usage: php seeds/013_seed_pieces_faq_stats_multilang.php
 */

define('ROOT', dirname(__DIR__));
require ROOT . '/config.php';

echo "=== Seed: Multilingual pieces, FAQ, stats ===\n\n";

// 1. Duplicate pieces FR → EN, ES
echo "--- PIECES ---\n";
$frPieces = Database::fetchAll("SELECT * FROM vp_pieces WHERE lang = 'fr' ORDER BY position");
$countP = 0;
foreach ($frPieces as $p) {
    foreach (['en', 'es'] as $lang) {
        $existing = Database::fetchOne(
            "SELECT id FROM vp_pieces WHERE lang = ? AND offer = ? AND position = ?",
            [$lang, $p['offer'], $p['position']]
        );
        if ($existing) {
            echo "  SKIP piece {$p['name']}:{$lang} (exists)\n";
            continue;
        }
        Database::insert('vp_pieces', [
            'offer' => $p['offer'],
            'type' => $p['type'],
            'position' => $p['position'],
            'name' => $p['name'], // same name initially
            'sous_titre' => $p['sous_titre'] ?? '',
            'description' => '', // empty — to translate
            'equip' => $p['equip'] ?? '',
            'note' => '',
            'image' => $p['image'] ?? '',
            'images' => $p['images'] ?? '[]',
            'css_class' => $p['css_class'] ?? '',
            'lang' => $lang,
        ]);
        $countP++;
        echo "  + piece {$p['name']}:{$lang}\n";
    }
}
echo "Pieces created: {$countP}\n\n";

// 2. Duplicate FAQ FR → EN, ES
echo "--- FAQ ---\n";
$frFaq = Database::fetchAll("SELECT * FROM vp_faq WHERE lang = 'fr' ORDER BY page_slug, position");
$countF = 0;
foreach ($frFaq as $f) {
    foreach (['en', 'es'] as $lang) {
        $existing = Database::fetchOne(
            "SELECT id FROM vp_faq WHERE lang = ? AND page_slug = ? AND position = ?",
            [$lang, $f['page_slug'], $f['position']]
        );
        if ($existing) {
            echo "  SKIP faq {$f['page_slug']}:{$lang}:pos{$f['position']} (exists)\n";
            continue;
        }
        Database::query(
            "INSERT INTO vp_faq (page_slug, lang, question, answer, position, active) VALUES (?, ?, ?, ?, ?, ?)",
            [$f['page_slug'], $lang, '', '', $f['position'], $f['active']]
        );
        $countF++;
        echo "  + faq {$f['page_slug']}:{$lang}:pos{$f['position']}\n";
    }
}
echo "FAQ created: {$countF}\n\n";

// 3. Add lang column to vp_stats if missing, then duplicate
echo "--- STATS ---\n";
$cols = Database::fetchAll("SHOW COLUMNS FROM vp_stats LIKE 'lang'");
if (empty($cols)) {
    Database::query("ALTER TABLE vp_stats ADD COLUMN lang VARCHAR(5) NOT NULL DEFAULT 'fr' AFTER icon");
    echo "  Added lang column to vp_stats\n";
}

// Set existing rows to FR
Database::query("UPDATE vp_stats SET lang = 'fr' WHERE lang = '' OR lang = 'fr'");

$frStats = Database::fetchAll("SELECT * FROM vp_stats WHERE lang = 'fr' ORDER BY position");
$countS = 0;
foreach ($frStats as $s) {
    foreach (['en', 'es'] as $lang) {
        $existing = Database::fetchOne(
            "SELECT id FROM vp_stats WHERE lang = ? AND position = ?",
            [$lang, $s['position']]
        );
        if ($existing) {
            echo "  SKIP stat pos{$s['position']}:{$lang} (exists)\n";
            continue;
        }
        Database::query(
            "INSERT INTO vp_stats (value, label, sublabel, icon, lang, position) VALUES (?, ?, ?, ?, ?, ?)",
            [$s['value'], '', '', $s['icon'] ?? '', $lang, $s['position']]
        );
        $countS++;
        echo "  + stat pos{$s['position']}:{$lang}\n";
    }
}
echo "Stats created: {$countS}\n\nDone.\n";
