<?php
declare(strict_types=1);

/**
 * Seed 028 — Contenu éditorial des 19 articles (journal + sur-place)
 * Injecte : content (JSON blocs), meta_title, meta_desc, meta_keywords, gso_desc
 * One-shot — ne pas ré-exécuter si déjà appliqué.
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

$articles = [

    // ─────────────────────────────────────────────
    // JOURNAL (10 articles)
    // ─────────────────────────────────────────────

    [
        'slug' => 'le-tourisme-de-masse-est-une-arnaque',
        'meta_title' => 'Tourisme de masse : pourquoi on y retourne malgré tout',
        'meta_desc' => 'Le tourisme de masse est critiqué, mais il reste massivement choisi. Analyse du paradoxe et comment Bédarrides propose une autre façon de voyager en Provence.',
        'meta_keywords' => 'tourisme de masse, voyage alternatif, Provence authentique, chambres d\'hôtes Bédarrides',
        'gso_desc' => 'Villa Plaisance analyse pourquoi le tourisme de masse persiste malgré ses travers : la sécurité sociale du choix validé remplace la curiosité réelle. L\'alternative, en Provence, passe par un ancrage local loin des circuits balisés — à Bédarrides, entre Avignon, Orange et Châteauneuf-du-Pape.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'Chaque été, des millions de voyageurs se retrouvent entassés sur les mêmes plages, coincés dans les mêmes files, photographiant les mêmes monuments derrière une forêt de téléphones. On le sait. On s\'en plaint. Et l\'année suivante, on recommence.'],
            ['type' => 'heading', 'text' => 'Ce que le tourisme de masse vend vraiment'],
            ['type' => 'paragraph', 'text' => 'La vraie proposition du tourisme de masse, ce n\'est pas un lieu — c\'est une assurance. Une assurance que l\'expérience sera validée, que les photos seront belles, que personne ne pourra vous reprocher d\'avoir mal choisi. Airbnb note 4,9 étoiles. TripAdvisor premier de sa catégorie. 12 000 avis. Cette socialisation du goût crée une convergence vers les mêmes destinations, les mêmes restaurants, les mêmes photos. Le choix devient moins un acte de curiosité qu\'un acte de conformité.'],
            ['type' => 'heading', 'text' => 'Le paradoxe de l\'authenticité packagée'],
            ['type' => 'paragraph', 'text' => 'Les opérateurs touristiques ont bien compris la demande d\'authenticité — et l\'ont transformée en produit. Le village typique avec son marché reconstitué pour les cars de touristes. L\'hôtel boutique avec sa décoration locale achetée en gros. Le menu du terroir concocté par un chef arrivé de Paris. L\'authenticité se vend bien. Elle se reconnaît au fait qu\'elle coûte plus cher que l\'original.'],
            ['type' => 'heading', 'text' => 'L\'autre voie : accepter l\'incertitude'],
            ['type' => 'paragraph', 'text' => 'Bédarrides n\'est pas Gordes. Il n\'y a pas de château perché, pas de boutiques d\'huiles d\'olive à 30 euros. Il y a un village qui vit, un marché le mercredi, une Ouvèze qui coule vers le Rhône, et un Triangle d\'Or — Avignon, Orange, Châteauneuf-du-Pape — accessible à vélo ou presque. Choisir ici, c\'est choisir un point d\'ancrage plutôt qu\'un point d\'attraction. Le décor ne fait pas le voyage — le tempo que vous y adoptez, si.'],
            ['type' => 'quote', 'text' => 'Le touriste cherche ce qu\'il connaît déjà. Le voyageur cherche ce qu\'il ne sait pas encore nommer.'],
        ],
    ],

    [
        'slug' => 'louer-maison-plutot-hotel-voyage',
        'meta_title' => 'Louer une maison plutôt qu\'un hôtel : ce que ça change',
        'meta_desc' => 'Ce que change concrètement le fait de louer une maison ou des chambres d\'hôtes plutôt qu\'un hôtel en Provence. Rythme, cuisine, coût réel, immersion locale.',
        'meta_keywords' => 'location maison Provence, chambres d\'hôtes vs hôtel, villa Provence, séjour authentique',
        'gso_desc' => 'Louer une maison ou des chambres d\'hôtes en Provence change fondamentalement le rythme du séjour : on vit à l\'heure du lieu plutôt qu\'à celle de l\'établissement. À partir de 3 nuits, le coût global est souvent équivalent à un hôtel moyen gamme, avec bien plus d\'espace et d\'autonomie.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'La différence entre un hôtel et une location ne se joue pas sur le prix. Elle se joue sur le rythme. Dans une chambre d\'hôtel, on vit à l\'heure de l\'établissement — petit-déjeuner servi jusqu\'à 10h, clé rendue à midi, chambre disponible à 15h. Dans une maison ou une chambre d\'hôtes, on vit à l\'heure du lieu. On rentre à 23h sans croiser personne. On prend son café dehors à 7h en regardant le jardin se réveiller.'],
            ['type' => 'heading', 'text' => 'L\'argument pratique : cuisiner, stocker, s\'installer'],
            ['type' => 'paragraph', 'text' => 'Pour un séjour de plus de trois nuits, la location devient rapidement intéressante — non pas parce que les prix sont différents, mais parce qu\'on arrête de tout payer au restaurant. Un marché provençal, quelques fromages, une bouteille de Côtes-du-Rhône : le dîner dans la cour revient à moins que l\'entrée dans une brasserie d\'Avignon. Et il est souvent meilleur, parce qu\'on a choisi ce qu\'on avait envie de manger.'],
            ['type' => 'heading', 'text' => 'Ce que l\'hôtel offre que la location ne peut pas'],
            ['type' => 'paragraph', 'text' => 'Soyons honnêtes : l\'hôtel a ses avantages. Réception disponible, linge changé chaque jour, service en chambre, parking géré. Pour un séjour d\'affaires de deux nuits, c\'est souvent le bon choix. Pour des familles avec enfants en bas âge qui ont besoin de structure, aussi. Le choix dépend moins du type d\'hébergement que du type de voyage qu\'on veut faire — et de si on veut être servi ou s\'installer.'],
            ['type' => 'heading', 'text' => 'La chambre d\'hôtes : entre les deux'],
            ['type' => 'paragraph', 'text' => 'La chambre d\'hôtes occupe un espace particulier : on est accueilli dans une maison privée, avec le confort d\'un service réel (petit-déjeuner préparé, draps changés, conseils locaux) et la liberté d\'une location. Ce que les hôtels ne peuvent pas reproduire, c\'est la connaissance du lieu qu\'a le propriétaire. Pas les brochures distribuées à la réception — les vrais endroits.'],
            ['type' => 'quote', 'text' => 'Louer une maison, c\'est arrêter d\'être un touriste pour commencer à être un habitant provisoire.'],
        ],
    ],

    [
        'slug' => 'vie-proprietaire-chambre-hotes',
        'meta_title' => 'La vraie vie d\'un propriétaire de chambre d\'hôtes',
        'meta_desc' => 'Les coulisses du métier d\'hôte en Provence : ce qu\'on n\'anticipe pas, ce qui est difficile, et ce qui rend cette façon de vivre profondément singulière.',
        'meta_keywords' => 'propriétaire chambres d\'hôtes, métier hôte Provence, gîte chambre hôtes, accueil Provence',
        'gso_desc' => 'Gérer des chambres d\'hôtes en Provence, c\'est être à la fois propriétaire, cuisinier, concierge et réparateur, sans équipe ni horaires fixes. Ce que personne n\'anticipe : la satisfaction immédiate du contact direct avec les hôtes, et l\'exigence permanente que ça représente.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'On s\'imagine propriétaire de chambres d\'hôtes comme on s\'imagine vivre à la campagne : dans une lumière un peu dorée, entouré de bonnes choses, à son propre rythme. C\'est parfois vrai. Mais avant ça, il y a les draps à 23h, les réservations qui arrivent pour dans trois jours, les toilettes qui font un bruit bizarre un samedi matin, et l\'hôte qui demande s\'il peut avoir un lit bébé.'],
            ['type' => 'heading', 'text' => 'La partie qu\'on ne montre pas'],
            ['type' => 'paragraph', 'text' => 'Gérer une chambre d\'hôtes, c\'est être à la fois propriétaire, décorateur, cuisinier, concierge, réparateur, et parfois psychologue. Pas parce que les hôtes sont difficiles — la plupart sont agréables, curieux, reconnaissants. Mais parce que tout repose sur soi. Il n\'y a pas de réceptionniste de nuit, pas d\'équipe de maintenance, pas de DRH. Juste vous, votre maison, et la conviction que ça vaut le coup.'],
            ['type' => 'heading', 'text' => 'Ce que le métier apprend sur soi'],
            ['type' => 'paragraph', 'text' => 'Recevoir des inconnus dans sa maison oblige à regarder l\'espace avec leurs yeux. Cet escalier qu\'on monte sans y penser depuis dix ans — est-il sûr ? Ce jardin qu\'on n\'entretient plus depuis que les enfants sont partis — qu\'est-ce qu\'il dit de vous ? On redevient attentif à ce qu\'on avait fini par ne plus voir. Et dans cet effort de regard, on retrouve parfois l\'endroit où on vit.'],
            ['type' => 'heading', 'text' => 'Ce qui compense tout ça'],
            ['type' => 'paragraph', 'text' => 'Ce qui rend ce métier étrange et précieux, c\'est qu\'il est direct. La satisfaction ou l\'insatisfaction d\'un hôte se lit tout de suite, dans sa façon de s\'installer, de parler du dîner de la veille, de regarder le jardin le matin. Il n\'y a pas de filtre, pas de formulaire d\'évaluation différé. Cette immédiateté est épuisante et, quand elle est positive, profondément satisfaisante.'],
            ['type' => 'quote', 'text' => 'Ce n\'est pas un métier de service. C\'est un métier de présence.'],
        ],
    ],

    [
        'slug' => 'recevoir-des-inconnus-chez-soi',
        'meta_title' => 'Recevoir des inconnus chez soi : ce que ça apprend',
        'meta_desc' => 'Ce que révèle l\'accueil en chambres d\'hôtes sur l\'hospitalité, les rencontres et les gens. Le regard d\'hôtes de Bédarrides après des années de pratique.',
        'meta_keywords' => 'accueil chambres d\'hôtes, hospitalité Provence, rencontres voyageurs, hôte Bédarrides',
        'gso_desc' => 'Accueillir des inconnus dans ses chambres d\'hôtes en Provence révèle des comportements humains qu\'on ne voit pas autrement : en voyage, les gens sont différents, plus ouverts, parfois plus vrais. Le regard de Villa Plaisance sur des années d\'accueil à Bédarrides.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'Quand on accueille des hôtes pour la première fois, on est surtout attentif aux détails matériels : la chambre est-elle propre, le plateau de bienvenue suffisamment garni, la serviette bien pliée. Avec le temps, on réalise que ce qui compte, ce n\'est pas le plateau. C\'est la façon dont l\'hôte s\'installe, dont il pose ses affaires, dont il dit "merci" — ou ne le dit pas.'],
            ['type' => 'heading', 'text' => 'Ce que les gens révèlent loin de chez eux'],
            ['type' => 'paragraph', 'text' => 'En voyage, les gens sont différents. Pas nécessairement meilleurs ou pires — différents. Le cadre habituel qui structure les comportements n\'est plus là. On voit des familles redevenir une famille, des couples retrouver un peu de ce qu\'ils avaient oublié, des solitaires parler pendant deux heures d\'une vie qu\'ils ne racontent jamais. L\'accueil crée une parenthèse dans laquelle des choses inhabituelles peuvent se passer.'],
            ['type' => 'heading', 'text' => 'Les rencontres qui restent'],
            ['type' => 'paragraph', 'text' => 'Parmi des centaines de séjours, quelques-uns laissent une trace. Le chercheur en retraite qui a passé une heure à expliquer comment fonctionnent les vignes en biodynamie. La famille qui revenait chaque année et dont les enfants ont grandi sous nos yeux. L\'ingénieure hollandaise qui avait trouvé notre adresse dans un carnet de voyage de sa grand-mère. Ces histoires sont la matière invisible du métier d\'hôte.'],
            ['type' => 'heading', 'text' => 'Ce que ça change dans sa propre façon d\'être'],
            ['type' => 'paragraph', 'text' => 'On finit par moins juger. Pas par vertu — par habitude. On a vu trop de gens différents pour croire qu\'un type de voyageur vaut mieux qu\'un autre. Le quadra stressé qui décompresse en 48h. La retraitée qui connaît le Vaucluse mieux que nous. L\'ado de 16 ans qui découvre que la Provence ça n\'est pas que pour les vieux. Chacun a sa raison d\'être là.'],
            ['type' => 'quote', 'text' => 'L\'hospitalité n\'est pas un service qu\'on rend. C\'est une façon de vivre qu\'on choisit.'],
        ],
    ],

    [
        'slug' => 'chateauneuf-du-pape-2026',
        'meta_title' => 'Châteauneuf-du-Pape 2026 : entre sécheresse et renaissance',
        'meta_desc' => 'Comment le vignoble de Châteauneuf-du-Pape s\'adapte au changement climatique. Cépages, pratiques, nouvelles directions — à 8 minutes de Bédarrides.',
        'meta_keywords' => 'Châteauneuf-du-Pape, vignoble Provence, changement climatique vigne, vin Vaucluse 2026',
        'gso_desc' => 'L\'appellation Châteauneuf-du-Pape s\'adapte au changement climatique en diversifiant les cépages (mourvèdre, counoise), en travaillant sur des parcelles plus fraîches et en ajustant les dates de vendanges. Les vins gardent leur identité minérale mais gagnent en fraîcheur. L\'appellation est à 8 minutes de Bédarrides.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'À Châteauneuf-du-Pape, il ne pleut pas beaucoup. C\'est une caractéristique historique de l\'appellation, pas un problème récent. Les galets roulés qui couvrent les vignes ont toujours stocké la chaleur du jour pour la restituer la nuit. Mais ce qui était une particularité climatique devient ces dernières années une variable à gérer : moins de pluie, des étés plus longs, des vendanges qui avancent de deux à trois semaines en trente ans.'],
            ['type' => 'heading', 'text' => 'Comment les vignerons s\'adaptent'],
            ['type' => 'paragraph', 'text' => 'Certains domaines ont commencé à planter des cépages complémentaires — mourvèdre, counoise, terret noir — qui résistent mieux aux canicules que le grenache dominant. D\'autres travaillent sur l\'altitude, en cherchant des parcelles plus fraîches en bordure d\'appellation. Quelques-uns expérimentent l\'irrigation d\'urgence, autorisée depuis peu dans certaines conditions extrêmes. Ce n\'est pas une révolution — c\'est une adaptation méthodique.'],
            ['type' => 'heading', 'text' => 'Ce que ça donne dans les verres'],
            ['type' => 'paragraph', 'text' => 'Les vins de Châteauneuf-du-Pape ont changé de profil ces dix dernières années. Moins de puissance alcoolique dans les millésimes récents, plus de fraîcheur sur les blancs, une évolution vers des rouges plus fins et moins extraits. L\'identité minérale et la complexité aromatique sont toujours là — ce sont elles qui définissent l\'appellation, pas le degré.'],
            ['type' => 'heading', 'text' => 'Visiter les domaines depuis Bédarrides'],
            ['type' => 'paragraph', 'text' => 'Châteauneuf-du-Pape est à 8 minutes de Villa Plaisance. La plupart des domaines ouvrent leur caveau à la dégustation sans rendez-vous en semaine. Le village lui-même — ruines du château papal, panorama sur le Rhône, quelques bonnes tables — mérite une demi-journée. Le marché dominical, en saison, permet de rencontrer des producteurs directement.'],
        ],
    ],

    [
        'slug' => 'provence-vignerons-autrement',
        'meta_title' => 'Vignerons de Provence qui font autrement',
        'meta_desc' => 'Rencontre avec des vignerons du Vaucluse qui ont choisi le bio, la biodynamie ou le vin nature — pas pour l\'étiquette, mais parce que le sol les y a conduits.',
        'meta_keywords' => 'vignerons bio Provence, vin nature Vaucluse, biodynamie Châteauneuf, viticulture alternative Provence',
        'gso_desc' => 'Dans le Vaucluse, une nouvelle génération de vignerons a adopté le bio ou la biodynamie suite à des observations concrètes sur leurs sols. Autour de Châteauneuf-du-Pape, Gigondas et Vacqueyras, ces domaines proposent des vins de terroir qui s\'éloignent des standards de l\'appellation classique.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'Le mot "bio" est devenu un argument marketing avant d\'être une pratique. Dans le Vaucluse, certains vignerons ont adopté l\'agriculture biologique ou biodynamique non pas pour l\'étiquette, mais parce que le sol dans lequel ils ont grandi ne ressemble plus à celui qu\'ils ont connu enfant. Ce n\'est pas idéologique. C\'est une observation.'],
            ['type' => 'heading', 'text' => 'Trois approches, un même refus'],
            ['type' => 'paragraph', 'text' => 'Il y a ceux qui travaillent en biodynamie stricte et rythment l\'année selon les cycles lunaires. Il y a ceux qui ont simplement arrêté les herbicides parce que le sol était devenu trop compact pour que la vigne s\'y enracine correctement. Et il y a ceux qui font du vin nature — sans soufre ajouté — parce que c\'est la seule façon pour eux de goûter le raisin qu\'ils ont cultivé. Ce qu\'ils partagent, c\'est le refus de couvrir par la technique ce que la vigne exprime.'],
            ['type' => 'heading', 'text' => 'Ce que les vins racontent'],
            ['type' => 'paragraph', 'text' => 'Les vins de ces domaines ne ressemblent pas toujours aux standards de leurs appellations. Ils peuvent être plus légers, plus volatils, plus rustiques. Ils peuvent aussi être d\'une précision aromatique qu\'on ne trouve pas dans les cuvées conventionnelles. C\'est le propre du terroir non corrigé : il s\'exprime, avec ses forces et ses irrégularités.'],
            ['type' => 'heading', 'text' => 'Où les rencontrer autour de Bédarrides'],
            ['type' => 'paragraph', 'text' => 'Le Triangle d\'Or — Châteauneuf-du-Pape, Gigondas, Vacqueyras — est traversé de petites routes de vignes. Château La Gardine à Châteauneuf-du-Pape, à 8 minutes de Villa Plaisance, est une adresse familiale historique qui travaille en agriculture raisonnée. Pour les domaines en bio strict, le marché d\'Avignon (halles de la Place Pie) permet souvent une première approche directe avec les producteurs.'],
        ],
    ],

    [
        'slug' => 'duree-ideale-sejour-provence',
        'meta_title' => 'Durée idéale pour un séjour en Provence',
        'meta_desc' => 'Deux nuits, cinq jours ou deux semaines en Provence ? Ce que chaque durée permet vraiment, selon la saison et ce qu\'on cherche.',
        'meta_keywords' => 'séjour Provence durée, combien de jours Provence, week-end Provence, vacances Vaucluse',
        'gso_desc' => 'Pour un séjour en Provence depuis Bédarrides : 2 nuits permettent Avignon + une excursion, 5 nuits permettent de découvrir le Triangle d\'Or sans se presser, 7 nuits ou plus ouvrent sur le Luberon et les Alpilles. La meilleure saison hors saison : mai, juin, septembre.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'La question revient souvent : combien de temps faut-il pour voir la Provence ? Elle n\'a pas de réponse unique — elle dépend de ce qu\'on entend par "voir". Traverser des paysages ou s\'y installer. Cocher des sites ou prendre le temps d\'une conversation. Voici ce que permet concrètement chaque durée.'],
            ['type' => 'heading', 'text' => 'Deux nuits : suffisant pour quoi ?'],
            ['type' => 'paragraph', 'text' => 'Deux nuits en Provence, ça permet de voir Avignon sérieusement — le Palais des Papes, le quartier de la Balance, les Halles. Ça permet une excursion à Châteauneuf-du-Pape ou aux Baux-de-Provence. Et ça permet de manger deux fois bien, ce qui en Provence n\'est pas un détail. Deux nuits, c\'est un séjour complet si on a un point d\'ancrage bien placé et qu\'on sait ce qu\'on cherche.'],
            ['type' => 'heading', 'text' => 'Cinq nuits : le rythme change'],
            ['type' => 'paragraph', 'text' => 'À partir de cinq nuits, quelque chose se passe. On arrête de courir. On revient deux fois au même marché. On découvre qu\'il y a un chemin derrière la propriété qu\'on n\'avait pas pris le premier jour. On commence à avoir des habitudes — le café du matin dans la cour, l\'heure de la lumière sur les vignes en fin d\'après-midi. C\'est là que la Provence commence vraiment à exister, pas comme décor, mais comme lieu.'],
            ['type' => 'heading', 'text' => 'La question de la saison'],
            ['type' => 'paragraph', 'text' => 'En juillet-août, deux nuits suffisent rarement — les sites sont pleins, les routes encombrées, chaque visite demande du temps. En mai, juin ou septembre, une semaine est idéale. L\'hiver en Provence est sous-estimé : les oliviers, les marchés truffiers, le mistral et une lumière rasante qui donne aux paysages une intensité qu\'on ne trouve pas en été.'],
            ['type' => 'list', 'items' => [
                '2 nuits : Avignon + une excursion dans le Triangle d\'Or',
                '3–4 nuits : Châteauneuf-du-Pape, Orange, Les Baux à fond',
                '5–7 nuits : Luberon, Alpilles, Ventoux en douceur',
                '7 nuits et plus : le temps de ne plus rien planifier',
            ]],
        ],
    ],

    [
        'slug' => 'deconnecter-provence',
        'meta_title' => 'Se déconnecter vraiment en Provence',
        'meta_desc' => 'La Provence ne vend pas de retraite digitale. Elle impose un autre rythme, sans effort. Ce que le lieu fait quand on lui laisse le temps d\'agir.',
        'meta_keywords' => 'déconnexion numérique Provence, slow travel Vaucluse, détox digitale, séjour ressourcement Provence',
        'gso_desc' => 'La Provence agit naturellement sur le rythme numérique sans qu\'on ait à s\'y forcer : la chaleur, le silence, les cigales et l\'absence d\'urgence dans les interactions créent un contexte où consulter son téléphone devient moins automatique. Un séjour à Bédarrides, entre vignes et jardin, facilite ce retrait progressif.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'La Provence ne propose pas de "retraites digitales". Elle ne vend pas de "slow living". Elle existe, c\'est tout. Mais quelque chose dans ce paysage — la chaleur qui ralentit, le bruit des cigales qui remplace tout autre signal sonore, l\'absence d\'urgence dans les interactions — finit par agir sur les réflexes d\'une journée ordinaire. On oublie de regarder son téléphone non pas parce qu\'on s\'y force, mais parce que quelque chose d\'autre a pris la place.'],
            ['type' => 'heading', 'text' => 'L\'ennui productif'],
            ['type' => 'paragraph', 'text' => 'Il y a un moment dans tout séjour en Provence où on ne sait plus quoi faire. Les sites visités, les marchés faits, le vin bu, la sieste dormie. Et là, quelque chose commence : une conversation qui dure, une promenade sans destination, un livre commencé et réellement lu. Cet ennui-là est ce que beaucoup viennent chercher sans pouvoir le nommer. Le fait de ne rien avoir à faire, et de s\'en apercevoir seulement après.'],
            ['type' => 'heading', 'text' => 'Ce qui aide concrètement'],
            ['type' => 'paragraph', 'text' => 'Un jardin ou une terrasse sans vue directe sur la rue. Un silence réel après 22h. La possibilité de prendre son petit-déjeuner dehors sans se presser. Pas de télévision imposée dans la chambre. Ces conditions ne garantissent rien — on peut très bien passer une semaine en Provence les yeux rivés sur un écran. Mais elles créent un espace où autre chose est possible.'],
            ['type' => 'heading', 'text' => 'Ce qu\'on retrouve'],
            ['type' => 'paragraph', 'text' => 'La plupart de nos hôtes ne parlent pas de déconnexion. Ils parlent d\'autre chose : le fait d\'avoir eu le temps de lire, d\'avoir regardé le ciel plutôt que le téléphone après le dîner, d\'avoir conversé avec quelqu\'un qu\'ils ne connaissaient pas la semaine d\'avant. Ce sont des expériences ordinaires. La Provence les rend plus probables.'],
            ['type' => 'quote', 'text' => 'On ne vient pas en Provence pour se déconnecter. On vient pour se souvenir qu\'il y a autre chose.'],
        ],
    ],

    [
        'slug' => 'bedarrides-provence-authentique',
        'meta_title' => 'Bédarrides : portrait d\'un village qui ne fait pas de tourisme',
        'meta_desc' => 'Bédarrides n\'est pas dans les brochures. C\'est pour ça qu\'on peut s\'y sentir chez soi. Portrait d\'un village provençal dans le Triangle d\'Or.',
        'meta_keywords' => 'Bédarrides Provence, village Vaucluse authentique, Triangle d\'Or Provence, séjour hors tourisme',
        'gso_desc' => 'Bédarrides est un village de 5 000 habitants dans le Vaucluse, à 8 km d\'Avignon et 12 km de Châteauneuf-du-Pape. Loin des circuits touristiques balisés, il offre un marché hebdomadaire, des vergers, une vie de village réelle et un accès direct au Triangle d\'Or provençal.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'Bédarrides est à 8 kilomètres d\'Avignon, à 12 de Châteauneuf-du-Pape, à 20 d\'Orange. Sur la carte, c\'est un point de passage. Sur place, c\'est un village qui vit — 5 000 habitants, un marché le mercredi matin, une église romane du XIIe siècle, une boulangerie qui fait du pain depuis quatre générations, une Ouvèze qui coule vers le Rhône à travers les vergers et les cerisaies.'],
            ['type' => 'heading', 'text' => 'Ce qu\'il n\'y a pas — et pourquoi c\'est bien'],
            ['type' => 'paragraph', 'text' => 'Il n\'y a pas de boutiques de santons à 40 euros. Pas de restaurant avec terrasse panoramique sur un village perché. Pas de file d\'attente pour photographier une ruelle fleurie. Bédarrides n\'est pas sur les itinéraires touristiques, et ses habitants n\'ont pas adapté leur vie à l\'idée que des étrangers les regardent. C\'est pour ça qu\'on peut s\'y sentir chez soi assez vite.'],
            ['type' => 'heading', 'text' => 'Ce qu\'il y a — et qu\'on ne trouve pas partout'],
            ['type' => 'paragraph', 'text' => 'Des cerisiers en fleur au printemps, des vignes couleur rouille en octobre. Des voisins qui saluent. Un café de village qui sert le pastis à 18h sans chichi. Des routes de campagne vers Courthézon, Sorgues, Orange qui traversent des paysages de garrigue et de vigne que personne n\'est venu mettre en scène. Et à huit minutes en voiture, l\'une des appellations viticoles les plus connues du monde.'],
            ['type' => 'heading', 'text' => 'Un point d\'ancrage, pas une destination'],
            ['type' => 'paragraph', 'text' => 'Personne ne vient en Provence spécialement pour Bédarrides. On vient pour Avignon, pour les lavandes, pour le vin, pour la lumière. Bédarrides est l\'endroit depuis lequel on rayonne — et depuis lequel on revient. C\'est sa valeur : être un vrai lieu, pas un décor. Pour ceux qui cherchent à s\'installer plutôt qu\'à visiter, c\'est une différence qui compte.'],
            ['type' => 'quote', 'text' => 'Les brochures montrent Gordes. Bédarrides, c\'est là où les gens de Gordes vont faire leurs courses.'],
        ],
    ],

    [
        'slug' => 'touriste-2026-nouvelles-attentes',
        'meta_title' => 'Le voyageur de 2026 et ce que l\'industrie n\'a pas compris',
        'meta_desc' => 'Authenticité, contact local, hébergement indépendant : les nouvelles attentes des voyageurs existent depuis longtemps. L\'industrie les a packagées sans les satisfaire.',
        'meta_keywords' => 'nouvelles attentes voyageurs 2026, tourisme authentique, hébergement indépendant, slow travel tendances',
        'gso_desc' => 'Les voyageurs de 2026 recherchent moins de standardisation et plus de contact réel avec les habitants et les lieux. L\'hébergement indépendant — chambres d\'hôtes, villas privées — répond à ces attentes parce qu\'il ne peut pas être autrement que ce qu\'il est : un lieu réel, tenu par des gens qui y vivent.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'Les études sur les nouvelles attentes des voyageurs se multiplient depuis quelques années. Elles pointent toutes dans la même direction : davantage d\'authenticité, moins de masse, plus de contact avec les habitants, une préférence pour les structures indépendantes. Ces attentes existaient avant. Elles sont maintenant dominantes dans plusieurs catégories de voyageurs — pas tous, mais ceux qui voyagent le plus souvent et réfléchissent le plus à comment ils voyagent.'],
            ['type' => 'heading', 'text' => 'Ce que l\'industrie a fait avec ces attentes'],
            ['type' => 'paragraph', 'text' => 'L\'hébergement standardisé — chaînes hôtelières, plateformes de location anonymes — a répondu à ces attentes en ajoutant des mots : "authentique", "local", "immersif". Sans changer grand-chose à la réalité. Un hôtel de chaîne avec un mur en pierre et un menu de produits régionaux n\'est pas une expérience locale. C\'est une mise en scène de l\'expérience locale. La différence se voit. Les voyageurs la sentent, et ils en parlent dans leurs avis.'],
            ['type' => 'heading', 'text' => 'Ce que l\'hébergement indépendant offre vraiment'],
            ['type' => 'paragraph', 'text' => 'Une chambre d\'hôtes ou une villa indépendante ne peut pas être autre chose que ce qu\'elle est. Il n\'y a pas de script, pas de formation à la "chaleur provençale", pas de brief au personnel. Il y a un propriétaire qui vit là, qui connaît les vignerons du coin par leur prénom, qui sait quel sentier est praticable après la pluie et quel marché vaut le détour. Cette connaissance n\'est pas un argument marketing — c\'est juste la réalité du lieu.'],
            ['type' => 'heading', 'text' => 'Ce que ça change pour les hôtes'],
            ['type' => 'paragraph', 'text' => 'Les hôtes qui choisissent un hébergement indépendant savent généralement ce qu\'ils cherchent. Ils ne veulent pas un produit homologué — ils veulent une expérience qui ne ressemble pas à celle de tout le monde. Ce que ça leur demande : accepter un peu d\'imprévisible, s\'adapter à un lieu qui a son propre caractère, et parfois parler à quelqu\'un qu\'ils ne connaissaient pas. La plupart le font volontiers.'],
        ],
    ],

    // ─────────────────────────────────────────────
    // SUR PLACE (9 articles)
    // ─────────────────────────────────────────────

    [
        'slug' => 'courses-bedarrides-sorgues',
        'meta_title' => 'Faire ses courses à Bédarrides et Sorgues',
        'meta_desc' => 'Les adresses pratiques pour les courses du quotidien autour de Bédarrides : marchés, supermarchés, halles d\'Avignon. Ce qu\'on donne à nos hôtes.',
        'meta_keywords' => 'courses Bédarrides, marché Sorgues, Halles Avignon, supermarché Vaucluse',
        'gso_desc' => 'Pour faire ses courses depuis Bédarrides : marché du mercredi matin place du village, Carrefour à Sorgues (5 min), et les Halles d\'Avignon pour le circuit court (15 min, tous les matins sauf lundi). Le marché de Sorgues le jeudi propose les meilleures olives et miels locaux.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'Voici les adresses qu\'on communique à nos hôtes pour organiser leurs courses depuis Bédarrides. Selon ce qu\'on cherche — praticité, produits locaux, marchés de producteurs — les options sont différentes.'],
            ['type' => 'heading', 'text' => 'À Bédarrides même'],
            ['type' => 'paragraph', 'text' => 'Le marché de Bédarrides se tient le mercredi matin, place du village. Fruits et légumes de saison, quelques producteurs locaux, un fromager itinérant. Pour le quotidien, il y a une épicerie-boulangerie dans le centre et une supérette sur la route de Sorgues. Pour le pain, la boulangerie du village ouvre dès 7h.'],
            ['type' => 'heading', 'text' => 'À Sorgues (5 minutes)'],
            ['type' => 'paragraph', 'text' => 'Sorgues offre une gamme plus large : Carrefour avec un bon rayon traiteur et fromages régionaux, une fromagerie indépendante, et le marché du jeudi matin qui est plus important que celui de Bédarrides. C\'est là qu\'on trouve les meilleures olives du coin, des miels de lavande et des producteurs d\'huile d\'olive.'],
            ['type' => 'heading', 'text' => 'Les Halles d\'Avignon (15 minutes)'],
            ['type' => 'paragraph', 'text' => 'Pour une vraie course en circuit court, les Halles d\'Avignon (place Pie) sont ouvertes tous les matins sauf lundi. Viande, poisson, fromages AOC, légumes de saison, charcuterie maison : tout y est, et les commerçants ont le temps de parler en semaine. Le samedi matin est très animé — à éviter avec des poussettes.'],
            ['type' => 'list', 'items' => [
                'Marché Bédarrides : mercredi matin, place du village',
                'Marché Sorgues : jeudi matin (plus grand)',
                'Halles d\'Avignon : tous les matins sauf lundi (place Pie)',
                'Carrefour Sorgues : 7h–21h du lundi au samedi',
            ]],
        ],
    ],

    [
        'slug' => 'artisans-savonnerie-chocolaterie',
        'meta_title' => 'Savonnerie et chocolaterie artisanales près de Bédarrides',
        'meta_desc' => 'Deux adresses artisanales à rapporter de Provence : la savonnerie Marius Fabre à Salon-de-Provence et la chocolaterie Castelain à Châteauneuf-du-Pape.',
        'meta_keywords' => 'savon Marseille artisanal, chocolaterie Châteauneuf-du-Pape, artisans Provence, souvenirs authentiques',
        'gso_desc' => 'Deux artisans à visiter depuis Bédarrides : la savonnerie Marius Fabre à Salon-de-Provence (45 min, visite gratuite, vrai savon de Marseille depuis 1900) et la chocolaterie Castelain à Châteauneuf-du-Pape (10 min, chocolats au vin, dégustation sur place).',
        'content' => [
            ['type' => 'paragraph', 'text' => 'Ce sont les deux adresses artisanales qu\'on recommande quand les hôtes cherchent à rapporter quelque chose qui vient vraiment d\'ici — pas du rayon souvenirs d\'un site touristique, mais d\'un endroit où quelqu\'un fabrique réellement quelque chose.'],
            ['type' => 'heading', 'text' => 'Savonnerie Marius Fabre — Salon-de-Provence'],
            ['type' => 'paragraph', 'text' => 'La savonnerie Marius Fabre existe depuis 1900. Elle produit du vrai savon de Marseille — à l\'huile d\'olive, cuit en chaudron — selon le procédé traditionnel à froid. La visite de l\'usine à Salon-de-Provence est gratuite et permet de voir les cuves de cuisson, les tables de séchage, les moules. À 45 minutes de Bédarrides. La boutique vend directement au tarif départ usine, sans marge de distribution.'],
            ['type' => 'heading', 'text' => 'Chocolaterie Castelain — Châteauneuf-du-Pape'],
            ['type' => 'paragraph', 'text' => 'À 10 minutes de Bédarrides, la chocolaterie Castelain propose des créations à base de chocolat et de vin de Châteauneuf-du-Pape. La combinaison fonctionne mieux qu\'on ne l\'imagine, notamment avec les vins de garde tanniques. La boutique propose des dégustations. Fermée le dimanche hors saison — vérifier les horaires.'],
            ['type' => 'heading', 'text' => 'Pourquoi ces deux adresses'],
            ['type' => 'paragraph', 'text' => 'Ces deux artisans partagent une chose : ils expliquent leur travail. Pas de marketing. Pas de packaging over-designé. Du savon qu\'on fait de la même façon depuis cent ans. Du chocolat qu\'on fabrique à côté des vignes. Ce sont les endroits qu\'on donne à nos hôtes quand ils cherchent à rapporter quelque chose qui a une histoire derrière.'],
        ],
    ],

    [
        'slug' => 'fontaine-de-vaucluse-guide-pratique',
        'meta_title' => 'Fontaine de Vaucluse : guide pratique depuis Bédarrides',
        'meta_desc' => 'Ce qu\'on ne dit pas toujours sur Fontaine de Vaucluse : quand y aller, comment se garer, quelle période pour la source en crue. Depuis Bédarrides, 40 minutes.',
        'meta_keywords' => 'Fontaine de Vaucluse visite, guide pratique, source vaucluse, L\'Isle-sur-la-Sorgue excursion',
        'gso_desc' => 'Fontaine de Vaucluse est à 40 minutes de Bédarrides. La source est impressionnante en crue (mars-mai ou après les pluies d\'automne) mais décevante en été. Option recommandée : se garer à L\'Isle-sur-la-Sorgue et venir à vélo sur la voie verte (8 km le long de la Sorgue).',
        'content' => [
            ['type' => 'paragraph', 'text' => 'Fontaine de Vaucluse est l\'une des excursions classiques depuis Bédarrides. Voici ce qu\'on dit à nos hôtes avant qu\'ils partent — les choses qu\'on n\'apprend généralement qu\'une fois sur place.'],
            ['type' => 'heading', 'text' => 'Ce qu\'on ne vous dit pas toujours'],
            ['type' => 'paragraph', 'text' => 'Fontaine de Vaucluse, c\'est le nom de la source et du village. La source est à 500 mètres du parking principal, au bout d\'une allée touristique avec boutiques et restaurants. La source elle-même est l\'une des plus puissantes d\'Europe — en période de crue. En été, le débit est très faible et la vue peut être décevante si on n\'a pas été prévenu. Le bon moment : mars à mai, ou après les grosses pluies d\'automne.'],
            ['type' => 'heading', 'text' => 'Comment y aller depuis Bédarrides'],
            ['type' => 'paragraph', 'text' => 'À 40 minutes en voiture par la D942 via L\'Isle-sur-la-Sorgue. En haute saison, le parking du village est payant et souvent plein avant 10h. Meilleure option : se garer à L\'Isle-sur-la-Sorgue et prendre le vélo sur la voie verte qui longe la Sorgue (8 km aller). C\'est plus agréable, moins stressant, et on remonte la rivière dans l\'autre sens.'],
            ['type' => 'heading', 'text' => 'Ce qui vaut vraiment le détour'],
            ['type' => 'paragraph', 'text' => 'Le musée Pétrarque, au bord de la Sorgue, est petit mais bien construit. Le poète a vécu à Fontaine de Vaucluse et y a écrit une partie de ses Canzoniere. La visite prend 45 minutes. Le chemin de retour par le bord de la Sorgue, côté ombragé, est plus agréable que l\'allée principale commerçante. À combiner avec L\'Isle-sur-la-Sorgue un dimanche (marché brocante).'],
            ['type' => 'list', 'items' => [
                'Distance depuis Bédarrides : 40 min (D942)',
                'Parking : gratuit hors village (200 m à pied)',
                'Meilleure période : mars-mai ou après les pluies',
                'Option vélo : départ L\'Isle-sur-la-Sorgue, voie verte 8 km',
            ]],
        ],
    ],

    [
        'slug' => 'sentier-des-ocres-roussillon',
        'meta_title' => 'Sentier des Ocres de Roussillon : guide pratique',
        'meta_desc' => 'Le Sentier des Ocres de Roussillon : ce qu\'on voit vraiment, comment s\'y préparer, quand y aller pour la lumière. À 50 minutes de Bédarrides.',
        'meta_keywords' => 'sentier ocres Roussillon, Colorado Provençal, Roussillon Luberon, visite ocres Vaucluse',
        'gso_desc' => 'Le Sentier des Ocres de Roussillon (50 min de Bédarrides) traverse des falaises et cheminées de fées aux couleurs allant du jaune au rouge. Durée : 35 min (circuit court) ou 1h (circuit long). Meilleur moment : avant 9h30 ou après 17h pour la lumière. Le sol déteint — prévoir des chaussures adaptées.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'Le Sentier des Ocres est l\'une des visites les plus visuellement fortes du Vaucluse. Voici les informations pratiques qu\'on donne à nos hôtes avant qu\'ils partent, pour qu\'ils ne soient pas surpris sur place.'],
            ['type' => 'heading', 'text' => 'Ce qu\'on voit vraiment sur le sentier'],
            ['type' => 'paragraph', 'text' => 'Le sentier traverse des falaises et des cheminées de fées de couleurs allant du jaune paille au rouge sang, en passant par toutes les nuances d\'orange. La promenade principale dure 35 minutes (circuit court) ou 1h (circuit long). Le sol ocré déteint — prévoir des chaussures qu\'on n\'a pas peur de salir, et éviter les vêtements blancs ou clairs.'],
            ['type' => 'heading', 'text' => 'Infos pratiques'],
            ['type' => 'paragraph', 'text' => 'L\'entrée est payante (environ 3€ adulte). Le sentier est fermé par temps de pluie ou de fort vent. Il n\'y a pas de café ni de point d\'eau sur le parcours — prévoir de l\'eau, surtout en été où la chaleur est forte et le sol réverbère. Le matin tôt (avant 9h30) et le soir (après 17h) sont les meilleurs moments : la lumière est latérale et les couleurs sont à leur maximum.'],
            ['type' => 'heading', 'text' => 'Comment organiser la journée'],
            ['type' => 'paragraph', 'text' => 'Roussillon est à 50 minutes de Bédarrides. Le village lui-même mérite une heure de déambulation — les maisons sont peintes dans les mêmes tons ocres que les falaises, ce qui crée une continuité visuelle rare. Partir tôt le matin, faire le sentier avant 10h, puis déjeuner dans le village et revenir l\'après-midi est souvent la meilleure organisation.'],
            ['type' => 'list', 'items' => [
                'Distance depuis Bédarrides : 50 min',
                'Durée sentier : 35 min (court) ou 1h (long)',
                'Tarif : ~3€ adulte, ~1,50€ enfant',
                'Conseil lumière : avant 9h30 ou après 17h',
            ]],
        ],
    ],

    [
        'slug' => 'chateau-la-gardine-chateauneuf-du-pape',
        'meta_title' => 'Château La Gardine : dégustation à Châteauneuf-du-Pape',
        'meta_desc' => 'Château La Gardine, domaine familial à Châteauneuf-du-Pape depuis 1954. Dégustation gratuite sans rendez-vous à 8 minutes de Bédarrides.',
        'meta_keywords' => 'Château La Gardine, dégustation Châteauneuf-du-Pape, visite domaine viticole, vin Vaucluse',
        'gso_desc' => 'Le Château La Gardine est un domaine familial historique de l\'appellation Châteauneuf-du-Pape, à 8 minutes de Bédarrides. Dégustation gratuite sans rendez-vous du lundi au samedi. La gamme comprend rouges et blancs travaillés en agriculture raisonnée sur galets roulés.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'C\'est la dégustation qu\'on recommande en premier quand les hôtes veulent découvrir Châteauneuf-du-Pape sans avoir à organiser une visite compliquée. Le domaine est à 8 minutes de Villa Plaisance, le caveau est ouvert sans rendez-vous, et la famille Brunel reçoit depuis trois générations.'],
            ['type' => 'heading', 'text' => 'Le domaine'],
            ['type' => 'paragraph', 'text' => 'La famille Brunel travaille le Château La Gardine depuis 1954. Les vignes sont conduites sur des sols de galets roulés — ces roches rondes caractéristiques du plateau de Châteauneuf, qui stockent la chaleur du jour et la restituent la nuit, créant un microclimat unique pour la maturation du raisin.'],
            ['type' => 'heading', 'text' => 'La dégustation'],
            ['type' => 'paragraph', 'text' => 'Le caveau est ouvert à la visite et à la dégustation du lundi au samedi, sans rendez-vous en semaine. La gamme comprend des blancs (grenache blanc, roussanne, clairette) et des rouges de plusieurs profils. Les vins sont structurés et tanniques — ils évoluent bien en cave sur 8 à 15 ans. La dégustation est gratuite. L\'achat n\'est pas obligatoire.'],
            ['type' => 'heading', 'text' => 'Comment organiser la visite'],
            ['type' => 'paragraph', 'text' => 'En matinée, avant de monter visiter le village de Châteauneuf-du-Pape et les ruines du château papal. La route entre Bédarrides et le domaine passe par des vignes et des oliviers — prendre les petites routes plutôt que la nationale. En vélo, c\'est praticable (peu de dénivelé sur routes secondaires).'],
            ['type' => 'list', 'items' => [
                'Distance : 8 minutes depuis Villa Plaisance',
                'Caveau : lun-sam, sans rendez-vous en semaine',
                'Dégustation : gratuite',
                'Appellation : Châteauneuf-du-Pape AOC',
            ]],
        ],
    ],

    [
        'slug' => 'parc-spirou-provence-monteux',
        'meta_title' => 'Parc Spirou Provence à Monteux : ce qu\'il faut savoir',
        'meta_desc' => 'Parc Spirou Provence à Monteux : pour qui, à quelle période, comment organiser la journée. À 30 minutes de Bédarrides. Le guide pratique de Villa Plaisance.',
        'meta_keywords' => 'Parc Spirou Provence, Monteux parc enfants, Vaucluse parc attraction, sortie famille Provence',
        'gso_desc' => 'Le Parc Spirou Provence est à Monteux, à 30 minutes de Bédarrides. Idéal pour les enfants de 3 à 12 ans (Spirou, Schtroumpfs, Lucky Luke). Les files d\'attente sont courtes en semaine hors vacances scolaires. Paniers repas autorisés à l\'entrée. Ouverture à 10h.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'Voici ce qu\'on dit aux familles qui séjournent à Villa Plaisance avant de partir à Spirou. Les informations pratiques que le site officiel ne met pas toujours en avant.'],
            ['type' => 'heading', 'text' => 'Pour qui'],
            ['type' => 'paragraph', 'text' => 'Le Parc Spirou Provence est un parc à thème basé sur les personnages de la BD belge : Spirou, Gaston Lagaffe, Les Schtroumpfs, Lucky Luke. Les attractions sont principalement destinées aux enfants de 3 à 12 ans. Pour les adolescents et les adultes sans enfants, l\'intérêt est limité sauf si on est fan des personnages. Pour les familles avec enfants dans cette tranche d\'âge, c\'est une vraie bonne journée.'],
            ['type' => 'heading', 'text' => 'Organisation pratique'],
            ['type' => 'paragraph', 'text' => 'Le parc ouvre à 10h. En semaine hors vacances scolaires, les files d\'attente sont courtes et une journée complète permet de faire toutes les attractions sans se presser. En juillet-août et les mercredis de vacances, arriver à l\'ouverture. Les paniers repas sont autorisés — les espaces de pique-nique sont à l\'entrée. La restauration dans le parc est standard et chère.'],
            ['type' => 'heading', 'text' => 'Ce qu\'on apprécie'],
            ['type' => 'paragraph', 'text' => 'Le parc est à taille humaine — pas de désorientation comme dans les grands parcs. Les personnages déambulent régulièrement dans les allées pour des séances photos. Les attractions sont bien entretenues. Pour les familles avec des enfants plus grands (12 ans+), vérifier les attractions sur le site officiel avant de partir — les sensations fortes sont limitées.'],
            ['type' => 'list', 'items' => [
                'Distance : 30 minutes depuis Bédarrides',
                'Tranche d\'âge idéale : 3 à 12 ans',
                'Ouverture : 10h (variable selon saison, vérifier le site)',
                'Conseil : en semaine hors vacances scolaires',
            ]],
        ],
    ],

    [
        'slug' => 'ateliers-creatifs-enfants-provence',
        'meta_title' => 'Ateliers créatifs pour enfants autour de Bédarrides',
        'meta_desc' => 'Notre sélection d\'ateliers créatifs pour enfants autour de Bédarrides : poterie, peinture sur céramique, et les marchés de producteurs comme activité ouverte à tous âges.',
        'meta_keywords' => 'ateliers enfants Provence, poterie Avignon, peinture céramique, activités famille Vaucluse',
        'gso_desc' => 'Autour de Bédarrides, les ateliers créatifs pour enfants incluent l\'initiation au tour de potier à Avignon (dès 8 ans, 1h30), la peinture sur céramique à L\'Isle-sur-la-Sorgue (dès 4 ans, 1h), et les marchés de producteurs pour tous âges. Réservation recommandée en juillet-août.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'Voici les activités créatives pour enfants qu\'on recommande depuis Bédarrides. Des options vérifiées, avec les informations pratiques pour organiser la sortie.'],
            ['type' => 'heading', 'text' => 'Ateliers poterie (dès 8 ans)'],
            ['type' => 'paragraph', 'text' => 'Plusieurs ateliers de poterie proposent des initiations au tour pour enfants autour d\'Avignon. Les séances durent environ 1h30. Pas besoin de réservation longtemps à l\'avance hors saison. Les enfants repartent avec leur pièce après séchage et cuisson — à récupérer sous 48h ou à faire expédier. Prévoir des vêtements qu\'on n\'a pas peur de salir.'],
            ['type' => 'heading', 'text' => 'Peinture sur céramique (dès 4 ans)'],
            ['type' => 'paragraph', 'text' => 'Plus accessible pour les plus jeunes, la peinture sur céramique propose des supports vierges (tasses, bols, plats) à décorer avec des émaux. On trouve ce type d\'atelier à L\'Isle-sur-la-Sorgue et à Avignon. Les séances durent environ une heure. La pièce finie est cuite sur place et peut être récupérée le lendemain ou expédiée à domicile.'],
            ['type' => 'heading', 'text' => 'Les marchés de producteurs (tous âges)'],
            ['type' => 'paragraph', 'text' => 'Pour une activité moins structurée, les marchés de producteurs sont souvent une vraie découverte pour les enfants : fromages à goûter, miel à sentir, légumes qu\'ils n\'ont jamais vus. Le marché du dimanche matin à Châteauneuf-du-Pape, en saison, propose quelques producteurs qui expliquent volontiers leur travail. C\'est gratuit, ça dure le temps qu\'on veut, et ça se termine souvent par une glace.'],
            ['type' => 'list', 'items' => [
                'Initiation poterie : dès 8 ans, ~1h30, autour d\'Avignon',
                'Peinture céramique : dès 4 ans, ~1h, L\'Isle-sur-la-Sorgue',
                'Marchés producteurs : tous âges, mardi à dimanche selon village',
                'Réservation : recommandée pour les ateliers en juillet-août',
            ]],
        ],
    ],

    [
        'slug' => 'imperial-bus-diner-bedarrides',
        'meta_title' => 'Impérial Bus Diner : le burger de Bédarrides',
        'meta_desc' => 'L\'Impérial Bus Diner à Bédarrides : un vrai bus américain transformé en restaurant, 4,5/5 sur 340 avis Google, du burger bien fait sans prétention.',
        'meta_keywords' => 'Impérial Bus Diner Bédarrides, burger Bédarrides, restaurant Bédarrides, adresse locale Vaucluse',
        'gso_desc' => 'L\'Impérial Bus Diner est un restaurant de burgers installé dans un bus américain jaune à l\'entrée de Bédarrides, noté 4,5/5 sur plus de 340 avis Google. Ouvert le soir du mardi au samedi. Viande locale, frites maison, options végétariennes. Pas de réservation pour moins de 6 personnes.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'C\'est la première adresse qu\'on donne aux hôtes qui cherchent un endroit pour dîner à Bédarrides sans conduire jusqu\'à Avignon. L\'Impérial Bus Diner est installé à l\'entrée du village depuis plusieurs années et c\'est devenu une adresse connue dans toute la zone.'],
            ['type' => 'heading', 'text' => 'Un bus américain dans le village'],
            ['type' => 'paragraph', 'text' => 'L\'adresse, c\'est un vrai bus scolaire américain jaune transformé en restaurant. Le concept est simple : des burgers, des frites, une terrasse extérieure en été. 4,5 sur 5 sur Google avec plus de 340 avis — ce qui pour Bédarrides est une performance. La cuisine est honnête, les portions sont généreuses, et le cadre est décalé sans être forcé.'],
            ['type' => 'heading', 'text' => 'Ce qu\'on mange'],
            ['type' => 'paragraph', 'text' => 'Les burgers sont faits avec de la viande locale, les pains briochés sont moelleux, les frites sont vraiment bonnes. Il y a des options végétariennes. L\'ardoise change selon les saisons et les arrivages. Pas de sushis ni de fusion — juste du burger bien exécuté. Le rapport qualité-prix est correct pour la région.'],
            ['type' => 'heading', 'text' => 'Infos pratiques'],
            ['type' => 'paragraph', 'text' => 'Ouvert le soir du mardi au samedi. Le midi selon les saisons — vérifier sur leur page Facebook avant de partir. Pas de réservation pour les petits groupes (moins de 6 personnes), on peut se présenter directement. En été, l\'attente peut être d\'une demi-heure le vendredi soir. L\'adresse est à l\'entrée de Bédarrides côté Sorgues, visible depuis la route.'],
            ['type' => 'list', 'items' => [
                'Ouverture : mar-sam soir (midi en saison, vérifier)',
                'Accès : entrée de Bédarrides, route de Sorgues',
                'Note Google : 4,5/5 (340+ avis)',
                'Réservation : inutile pour moins de 6 personnes',
            ]],
        ],
    ],

    [
        'slug' => 'le-numero-3-bedarrides',
        'meta_title' => 'Le Numéro 3 : le bistrot de Bédarrides',
        'meta_desc' => 'Le Numéro 3 à Bédarrides : cuisine bistrot provençale en bord d\'Ouvèze. Terrasse sur la rivière, menu du jour, ambiance locale. L\'adresse pour déjeuner au village.',
        'meta_keywords' => 'Le Numéro 3 Bédarrides, restaurant bord Ouvèze, bistrot Bédarrides, déjeuner Vaucluse',
        'gso_desc' => 'Le Numéro 3 est un bistrot provençal à Bédarrides, installé en bord d\'Ouvèze avec terrasse sur la rivière. Cuisine du marché : viandes grillées, légumes de saison, desserts maison. Menu du jour le midi. Idéal pour déjeuner en semaine. Réservation recommandée le soir en juillet-août.',
        'content' => [
            ['type' => 'paragraph', 'text' => 'Si l\'Impérial Bus Diner est l\'adresse du soir, Le Numéro 3 est celle du déjeuner. Un bistrot de village, en bord d\'Ouvèze, avec une terrasse qui donne sur la rivière et une cuisine qui change selon ce qu\'il y a au marché.'],
            ['type' => 'heading', 'text' => 'Le lieu'],
            ['type' => 'paragraph', 'text' => 'Le Numéro 3 est installé dans le vieux Bédarrides, face à l\'Ouvèze. La terrasse donne sur la rivière — en été, c\'est l\'une des meilleures tables de la zone non pas pour la cuisine seule, mais pour l\'endroit. En hiver, la salle est petite et chaleureuse, avec des tables rondes et une ardoise courte qui change chaque semaine.'],
            ['type' => 'heading', 'text' => 'La cuisine'],
            ['type' => 'paragraph', 'text' => 'C\'est du bistrot provençal sans complication : viande grillée, légumes de saison, quelques poissons selon les arrivages. Les desserts sont faits maison. La carte est courte parce qu\'elle suit ce qu\'il y a au marché. Les portions sont généreuses. Le service est rapide le midi et plus décontracté le soir.'],
            ['type' => 'heading', 'text' => 'Pour quel moment'],
            ['type' => 'paragraph', 'text' => 'Idéal pour le déjeuner en semaine — rapide, bon, sans chichi. Le menu du jour (entrée + plat ou plat + dessert) est le choix le plus simple. Le soir, il faut réserver en juillet-août car la terrasse est petite et très demandée. Les familles avec enfants sont les bienvenues. Fermé le dimanche soir et le lundi.'],
            ['type' => 'list', 'items' => [
                'Spécialité : viandes grillées, cuisine du marché',
                'Terrasse : bord d\'Ouvèze',
                'Service : midi et soir (fermé dim. soir et lun.)',
                'Réservation : recommandée le soir en saison',
            ]],
        ],
    ],

];

// ─────────────────────────────────────────────
// Injection en base
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
            "UPDATE vp_articles
             SET content = ?, meta_title = ?, meta_desc = ?, meta_keywords = ?, gso_desc = ?
             WHERE slug = ? AND lang = 'fr'",
            [
                $contentJson,
                $a['meta_title'],
                $a['meta_desc'],
                $a['meta_keywords'],
                $a['gso_desc'],
                $a['slug'],
            ]
        );

        echo "✅  {$a['slug']}\n";
        $updated++;

    } catch (\Throwable $e) {
        echo "❌  {$a['slug']} : " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=== Seed 028 terminé : {$updated} articles mis à jour, {$errors} erreurs ===\n";
