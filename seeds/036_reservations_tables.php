<?php
declare(strict_types=1);

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS vp_ical_feeds (
        id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        propriete       ENUM('VP-BB','VP-ETE','AV-ANN') NOT NULL,
        source          ENUM('Airbnb','Booking') NOT NULL,
        url             TEXT           NOT NULL,
        actif           BOOLEAN DEFAULT 1,
        last_sync_at    DATETIME,
        last_sync_ok    BOOLEAN,
        last_sync_msg   TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS vp_ical_sync_log (
        id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        started_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
        ended_at      DATETIME,
        created       INT DEFAULT 0,
        updated       INT DEFAULT 0,
        deleted       INT DEFAULT 0,
        errors        TEXT,
        triggered_by  ENUM('cron','manual') NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];

foreach ($sql as $q) {
    \Database::query($q);
}

// Seed des 4 flux iCal actuels (repris depuis sync_ical.py de l'app Flask)
$feeds = [
    ['propriete' => 'AV-ANN', 'source' => 'Airbnb',
     'url' => 'https://www.airbnb.fr/calendar/ical/3520144.ics?t=5947398f53f7446bbb769c58f8ef322f'],
    ['propriete' => 'VP-BB', 'source' => 'Airbnb',
     'url' => 'https://www.airbnb.fr/calendar/ical/597660428689098985.ics?t=64ded1ce0b024ff5b6b62f7fdcbb3e01'],
    ['propriete' => 'VP-ETE', 'source' => 'Airbnb',
     'url' => 'https://www.airbnb.fr/calendar/ical/625764424244747021.ics?t=a1efb4b6dcb64e69a56939227503466e'],
    ['propriete' => 'VP-BB', 'source' => 'Booking',
     'url' => 'https://ical.booking.com/v1/export?t=0c2ec4cc-4968-4753-9227-0c18b3094247'],
];

foreach ($feeds as $f) {
    $existing = \Database::fetchOne(
        "SELECT id FROM vp_ical_feeds WHERE propriete = ? AND source = ?",
        [$f['propriete'], $f['source']]
    );
    if (!$existing) {
        \Database::insert('vp_ical_feeds', $f + ['actif' => 1]);
    }
}

echo "✓ Tables créées : vp_reservations, vp_ical_feeds, vp_ical_sync_log, vp_trusted_devices\n";
echo "✓ 4 flux iCal seedés dans vp_ical_feeds\n";
