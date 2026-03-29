<?php
declare(strict_types=1);

/**
 * Seed complet — Toutes les données initiales V7
 * Usage: php seeds/002_seed_data.php
 */

define('ROOT', dirname(__DIR__));
require ROOT . '/config.php';

echo "=== Seed Villa Plaisance V7 ===\n\n";

// --- Admin user ---
$password = password_hash('VillaP@2026!!', PASSWORD_DEFAULT);
try {
    Database::query("DELETE FROM vp_users WHERE email = ?", ['contact@villaplaisance.fr']);
    Database::insert('vp_users', [
        'email' => 'contact@villaplaisance.fr',
        'password' => $password,
        'name' => 'Admin Villa Plaisance',
    ]);
    echo "✅ Admin créé (contact@villaplaisance.fr / VillaP@2026!!)\n";
} catch (\Throwable $e) {
    echo "❌ Admin : " . $e->getMessage() . "\n";
}

// --- Pages ---
$pages = [
    ['accueil', 'fr', 'Accueil', 'Villa Plaisance — Chambres d\'hôtes et villa à Bédarrides', 'Chambres d\'hôtes de septembre à juin, villa entière en juillet-août. Piscine privée, 4 chambres, entre Avignon et Orange.'],
    ['chambres-d-hotes', 'fr', 'Chambres d\'hôtes', 'Chambres d\'hôtes à Bédarrides — Villa Plaisance, Provence', 'Deux chambres climatisées avec petit-déjeuner maison et piscine partagée. De septembre à juin, entre Avignon et Orange.'],
    ['location-villa-provence', 'fr', 'Villa entière', 'Location villa Provence — Villa Plaisance, Bédarrides', '4 chambres, piscine privée 12×6m, jardin provençal. Villa en exclusivité juillet-août, jusqu\'à 10 personnes.'],
    ['espaces-exterieurs', 'fr', 'Espaces extérieurs', 'Espaces extérieurs — Villa Plaisance, Bédarrides', 'Piscine privée 12×6m, jardin provençal, terrasses ombragées. Les espaces extérieurs de Villa Plaisance.'],
    ['journal', 'fr', 'Journal', 'Journal — Villa Plaisance, Provence', 'Récits, conseils et regards sur la Provence. Le journal de Villa Plaisance à Bédarrides.'],
    ['sur-place', 'fr', 'Sur place', 'Sur place — Adresses autour de Bédarrides', 'Restaurants, commerces, sites à visiter. Nos recommandations autour de Villa Plaisance.'],
    ['contact', 'fr', 'Contact', 'Contact — Villa Plaisance, Bédarrides', 'Contactez Villa Plaisance pour organiser votre séjour en Provence.'],
    ['mentions-legales', 'fr', 'Mentions légales', 'Mentions légales — Villa Plaisance', 'Mentions légales du site Villa Plaisance.'],
    ['politique-confidentialite', 'fr', 'Politique de confidentialité', 'Politique de confidentialité — Villa Plaisance', 'Politique de confidentialité et protection des données.'],
    ['plan-du-site', 'fr', 'Plan du site', 'Plan du site — Villa Plaisance', 'Toutes les pages du site Villa Plaisance.'],
];

Database::query("DELETE FROM vp_pages");
foreach ($pages as $p) {
    Database::insert('vp_pages', [
        'slug' => $p[0], 'lang' => $p[1], 'title' => $p[2],
        'meta_title' => $p[3], 'meta_desc' => $p[4],
    ]);
}
echo "✅ " . count($pages) . " pages créées\n";

