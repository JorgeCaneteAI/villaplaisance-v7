<?php
declare(strict_types=1);

/**
 * Migration — Tables pour réglages enrichis : liens de réservation, réseaux sociaux, équipements
 * Usage: php seeds/004_reglages_tables.php
 */

if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}
require ROOT . '/config.php';

$pdo = Database::getInstance();

$queries = [
    // Liens de réservation (Booking, Airbnb, etc.)
    "CREATE TABLE IF NOT EXISTS vp_booking_links (
        id INT AUTO_INCREMENT PRIMARY KEY,
        offer ENUM('bb', 'villa') NOT NULL DEFAULT 'bb',
        platform_name VARCHAR(100) NOT NULL,
        url VARCHAR(500) NOT NULL,
        position INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Réseaux sociaux
    "CREATE TABLE IF NOT EXISTS vp_social_links (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        url VARCHAR(500) NOT NULL,
        icon VARCHAR(50) DEFAULT NULL,
        position INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Équipements / points forts
    "CREATE TABLE IF NOT EXISTS vp_amenities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category VARCHAR(100) NOT NULL,
        name VARCHAR(200) NOT NULL,
        description TEXT DEFAULT NULL,
        position INT DEFAULT 0,
        active TINYINT(1) DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];

echo "=== Migration réglages enrichis ===\n\n";

foreach ($queries as $sql) {
    try {
        $pdo->exec($sql);
        preg_match('/TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?(\w+)/i', $sql, $m);
        echo "✅ Table {$m[1]} créée.\n";
    } catch (PDOException $e) {
        echo "❌ Erreur : " . $e->getMessage() . "\n";
    }
}

// Seed booking links
$bookingLinks = [
    ['bb', 'Booking.com', 'https://www.booking.com/', 1],
    ['bb', 'Airbnb', 'https://www.airbnb.fr/', 2],
    ['villa', 'Airbnb', 'https://www.airbnb.fr/', 1],
    ['villa', 'Abritel', 'https://www.abritel.fr/', 2],
];

echo "\n--- Liens de réservation ---\n";
foreach ($bookingLinks as $bl) {
    try {
        Database::insert('vp_booking_links', [
            'offer' => $bl[0],
            'platform_name' => $bl[1],
            'url' => $bl[2],
            'position' => $bl[3],
        ]);
        echo "  ✅ {$bl[0]} — {$bl[1]}\n";
    } catch (\Throwable $e) {
        echo "  ⚠ {$bl[1]} : {$e->getMessage()}\n";
    }
}

// Seed social links
$socialLinks = [
    ['Instagram', 'https://instagram.com/villaplaisance', 'instagram', 1],
    ['Facebook', 'https://facebook.com/villaplaisance', 'facebook', 2],
];

echo "\n--- Réseaux sociaux ---\n";
foreach ($socialLinks as $sl) {
    try {
        Database::insert('vp_social_links', [
            'name' => $sl[0],
            'url' => $sl[1],
            'icon' => $sl[2],
            'position' => $sl[3],
        ]);
        echo "  ✅ {$sl[0]}\n";
    } catch (\Throwable $e) {
        echo "  ⚠ {$sl[0]} : {$e->getMessage()}\n";
    }
}

