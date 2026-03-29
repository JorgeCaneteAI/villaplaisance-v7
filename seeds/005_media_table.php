<?php
declare(strict_types=1);

/**
 * Migration — Table médiathèque SEO-optimisée
 * Usage: php seeds/005_media_table.php
 */

if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}
require ROOT . '/config.php';

$pdo = Database::getInstance();

$sql = "CREATE TABLE IF NOT EXISTS vp_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL UNIQUE,
    original_name VARCHAR(255) NOT NULL,
    alt_fr VARCHAR(500) DEFAULT NULL,
    alt_en VARCHAR(500) DEFAULT NULL,
    alt_es VARCHAR(500) DEFAULT NULL,
    alt_de VARCHAR(500) DEFAULT NULL,
    title VARCHAR(255) DEFAULT NULL,
    caption TEXT DEFAULT NULL,
    credit VARCHAR(255) DEFAULT NULL,
    mime_type VARCHAR(50) NOT NULL DEFAULT 'image/webp',
    file_size INT DEFAULT 0,
    width INT DEFAULT 0,
    height INT DEFAULT 0,
    folder VARCHAR(100) DEFAULT 'general',
    tags VARCHAR(500) DEFAULT NULL,
    seo_filename VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_folder (folder),
    INDEX idx_filename (filename)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

echo "=== Migration médiathèque ===\n\n";

try {
    $pdo->exec($sql);
    echo "✅ Table vp_media créée.\n";
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}

echo "\n=== Migration terminée ===\n";
