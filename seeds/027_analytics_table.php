<?php
declare(strict_types=1);

require __DIR__ . '/../config.php';

echo "=== Migration 027: Analytics table ===\n";

Database::query("
    CREATE TABLE IF NOT EXISTS vp_pageviews (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        visitor_id VARCHAR(64) NOT NULL,
        page_url VARCHAR(500) NOT NULL,
        referrer VARCHAR(500) DEFAULT NULL,
        user_agent VARCHAR(500) DEFAULT NULL,
        device_type ENUM('desktop','mobile','tablet') DEFAULT 'desktop',
        ip_hash VARCHAR(64) DEFAULT NULL,
        lang VARCHAR(5) DEFAULT 'fr',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_created (created_at),
        INDEX idx_visitor (visitor_id),
        INDEX idx_page (page_url(191))
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "✓ Table vp_pageviews créée\n";

// Add GA4 setting
$exists = Database::fetchOne("SELECT id FROM vp_settings WHERE setting_key = 'ga4_measurement_id'");
if (!$exists) {
    Database::insert('vp_settings', ['setting_key' => 'ga4_measurement_id', 'setting_value' => '']);
    echo "✓ Setting ga4_measurement_id ajouté\n";
} else {
    echo "- Setting ga4_measurement_id existe déjà\n";
}

echo "=== Migration 027 terminée ===\n";