// --- Pièces (chambres) ---
Database::query("DELETE FROM vp_pieces");
$pieces = [
    ['bb', 'chambre', 1, 'Chambre Verte', 'Grand lit, vue jardin', 'Chambre lumineuse avec un grand lit 160×200, donnant sur le jardin et les oliviers. Espace cocooning, sobriété et calme. Climatisation réversible, TV.', 'Lit 160×200, Vue jardin, Climatisation réversible, TV, Wifi', 'Rez-de-chaussée', 'fr'],
    ['bb', 'chambre', 2, 'Chambre Bleue', 'Bibliothèque, idéale famille', 'Deux lits 90×200 jumelables en grand lit 180. Un clic-clac pour une troisième personne. Une bibliothèque de 300 livres. La chambre des lecteurs et des familles.', '2 lits 90×200 jumelables, Clic-clac (1 pers.), Bibliothèque 300 livres, Climatisation réversible, Wifi', 'Idéale pour famille ou voyage entre amis', 'fr'],
    ['villa', 'chambre', 1, 'Chambre Verte', 'Grand lit, vue jardin', 'Lit 160×200, vue sur le jardin et les oliviers. Climatisation réversible, TV. Au rez-de-chaussée.', 'Lit 160×200, Vue jardin, Climatisation, TV, Wifi', 'Rez-de-chaussée', 'fr'],
    ['villa', 'chambre', 2, 'Chambre Bleue', 'Bibliothèque 300 livres', 'Deux lits 90×200 jumelables, clic-clac, bibliothèque de 300 livres. Climatisation réversible.', '2 lits 90×200 jumelables, Clic-clac, Bibliothèque 300 livres, Climatisation, Wifi', '', 'fr'],
    ['villa', 'chambre', 3, 'Chambre Arche', 'Arche bleue nuit, bibliothèques sol-plafond', 'Lit 140×180 sous une grande arche peinte en bleu nuit. Bibliothèques sol-plafond des deux côtés. Au rez-de-chaussée, avec vue sur le jardin.', 'Lit 140×180, Arche bleue nuit, Bibliothèques sol-plafond, Vue jardin, Climatisation', 'Rez-de-chaussée · Accès direct jardin', 'fr'],
    ['villa', 'chambre', 4, 'Chambre 70', 'Mobilier vintage années 70', 'Grand lit double, mobilier chiné des années 70. Accès direct sur le jardin par une porte-fenêtre. La chambre la plus atypique de la villa.', 'Grand lit double, Mobilier vintage, Accès direct jardin, Climatisation', 'Accès direct jardin', 'fr'],
];
foreach ($pieces as $p) {
    Database::insert('vp_pieces', [
        'offer' => $p[0], 'type' => $p[1], 'position' => $p[2],
        'name' => $p[3], 'sous_titre' => $p[4], 'description' => $p[5],
        'equip' => $p[6], 'note' => $p[7], 'lang' => $p[8],
    ]);
}
echo "✅ " . count($pieces) . " pièces créées\n";

// --- Stats ---
Database::query("DELETE FROM vp_stats");
$stats = [
    ['4', 'Chambres', 'en villa entière', 1],
    ['10', 'Personnes max', 'en villa exclusive', 2],
    ['8 min', 'Châteauneuf-du-Pape', 'Triangle d\'Or', 3],
    ['12×6', 'Piscine privée', 'Clôturée, privatisée', 4],
    ['15 min', 'Avignon', 'Centre historique', 5],
    ['18 min', 'Orange', 'Théâtre antique', 6],
];
foreach ($stats as $s) {
    Database::insert('vp_stats', [
        'value' => $s[0], 'label' => $s[1], 'sublabel' => $s[2], 'position' => $s[3],
    ]);
}
echo "✅ " . count($stats) . " stats créées\n";

// --- Proximités ---
Database::query("DELETE FROM vp_proximites");
$prox = [
    ['Châteauneuf-du-Pape', '8 min', 8, 'Vignobles et patrimoine'],
    ['Avignon', '15 min', 15, 'Palais des Papes, pont, festival'],
    ['Orange', '18 min', 18, 'Théâtre antique romain'],
    ['L\'Isle-sur-la-Sorgue', '25 min', 25, 'Antiquités et marchés'],
    ['Pont du Gard', '30 min', 30, 'Aqueduc romain UNESCO'],
    ['Gordes', '42 min', 42, 'Plus beaux villages de France'],
    ['Les Baux-de-Provence', '45 min', 45, 'Village perché, carrières de lumières'],
    ['Vaison-la-Romaine', '35 min', 35, 'Site archéologique gallo-romain'],
    ['Mont Ventoux', '45 min', 45, 'Géant de Provence'],
];
foreach ($prox as $p) {
    Database::insert('vp_proximites', [
        'name' => $p[0], 'distance' => $p[1], 'distance_min' => $p[2], 'description' => $p[3],
    ]);
}
echo "✅ " . count($prox) . " proximités créées\n";

