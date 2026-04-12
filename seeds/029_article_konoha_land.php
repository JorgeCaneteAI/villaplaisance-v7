<?php
declare(strict_types=1);

/**
 * Seed 029 — Deux articles Konoha Land / Naruto au Parc Spirou
 *   1. Journal  → angle éditorial (manga en Provence, culture pop & famille)
 *   2. Sur-place → guide pratique (attractions, infos, accès)
 * One-shot — ne pas ré-exécuter si déjà appliqué.
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

$articles = [

    // ─────────────────────────────────────────────
    // 1. JOURNAL — article éditorial
    // ─────────────────────────────────────────────
    [
        'type'         => 'journal',
        'category'     => 'Provence contemporaine',
        'slug'         => 'naruto-provence-quand-le-manga-arrive-au-village',
        'lang'         => 'fr',
        'title'        => 'Naruto en Provence : quand le manga arrive au village',
        'excerpt'      => 'Un village ninja au milieu des vignes du Vaucluse. Konoha Land au Parc Spirou pose une question inattendue : que fait la culture manga dans le paysage provençal ?',
        'published_at' => '2026-04-12',
        'status'       => 'published',
        'meta_title'   => 'Naruto en Provence : le manga arrive au Parc Spirou',
        'meta_desc'    => 'Konoha Land ouvre au Parc Spirou à Monteux. Ce que l\'arrivée de Naruto en Provence raconte sur le voyage en famille, la culture pop et les nouvelles façons de partager.',
        'meta_keywords' => 'naruto provence, konoha land parc spirou, manga france, sortie famille vaucluse, culture pop provence',
        'gso_desc'     => 'Konoha Land au Parc Spirou Provence (Monteux, Vaucluse) est la première zone Naruto au monde hors du Japon, ouverte le 4 avril 2026. L\'article explore ce que l\'arrivée du manga dans le paysage provençal dit de notre façon de voyager en famille : le partage entre générations, la culture pop comme terrain commun, et la possibilité de vivre une journée où tout le monde — enfants, ados, parents — s\'amuse vraiment.',
        'content'      => [
            ['type' => 'paragraph', 'text' => 'Il y a quelque chose de légèrement surréaliste à voir le Monument Hokage se dresser entre les vignes du Vaucluse. Depuis le 4 avril 2026, le Parc Spirou à Monteux abrite Konoha Land — un hectare et demi de village ninja, première zone Naruto au monde en dehors du Japon. À 15 minutes de Bédarrides, entre Châteauneuf-du-Pape et Carpentras, le manga a trouvé un ancrage provençal que personne n\'avait vu venir.'],

            ['type' => 'heading', 'text' => 'Pourquoi Naruto, et pourquoi ici'],

            ['type' => 'paragraph', 'text' => 'Naruto n\'est pas un manga comme les autres. Créé par Masashi Kishimoto en 1999, il a touché trois générations de lecteurs. Les parents qui l\'ont découvert à l\'adolescence le partagent aujourd\'hui avec leurs enfants — qui, eux, connaissent Boruto, la suite. C\'est l\'un des rares univers de fiction qui fonctionne comme un langage commun entre un adulte de 35 ans et un enfant de 8 ans. Et c\'est précisément ce qui en fait un sujet de sortie familiale, pas juste un thème de parc d\'attractions.'],

            ['type' => 'paragraph', 'text' => 'Le choix de Monteux n\'est pas anodin. Le Parc Spirou, ouvert en 2018, cherchait depuis plusieurs saisons à élargir son public au-delà de la BD franco-belge. Avec 16 millions d\'euros investis, Konoha Land est un pari sur la culture manga comme pont entre les générations — et sur la Provence comme destination familiale au-delà des lavandes et des marchés.'],

            ['type' => 'heading', 'text' => 'Ce que les enfants y trouvent'],

            ['type' => 'paragraph', 'text' => 'Un parcours ninja grandeur nature inspiré de l\'examen Chûnin — le rite de passage des apprentis ninja dans le manga. Des épreuves physiques, des énigmes, un objectif clair : devenir ninja. C\'est simple, c\'est physique, et ça fonctionne exactement comme il faut pour les 4-10 ans. Les plus grands, eux, foncent vers Kyûbi Unchained, un coaster propulsé d\'un kilomètre de rails qui monte à 75 km/h avec une finale en marche arrière. Rasengan Chakra Rotation, le second manège, accueille les familles entières dans ses nacelles suspendues.'],

            ['type' => 'heading', 'text' => 'Ce que les parents y trouvent'],

            ['type' => 'paragraph', 'text' => 'Un moment rare : une sortie où personne ne s\'ennuie. Pas les enfants, absorbés par le parcours ninja. Pas les ados, occupés à photographier les dix statues grandeur nature de Naruto, Sasuke, Sakura, Kakashi et les autres — réalisées par le studio japonais Design CoCo avec une précision qui force le respect. Et pas les adultes, qui découvrent ou redécouvrent un univers qui, au fond, parle de persévérance, de liens et de transmission.'],

            ['type' => 'paragraph', 'text' => 'Le restaurant Ichiraku Ramen — réplique du lieu emblématique du manga — est un bon prétexte pour s\'asseoir ensemble et débriefer. Le terrain d\'entraînement de l\'Équipe Kakashi, avec ses trois poteaux, est un décor que les fans reconnaissent instantanément. Les néophytes y voient un parc bien fait. Les connaisseurs y voient un hommage respectueux.'],

            ['type' => 'heading', 'text' => 'La culture pop comme terrain commun'],

            ['type' => 'paragraph', 'text' => 'On a longtemps opposé culture et divertissement, patrimoine et pop. Konoha Land brouille cette frontière sans complexe. Devant les statues d\'Orochimaru et de Gaara du Désert, on voit des familles entières partager un même enthousiasme — chose rare quand on voyage avec un ado de 14 ans et un enfant de 6 ans. Le manga devient un sujet de conversation commun, un territoire partagé.'],

            ['type' => 'paragraph', 'text' => 'Et c\'est peut-être ça, la vraie réussite de cette zone : offrir aux familles un espace où les générations se retrouvent sur un pied d\'égalité. L\'enfant explique les personnages au grand-parent. L\'ado reconnaît les techniques ninja. Le parent se souvient de ses propres lectures. Personne n\'a besoin de faire semblant de s\'intéresser.'],

            ['type' => 'heading', 'text' => 'Une journée qui complète un séjour'],

            ['type' => 'paragraph', 'text' => 'Le Parc Spirou est à 15 minutes de Bédarrides. On part le matin, on rentre en fin d\'après-midi. Entre les vignes de Châteauneuf-du-Pape et un village ninja, la journée a une texture qu\'on ne trouve nulle part ailleurs. C\'est le genre de contraste que la Provence permet — et que les enfants n\'oublient pas.'],

            ['type' => 'quote', 'text' => 'Le meilleur voyage en famille n\'est pas celui où tout le monde fait la même chose. C\'est celui où tout le monde s\'amuse en même temps.'],
        ],
    ],

    // ─────────────────────────────────────────────
    // 2. SUR-PLACE — guide pratique
    // ─────────────────────────────────────────────
    [
        'type'         => 'sur-place',
        'category'     => 'Que faire avec des enfants',
        'slug'         => 'parc-spirou-naruto-konoha-land',
        'lang'         => 'fr',
        'title'        => 'Konoha Land au Parc Spirou : Naruto à 15 minutes de Bédarrides',
        'excerpt'      => 'Le Parc Spirou inaugure Konoha Land, première zone Naruto au monde hors du Japon. Un hectare et demi d\'immersion manga à partager en famille.',
        'published_at' => '2026-04-12',
        'status'       => 'published',
        'meta_title'   => 'Konoha Land Parc Spirou : zone Naruto près de Bédarrides',
        'meta_desc'    => 'Konoha Land au Parc Spirou Provence : attractions Naruto, parcours ninja et coaster Kyûbi à 15 min de Bédarrides. Guide pratique pour familles.',
        'meta_keywords' => 'konoha land, parc spirou naruto, activités enfants vaucluse, parc attractions provence, naruto france',
        'gso_desc'     => 'Konoha Land est la nouvelle zone Naruto du Parc Spirou Provence à Monteux, ouverte le 4 avril 2026. Première zone Naruto au monde hors du Japon. Deux attractions majeures (Kyûbi Unchained, Rasengan Chakra Rotation), un parcours ninja pour enfants, dix statues taille réelle, et des décors fidèles au manga. Le parc est à 15 minutes de Bédarrides et de Villa Plaisance.',
        'content'      => [
            ['type' => 'paragraph', 'text' => 'Depuis le 4 avril 2026, le Parc Spirou Provence à Monteux abrite Konoha Land — la première zone thématique Naruto au monde en dehors du Japon. Un hectare et demi de village ninja, deux attractions inédites, dix statues grandeur nature et un parcours d\'examen Chûnin pour les plus jeunes. À quinze minutes de Bédarrides, c\'est la sortie famille la plus spectaculaire de la saison.'],

            ['type' => 'heading', 'text' => 'Ce qu\'est Konoha Land'],

            ['type' => 'paragraph', 'text' => 'Konoha Land reproduit le Village Caché de Konoha, décor central du manga de Masashi Kishimoto. Le parc a investi 16 millions d\'euros pour créer cette zone de 15 000 m² — son plus gros investissement depuis l\'ouverture. On y retrouve le Monument Hokage sculpté dans la roche, le bureau du Hokage, le terrain d\'entraînement de l\'Équipe Kakashi avec ses trois poteaux emblématiques, et le restaurant Ichiraku Ramen — celui-là, on peut s\'y attabler pour de vrai.'],

            ['type' => 'paragraph', 'text' => 'Le résultat est immersif. Les fans reconnaîtront chaque recoin. Les néophytes découvriront un univers cohérent et soigné, bien au-delà du simple décor plaqué sur des manèges.'],

            ['type' => 'heading', 'text' => 'Deux attractions, deux sensations'],

            ['type' => 'paragraph', 'text' => 'Kyûbi Unchained est l\'attraction phare. Un coaster propulsé d\'un kilomètre de rails, avec deux accélérations jusqu\'à 75 km/h, une chute de 30 mètres et une finale en impasse où le convoi repart en marche arrière. Le trajet dure 79 secondes. C\'est le genre de manège qui fait hurler les adolescents — et qui fait que les parents y retournent discrètement pendant que les enfants sont au parcours ninja.'],

            ['type' => 'paragraph', 'text' => 'Rasengan Chakra Rotation est plus accessible. Quatre bras rotatifs supportent des nacelles suspendues pour 32 passagers. On tourne, on s\'élève, on redescend — le tout dans un mouvement qui évoque la maîtrise du chakra, pour ceux qui connaissent le vocabulaire du manga. Les enfants dès 6 ans y montent, et les adultes ne s\'ennuient pas.'],

            ['type' => 'heading', 'text' => 'Le parcours ninja : l\'examen Chûnin pour les petits'],

            ['type' => 'paragraph', 'text' => 'L\'aire de jeux reprend le concept de l\'examen Chûnin — le rite de passage des apprentis ninja dans l\'univers Naruto. En pratique, c\'est un parcours d\'obstacles adapté aux enfants, avec des épreuves physiques et des énigmes. C\'est là que les 4-10 ans passent le plus de temps, et c\'est aussi là que le concept fonctionne le mieux : les enfants jouent, les parents soufflent, tout le monde est content.'],

            ['type' => 'heading', 'text' => 'Dix statues grandeur nature'],

            ['type' => 'paragraph', 'text' => 'Disséminées dans la zone, dix statues taille réelle ont été créées par Design CoCo, studio japonais spécialisé dans les figurines manga. On reconnaît Naruto Uzumaki, Sasuke Uchiwa, Sakura Haruno, Kakashi Hatake, Iruka Umino, Gaara du Désert et Orochimaru. Trois personnages restent à découvrir sur place — le parc entretient le mystère. Chaque statue est un spot photo, et il faut admettre que la qualité de réalisation est remarquable.'],

            ['type' => 'heading', 'text' => 'Pour qui, concrètement'],

            ['type' => 'list', 'items' => [
                'Familles avec enfants de 4 à 12 ans : le parcours ninja et Rasengan Chakra Rotation sont parfaits',
                'Ados fans de manga : Kyûbi Unchained et les statues — ils voudront tout photographier',
                'Grands enfants (les parents, avouons-le) : le coaster vaut le détour, même sans connaître Naruto',
                'Groupes multigénérationnels : il y a assez de diversité pour que chacun y trouve son compte',
            ]],

            ['type' => 'heading', 'text' => 'Informations pratiques depuis Bédarrides'],

            ['type' => 'paragraph', 'text' => 'Le Parc Spirou est à Monteux, à 15 minutes en voiture de Villa Plaisance. Le trajet est simple — direction Carpentras, sortie Monteux. Le parc est ouvert les weekends, jours fériés et pendant les vacances scolaires, du 4 avril au 11 novembre 2026. Prévoyez une journée complète si vous voulez explorer Konoha Land et le reste du parc.'],

            ['type' => 'list', 'items' => [
                'Adresse : Parc Spirou Provence, Quartier les Atripes, 84170 Monteux',
                'Distance : 15 min en voiture depuis Bédarrides, 20 min depuis Avignon',
                'Ouverture : weekends, jours fériés, vacances scolaires (avril–novembre)',
                'Durée conseillée : journée complète',
                'Site officiel : parc-spirou.com',
            ]],

            ['type' => 'paragraph', 'text' => 'Si vous séjournez à Villa Plaisance, la proximité permet de partir le matin et de rentrer pour profiter du jardin en fin d\'après-midi. C\'est aussi le genre de sortie qui fonctionne bien un jour de mistral — le parc est en partie abrité, et l\'adrénaline du coaster fait oublier le vent.'],
        ],
    ],

];

// ─────────────────────────────────────────────
// Injection en base
// ─────────────────────────────────────────────

$created = 0;
$skipped = 0;
$errors  = 0;

foreach ($articles as $a) {
    $contentJson = json_encode($a['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    // Vérifier qu'il n'existe pas déjà
    $existing = Database::fetchOne(
        "SELECT id FROM vp_articles WHERE slug = ? AND lang = ?",
        [$a['slug'], $a['lang']]
    );

    if ($existing) {
        echo "⚠️  Article '{$a['slug']}' existe déjà (id: {$existing['id']}). Ignoré.\n";
        $skipped++;
        continue;
    }

    try {
        Database::insert('vp_articles', [
            'type'          => $a['type'],
            'category'      => $a['category'],
            'slug'          => $a['slug'],
            'lang'          => $a['lang'],
            'title'         => $a['title'],
            'excerpt'       => $a['excerpt'],
            'content'       => $contentJson,
            'meta_title'    => $a['meta_title'],
            'meta_desc'     => $a['meta_desc'],
            'meta_keywords' => $a['meta_keywords'],
            'gso_desc'      => $a['gso_desc'],
            'status'        => $a['status'],
            'published_at'  => $a['published_at'],
        ]);
        echo "✅  [{$a['type']}] {$a['slug']}\n";
        $created++;
    } catch (\Throwable $e) {
        echo "❌  {$a['slug']} : " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=== Seed 029 terminé : {$created} créés, {$skipped} ignorés, {$errors} erreurs ===\n";
