<?php
declare(strict_types=1);

/**
 * Seed 036 — Tables MySQL pour le calendrier de réservations (VP-BB / VP-ETE / AV-ANN).
 * Crée 4 tables (vp_reservations, vp_ical_feeds, vp_ical_sync_log, vp_trusted_devices)
 * + seed les 4 flux iCal Airbnb/Booking. Idempotent.
 * Usage : php seeds/036_reservations_tables.php
 */

require __DIR__ . '/../config.php';

$sql = [
    "CREATE TABLE IF NOT EXISTS vp_reservations (
        id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        code            VARCHAR(16)    NOT NULL,
        nom_client      VARCHAR(120)   NOT NULL,
        propriete       ENUM('VP-BB','VP-ETE','AV-ANN') NOT NULL,
        source          ENUM('Airbnb','Booking','Direct','Privée','Absence') NOT NULL,
        arrivee         DATE           NOT NULL,
        depart          DATE           NOT NULL,
        duree           SMALLINT UNSIGNED,
        adultes         TINYINT UNSIGNED DEFAULT 0,
        enfants         TINYINT UNSIGNED DEFAULT 0,
        bebes           TINYINT UNSIGNED DEFAULT 0,
        animaux         TINYINT UNSIGNED DEFAULT 0,
        animaux_details VARCHAR(255),
        provenance      VARCHAR(255),
        commentaire     TEXT,
        prive           BOOLEAN DEFAULT 0,
        statut          ENUM('Confirmée','Option','Annulée') DEFAULT 'Confirmée',
        numero_resa     VARCHAR(64),
        montant         DECIMAL(8,2),
        ical_uid        VARCHAR(255),
        created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_arrivee (arrivee),
        INDEX idx_propriete_arrivee (propriete, arrivee),
        UNIQUE KEY uniq_ical (source, ical_uid)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS vp_ical_feeds (
        id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        propriete       ENUM('VP-BB','VP-ETE','AV-ANN') NOT NULL,
        source          ENUM('Airbnb','Booking') NOT NULL,
        url             TEXT           NOT NULL,
        actif           BOOLEAN DEFAULT 1,
        last_sync_at    DATETIME,
        last_sync_ok    BOOLEAN,
        last_sync_msg   TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS vp_ical_sync_log (
        id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        started_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
        ended_at      DATETIME,
        created       INT DEFAULT 0,
        updated       INT DEFAULT 0,
        deleted       INT DEFAULT 0,
        errors        TEXT,
        triggered_by  ENUM('cron','manual') NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS vp_trusted_devices (
        id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id    INT UNSIGNED NOT NULL,
        token_hash CHAR(64)     NOT NULL,
        user_agent VARCHAR(255),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        last_used  DATETIME,
        expires_at DATETIME NOT NULL,
        UNIQUE KEY uniq_token (token_hash),
        INDEX idx_user (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];

foreach ($sql as $q) {
    \Database::query($q);
}

// Seed des 4 flux iCal. Les URLs contiennent des tokens secrets et sont
// lues depuis les variables d'environnement (voir .env / .env.example).
$feedDefs = [
    ['propriete' => 'AV-ANN', 'source' => 'Airbnb',  'env' => 'ICAL_AV_ANN_AIRBNB'],
    ['propriete' => 'VP-BB',  'source' => 'Airbnb',  'env' => 'ICAL_VP_BB_AIRBNB'],
    ['propriete' => 'VP-ETE', 'source' => 'Airbnb',  'env' => 'ICAL_VP_ETE_AIRBNB'],
    ['propriete' => 'VP-BB',  'source' => 'Booking', 'env' => 'ICAL_VP_BB_BOOKING'],
];

foreach ($feedDefs as $def) {
    $url = $_ENV[$def['env']] ?? '';
    if ($url === '') {
        fwrite(STDERR, "⚠ {$def['env']} non défini dans .env — flux {$def['propriete']}/{$def['source']} NON seedé\n");
        continue;
    }
    $existing = \Database::fetchOne(
        "SELECT id FROM vp_ical_feeds WHERE propriete = ? AND source = ?",
        [$def['propriete'], $def['source']]
    );
    if (!$existing) {
        \Database::insert('vp_ical_feeds', [
            'propriete' => $def['propriete'],
            'source'    => $def['source'],
            'url'       => $url,
            'actif'     => 1,
        ]);
    }
}

echo "✓ Tables créées : vp_reservations, vp_ical_feeds, vp_ical_sync_log, vp_trusted_devices\n";
echo "✓ 4 flux iCal seedés dans vp_ical_feeds\n";