// --- FAQ ---
Database::query("DELETE FROM vp_faq");
$faqs = [
    // Accueil
    ['accueil', 'fr', 'Où se situe Villa Plaisance ?', 'Villa Plaisance se trouve à Bédarrides, dans le Vaucluse (84370), au cœur du Triangle d\'Or provençal, à 8 minutes de Châteauneuf-du-Pape, 15 minutes d\'Avignon et 18 minutes d\'Orange.', 1],
    ['accueil', 'fr', 'Quelle est la différence entre chambres d\'hôtes et villa entière ?', 'De septembre à juin, nous accueillons en chambres d\'hôtes (2 chambres, petit-déjeuner inclus, piscine partagée). En juillet et août, la villa entière se loue en exclusivité (4 chambres, piscine privée, cuisine équipée, jusqu\'à 10 personnes).', 2],
    ['accueil', 'fr', 'Y a-t-il une piscine ?', 'Oui. La piscine mesure 12 mètres sur 6, elle est clôturée et sécurisée. En chambres d\'hôtes, elle est partagée avec les autres hôtes. En location villa, elle est entièrement privatisée.', 3],

    // Chambres
    ['chambres-d-hotes', 'fr', 'Le petit-déjeuner est-il inclus ?', 'Oui, le petit-déjeuner est inclus et préparé chaque matin avec des produits locaux et de saison : confitures maison, fruits frais, pain de boulanger, fromages et charcuteries du terroir.', 1],
    ['chambres-d-hotes', 'fr', 'Les chambres sont-elles climatisées ?', 'Oui, les deux chambres (Verte et Bleue) sont équipées de climatisation réversible et du wifi gratuit.', 2],
    ['chambres-d-hotes', 'fr', 'Peut-on accueillir des enfants en chambres d\'hôtes ?', 'Oui, la Chambre Bleue dispose d\'un clic-clac pouvant accueillir une personne supplémentaire, ce qui en fait une chambre idéale pour les familles.', 3],
    ['chambres-d-hotes', 'fr', 'À quelle période les chambres d\'hôtes sont-elles disponibles ?', 'Les chambres d\'hôtes sont ouvertes de septembre à juin. En juillet et août, la villa se loue en exclusivité.', 4],
    ['chambres-d-hotes', 'fr', 'Comment se rendre à Villa Plaisance ?', 'Bédarrides est accessible en voiture (autoroute A7), en TGV (gare d\'Avignon TGV à 15 minutes) ou via l\'aéroport de Marseille-Provence (1h). Nous pouvons vous fournir les indications détaillées.', 5],
    ['chambres-d-hotes', 'fr', 'Y a-t-il un parking ?', 'Oui, un parking privé gratuit est disponible sur place pour tous les hôtes.', 6],

    // Villa
    ['location-villa-provence', 'fr', 'Combien de personnes la villa peut-elle accueillir ?', 'La villa accueille jusqu\'à 10 personnes réparties dans 4 chambres : Chambre Verte, Chambre Bleue, Chambre Arche et Chambre 70.', 1],
    ['location-villa-provence', 'fr', 'La piscine est-elle privée en location villa ?', 'Oui, en juillet et août, la piscine de 12m×6m est entièrement privée et réservée aux locataires. Elle est clôturée et sécurisée.', 2],
    ['location-villa-provence', 'fr', 'La cuisine est-elle équipée ?', 'Oui, la cuisine est entièrement équipée : four, plaques, lave-vaisselle, micro-ondes, réfrigérateur, ustensiles de cuisine et vaisselle pour 10 personnes.', 3],
    ['location-villa-provence', 'fr', 'Le linge de maison est-il fourni ?', 'Oui, les draps, serviettes de bain et serviettes de piscine sont fournis et changés chaque semaine.', 4],
    ['location-villa-provence', 'fr', 'Quelle est la durée minimum de location ?', 'En haute saison (juillet-août), la durée minimum est d\'une semaine, du samedi au samedi.', 5],
    ['location-villa-provence', 'fr', 'Y a-t-il des commerces à proximité ?', 'Oui, Bédarrides dispose de boulangeries, supérette, restaurants et pharmacie. Le supermarché le plus proche est à Sorgues, à 5 minutes en voiture.', 6],
];
foreach ($faqs as $f) {
    Database::insert('vp_faq', [
        'page_slug' => $f[0], 'lang' => $f[1], 'question' => $f[2], 'answer' => $f[3], 'position' => $f[4],
    ]);
}
echo "✅ " . count($faqs) . " FAQ créées\n";

