<?php
declare(strict_types=1);

/**
 * Migration — Table vp_host_blocks pour les blocs CRUD de la page hôte
 */

require_once __DIR__ . '/../config.php';

echo "=== Migration: vp_host_blocks ===\n";

Database::query("
    CREATE TABLE IF NOT EXISTS vp_host_blocks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        lang VARCHAR(5) NOT NULL DEFAULT 'fr',
        title_fr VARCHAR(255) NOT NULL DEFAULT '',
        title_en VARCHAR(255) NOT NULL DEFAULT '',
        title_es VARCHAR(255) NOT NULL DEFAULT '',
        content_fr TEXT NOT NULL,
        content_en TEXT NOT NULL,
        content_es TEXT NOT NULL,
        image VARCHAR(255) NOT NULL DEFAULT '',
        position INT NOT NULL DEFAULT 0,
        active TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "Table vp_host_blocks créée.\n";

// Migrate existing data from vp_host_profile into blocks
$fr = Database::fetchOne("SELECT * FROM vp_host_profile WHERE lang = 'fr'");
$en = Database::fetchOne("SELECT * FROM vp_host_profile WHERE lang = 'en'");
$es = Database::fetchOne("SELECT * FROM vp_host_profile WHERE lang = 'es'");

if ($fr) {
    $existing = Database::fetchOne("SELECT id FROM vp_host_blocks LIMIT 1");
    if (!$existing) {
        $blocks = [
            ['D\'où je viens', 'Where I come from', 'De dónde vengo', 'origin', 1],
            ['Ce qui me passionne', 'What I\'m passionate about', 'Lo que me apasiona', 'passions', 2],
            ['Ma vision de l\'accueil', 'My hospitality philosophy', 'Mi visión de la hospitalidad', 'philosophy', 3],
            ['Fun facts', 'Fun facts', 'Datos curiosos', 'fun_facts', 4],
        ];

        foreach ($blocks as [$titleFr, $titleEn, $titleEs, $field, $pos]) {
            Database::insert('vp_host_blocks', [
                'title_fr' => $titleFr,
                'title_en' => $titleEn,
                'title_es' => $titleEs,
                'content_fr' => $fr[$field] ?? '',
                'content_en' => $en[$field] ?? '',
                'content_es' => $es[$field] ?? '',
                'image' => '',
                'position' => $pos,
            ]);
        }
        echo "4 blocs migrés depuis vp_host_profile.\n";
    }
}

echo "\n✅ Migration vp_host_blocks terminée.\n";
