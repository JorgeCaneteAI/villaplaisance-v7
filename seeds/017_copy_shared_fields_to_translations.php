<?php
declare(strict_types=1);

/**
 * Copy shared fields (images, checkboxes, etc.) from FR sections to EN/ES sections.
 * Usage: php seeds/017_copy_shared_fields_to_translations.php
 */

define('ROOT', dirname(__DIR__));
require ROOT . '/config.php';

echo "=== Copy shared fields from FR to EN/ES ===\n\n";

$sharedKeys = ['image', 'image_alt', 'compact', 'lead', 'dark', 'offer', 'images',
               'limit', 'offer_filter', 'type', 'page_slug', 'style', 'button_url'];

$frSections = Database::fetchAll("SELECT * FROM vp_sections WHERE lang = 'fr' ORDER BY page_slug, position");
$count = 0;

foreach ($frSections as $fr) {
    $frContent = json_decode($fr['content'], true) ?: [];

    // Extract shared fields from FR content
    $sharedData = [];
    foreach ($sharedKeys as $sk) {
        if (array_key_exists($sk, $frContent)) {
            $sharedData[$sk] = $frContent[$sk];
        }
    }

    if (empty($sharedData)) continue;

    // Find matching EN/ES sections
    $others = Database::fetchAll(
        "SELECT id, content, lang FROM vp_sections WHERE page_slug = ? AND position = ? AND lang != 'fr'",
        [$fr['page_slug'], $fr['position']]
    );

    foreach ($others as $other) {
        $otherContent = json_decode($other['content'], true) ?: [];
        $changed = false;
        foreach ($sharedData as $sk => $sv) {
            if (!array_key_exists($sk, $otherContent) || $otherContent[$sk] !== $sv) {
                $otherContent[$sk] = $sv;
                $changed = true;
            }
        }
        if ($changed) {
            Database::update('vp_sections', [
                'content' => json_encode($otherContent, JSON_UNESCAPED_UNICODE),
            ], 'id = ?', [$other['id']]);
            $count++;
            echo "  {$fr['page_slug']}:{$fr['position']} → {$other['lang']}\n";
        }
    }
}

echo "\nDone: {$count} sections updated with shared fields.\n";