// --- Avis clients ---
Database::query("DELETE FROM vp_reviews");
$reviews = [
    ['Airbnb', 'villa', 'Marianne', 'Waterloo, Belgique', 'Un endroit magnifique, calme et reposant. La piscine est superbe et le jardin enchanteur. Nous avons passé deux semaines inoubliables en famille.', 5, '2025-08-15', 1, 1],
    ['Airbnb', 'villa', 'Rachel', '', 'Cette maison est exactement comme sur les photos. Spacieuse, bien équipée, avec une piscine parfaite. Les enfants ont adoré.', 5, '2023-08-10', 1, 1],
    ['Airbnb', 'villa', 'Carina', 'Le Coteau, France', 'Séjour merveilleux dans cette villa provençale. Tout est pensé pour le confort des voyageurs.', 5, '2022-08-20', 1, 1],
    ['Airbnb', 'villa', 'Charlotte', 'Allemagne', 'Wunderbar! Die Villa ist perfekt für Familien. Der Pool, der Garten, alles war traumhaft.', 5, '2022-07-25', 1, 1],
    ['Airbnb', 'bb', 'Mathieu', 'Riols, France', 'Accueil chaleureux, petit-déjeuner excellent avec des produits locaux. La chambre est confortable et la piscine un vrai plus.', 5, '2025-10-12', 1, 1],
    ['Airbnb', 'bb', 'Rosemarie', 'Northampton, Royaume-Uni', 'A lovely stay. The hosts are warm and welcoming. The breakfast was delicious and the pool a wonderful bonus.', 5, '2025-09-18', 1, 1],
    ['Airbnb', 'bb', 'Manon', 'Annecy, France', 'Un havre de paix. Le cadre est superbe, l\'accueil personnalisé, et le petit-déjeuner un vrai régal.', 5, '2025-05-22', 1, 1],
    ['Booking', 'bb', 'Jeroen', 'Pays-Bas', 'Perfect verblijf. Gastvrije ontvangst, heerlijk ontbijt, prachtige tuin en zwembad.', 10, '2025-06-15', 1, 1],
    ['Booking', 'bb', 'Giorgos', 'Grèce', 'Exceptional hospitality. The location is perfect for exploring Provence. Will definitely return.', 10, '2025-06-08', 1, 1],
    ['Google', 'bb', 'Achim Donald', '', 'Magnifique endroit, hôtes très accueillants. Le petit-déjeuner est un moment fort du séjour.', 5, '2025-04-20', 1, 0],
];
foreach ($reviews as $r) {
    Database::insert('vp_reviews', [
        'platform' => $r[0], 'offer' => $r[1], 'author' => $r[2], 'origin' => $r[3],
        'content' => $r[4], 'rating' => $r[5], 'review_date' => $r[6],
        'featured' => $r[7], 'home_carousel' => $r[8],
    ]);
}
echo "✅ " . count($reviews) . " avis créés\n";

// --- Settings ---
Database::query("DELETE FROM vp_settings");
$settings = [
    ['phone', '+33 6 00 00 00 00'],
    ['email', 'contact@villaplaisance.fr'],
    ['address', 'Bédarrides, 84370 Vaucluse, France'],
    ['booking_url_bb', ''],
    ['booking_url_villa', ''],
    ['instagram', ''],
    ['facebook', ''],
    ['site_name', 'Villa Plaisance'],
];
foreach ($settings as $s) {
    Database::insert('vp_settings', ['setting_key' => $s[0], 'setting_value' => $s[1]]);
}
echo "✅ " . count($settings) . " réglages créés\n";

// --- Livret d'accueil ---
Database::query("DELETE FROM vp_livret");
$livret = [
    ['bb', 'Bienvenue', 'Bienvenue à Villa Plaisance ! Nous sommes ravis de vous accueillir. Voici quelques informations pour rendre votre séjour agréable.', 1, 'fr'],
    ['bb', 'Wifi', 'Réseau : VillaPlaisance\nMot de passe : (à définir)', 2, 'fr'],
    ['bb', 'Petit-déjeuner', 'Le petit-déjeuner est servi entre 8h30 et 10h00, en terrasse quand le temps le permet. Merci de nous prévenir de tout régime alimentaire spécifique.', 3, 'fr'],
    ['bb', 'Piscine', 'La piscine est accessible de 9h à 20h. Merci de doucher avant de vous baigner. Serviettes de piscine fournies.', 4, 'fr'],
    ['bb', 'Aux alentours', 'Nous avons préparé une sélection de nos adresses préférées dans la section "Sur place" du site.', 5, 'fr'],
    ['villa', 'Bienvenue', 'Bienvenue à Villa Plaisance ! La maison est à vous pour la durée de votre séjour. Voici les informations essentielles.', 1, 'fr'],
    ['villa', 'Wifi', 'Réseau : VillaPlaisance\nMot de passe : (à définir)', 2, 'fr'],
    ['villa', 'Piscine', 'La piscine est privée pendant votre séjour. Horaires recommandés : 9h à 21h. Merci de surveiller les enfants en permanence.', 3, 'fr'],
    ['villa', 'Cuisine', 'La cuisine est entièrement équipée. Vaisselle, ustensiles, lave-vaisselle, four, micro-ondes. Merci de laisser la cuisine propre en fin de séjour.', 4, 'fr'],
    ['villa', 'Poubelles', 'Tri sélectif : poubelle jaune (recyclable), poubelle noire (ordures). Point de collecte à 50m de la maison.', 5, 'fr'],
    ['villa', 'Départ', 'Check-out à 10h le samedi. Merci de laisser les clés sur la table de la cuisine et de fermer les volets.', 6, 'fr'],
];
foreach ($livret as $l) {
    Database::insert('vp_livret', [
        'type' => $l[0], 'section_title' => $l[1], 'content' => $l[2],
        'position' => $l[3], 'lang' => $l[4],
    ]);
}
echo "✅ " . count($livret) . " sections livret créées\n";

