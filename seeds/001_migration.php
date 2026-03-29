<?php
declare(strict_types=1);

/**
 * Migration initiale — Création de toutes les tables Villa Plaisance V7
 * Usage: php seeds/001_migration.php
 */

define('ROOT', dirname(__DIR__));
require ROOT . '/config.php';

$pdo = Database::getInstance();

$queries = [
    // Users (admin)
    "CREATE TABLE IF NOT EXISTS vp_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) DEFAULT 'Admin',
        reset_token VARCHAR(255) DEFAULT NULL,
        reset_expires DATETIME DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Pages
    "CREATE TABLE IF NOT EXISTS vp_pages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(100) NOT NULL,
        lang VARCHAR(5) NOT NULL DEFAULT 'fr',
        title VARCHAR(255) NOT NULL,
        meta_title VARCHAR(70) DEFAULT NULL,
        meta_desc VARCHAR(170) DEFAULT NULL,
        og_title VARCHAR(100) DEFAULT NULL,
        og_desc VARCHAR(200) DEFAULT NULL,
        og_image VARCHAR(500) DEFAULT NULL,
        jsonld TEXT DEFAULT NULL,
        active TINYINT(1) DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_page_lang (slug, lang)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Sections (CMS blocks)
    "CREATE TABLE IF NOT EXISTS vp_sections (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page_slug VARCHAR(100) NOT NULL,
        lang VARCHAR(5) NOT NULL DEFAULT 'fr',
        block_type VARCHAR(50) NOT NULL,
        title VARCHAR(255) DEFAULT NULL,
        content JSON DEFAULT NULL,
        position INT DEFAULT 0,
        active TINYINT(1) DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_page_lang (page_slug, lang)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Pieces (rooms/spaces)
    "CREATE TABLE IF NOT EXISTS vp_pieces (
        id INT AUTO_INCREMENT PRIMARY KEY,
        offer ENUM('bb', 'villa', 'both') NOT NULL DEFAULT 'bb',
        type ENUM('chambre', 'espace') NOT NULL DEFAULT 'chambre',
        position INT DEFAULT 0,
        name VARCHAR(100) NOT NULL,
        sous_titre VARCHAR(255) DEFAULT NULL,
        description TEXT DEFAULT NULL,
        equip TEXT DEFAULT NULL,
        note VARCHAR(255) DEFAULT NULL,
        css_class VARCHAR(50) DEFAULT NULL,
        lang VARCHAR(5) NOT NULL DEFAULT 'fr',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Reviews
    "CREATE TABLE IF NOT EXISTS vp_reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        platform VARCHAR(50) DEFAULT 'direct',
        offer ENUM('bb', 'villa', 'both') DEFAULT 'bb',
        author VARCHAR(100) NOT NULL,
        origin VARCHAR(100) DEFAULT NULL,
        content TEXT NOT NULL,
        rating DECIMAL(3,1) DEFAULT 5.0,
        review_date DATE DEFAULT NULL,
        featured TINYINT(1) DEFAULT 0,
        home_carousel TINYINT(1) DEFAULT 0,
        status ENUM('published', 'draft', 'hidden') DEFAULT 'published',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // FAQ
    "CREATE TABLE IF NOT EXISTS vp_faq (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page_slug VARCHAR(100) NOT NULL,
        lang VARCHAR(5) NOT NULL DEFAULT 'fr',
        question VARCHAR(500) NOT NULL,
        answer TEXT NOT NULL,
        position INT DEFAULT 0,
        active TINYINT(1) DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Stats (chiffres clés)
    "CREATE TABLE IF NOT EXISTS vp_stats (
        id INT AUTO_INCREMENT PRIMARY KEY,
        value VARCHAR(50) NOT NULL,
        label VARCHAR(100) NOT NULL,
        sublabel VARCHAR(200) DEFAULT NULL,
        icon VARCHAR(50) DEFAULT NULL,
        position INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Proximités (Triangle d'Or)
    "CREATE TABLE IF NOT EXISTS vp_proximites (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        distance VARCHAR(50) NOT NULL,
        distance_min INT DEFAULT 0,
        description VARCHAR(255) DEFAULT NULL,
        category VARCHAR(50) DEFAULT 'ville',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Articles (journal + sur-place)
    "CREATE TABLE IF NOT EXISTS vp_articles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type ENUM('journal', 'sur-place') NOT NULL DEFAULT 'journal',
        category VARCHAR(100) DEFAULT NULL,
        slug VARCHAR(255) NOT NULL,
        lang VARCHAR(5) NOT NULL DEFAULT 'fr',
        title VARCHAR(500) NOT NULL,
        excerpt TEXT DEFAULT NULL,
        content JSON DEFAULT NULL,
        meta_title VARCHAR(70) DEFAULT NULL,
        meta_desc VARCHAR(170) DEFAULT NULL,
        meta_keywords VARCHAR(255) DEFAULT NULL,
        gso_desc TEXT DEFAULT NULL,
        og_image VARCHAR(500) DEFAULT NULL,
        cover_image VARCHAR(500) DEFAULT NULL,
        status ENUM('published', 'draft') DEFAULT 'draft',
        published_at DATETIME DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_article_slug_lang (slug, lang)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Messages (contact form)
    "CREATE TABLE IF NOT EXISTS vp_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        subject VARCHAR(255) DEFAULT NULL,
        message TEXT NOT NULL,
        lang VARCHAR(5) DEFAULT 'fr',
        ip VARCHAR(45) DEFAULT NULL,
        read_at DATETIME DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Settings (réglages)
    "CREATE TABLE IF NOT EXISTS vp_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) NOT NULL UNIQUE,
        setting_value TEXT DEFAULT NULL,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Livret d'accueil
    "CREATE TABLE IF NOT EXISTS vp_livret (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type ENUM('bb', 'villa') NOT NULL,
        section_title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        position INT DEFAULT 0,
        active TINYINT(1) DEFAULT 1,
        lang VARCHAR(5) NOT NULL DEFAULT 'fr',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];

echo "=== Migration Villa Plaisance V7 ===\n\n";

foreach ($queries as $sql) {
    try {
        $pdo->exec($sql);
        // Extract table name
        preg_match('/TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?(\w+)/i', $sql, $m);
        echo "✅ Table {$m[1]} créée.\n";
    } catch (PDOException $e) {
        echo "❌ Erreur : " . $e->getMessage() . "\n";
    }
}

echo "\n=== Migration terminée ===\n";
