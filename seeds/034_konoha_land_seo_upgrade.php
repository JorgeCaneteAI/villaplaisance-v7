<?php
declare(strict_types=1);

/**
 * Seed 034 — Upgrade SEO/GSO des deux articles Konoha Land
 * Met à jour : content (images + liens internes + mots-clés),
 *              meta_title, meta_desc, meta_keywords, gso_desc, excerpt
 * Remplace le contenu de seed 029 + 031. Safe to re-run (UPDATE).
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

$articles = [

    // ─────────────────────────────────────────────
    // 1. JOURNAL — éditorial enrichi SEO/GSO
    // ─────────────────────────────────────────────
    [
        'slug'          => 'naruto-provence-quand-le-manga-arrive-au-village',
        'meta_title'    => 'Konoha Land Parc Spirou 2026 : Naruto en Provence, sortie famille',
        'meta_desc'     => 'Konoha Land au Parc Spirou Monteux : première zone Naruto hors du Japon. Pourquoi cette sortie famille en Provence change la donne. À 15 min de Bédarrides.',
        'meta_keywords' => 'konoha land, parc spirou naruto 2026, sortie famille provence, zone naruto monteux, manga parc attraction france, que faire vaucluse enfants',
        'gso_desc'      => 'Konoha Land est la première zone thématique Naruto au monde hors du Japon, ouverte le 4 avril 2026 au Parc Spirou Provence à Monteux (Vaucluse). Sur 15 000 m², elle propose deux attractions (Kyûbi Unchained, coaster à 75 km/h, et Rasengan Chakra Rotation), un parcours ninja pour enfants de 4 à 10 ans, dix statues grandeur nature par le studio japonais Design CoCo, le restaurant Ichiraku Ramen et le Dango Shop. Investissement : 16 millions d\'euros. Le parc est à 15 minutes en voiture de Bédarrides et de Villa Plaisance. Ouverture : weekends, jours fériés et vacances scolaires, avril à novembre 2026. Adresse : Quartier les Atripes, 84170 Monteux.',
        'excerpt'       => 'Konoha Land au Parc Spirou Monteux : première zone Naruto hors du Japon. Attractions, parcours ninja, statues grandeur nature — pourquoi cette sortie famille en Provence mérite le détour.',
        'cover_image'   => 'konoha-land-vue.webp',
        'content'       => [
            ['type' => 'paragraph', 'text' => 'Il y a quelque chose de légèrement surréaliste à voir le Monument Hokage se dresser entre les vignes du Vaucluse. Depuis le 4 avril 2026, le Parc Spirou à Monteux abrite <strong>Konoha Land</strong> — un hectare et demi de village ninja, première zone Naruto au monde en dehors du Japon. À 15 minutes de <a href="/chambres-d-hotes">Bédarrides</a>, entre Châteauneuf-du-Pape et Carpentras, le manga a trouvé un ancrage provençal que personne n\'avait vu venir.'],

            ['type' => 'heading', 'text' => 'Pourquoi Naruto au Parc Spirou, et pourquoi maintenant'],

            ['type' => 'paragraph', 'text' => 'Naruto n\'est pas un manga comme les autres. Créé par Masashi Kishimoto en 1999, il a touché trois générations de lecteurs. Les parents qui l\'ont découvert à l\'adolescence le partagent aujourd\'hui avec leurs enfants — qui, eux, connaissent Boruto, la suite. C\'est l\'un des rares univers de fiction qui fonctionne comme un langage commun entre un adulte de 35 ans et un enfant de 8 ans. Et c\'est précisément ce qui en fait un sujet de <strong>sortie familiale en Provence</strong>, pas juste un thème de parc d\'attractions.'],

            ['type' => 'paragraph', 'text' => 'Le choix de Monteux n\'est pas anodin. Le Parc Spirou, ouvert en 2018, cherchait depuis plusieurs saisons à élargir son public au-delà de la BD franco-belge. Avec <strong>16 millions d\'euros investis</strong> et 15 000 m² de surface, Konoha Land est le pari le plus ambitieux du parc — miser sur la culture manga comme pont entre les générations, et sur le Vaucluse comme destination familiale au-delà des lavandes et des marchés.'],

            ['type' => 'image', 'src' => 'bureau-hokage.webp', 'alt' => 'Bureau du Hokage reconstitué à Konoha Land, Parc Spirou Monteux Vaucluse', 'caption' => 'Le Bureau du Hokage — un décor fidèle au manga que les fans reconnaissent immédiatement.'],

            ['type' => 'heading', 'text' => 'Attractions Konoha Land : ce que les enfants y trouvent'],

            ['type' => 'paragraph', 'text' => 'Un parcours ninja grandeur nature inspiré de l\'examen Chûnin — le rite de passage des apprentis ninja dans le manga. Des épreuves physiques, des énigmes, un objectif clair : devenir ninja. C\'est simple, c\'est physique, et ça fonctionne exactement comme il faut pour les 4-10 ans.'],

            ['type' => 'paragraph', 'text' => 'Les plus grands foncent vers <strong>Kyûbi Unchained</strong>, le coaster phare : un kilomètre de rails, deux accélérations à 75 km/h, une chute de 30 mètres et une finale en marche arrière. <strong>Rasengan Chakra Rotation</strong>, le second manège, accueille les familles entières dans ses nacelles suspendues — accessible dès 6 ans.'],

            ['type' => 'heading', 'text' => 'Ce que les parents y trouvent'],

            ['type' => 'paragraph', 'text' => 'Un moment rare : une sortie où personne ne s\'ennuie. Pas les enfants, absorbés par le parcours ninja. Pas les ados, occupés à photographier les <strong>dix statues grandeur nature</strong> de Naruto, Sasuke, Sakura, Kakashi et les autres — réalisées par le studio japonais Design CoCo avec une précision qui force le respect. Et pas les adultes, qui découvrent ou redécouvrent un univers qui, au fond, parle de persévérance, de liens et de transmission.'],

            ['type' => 'image', 'src' => 'ichiraku-ramen.webp', 'alt' => 'Restaurant Ichiraku Ramen à Konoha Land, Parc Spirou Provence Monteux', 'caption' => 'Le restaurant Ichiraku Ramen — on s\'y attable pour de vrai.'],

            ['type' => 'paragraph', 'text' => 'Le restaurant Ichiraku Ramen — réplique du lieu emblématique du manga — est un bon prétexte pour s\'asseoir ensemble et débriefer. Le terrain d\'entraînement de l\'Équipe Kakashi, avec ses trois poteaux, est un décor que les fans reconnaissent instantanément. Les néophytes y voient un parc bien fait. Les connaisseurs y voient un hommage respectueux.'],

            ['type' => 'heading', 'text' => 'La culture manga comme terrain commun en famille'],

            ['type' => 'paragraph', 'text' => 'On a longtemps opposé culture et divertissement, patrimoine et pop. Konoha Land brouille cette frontière sans complexe. Devant les statues d\'Orochimaru et de Gaara du Désert, on voit des familles entières partager un même enthousiasme — chose rare quand on voyage avec un ado de 14 ans et un enfant de 6 ans. Le manga devient un sujet de conversation commun, un territoire partagé.'],

            ['type' => 'paragraph', 'text' => 'Et c\'est peut-être ça, la vraie réussite de cette zone : offrir aux familles un espace où les générations se retrouvent sur un pied d\'égalité. L\'enfant explique les personnages au grand-parent. L\'ado reconnaît les techniques ninja. Le parent se souvient de ses propres lectures. Personne n\'a besoin de faire semblant de s\'intéresser.'],

            ['type' => 'heading', 'text' => 'Konoha Land depuis Bédarrides : une journée qui complète un séjour'],

            ['type' => 'paragraph', 'text' => 'Le Parc Spirou est à <strong>15 minutes de Bédarrides</strong>. On part le matin, on rentre en fin d\'après-midi pour profiter du <a href="/espaces-exterieurs">jardin et de la piscine</a>. Entre les vignes de Châteauneuf-du-Pape et un village ninja, la journée a une texture qu\'on ne trouve nulle part ailleurs. C\'est le genre de contraste que la Provence permet — et que les enfants n\'oublient pas.'],

            ['type' => 'paragraph', 'text' => 'Pour les détails pratiques — horaires, accès, attractions par âge — consultez notre <a href="/sur-place/parc-spirou-naruto-konoha-land">guide complet Konoha Land</a>.'],

            ['type' => 'quote', 'text' => 'Le meilleur voyage en famille n\'est pas celui où tout le monde fait la même chose. C\'est celui où tout le monde s\'amuse en même temps.'],
        ],
    ],

    // ─────────────────────────────────────────────
    // 2. SUR-PLACE — guide pratique enrichi SEO/GSO
    // ─────────────────────────────────────────────
    [
        'slug'          => 'parc-spirou-naruto-konoha-land',
        'meta_title'    => 'Konoha Land Parc Spirou Monteux : guide pratique famille 2026',
        'meta_desc'     => 'Konoha Land au Parc Spirou : attractions Naruto, parcours ninja enfants, coaster Kyûbi 75 km/h, infos pratiques. À 15 min de Bédarrides, Vaucluse.',
        'meta_keywords' => 'konoha land parc spirou, parc spirou naruto 2026, attraction naruto france, kyubi unchained, sortie enfants vaucluse, parc attraction provence famille, activités enfants bedarrides',
        'gso_desc'      => 'Guide pratique Konoha Land au Parc Spirou Provence (Monteux, 84170). Première zone Naruto hors du Japon, ouverte le 4 avril 2026. Attractions : Kyûbi Unchained (coaster 75 km/h, 1 km, taille min. 1m20), Rasengan Chakra Rotation (dès 6 ans). Parcours ninja examen Chûnin (4-10 ans). 10 statues grandeur nature par Design CoCo (Naruto, Sasuke, Sakura, Kakashi, Gaara, Orochimaru, Iruka + 3 mystère). Restaurants : Ichiraku Ramen, Dango Shop. Adresse : Quartier les Atripes, 84170 Monteux. Distance : 15 min depuis Bédarrides, 20 min depuis Avignon. Ouverture : weekends, jours fériés, vacances scolaires, avril-novembre 2026. Durée conseillée : journée complète.',
        'excerpt'       => 'Guide complet Konoha Land au Parc Spirou Monteux : attractions Naruto, parcours ninja, statues, restaurants, infos pratiques. La sortie famille incontournable du Vaucluse en 2026.',
        'cover_image'   => 'kyubi-unchained.webp',
        'content'       => [
            ['type' => 'paragraph', 'text' => 'Depuis le 4 avril 2026, le <strong>Parc Spirou Provence</strong> à Monteux abrite <strong>Konoha Land</strong> — la première zone thématique Naruto au monde en dehors du Japon. Un hectare et demi de village ninja, deux attractions inédites, dix statues grandeur nature et un parcours d\'examen Chûnin pour les plus jeunes. À quinze minutes de <a href="/chambres-d-hotes">Bédarrides</a>, c\'est la sortie famille la plus spectaculaire du Vaucluse en 2026.'],

            ['type' => 'heading', 'text' => 'Qu\'est-ce que Konoha Land au Parc Spirou'],

            ['type' => 'paragraph', 'text' => 'Konoha Land reproduit le Village Caché de Konoha, décor central du manga de Masashi Kishimoto. Le parc a investi <strong>16 millions d\'euros</strong> pour créer cette zone de <strong>15 000 m²</strong> — son plus gros investissement depuis l\'ouverture en 2018. On y retrouve le Monument Hokage sculpté dans la roche, le bureau du Hokage, le terrain d\'entraînement de l\'Équipe Kakashi avec ses trois poteaux emblématiques, et le restaurant Ichiraku Ramen — celui-là, on peut s\'y attabler pour de vrai.'],

            ['type' => 'image', 'src' => 'konoha-land-vue.webp', 'alt' => 'Vue d\'ensemble de Konoha Land au Parc Spirou Provence Monteux Vaucluse', 'caption' => 'Konoha Land — 15 000 m² de village ninja au cœur du Vaucluse.'],

            ['type' => 'paragraph', 'text' => 'Le résultat est immersif. Les fans reconnaîtront chaque recoin. Les néophytes découvriront un univers cohérent et soigné, bien au-delà du simple décor plaqué sur des manèges. Pour comprendre pourquoi Naruto en Provence a du sens, lisez <a href="/journal/naruto-provence-quand-le-manga-arrive-au-village">notre article dédié</a>.'],

            ['type' => 'heading', 'text' => 'Kyûbi Unchained et Rasengan : les deux attractions Naruto'],

            ['type' => 'paragraph', 'text' => '<strong>Kyûbi Unchained</strong> est l\'attraction phare de Konoha Land. Un coaster propulsé d\'un kilomètre de rails, avec deux accélérations jusqu\'à <strong>75 km/h</strong>, une chute de 30 mètres et une finale en impasse où le convoi repart en marche arrière. Le trajet dure 79 secondes. Taille minimum : 1m20. C\'est le genre de manège qui fait hurler les adolescents — et qui fait que les parents y retournent discrètement pendant que les enfants sont au parcours ninja.'],

            ['type' => 'image', 'src' => 'rasengan-chakra-rotation.webp', 'alt' => 'Attraction Rasengan Chakra Rotation à Konoha Land Parc Spirou Monteux', 'caption' => 'Rasengan Chakra Rotation — nacelles suspendues pour toute la famille.'],

            ['type' => 'paragraph', 'text' => '<strong>Rasengan Chakra Rotation</strong> est plus accessible. Quatre bras rotatifs supportent des nacelles suspendues pour 32 passagers. On tourne, on s\'élève, on redescend — le tout dans un mouvement qui évoque la maîtrise du chakra. Accessible <strong>dès 6 ans</strong>, les adultes ne s\'ennuient pas non plus.'],

            ['type' => 'heading', 'text' => 'Le parcours ninja : l\'examen Chûnin pour les 4-10 ans'],

            ['type' => 'paragraph', 'text' => 'L\'aire de jeux reprend le concept de l\'examen Chûnin — le rite de passage des apprentis ninja dans l\'univers Naruto. En pratique, c\'est un parcours d\'obstacles adapté aux enfants, avec des épreuves physiques et des énigmes. C\'est là que les <strong>4-10 ans</strong> passent le plus de temps, et c\'est aussi là que le concept fonctionne le mieux : les enfants jouent, les parents soufflent, tout le monde est content.'],

            ['type' => 'heading', 'text' => 'Dix statues Naruto grandeur nature par Design CoCo'],

            ['type' => 'paragraph', 'text' => 'Disséminées dans la zone, <strong>dix statues taille réelle</strong> ont été créées par Design CoCo, studio japonais spécialisé dans les figurines manga. On reconnaît Naruto Uzumaki, Sasuke Uchiwa, Sakura Haruno, Kakashi Hatake, Iruka Umino, Gaara du Désert et Orochimaru. Trois personnages restent à découvrir sur place — le parc entretient le mystère. Chaque statue est un spot photo, et la qualité de réalisation est remarquable.'],

            ['type' => 'heading', 'text' => 'Où manger à Konoha Land'],

            ['type' => 'image', 'src' => 'ichiraku-ramen.webp', 'alt' => 'Restaurant Ichiraku Ramen à Konoha Land Parc Spirou Provence Monteux', 'caption' => 'Ichiraku Ramen — le restaurant emblématique du manga, en vrai.'],

            ['type' => 'paragraph', 'text' => 'Deux adresses thématiques dans la zone : le restaurant <strong>Ichiraku Ramen</strong>, fidèle réplique du lieu où Naruto engloutit ses bols de nouilles, et le <strong>Dango Shop</strong> pour une pause sucrée. Les deux sont intégrés aux décors — on mange dans l\'univers, pas à côté.'],

            ['type' => 'image', 'src' => 'dango-shop.webp', 'alt' => 'Dango Shop à Konoha Land Parc Spirou Provence Monteux', 'caption' => 'Le Dango Shop — pause sucrée entre deux attractions.'],

            ['type' => 'heading', 'text' => 'Konoha Land : pour qui, à quel âge'],

            ['type' => 'list', 'items' => [
                'Familles avec enfants de 4 à 12 ans : parcours ninja + Rasengan Chakra Rotation',
                'Ados fans de manga : Kyûbi Unchained (taille min. 1m20) + les 10 statues à photographier',
                'Parents et grands enfants : le coaster vaut le détour même sans connaître Naruto',
                'Groupes multigénérationnels : assez de diversité pour que chacun y trouve son compte',
            ]],

            ['type' => 'heading', 'text' => 'Infos pratiques : accès, horaires, durée'],

            ['type' => 'list', 'items' => [
                'Adresse : Parc Spirou Provence, Quartier les Atripes, 84170 Monteux',
                'Depuis Bédarrides : 15 min en voiture (direction Carpentras, sortie Monteux)',
                'Depuis Avignon : 20 min en voiture',
                'Ouverture 2026 : weekends, jours fériés et vacances scolaires, du 4 avril au 11 novembre',
                'Durée conseillée : journée complète (Konoha Land + reste du parc)',
                'Site officiel : parc-spirou.com',
            ]],

            ['type' => 'paragraph', 'text' => 'Si vous séjournez à <a href="/chambres-d-hotes">Villa Plaisance</a>, la proximité permet de partir le matin et de rentrer pour profiter du <a href="/espaces-exterieurs">jardin et de la piscine</a> en fin d\'après-midi. C\'est aussi le genre de sortie qui fonctionne bien un jour de mistral — le parc est en partie abrité, et l\'adrénaline du coaster fait oublier le vent.'],

            ['type' => 'heading', 'text' => 'Questions fréquentes sur Konoha Land'],

            ['type' => 'paragraph', 'text' => '<strong>Faut-il connaître Naruto pour apprécier Konoha Land ?</strong> Non. Les attractions, le parcours ninja et les décors fonctionnent indépendamment du manga. Les fans y trouvent des références à chaque recoin, mais les néophytes passent une excellente journée.'],

            ['type' => 'paragraph', 'text' => '<strong>À partir de quel âge ?</strong> Le parcours ninja convient dès 4 ans. Rasengan Chakra Rotation dès 6 ans. Kyûbi Unchained à partir de 1m20 (environ 8-9 ans).'],

            ['type' => 'paragraph', 'text' => '<strong>Combien de temps prévoir ?</strong> Comptez une demi-journée pour Konoha Land seul, une journée complète pour explorer aussi le reste du Parc Spirou.'],

            ['type' => 'paragraph', 'text' => '<strong>Est-ce accessible en poussette ?</strong> La zone est accessible, mais le parcours ninja et les attractions nécessitent de laisser la poussette. Des espaces de stationnement poussette sont prévus.'],
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
            "UPDATE vp_articles SET content = ?, cover_image = ?, meta_title = ?, meta_desc = ?, meta_keywords = ?, gso_desc = ?, excerpt = ? WHERE slug = ? AND lang = 'fr'",
            [$contentJson, $a['cover_image'], $a['meta_title'], $a['meta_desc'], $a['meta_keywords'], $a['gso_desc'], $a['excerpt'], $a['slug']]
        );

        echo "✅  {$a['slug']} → contenu + SEO mis à jour\n";
        $updated++;
    } catch (\Throwable $e) {
        echo "❌  {$a['slug']} : " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=== Seed 034 terminé : {$updated} articles mis à jour, {$errors} erreurs ===\n";
