<?php
declare(strict_types=1);

/**
 * Duplicate FR sections to EN and ES with empty content
 * Usage: php seeds/012_seed_sections_en_es.php
 */

define('ROOT', dirname(__DIR__));
require ROOT . '/config.php';

echo "=== Seed: Create EN/ES sections from FR ===\n\n";

$frSections = Database::fetchAll("SELECT * FROM vp_sections WHERE lang = 'fr' ORDER BY page_slug, position");

$count = 0;
foreach ($frSections as $fr) {
    foreach (['en', 'es'] as $lang) {
        // Check if already exists
        $existing = Database::fetchOne(
            "SELECT id FROM vp_sections WHERE page_slug = ? AND lang = ? AND position = ?",
            [$fr['page_slug'], $lang, $fr['position']]
        );
        if ($existing) {
            echo "  SKIP {$fr['page_slug']}:{$lang}:pos{$fr['position']} (exists)\n";
            continue;
        }

        Database::query(
            "INSERT INTO vp_sections (page_slug, lang, block_type, title, content, position, active) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $fr['page_slug'],
                $lang,
                $fr['block_type'],
                $fr['title'],
                '{}', // empty content — to be translated in admin
                $fr['position'],
                $fr['active'],
            ]
        );
        $count++;
        echo "  + {$fr['page_slug']}:{$lang}:pos{$fr['position']} ({$fr['block_type']})\n";
    }
}

echo "\nDone: {$count} sections created.\n";