// Seed amenities
$amenities = [
    // Points forts principaux
    ['Points forts', 'Piscine extérieure privée', null, 1],
    ['Points forts', 'Parking gratuit', null, 2],
    ['Points forts', 'Connexion Wi-Fi gratuite', null, 3],
    ['Points forts', 'Chambres familiales', null, 4],
    ['Points forts', 'Petit-déjeuner maison', null, 5],
    ['Points forts', 'Climatisation', null, 6],

    // Salle de bains
    ['Salle de bains', 'Salle de bains privative', null, 1],
    ['Salle de bains', 'Douche', null, 2],
    ['Salle de bains', 'Baignoire ou douche', null, 3],
    ['Salle de bains', 'Serviettes', null, 4],
    ['Salle de bains', 'Sèche-cheveux', null, 5],
    ['Salle de bains', 'Papier toilette', null, 6],
    ['Salle de bains', 'Bidet', null, 7],

    // Chambre
    ['Chambre', 'Linge de maison', null, 1],
    ['Chambre', 'Armoire ou penderie', null, 2],
    ['Chambre', 'Dressing', null, 3],
    ['Chambre', 'Très grands lits (> 2 mètres)', null, 4],
    ['Chambre', 'Prise près du lit', null, 5],
    ['Chambre', 'Canapé-lit', null, 6],

    // Vue
    ['Vue', 'Vue sur le jardin', null, 1],
    ['Vue', 'Vue dégagée', null, 2],

    // En extérieur
    ['En extérieur', 'Piscine privée clôturée', null, 1],
    ['En extérieur', 'Jardin provençal', null, 2],
    ['En extérieur', 'Terrasse', null, 3],
    ['En extérieur', 'Terrasse bien exposée', null, 4],
    ['En extérieur', 'Balcon', null, 5],
    ['En extérieur', 'Mobilier extérieur', null, 6],
    ['En extérieur', 'Aire de pique-nique', null, 7],

    // Équipements en chambre
    ['Équipements en chambre', 'Étendoir', null, 1],
    ['Équipements en chambre', 'Portant', null, 2],
    ['Équipements en chambre', 'Fer à repasser', null, 3],
    ['Équipements en chambre', 'Matériel de repassage', null, 4],

    // High-tech
    ['High-tech', 'Télévision à écran plat', null, 1],
    ['High-tech', 'Chaînes du câble', null, 2],
    ['High-tech', 'Radio', null, 3],

    // Internet
    ['Internet', 'Wi-Fi gratuit dans tout l\'établissement', null, 1],

    // Parking
    ['Parking', 'Parking privé gratuit sur place', 'Sans réservation préalable', 1],

    // Services
    ['Services', 'Gamelles pour animaux de compagnie', null, 1],
    ['Services', 'Salon commun / salle de télévision', null, 2],
    ['Services', 'Facture fournie sur demande', null, 3],

    // Animaux
    ['Animaux domestiques', 'Animaux admis sur demande', 'Sans supplément', 1],

    // Familles
    ['Pour les familles', 'Chambres familiales', null, 1],
    ['Pour les familles', 'Chambre(s) communicante(s)', null, 2],
    ['Pour les familles', 'Livres, DVD ou musique pour enfants', null, 3],
    ['Pour les familles', 'Jeux de société / puzzles', null, 4],

    // Sécurité
    ['Sécurité', 'Clés d\'accès', null, 1],

    // Général
    ['Général', 'Climatisation', null, 1],
    ['Général', 'Établissement non-fumeurs', null, 2],
    ['Général', 'Moustiquaire', null, 3],
    ['Général', 'Sol carrelé / en marbre', null, 4],
    ['Général', 'Chauffage', null, 5],

    // Piscine extérieure
    ['Piscine extérieure', 'En saison', null, 1],
    ['Piscine extérieure', 'Tous les âges bienvenus', null, 2],
    ['Piscine extérieure', 'Chaises longues', null, 3],
    ['Piscine extérieure', 'Parasols', null, 4],

    // Bien-être
    ['Bien-être', 'Parasols', null, 1],
    ['Bien-être', 'Chaises longues', null, 2],

    // Langues parlées
    ['Langues parlées', 'Français', null, 1],
    ['Langues parlées', 'Anglais', null, 2],
    ['Langues parlées', 'Espagnol', null, 3],
    ['Langues parlées', 'Italien', null, 4],
];

echo "\n--- Équipements ---\n";
foreach ($amenities as $am) {
    try {
        Database::insert('vp_amenities', [
            'category' => $am[0],
            'name' => $am[1],
            'description' => $am[2],
            'position' => $am[3],
        ]);
        echo "  ✅ [{$am[0]}] {$am[1]}\n";
    } catch (\Throwable $e) {
        echo "  ⚠ {$am[1]} : {$e->getMessage()}\n";
    }
}

echo "\n=== Migration terminée ===\n";
