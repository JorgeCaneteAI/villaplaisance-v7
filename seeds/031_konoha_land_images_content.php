<?php
declare(strict_types=1);

/**
 * Seed 031 — Enrichir les deux articles Konoha Land avec images intégrées
 * Met à jour le content JSON + cover_image des deux articles.
 * Pré-requis : les 6 images WebP doivent être dans public/uploads/
 * One-shot — ne pas ré-exécuter si déjà appliqué.
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

$articles = [

    // ─────────────────────────────────────────────
    // 1. JOURNAL — article éditorial (3 images)
    // ─────────────────────────────────────────────
    [
        'slug'        => 'naruto-provence-quand-le-manga-arrive-au-village',
        'cover_image' => 'konoha-land-vue.webp',
        'content'     => [
            ['type' => 'paragraph', 'text' => 'Il y a quelque chose de légèrement surréaliste à voir le Monument Hokage se dresser entre les vignes du Vaucluse. Depuis le 4 avril 2026, le Parc Spirou à Monteux abrite Konoha Land — un hectare et demi de village ninja, première zone Naruto au monde en dehors du Japon. À 15 minutes de <a href="/chambres-d-hotes">Bédarrides</a>, entre Châteauneuf-du-Pape et Carpentras, le manga a trouvé un ancrage provençal que personne n\'avait vu venir.'],

            ['type' => 'heading', 'text' => 'Pourquoi Naruto, et pourquoi ici'],

            ['type' => 'paragraph', 'text' => 'Naruto n\'est pas un manga comme les autres. Créé par Masashi Kishimoto en 1999, il a touché trois générations de lecteurs. Les parents qui l\'ont découvert à l\'adolescence le partagent aujourd\'hui avec leurs enfants — qui, eux, connaissent Boruto, la suite. C\'est l\'un des rares univers de fiction qui fonctionne comme un langage commun entre un adulte de 35 ans et un enfant de 8 ans. Et c\'est précisément ce qui en fait un sujet de sortie familiale, pas juste un thème de parc d\'attractions.'],

            ['type' => 'paragraph', 'text' => 'Le choix de Monteux n\'est pas anodin. Le Parc Spirou, ouvert en 2018, cherchait depuis plusieurs saisons à élargir son public au-delà de la BD franco-belge. Avec 16 millions d\'euros investis, Konoha Land est un pari sur la culture manga comme pont entre les générations — et sur la Provence comme destination familiale au-delà des lavandes et des marchés.'],

            ['type' => 'image', 'src' => 'bureau-hokage.webp', 'alt' => 'Le Bureau du Hokage reconstitué à Konoha Land, Parc Spirou Provence', 'caption' => 'Le Bureau du Hokage — un décor fidèle au manga que les fans reconnaissent immédiatement.'],

            ['type' => 'heading', 'text' => 'Ce que les enfants y trouvent'],

            ['type' => 'paragraph', 'text' => 'Un parcours ninja grandeur nature inspiré de l\'examen Chûnin — le rite de passage des apprentis ninja dans le manga. Des épreuves physiques, des énigmes, un objectif clair : devenir ninja. C\'est simple, c\'est physique, et ça fonctionne exactement comme il faut pour les 4-10 ans. Les plus grands, eux, foncent vers Kyûbi Unchained, un coaster propulsé d\'un kilomètre de rails qui monte à 75 km/h avec une finale en marche arrière. Rasengan Chakra Rotation, le second manège, accueille les familles entières dans ses nacelles suspendues.'],

            ['type' => 'heading', 'text' => 'Ce que les parents y trouvent'],

            ['type' => 'paragraph', 'text' => 'Un moment rare : une sortie où personne ne s\'ennuie. Pas les enfants, absorbés par le parcours ninja. Pas les ados, occupés à photographier les dix statues grandeur nature de Naruto, Sasuke, Sakura, Kakashi et les autres — réalisées par le studio japonais Design CoCo avec une précision qui force le respect. Et pas les adultes, qui découvrent ou redécouvrent un univers qui, au fond, parle de persévérance, de liens et de transmission.'],

            ['type' => 'image', 'src' => 'ichiraku-ramen.webp', 'alt' => 'Restaurant Ichiraku Ramen à Konoha Land, Parc Spirou Provence', 'caption' => 'Le restaurant Ichiraku Ramen — on s\'y attable pour de vrai.'],

            ['type' => 'paragraph', 'text' => 'Le restaurant Ichiraku Ramen — réplique du lieu emblématique du manga — est un bon prétexte pour s\'asseoir ensemble et débriefer. Le terrain d\'entraînement de l\'Équipe Kakashi, avec ses trois poteaux, est un décor que les fans reconnaissent instantanément. Les néophytes y voient un parc bien fait. Les connaisseurs y voient un hommage respectueux.'],

            ['type' => 'heading', 'text' => 'La culture pop comme terrain commun'],

            ['type' => 'paragraph', 'text' => 'On a longtemps opposé culture et divertissement, patrimoine et pop. Konoha Land brouille cette frontière sans complexe. Devant les statues d\'Orochimaru et de Gaara du Désert, on voit des familles entières partager un même enthousiasme — chose rare quand on voyage avec un ado de 14 ans et un enfant de 6 ans. Le manga devient un sujet de conversation commun, un territoire partagé.'],

            ['type' => 'paragraph', 'text' => 'Et c\'est peut-être ça, la vraie réussite de cette zone : offrir aux familles un espace où les générations se retrouvent sur un pied d\'égalité. L\'enfant explique les personnages au grand-parent. L\'ado reconnaît les techniques ninja. Le parent se souvient de ses propres lectures. Personne n\'a besoin de faire semblant de s\'intéresser.'],

            ['type' => 'heading', 'text' => 'Une journée qui complète un séjour'],

            ['type' => 'paragraph', 'text' => 'Le Parc Spirou est à 15 minutes de Bédarrides. On part le matin, on rentre en fin d\'après-midi pour profiter du <a href="/espaces-exterieurs">jardin et de la piscine</a>. Entre les vignes de Châteauneuf-du-Pape et un village ninja, la journée a une texture qu\'on ne trouve nulle part ailleurs. C\'est le genre de contraste que la Provence permet — et que les enfants n\'oublient pas.'],

            ['type' => 'paragraph', 'text' => 'Pour les détails pratiques — horaires, accès, attractions par âge — consultez notre <a href="/sur-place/parc-spirou-naruto-konoha-land">guide complet Konoha Land</a>.'],

            ['type' => 'quote', 'text' => 'Le meilleur voyage en famille n\'est pas celui où tout le monde fait la même chose. C\'est celui où tout le monde s\'amuse en même temps.'],
        ],
    ],

    // ─────────────────────────────────────────────
    // 2. SUR-PLACE — guide pratique (4 images)
    // ─────────────────────────────────────────────
    [
        'slug'        => 'parc-spirou-naruto-konoha-land',
        'cover_image' => 'kyubi-unchained.webp',
        'content'     => [
            ['type' => 'paragraph', 'text' => 'Depuis le 4 avril 2026, le Parc Spirou Provence à Monteux abrite Konoha Land — la première zone thématique Naruto au monde en dehors du Japon. Un hectare et demi de village ninja, deux attractions inédites, dix statues grandeur nature et un parcours d\'examen Chûnin pour les plus jeunes. À quinze minutes de <a href="/chambres-d-hotes">Bédarrides</a>, c\'est la sortie famille la plus spectaculaire de la saison.'],

            ['type' => 'heading', 'text' => 'Ce qu\'est Konoha Land'],

            ['type' => 'paragraph', 'text' => 'Konoha Land reproduit le Village Caché de Konoha, décor central du manga de Masashi Kishimoto. Le parc a investi 16 millions d\'euros pour créer cette zone de 15 000 m² — son plus gros investissement depuis l\'ouverture. On y retrouve le Monument Hokage sculpté dans la roche, le bureau du Hokage, le terrain d\'entraînement de l\'Équipe Kakashi avec ses trois poteaux emblématiques, et le restaurant Ichiraku Ramen — celui-là, on peut s\'y attabler pour de vrai.'],

            ['type' => 'image', 'src' => 'konoha-land-vue.webp', 'alt' => 'Vue d\'ensemble de Konoha Land au Parc Spirou Provence à Monteux', 'caption' => 'Konoha Land — 15 000 m² de village ninja au cœur du Vaucluse.'],

            ['type' => 'paragraph', 'text' => 'Le résultat est immersif. Les fans reconnaîtront chaque recoin. Les néophytes découvriront un univers cohérent et soigné, bien au-delà du simple décor plaqué sur des manèges. Pour comprendre pourquoi Naruto en Provence a du sens, lisez <a href="/journal/naruto-provence-quand-le-manga-arrive-au-village">notre article dédié</a>.'],

            ['type' => 'heading', 'text' => 'Deux attractions, deux sensations'],

            ['type' => 'paragraph', 'text' => 'Kyûbi Unchained est l\'attraction phare. Un coaster propulsé d\'un kilomètre de rails, avec deux accélérations jusqu\'à 75 km/h, une chute de 30 mètres et une finale en impasse où le convoi repart en marche arrière. Le trajet dure 79 secondes. C\'est le genre de manège qui fait hurler les adolescents — et qui fait que les parents y retournent discrètement pendant que les enfants sont au parcours ninja.'],

            ['type' => 'image', 'src' => 'rasengan-chakra-rotation.webp', 'alt' => 'Attraction Rasengan Chakra Rotation à Konoha Land, Parc Spirou', 'caption' => 'Rasengan Chakra Rotation — nacelles suspendues pour toute la famille.'],

            ['type' => 'paragraph', 'text' => 'Rasengan Chakra Rotation est plus accessible. Quatre bras rotatifs supportent des nacelles suspendues pour 32 passagers. On tourne, on s\'élève, on redescend — le tout dans un mouvement qui évoque la maîtrise du chakra, pour ceux qui connaissent le vocabulaire du manga. Les enfants dès 6 ans y montent, et les adultes ne s\'ennuient pas.'],

            ['type' => 'heading', 'text' => 'Le parcours ninja : l\'examen Chûnin pour les petits'],

            ['type' => 'paragraph', 'text' => 'L\'aire de jeux reprend le concept de l\'examen Chûnin — le rite de passage des apprentis ninja dans l\'univers Naruto. En pratique, c\'est un parcours d\'obstacles adapté aux enfants, avec des épreuves physiques et des énigmes. C\'est là que les 4-10 ans passent le plus de temps, et c\'est aussi là que le concept fonctionne le mieux : les enfants jouent, les parents soufflent, tout le monde est content.'],

            ['type' => 'heading', 'text' => 'Dix statues grandeur nature'],

            ['type' => 'paragraph', 'text' => 'Disséminées dans la zone, dix statues taille réelle ont été créées par Design CoCo, studio japonais spécialisé dans les figurines manga. On reconnaît Naruto Uzumaki, Sasuke Uchiwa, Sakura Haruno, Kakashi Hatake, Iruka Umino, Gaara du Désert et Orochimaru. Trois personnages restent à découvrir sur place — le parc entretient le mystère. Chaque statue est un spot photo, et il faut admettre que la qualité de réalisation est remarquable.'],

            ['type' => 'heading', 'text' => 'Se restaurer sur place'],

            ['type' => 'image', 'src' => 'ichiraku-ramen.webp', 'alt' => 'Restaurant Ichiraku Ramen à Konoha Land, Parc Spirou Provence', 'caption' => 'Ichiraku Ramen — le restaurant emblématique du manga, en vrai.'],

            ['type' => 'paragraph', 'text' => 'Deux adresses thématiques dans la zone : le restaurant Ichiraku Ramen, fidèle réplique du lieu où Naruto engloutit ses bols de nouilles, et le Dango Shop pour une pause sucrée. Les deux sont intégrés aux décors — on mange dans l\'univers, pas à côté.'],

            ['type' => 'image', 'src' => 'dango-shop.webp', 'alt' => 'Dango Shop à Konoha Land, Parc Spirou Provence', 'caption' => 'Le Dango Shop — pause sucrée entre deux attractions.'],

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

            ['type' => 'paragraph', 'text' => 'Si vous séjournez à <a href="/chambres-d-hotes">Villa Plaisance</a>, la proximité permet de partir le matin et de rentrer pour profiter du <a href="/espaces-exterieurs">jardin et de la piscine</a> en fin d\'après-midi. C\'est aussi le genre de sortie qui fonctionne bien un jour de mistral — le parc est en partie abrité, et l\'adrénaline du coaster fait oublier le vent.'],
        ],
    ],

];

// ─────────────────────────────────────────────
// Mise à jour en base
// ─────────────────────────────────────────────

$updated = 0;
$errors  = 0;

foreach ($articles as $a) {
    $contentJson = json_encode($a['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    try {
        $existing = Database::fetchOne(
            "SELECT id FROM vp_articles WHERE slug = ? AND lang = 'fr'",
            [$a['slug']]
        );

        if (!$existing) {
            echo "⚠️  Article introuvable : {$a['slug']}\n";
            $errors++;
            continue;
        }

        Database::query(
            "UPDATE vp_articles SET content = ?, cover_image = ? WHERE slug = ? AND lang = 'fr'",
            [$contentJson, $a['cover_image'], $a['slug']]
        );

        echo "✅  {$a['slug']} → contenu + cover mis à jour\n";
        $updated++;
    } catch (\Throwable $e) {
        echo "❌  {$a['slug']} : " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=== Seed 031 terminé : {$updated} articles mis à jour, {$errors} erreurs ===\n";