// --- Articles Journal (10) ---
Database::query("DELETE FROM vp_articles");
$journalArticles = [
    ['journal', 'Voyager autrement', 'le-tourisme-de-masse-est-une-arnaque', 'Le tourisme de masse est une arnaque. Voilà pourquoi on y retourne quand même.', 'Pourquoi le tourisme de masse persiste malgré ses travers, et comment choisir une autre voie en Provence.', '2025-10-15'],
    ['journal', 'Voyager autrement', 'louer-maison-plutot-hotel-voyage', 'Louer une maison plutôt qu\'un hôtel : pourquoi ça change tout au voyage', 'Comparatif entre séjour hôtel et location de maison en Provence. Ce que ça change vraiment.', '2025-10-01'],
    ['journal', 'Hôtes & hôteliers', 'vie-proprietaire-chambre-hotes', 'Ce que personne ne dit sur la vie d\'un propriétaire de chambre d\'hôtes', 'Les coulisses du métier d\'hôte en Provence. Entre passion et réalité quotidienne.', '2025-09-20'],
    ['journal', 'Hôtes & hôteliers', 'recevoir-des-inconnus-chez-soi', 'Recevoir des inconnus chez soi : ce que ça apprend sur les gens', 'Ce que l\'accueil en chambres d\'hôtes révèle sur l\'hospitalité et les rencontres.', '2025-09-05'],
    ['journal', 'Territoire & transition', 'chateauneuf-du-pape-2026', 'Châteauneuf-du-Pape en 2026 : entre sécheresse et renaissance', 'Comment le vignoble de Châteauneuf-du-Pape s\'adapte au changement climatique.', '2025-08-25'],
    ['journal', 'Territoire & transition', 'provence-vignerons-autrement', 'La Provence qui résiste : portraits de vignerons qui font autrement', 'Rencontre avec des vignerons provençaux qui choisissent le bio et le respect du terroir.', '2025-08-10'],
    ['journal', 'L\'art de séjourner', 'duree-ideale-sejour-provence', 'Deux nuits ou deux semaines : comment trouver la durée idéale pour un séjour en Provence', 'Guide pour choisir la durée de son séjour en Provence selon ses envies.', '2025-07-28'],
    ['journal', 'L\'art de séjourner', 'deconnecter-provence', 'Déconnecter vraiment : ce que la Provence impose à ceux qui s\'y posent', 'Pourquoi la Provence est l\'endroit idéal pour une vraie coupure numérique.', '2025-07-15'],
    ['journal', 'Provence contemporaine', 'bedarrides-provence-authentique', 'Bédarrides n\'est pas sur les brochures. C\'est pour ça qu\'on y vit.', 'Portrait de Bédarrides, village provençal authentique loin du tourisme de masse.', '2025-07-01'],
    ['journal', 'Provence contemporaine', 'touriste-2026-nouvelles-attentes', 'Le touriste de 2026 : ce qu\'il veut vraiment, et ce que l\'industrie n\'a pas compris', 'Analyse des nouvelles attentes des voyageurs et comment l\'hébergement indépendant y répond.', '2025-06-15'],
];
foreach ($journalArticles as $a) {
    $content = json_encode([
        ['type' => 'paragraph', 'text' => $a[4]],
        ['type' => 'heading', 'text' => 'Introduction'],
        ['type' => 'paragraph', 'text' => 'Contenu de l\'article à rédiger. Cet article fait partie du journal de Villa Plaisance, chambres d\'hôtes et villa de charme à Bédarrides, au cœur du Triangle d\'Or provençal.'],
        ['type' => 'heading', 'text' => 'Notre point de vue'],
        ['type' => 'paragraph', 'text' => 'Villa Plaisance propose une approche différente de l\'hébergement en Provence. Ici, pas de standardisation, mais une attention portée à chaque détail, chaque matin, chaque rencontre.'],
    ], JSON_UNESCAPED_UNICODE);

    Database::insert('vp_articles', [
        'type' => $a[0], 'category' => $a[1], 'slug' => $a[2], 'lang' => 'fr',
        'title' => $a[3], 'excerpt' => $a[4], 'content' => $content,
        'meta_title' => mb_substr($a[3], 0, 60),
        'meta_desc' => mb_substr($a[4], 0, 160),
        'status' => 'published', 'published_at' => $a[5],
    ]);
}
echo "✅ " . count($journalArticles) . " articles journal créés\n";

