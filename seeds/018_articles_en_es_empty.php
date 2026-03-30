<?php
declare(strict_types=1);

/**
 * Create empty EN/ES versions for all FR articles (shared fields copied).
 * Usage: php seeds/018_articles_en_es_empty.php
 */

define('ROOT', dirname(__DIR__));
require ROOT . '/config.php';

echo "=== Create EN/ES article shells ===\n\n";

$frArticles = Database::fetchAll("SELECT * FROM vp_articles WHERE lang = 'fr' ORDER BY type, id");
$count = 0;

foreach ($frArticles as $fr) {
    foreach (['en', 'es'] as $lang) {
        $existing = Database::fetchOne(
            "SELECT id FROM vp_articles WHERE slug = ? AND type = ? AND lang = ?",
            [$fr['slug'], $fr['type'], $lang]
        );
        if ($existing) {
            echo "  SKIP {$fr['slug']} ({$lang}) — exists\n";
            continue;
        }

        Database::insert('vp_articles', [
            'type' => $fr['type'],
            'category' => $fr['category'],
            'slug' => $fr['slug'],
            'lang' => $lang,
            'title' => '', // to translate
            'excerpt' => '',
            'content' => '[]',
            'meta_title' => '',
            'meta_desc' => '',
            'meta_keywords' => '',
            'gso_desc' => '',
            'og_image' => $fr['og_image'] ?? '',
            'cover_image' => $fr['cover_image'] ?? '',
            'status' => $fr['status'],
            'published_at' => $fr['published_at'],
        ]);
        $count++;
        echo "  + {$fr['slug']} ({$lang})\n";
    }
}

echo "\nDone: {$count} article shells created.\n";
