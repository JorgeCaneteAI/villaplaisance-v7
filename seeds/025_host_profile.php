<?php
declare(strict_types=1);

/**
 * Migration — Table vp_host_profile pour la page "Votre hôte"
 */

require_once __DIR__ . '/../config.php';

echo "=== Migration: vp_host_profile ===\n";

Database::query("
    CREATE TABLE IF NOT EXISTS vp_host_profile (
        id INT AUTO_INCREMENT PRIMARY KEY,
        lang VARCHAR(5) NOT NULL DEFAULT 'fr',
        name VARCHAR(255) NOT NULL DEFAULT '',
        subtitle VARCHAR(255) NOT NULL DEFAULT '',
        photo VARCHAR(255) NOT NULL DEFAULT '',
        intro TEXT NOT NULL,
        origin TEXT NOT NULL,
        passions TEXT NOT NULL,
        philosophy TEXT NOT NULL,
        fun_facts TEXT NOT NULL,
        quote TEXT NOT NULL,
        active TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_lang (lang)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "Table vp_host_profile créée.\n";

// Seed FR
$exists = Database::fetchOne("SELECT id FROM vp_host_profile WHERE lang = 'fr'");
if (!$exists) {
    Database::insert('vp_host_profile', [
        'lang' => 'fr',
        'name' => 'Jorge Cañete',
        'subtitle' => 'Votre hôte à Villa Plaisance',
        'photo' => '',
        'intro' => 'Bienvenue ! Je suis Jorge, votre hôte à Villa Plaisance. Passionné par l\'accueil et la Provence, j\'ai ouvert les portes de cette maison pour partager un art de vivre simple et authentique.',
        'origin' => 'Originaire du sud de la France, j\'ai grandi entre la mer et la garrigue. Après des années dans le monde du digital et de la communication, j\'ai choisi de revenir aux essentiels : le soleil, la terre, les gens.',
        'passions' => 'La cuisine provençale, les marchés du dimanche, le vin (surtout celui des voisins vignerons), la photographie, les longues discussions sur la terrasse, et faire découvrir les trésors cachés du Vaucluse.',
        'philosophy' => 'Ici, pas de protocole. On se tutoie, on partage les bons plans, on prend le temps. Villa Plaisance, c\'est votre maison en Provence — avec un hôte qui connaît chaque recoin du territoire.',
        'fun_facts' => 'Je parle 3 langues (français, espagnol, anglais). Je fais le meilleur café du Vaucluse (selon mes hôtes). Je connais le prénom de chaque olivier du jardin.',
        'quote' => 'L\'hospitalité, c\'est faire sentir à l\'autre qu\'il est chez lui — même quand il est chez vous.',
    ]);
    echo "Profil FR créé.\n";
}

// Seed EN
$exists = Database::fetchOne("SELECT id FROM vp_host_profile WHERE lang = 'en'");
if (!$exists) {
    Database::insert('vp_host_profile', [
        'lang' => 'en',
        'name' => 'Jorge Cañete',
        'subtitle' => 'Your host at Villa Plaisance',
        'photo' => '',
        'intro' => 'Welcome! I\'m Jorge, your host at Villa Plaisance. Passionate about hospitality and Provence, I opened the doors of this house to share a simple and authentic way of life.',
        'origin' => 'Originally from the south of France, I grew up between the sea and the garrigue. After years in the digital and communications world, I chose to return to the essentials: sunshine, earth, people.',
        'passions' => 'Provençal cuisine, Sunday markets, wine (especially from our winemaker neighbours), photography, long conversations on the terrace, and helping guests discover the hidden treasures of Vaucluse.',
        'philosophy' => 'No formalities here. We share tips, take our time, and enjoy the moment. Villa Plaisance is your home in Provence — with a host who knows every corner of the territory.',
        'fun_facts' => 'I speak 3 languages (French, Spanish, English). I make the best coffee in Vaucluse (according to my guests). I know each olive tree in the garden by name.',
        'quote' => 'Hospitality is making others feel at home — even when they\'re at yours.',
    ]);
    echo "Profil EN créé.\n";
}

// Seed ES
$exists = Database::fetchOne("SELECT id FROM vp_host_profile WHERE lang = 'es'");
if (!$exists) {
    Database::insert('vp_host_profile', [
        'lang' => 'es',
        'name' => 'Jorge Cañete',
        'subtitle' => 'Su anfitrión en Villa Plaisance',
        'photo' => '',
        'intro' => '¡Bienvenidos! Soy Jorge, su anfitrión en Villa Plaisance. Apasionado por la hospitalidad y la Provenza, abrí las puertas de esta casa para compartir un arte de vivir sencillo y auténtico.',
        'origin' => 'Originario del sur de Francia, crecí entre el mar y la garriga. Después de años en el mundo digital y la comunicación, elegí volver a lo esencial: el sol, la tierra, la gente.',
        'passions' => 'La cocina provenzal, los mercados del domingo, el vino (especialmente el de los vecinos viticultores), la fotografía, las largas conversaciones en la terraza, y hacer descubrir los tesoros escondidos del Vaucluse.',
        'philosophy' => 'Aquí no hay protocolos. Compartimos consejos, nos tomamos nuestro tiempo y disfrutamos del momento. Villa Plaisance es su hogar en Provenza — con un anfitrión que conoce cada rincón del territorio.',
        'fun_facts' => 'Hablo 3 idiomas (francés, español, inglés). Preparo el mejor café del Vaucluse (según mis huéspedes). Conozco cada olivo del jardín por su nombre.',
        'quote' => 'La hospitalidad es hacer sentir al otro en su casa — incluso cuando está en la tuya.',
    ]);
    echo "Profil ES créé.\n";
}

echo "\n✅ Migration vp_host_profile terminée.\n";