// --- Articles Sur Place (9) ---
$surPlaceArticles = [
    ['sur-place', 'Commerces', 'courses-bedarrides-sorgues', 'Faire ses courses à Bédarrides et Sorgues', 'Les adresses qu\'on donne à nos hôtes pour les courses du quotidien.', '2025-10-10'],
    ['sur-place', 'Commerces', 'artisans-savonnerie-chocolaterie', 'Savonnerie et chocolaterie : deux adresses artisanales', 'Deux artisans à découvrir autour de Bédarrides.', '2025-10-05'],
    ['sur-place', 'Sites à visiter', 'fontaine-de-vaucluse-guide-pratique', 'Fontaine de Vaucluse : le guide pratique', 'Ce qu\'on ne vous dit pas toujours avant de visiter Fontaine de Vaucluse.', '2025-09-28'],
    ['sur-place', 'Sites à visiter', 'sentier-des-ocres-roussillon', 'Le Sentier des Ocres de Roussillon', 'Guide pratique avant de partir sur le sentier des ocres.', '2025-09-15'],
    ['sur-place', 'Sites à visiter', 'chateau-la-gardine-chateauneuf-du-pape', 'Château de la Gardine : dégustation à Châteauneuf-du-Pape', 'Dégustation à 8 minutes de Bédarrides, dans l\'un des domaines historiques de l\'appellation.', '2025-09-01'],
    ['sur-place', 'Que faire avec des enfants', 'parc-spirou-provence-monteux', 'Parc Spirou Provence : tout savoir avant d\'y aller', 'Guide pratique pour visiter le Parc Spirou avec des enfants.', '2025-08-20'],
    ['sur-place', 'Que faire avec des enfants', 'ateliers-creatifs-enfants-provence', 'Ateliers créatifs pour enfants en Provence', 'Notre sélection vérifiée d\'ateliers pour enfants autour de Bédarrides.', '2025-08-15'],
    ['sur-place', 'Restaurants & tables', 'imperial-bus-diner-bedarrides', 'Impérial Bus Diner : le burger de Bédarrides', 'Le burger de Bédarrides, 4,5/5 sur 340 avis. À tester.', '2025-08-05'],
    ['sur-place', 'Restaurants & tables', 'le-numero-3-bedarrides', 'Le Numéro 3 : le bistrot de Bédarrides', 'Le bistrot de Bédarrides, en bord d\'Ouvèze. Cuisine simple et sincère.', '2025-07-20'],
];
foreach ($surPlaceArticles as $a) {
    $content = json_encode([
        ['type' => 'paragraph', 'text' => $a[4]],
        ['type' => 'heading', 'text' => 'Informations pratiques'],
        ['type' => 'paragraph', 'text' => 'Contenu détaillé à rédiger. Cette fiche fait partie des recommandations de Villa Plaisance pour les hôtes séjournant à Bédarrides.'],
    ], JSON_UNESCAPED_UNICODE);

    Database::insert('vp_articles', [
        'type' => $a[0], 'category' => $a[1], 'slug' => $a[2], 'lang' => 'fr',
        'title' => $a[3], 'excerpt' => $a[4], 'content' => $content,
        'meta_title' => mb_substr($a[3], 0, 60),
        'meta_desc' => mb_substr($a[4], 0, 160),
        'status' => 'published', 'published_at' => $a[5],
    ]);
}
echo "✅ " . count($surPlaceArticles) . " articles sur-place créés\n";

echo "\n=== Seed terminé ===\n";
