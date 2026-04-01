<?php
declare(strict_types=1);

/**
 * Seed 030 — Tables itinéraires personnalisés + itinéraire Elisabeth-J
 * One-shot — ne pas ré-exécuter si déjà appliqué.
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

echo "=== Migration 030 : Itinéraires personnalisés ===\n";

// ── Tables ──────────────────────────────────────────────────────────────────

Database::query("
    CREATE TABLE IF NOT EXISTS vp_itineraries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(100) NOT NULL,
        guest_name VARCHAR(200) NOT NULL,
        intro_text TEXT DEFAULT NULL,
        itinerary_date DATE DEFAULT NULL,
        lang VARCHAR(5) NOT NULL DEFAULT 'fr',
        status ENUM('active','archived') DEFAULT 'active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_itinerary_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "✅ Table vp_itineraries créée\n";

Database::query("
    CREATE TABLE IF NOT EXISTS vp_itinerary_steps (
        id INT AUTO_INCREMENT PRIMARY KEY,
        itinerary_id INT NOT NULL,
        time_label VARCHAR(20) DEFAULT NULL,
        title VARCHAR(300) NOT NULL,
        duration VARCHAR(50) DEFAULT NULL,
        description TEXT DEFAULT NULL,
        position INT NOT NULL DEFAULT 0,
        FOREIGN KEY (itinerary_id) REFERENCES vp_itineraries(id) ON DELETE CASCADE,
        INDEX idx_itinerary_pos (itinerary_id, position)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "✅ Table vp_itinerary_steps créée\n";

// ── Itinéraire Elisabeth-J ──────────────────────────────────────────────────

$existing = Database::fetchOne("SELECT id FROM vp_itineraries WHERE slug = 'elisabeth-j'");
if ($existing) {
    echo "- Itinéraire elisabeth-j existe déjà, rien à faire.\n";
} else {
    Database::insert('vp_itineraries', [
        'slug'           => 'elisabeth-j',
        'guest_name'     => 'Elisabeth-J',
        'intro_text'     => 'Voici l\'itinéraire que nous avons préparé pour votre dernière journée en Provence. De Châteauneuf-du-Pape au Pont du Gard, en passant par Uzès — une belle boucle avant de rejoindre Alès. Bonne route !',
        'itinerary_date' => '2026-04-02',
        'lang'           => 'fr',
        'status'         => 'active',
    ]);

    $itineraryId = (int)Database::fetchOne("SELECT id FROM vp_itineraries WHERE slug = 'elisabeth-j'")['id'];

    $steps = [
        ['10h30', 'Départ Villa Plaisance', null, 'Direction Châteauneuf-du-Pape (~20 min).', 1],
        ['~11h00', 'Château La Gardine', '~1h', 'Dégustation de vins dans un cadre magnifique, note Google de 4,8/5. La visite est gratuite et souvent possible sans réservation, mais un coup de fil avant est conseillé : 04 90 83 73 20. Ouvert le lundi dès 10h.', 2],
        ['~12h30', 'Pont du Gard', '~1h30–2h avec déjeuner', 'Déjeuner sur place au restaurant du site, puis visite du monument. Parking payant sur place.', 3],
        ['~15h00', 'Musée Haribo, Uzès', '~45 min', 'Attention : fermé le lundi — mais demain c\'est jeudi, donc c\'est ouvert. Horaires : 10h-19h. La boutique usine est l\'intérêt principal (bonbons moins chers qu\'en grande surface).', 4],
        ['~15h50', 'Centre historique d\'Uzès', '~45 min', 'Flânerie place aux Herbes, Tour Fenestrelle, vieilles ruelles. Uzès est à 5 min du musée Haribo.', 5],
        ['~18h00', 'Arrivée à Alès', null, 'Trajet Uzès → Alès : ~35 min.', 6],
    ];

    foreach ($steps as $s) {
        Database::insert('vp_itinerary_steps', [
            'itinerary_id' => $itineraryId,
            'time_label'   => $s[0],
            'title'        => $s[1],
            'duration'     => $s[2],
            'description'  => $s[3],
            'position'     => $s[4],
        ]);
    }
    echo "✅ Itinéraire elisabeth-j créé avec " . count($steps) . " étapes\n";
}

echo "=== Migration 030 terminée ===\n";
