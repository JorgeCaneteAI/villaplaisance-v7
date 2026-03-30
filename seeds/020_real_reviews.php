<?php
declare(strict_types=1);

/**
 * Seed 020 — Import all real reviews from Airbnb (chambres + villa), Booking, and Google
 * Replaces the 10 placeholder reviews with 103 real reviews
 */

require __DIR__ . '/../config.php';

// Clear existing reviews
Database::query("DELETE FROM vp_reviews");
echo "Cleared existing reviews.\n";

$reviews = [];

// ═══════════════════════════════════════════════════════
// AIRBNB — Chambres d'hôtes (66 reviews, note 4.98/5)
// ═══════════════════════════════════════════════════════

$reviews[] = ['airbnb', 'bb', 'Mathieu', '', 'Merci à Jorge pour ces 2 jours, Hote disponible, accueillant, et de bon conseil. Un petit dej incroyable. Attentionné envers les enfants comme avec les adultes. Chambres propres, conformes à la description. Merci, nous n\'hésiterons pas lors d\'un prochain séjour', 5.0, '2025-10-01', 1, 1];

$reviews[] = ['airbnb', 'bb', 'Manon', '', 'Nous avons passé un excellent séjour à la Villa Plaisance ! Merci Jorge pour vos bons conseils pour les visites et les restaurants, et pour les petits-déjeuners incroyables tous les matins, qui étaient aussi que copieux, et faits-maison ! Tout etait conforme à la description par ailleurs, propreté irréprochable, et le jardin est très agréable pour se détendre.', 5.0, '2025-05-01', 1, 1];

$reviews[] = ['airbnb', 'bb', 'L\'Arpenaz', '', 'Encore un grand merci pour cette accueil gorge excellent petits déjeuners au plaisir de revenir avec grand plaisir bien amicalement. Isabelle pascal et Aloha', 5.0, '2025-10-01', 0, 0];

$reviews[] = ['airbnb', 'bb', '유진', '', 'C\'était ma deuxième visite. Tout comme lorsque je suis venu il y a 3 ans, Jorge était toujours aussi gentil. Le logement était bien rangé, et mes amis étaient très satisfaits.', 5.0, '2025-10-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Valentine', '', 'Goerges est un hôtes adorable et à l écoute. Tout parfait.', 5.0, '2025-05-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Rosemarie', '', 'Jorge a été un hôte exceptionnel, nous avons séjourné 6 nuits et le petit déjeuner était différent chaque jour - et incroyable ! Il nous a également recommandé des endroits à visiter localement et nous a réservé des dégustations de vins à Châteaneuf-du-Pape. Les chambres étaient propres, confortables, parking sécurisé et belle piscine. À 10 minutes à pied de la gare - puis à 10 minutes en train d\'Avignon. Merci beaucoup Jorge pour votre gentillesse, votre enthousiasme et votre hospitalité.', 5.0, '2025-09-01', 1, 1];

$reviews[] = ['airbnb', 'bb', 'Dirk', '', 'Nous avons été chaleureusement accueillis, très gentils et serviables. Tout a été rendu possible. Une belle propriété avec une grande piscine. Nous nous sommes sentis vraiment à l\'aise. Jorge a préparé un merveilleux petit déjeuner, avec tout ce qui est frais et fait maison et était toujours là pour nous.', 5.0, '2025-07-01', 1, 0];

$reviews[] = ['airbnb', 'bb', '薇', '', 'Séjour très agréable. Maison calme et belle, chambre propre et bien rangée, hôte chaleureux et joyeux, nous nous sommes sentis comme chez nous. Surtout le petit déjeuner que l\'hôte nous a préparé, c\'était incroyable et nous ne voulions pas partir.', 5.0, '2025-04-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Lucas', '', 'Jorge a été très hospitalier et prévenant, il a tout mis en œuvre pour que notre séjour se déroule de la meilleure des manières et nous a proposé des programmes de visites sur mesure. Ses petits déjeuners 100% fait maison sont un vrai plus pour bien commencer la journée ! Nous reviendrons avec plaisir', 5.0, '2024-05-01', 1, 1];

$reviews[] = ['airbnb', 'bb', 'Sylvie', '', 'Nous avons apprécié l\'hospitalité de Jorge et son accueil. C\'est une personne très serviable et prévenante. Nous avons eu le privilège de déguster et savourer ses produits maison lors de très bons petits déjeuners.', 5.0, '2024-10-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Louise', '', 'Jorge nous a accueilli très chaleureusement, hôte réactif et flexible. Petits déjeuners excellent, le tout fait maison ! Une villa calme et paisible, qui nous a réellement apporter le repos nécessaire. Nous nous réjouissons déjà d\'y retourner !', 5.0, '2024-09-01', 1, 0];

$reviews[] = ['airbnb', 'bb', 'Mélanie', '', 'Notre séjour de dernière minute à parfaitement démarré grâce à l\'accueil chaleureux de Jorge, il soigne ses hôtes à merveille !! Bienveillant et arrangeant il nous a reçu dans un cadre idyllique pour se reposer, nous reviendrons avec grand plaisir pour qq jours de plus. Nous recommandons vivement', 5.0, '2024-06-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Tino', '', 'J\'ai séjourné avec mon chien une nuit. Jorge est tout simplement un gars formidable. Super serviable et accommodant. Nous reviendrons avec plaisir.', 5.0, '2025-10-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Gregory', 'Burtonsville, Maryland', 'Excellent endroit et Jorge a été un excellent hôte. Le petit déjeuner était également merveilleux.', 5.0, '2025-06-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Pierre', 'Port Townsend, Washington', 'Une hospitalité exceptionnelle de la part de Jorge ! La piscine était charmante, propre et privée. Lits confortables, toilettes et douche. Au centre des principaux quartiers viticoles.', 5.0, '2025-06-01', 1, 0];

$reviews[] = ['airbnb', 'bb', 'Jaime', '', 'Le logement de Jorge et Alexander est idéalement situé pour visiter les vignobles à proximité. La chambre était chaude en hiver et j\'ai passé une très bonne nuit de sommeil ! Encore mieux, le petit déjeuner ! Merci pour ce merveilleux séjour – je reviendrai pour l\'été !', 5.0, '2025-01-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Yu', '', 'Nous avons passé un séjour merveilleux à la villa. Jorge est un excellent majordome. Il est venu nous chercher à la gare et nous a préparé un très bon petit déjeuner. Situé dans une ville tranquille, facile à explorer comme Avignon. Nous reviendrons certainement.', 5.0, '2024-07-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Aurélie', '', 'Une belle parenthèse à la Villa Plaisance ! « Comme à la maison » mais en mieux ! Jorge est hyper attentionné et de bons conseils pour les activités et restaurants aux alentours. Le cadre de la villa est magnifique et invite à la farniente. Les petits déjeuners faits maison sont délicieux. Je recommande +++', 5.0, '2023-06-01', 1, 1];

$reviews[] = ['airbnb', 'bb', 'Nicolas', '', 'Merci Jorge pour l\'accueil et vos super petits dej !', 5.0, '2024-10-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Jean-Michel', '', 'Accueil chaleureux. Excellents petits déjeuners maison.', 5.0, '2024-09-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Mario', 'Québec City, Canada', 'Un séjour extraordinaire ! M. Jorge est hôte des plus attentionnés. Ses précieux conseils sur nos visites dans la régions ainsi que ses délicieux déjeuners ont fait de notre passage chez lui un souvenir impérissable. Je recommande chaudement !', 5.0, '2023-06-01', 1, 0];

$reviews[] = ['airbnb', 'bb', 'Nicolas', 'Paris, France', 'La Villa Plaisance est très facilement accessible et à proximité de tous les centres d\'intérêt de la région. Jorge est très accueillant, nous a donné de bons conseils de visite, et nous a préparé des petits déjeuners succulents ! Je recommande !', 5.0, '2023-04-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Timo', '', 'Tout s\'est très bien passé. L\'accueil était chaleureux et le service excellent. Le petit déjeuner était meilleur que dans n\'importe quel hôtel ! Nous avons bien dormi dans une chambre calme. Je recommande vivement cet hébergement à tout le monde.', 5.0, '2024-10-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Rob', 'Sydney, Australie', 'Nous avons passé un séjour incroyable. Jorge a été l\'hôte idéal pour organiser des dégustations de vin pour nous et nous préparer un délicieux petit-déjeuner tous les jours. Hautement recommandé !', 5.0, '2024-05-01', 1, 0];

$reviews[] = ['airbnb', 'bb', 'Massimo', '', 'Nous n\'aurions jamais imaginé trouver un endroit comme celui-ci. Une merveilleuse villa entourée de verdure, joliment décorée, avec une belle piscine. Jorge n\'était pas un simple hôte mais une personne spéciale et rare qui a pris soin de nous. Ses petits déjeuners sont excellents et toujours différents. Nous avons été très bien, nous reviendrons bientôt. Super recommandé ! Arianna et Massimo', 5.0, '2024-08-01', 1, 1];

$reviews[] = ['airbnb', 'bb', 'Wenfang', '', 'Jorge nous a gâtés avec un grand sourire chaleureux, une orientation détaillée de la villa et des environs. Il a recommandé un excellent restaurant et a fait une réservation pour nous. Après ma promenade matinale, Jorge nous a offert un petit déjeuner délicieux. Je veux revenir dès que possible pour un séjour beaucoup plus long.', 5.0, '2023-06-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Graham', '', 'Nous avons été accueillis par Jorge pour notre séjour de 3 nuits et c\'est vraiment un Superhost. Ses petits déjeuners sont excellents et il a fait tout son possible pour nous aider. L\'hébergement était très confortable dans un cadre paisible. Hautement recommandé.', 5.0, '2023-05-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Christine', '', 'La maison de Jorge et d\'Alexandre est très bien située entre Orange et Avignon. Lors de notre petit séjour, tout était parfait (les chambres, le petit déjeuner, la piscine, l\'apéritif au bord de la piscine, la balade dans les vignes, l\'accueil). Ce fut un vrai moment de plaisir !', 5.0, '2022-06-01', 1, 0];

$reviews[] = ['airbnb', 'bb', 'Jean-Noël', '', 'Une agréable nuit en famille à 20 minutes d\'Avignon et 12 minutes du parc Spirou. Belle maison des années 20 entourée de vignes. Jorge nous a offert un accueil chaleureux, de bon conseils et un succulent petit déjeuner maison. Les chambres en enfilade sont idéales pour une famille. La maison a une âme.', 5.0, '2022-05-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'John', '', 'Nous voulions un point de départ pour explorer l\'histoire romaine à Nîmes, Orange et Pont du Gard. Nous n\'aurions pas pu choisir un meilleur endroit. La chambre était spacieuse et impeccablement propre. Les petits déjeuners préparés par Jorge étaient fantastiques. Jorge nous a donné d\'excellentes adresses de restaurants et des conseils locaux. Nous avons hâte de revenir.', 5.0, '2023-09-01', 1, 1];

$reviews[] = ['airbnb', 'bb', 'Bei', '', 'Nous avons passé un séjour incroyable ici. Dès notre arrivée, l\'hospitalité chaleureuse de Jorge nous a réconfortés. La maison était magnifique, avec une décoration de bon goût et une propreté impeccable. Belle piscine entourée d\'un jardin verdoyant. Le petit déjeuner maison le plus impressionnant ! Je recommande vivement ce lieu !', 5.0, '2023-07-01', 1, 1];

$reviews[] = ['airbnb', 'bb', 'Monica', '', 'On a passé le meilleur moment chez Alexandre et Jorge. La maison était magnifique et la piscine très rafraîchissante ! Jorge a fait des petits déjeuners incroyables chaque matin et les a adaptés à nos préférences. Jorge a eu la gentillesse de nous apprendre à faire de la brioche ! Je vous recommande vivement de séjourner ici.', 5.0, '2023-06-01', 1, 0];

$reviews[] = ['airbnb', 'bb', 'Matt', 'Charlotte, Caroline du Nord', 'Séjour incroyable, Jorge était incroyablement hospitalier. La piscine était parfaite, le trampoline était un ajout amusant, et le petit déjeuner était phénoménal. Une véritable expérience BNB, merci beaucoup Jorge et Sophie.', 5.0, '2023-06-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Laurène', '', 'Nous avons passé un excellent séjour dans cette charmante maison au milieu des vignes. Nous avons été très chaleureusement accueillis par Jorge. Mention spéciale à l\'excellente brioche faite maison, ainsi qu\'au reste du petit déjeuner maison. Jorge a été aux petits soins avec nous !', 5.0, '2022-10-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Alexandra', '', 'Hôtes très sympathiques et attentionnés. Maison superbe et idéalement située pour visiter la région. Tout était parfait. Rendez-vous est pris pour l\'année prochaine. MERCI POUR TOUT', 5.0, '2022-09-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Valérie', '', 'Deuxième séjour chez Alexandre et enfin profiter de l\'immense piscine est un vrai bonheur, je vous recommande cette adresse pour l\'emplacement et l\'accueil chaleureux de mes hôtes.', 5.0, '2022-06-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Faustine', '', 'Notre séjour a été paradisiaque. Jorge et Alexandre ont été aux petits soins et absolument adorables. Le petit-déjeuner indescriptible... tout est fait maison par Jorge, on a failli pleurer tellement c\'était bon. La région est sublime. J\'ai déjà recommandé cette adresse et j\'ai hâte de revenir !', 5.0, '2022-06-01', 1, 1];

$reviews[] = ['airbnb', 'bb', 'Valérie', '', 'Nous avons passé un séjour très agréable dans cette grande maison. Une chambre au calme parfaitement bien décorée. Note spéciale pour la literie hyper confortable !!! Jorge est particulièrement attentif au bien être des occupants. Une immense piscine dans laquelle nous aurons plaisir à nous baigner lors de notre prochain passage.', 5.0, '2022-04-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Carmen', '', 'Nous avons été accueillis et choyés et nous nous sommes sentis en famille. Petit déjeuner super et avec des aliments authentiques. Nous nous sommes détendus dans la piscine. Très recommandé.', 5.0, '2023-07-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Birgitta', '', 'J\'ai beaucoup aimé. Un petit bijou. Un hôte attentionné. De merveilleux conseils pour les activités. Petit déjeuner une petite œuvre d\'art. Le séjour restera longtemps très positif dans nos souvenirs.', 5.0, '2023-06-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Annette', '', 'Nous étions déjà 2x dans le logement et nous avons été très satisfaits comme la première fois. Jorge chouchoute ses clients et répond aux souhaits des clients. Le petit déjeuner est chaque jour à nouveau une merveilleuse surprise. Nous reviendrons.', 5.0, '2023-05-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Catherine', '', 'Jorge a été une star absolue, il m\'a aidé à trouver 150 roses blanches, des bâches, 10 énormes seaux pour le mariage de mon fils le lendemain, rien n\'était trop compliqué, c\'était un vrai super hôte.', 5.0, '2023-05-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Josephine', '', 'L\'emplacement idéal pour visiter Châteauneuf-du-Pape. Les deux chambres étaient spacieuses et la vue de nos fenêtres était spectaculaire. Alexandre et George étaient des hôtes extraordinaires. Nous a accueillis avec Rosé réfrigéré. George nous a servi un élégant petit déjeuner. Mon seul regret était de ne pas pouvoir rester plus longtemps.', 5.0, '2022-09-01', 1, 0];

$reviews[] = ['airbnb', 'bb', 'Quentin', '', 'Superbe séjour avec Jorge et Alexandre. Idéalement situé au milieu des vignes et non loin de Châteauneuf-du-Pape. Commencez votre journée avec un superbe petit déjeuner copieux et fait maison, piquez une tête dans l\'immense piscine et enchaînez sur les bons conseils de l\'hôte. Je recommande pour un voyage en famille ou en couple. P.S : Ami lecteur c\'est l\'endroit idéal ! Livres à foison et spot pour lire tranquillement', 5.0, '2022-06-01', 1, 0];

$reviews[] = ['airbnb', 'bb', 'Emilie', '', 'Séjour excellent, accueil parfait dans une maison au charme fou, fin mélange d\'authenticité et de modernité. La piscine est entourée de jasmin, très agréable. Et chaque jour une délicieuse surprise au petit dej ! Clim et moustiquaire dans la chambre. Bref nous avons passé un super moment. On recommande et on espère aussi revenir !!', 5.0, '2022-05-01', 1, 1];

$reviews[] = ['airbnb', 'bb', 'Andrea', '', 'Bel emplacement, d\'excellentes installations. Beaucoup d\'espace dans le logement. Jorge a été un hôte de première classe et nous a aidés avec tout ce que nous avons demandé.', 5.0, '2023-09-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Kirsten Frances', 'Costa Mesa, Californie', 'Alexandre et Sophie sont de charmants hôtes. Ils n\'ont pas pu faire assez pour nous. Merci de nous avoir accueillis.', 5.0, '2023-06-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Lisa', '', 'Nous avons passé une nuit de passage en Espagne. Tout était tel que décrit, il ne manquait de rien ! Le matin, il y avait un très bon petit déjeuner français !', 5.0, '2023-05-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Clemence', '', 'Hôte très accueillant et très sympathique. Le logement est idéalement situé et très confortable. Je recommande.', 5.0, '2022-06-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Jérémy', 'Montréal, Canada', 'L\'accueil de Jorge a été chaleureux, le logement est calme, propre, dans un cadre magnifique. Et le petit-déjeuner a été juste merveilleux !! Encore merci !', 5.0, '2022-05-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'William', '', 'Très jolie résidence, accueil très chaleureux, hôte aux petits soins. Très bonne literie, petit déjeuner très bon !', 5.0, '2022-05-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Cédric', '', 'Séjour super. Jorge est aux petits soins, de très bons conseils et le petit-déjeuner une tuerie ! Merci encore.', 5.0, '2022-05-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Elisabeth', '', 'La plus belle expérience Airbnb jamais. Georges nous a accueillis chaleureusement et a partagé son domicile de manière simple, sympathique et généreuse. Il nous a gâtés le matin avec un merveilleux petit déjeuner : des variations sur des brioches maison, des bols de fruits frais, de délicieuses confitures. Georges était toujours disponible pour nous, le meilleur hôte. J\'espère revenir bientôt !', 5.0, '2023-04-01', 1, 1];

$reviews[] = ['airbnb', 'bb', 'Tobias', '', 'Nous avons passé un super séjour. Surtout l\'hôte était très sympathique.', 4.0, '2023-06-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Valérie', '', 'C\'est mon troisième séjour dans cette maison d\'hôte… toujours un plaisir… je recommande.', 5.0, '2022-10-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Charlotte', '', 'P E R F E C T ! Nous sommes arrivés dans la belle villa et nous nous sommes immédiatement sentis comme à la maison ! Jorge nous a préparé le plus délicieux petit déjeuner (jus maison, confitures, brioche !) que nous attendions avec impatience chaque matin ! Il a été très attentionné et nous a donné les meilleurs conseils. On a adoré !!!', 5.0, '2022-07-01', 1, 1];

$reviews[] = ['airbnb', 'bb', 'Ellen', 'Maine, États-Unis', 'Merci Alexandre et Jorge pour cette expérience fantastique ! C\'était notre AirBNB préféré du voyage ! Jorge a pris grand soin de nous, notamment en nous faisant un petit-déjeuner génial - la meilleure salade de fruits que j\'aie jamais mangée !', 5.0, '2022-06-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Nancy', 'Austin, Texas', 'Quelle expérience merveilleuse ! La villa et le terrain environnant étaient charmants et la piscine très relaxante. C\'est Jorge qui a vraiment rendu notre séjour exceptionnel avec sa personnalité amicale et son incroyable petit déjeuner fait maison !', 5.0, '2022-06-01', 1, 0];

$reviews[] = ['airbnb', 'bb', 'Lio', 'New York, États-Unis', 'En tant que super hôte, j\'ai été très impressionnée en entrant dans cette propriété ! Un si joli cadre niché au cœur d\'incroyables vignobles. Notre hôte George a été merveilleux. La maison était propre, ouverte et joliment décorée. George fournissait un petit-déjeuner avec une incroyable brioche et une tasse d\'expresso parfaite !', 5.0, '2022-04-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Angélique', '', 'Jorge sait mieux que quiconque comment recevoir ses voyageurs. La convivialité, l\'excellent petit déjeuner, pour nous, ce furent deux jours inoubliables. Nous aimerions revenir ici.', 5.0, '2022-10-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Jarid', '', 'Séjour incroyable, hôtes incroyables. L\'une de nos meilleures expériences Airbnb. J\'ai hâte d\'y séjourner à nouveau !', 5.0, '2022-08-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Grace', '', 'La maison est magnifique et l\'espace était parfait pour nous. La piscine est géniale. Jorge est un hôte et une personne extraordinaire, sincère, amicale et serviable ! Il a fait un excellent petit-déjeuner tous les matins avec du jus fraîchement préparé, des brioches et des gaufres maison. Et puis il nous a préparé des rouleaux de brioche pour notre goûter. Il est le plus gentil.', 5.0, '2022-07-01', 1, 0];

$reviews[] = ['airbnb', 'bb', 'Nadja', 'Géorgie, États-Unis', 'Cette villa est d\'un excellent rapport qualité-prix avec de superbes hôtes. Nous avons largement dépassé nos attentes et nous reviendrons.', 5.0, '2022-06-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Barney', 'New York, New York', 'Une propriété agréable avec un hôte charmant. Un bel endroit pour une famille avec un ou deux enfants. Et un excellent petit déjeuner avec des crêpes, une brioche maison parfaite et plus encore !', 5.0, '2022-05-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Nate', 'San Francisco, Californie', 'Super séjour. Belle piscine. Merci pour le petit déjeuner !', 5.0, '2022-09-01', 0, 0];

$reviews[] = ['airbnb', 'bb', 'Annette', '', 'Nous avons rendu visite à notre fille qui vit depuis peu à Châteauneuf-du-Pape.', 5.0, '2022-09-01', 0, 0];

// ═══════════════════════════════════════════════════════
// AIRBNB — Villa entière (9 reviews, note 4.89/5)
// ═══════════════════════════════════════════════════════

$reviews[] = ['airbnb', 'villa', 'Marianne', '', 'Nous avons passé un merveilleux séjour en famille à la Villa Plaisance. On s\'y sent comme chez soi. Tout nous a plu : le charme de la maison, la magnifique piscine, les activités et les visites dans la région. La maison est située au calme mais proche des commerces. Nous reviendrons sûrement ! Un grand merci à Alexandre et Jorge pour leur accueil, tout était parfait.', 5.0, '2025-08-01', 1, 1];

$reviews[] = ['airbnb', 'villa', 'Jan', '', 'Nous avons passé un bon séjour dans cette maison de vacances bien située. Les hôtes ont été très accueillants et disponibles. La piscine, grande et bien entretenue, est sécurisée par une clôture, ce qui est rassurant avec de jeunes enfants. La cuisine est bien équipée. Merci aux propriétaires pour leur accueil et leur écoute.', 4.0, '2025-07-01', 0, 0];

$reviews[] = ['airbnb', 'villa', 'Marie-Louise', '', 'Tout s\'est très bien passé. Accueil et contact très bons.', 5.0, '2024-08-01', 0, 0];

$reviews[] = ['airbnb', 'villa', 'Rachel', '', 'Un chez-soi loin de chez soi. Maison très bien équipée, merveilleuse piscine et à proximité des attractions. Alexandre a été très accueillant et serviable. Ma famille a passé un agréable séjour relaxant.', 5.0, '2023-08-01', 1, 0];

$reviews[] = ['airbnb', 'villa', 'Emma', '', 'Nous avons passé un super séjour entre amis dans la belle maison d\'Alexandre. Il a été très accueillant et disponible. Maison très bien équipée. Cadre et piscine très agréable et calme. Nous recommandons !', 5.0, '2022-08-01', 0, 0];

$reviews[] = ['airbnb', 'villa', 'Déborah', '', 'Maison spacieuse idéale pour des vacances en famille ou entre amis. Un vrai cocon avec vue sur les vignes. Une immense piscine et une grande bibliothèque feront le bonheur des petits et grands. Un très bon accueil.', 5.0, '2022-08-01', 1, 0];

$reviews[] = ['airbnb', 'villa', 'Carina', '', 'Nous avons passé une semaine formidable à la Villa Plaisance avec sa grande piscine et son beau jardin. La maison est très bien équipée, surtout la cuisine. Nous n\'oublierons jamais les soirées douces au bord de la piscine. Le chant des cigales et la silhouette des cyprès se détachant du ciel rendaient ce lieu enchanteur.', 5.0, '2022-08-01', 1, 1];

$reviews[] = ['airbnb', 'villa', 'Yashi', '', 'Nous avons passé un super séjour à la Villa, l\'emplacement est magnifique dans les vignobles. Alexandre et Jorge ont été très bons dans la communication et disponibles facilement. La piscine a été bien rangée et propre. La maison est belle avec beaucoup de caractère et bien équipée.', 5.0, '2022-07-01', 0, 0];

$reviews[] = ['airbnb', 'villa', 'Elisabeth', '', 'Hôte très sympathique qui vit à proximité et fait en sorte que tout se passe au mieux. Toujours serviable avec des conseils et un accueil très chaleureux à la française !', 5.0, '2022-07-01', 0, 0];

// ═══════════════════════════════════════════════════════
// BOOKING — Chambres d'hôtes (21 reviews, note 9.2/10)
// ═══════════════════════════════════════════════════════

$reviews[] = ['booking', 'bb', 'Fred & Marine', 'France', 'L\'hôte est aux petits soins. La maison est magnifique et la piscine incroyable. Le petit déjeuner du matin est tout simplement magique. Merci encore pour l\'accueil : nous reviendrons sans hésiter et recommandons évidemment cette adresse !', 10.0, '2025-06-01', 1, 1];

$reviews[] = ['booking', 'bb', 'Maud', 'France', 'Accueil chaleureux, literie confortable et petit déjeuner complet (fait maison) et copieux !', 9.0, '2025-05-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Robert', 'France', 'La proximité des lieux touristiques. La diligence et l\'amabilité de Jorge.', 10.0, '2025-05-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Christine', 'France', 'Qualité et gentillesse de l\'accueil. De très bons produits au petit déjeuner. Jolie maison et très joli jardin. L\'hôte vous partage ses bonnes adresses.', 9.0, '2025-05-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Krystelle', 'France', 'L\'accueil de Jorge et ses bons conseils. Un super petit déjeuner avec des produits frais et locaux. Très belle collection de livres à disposition pour passer une belle soirée lecture.', 10.0, '2025-03-01', 1, 0];

$reviews[] = ['booking', 'bb', 'Karim', 'Tunisie', 'Authenticité des lieux et le petit déjeuner avec des ingrédients locaux.', 10.0, '2025-02-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Chrystelle', 'France', 'Accueil au top par Jorge. A préparé un excellent petit déjeuner.', 10.0, '2025-02-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Katia', 'France', 'En déplacement professionnel, nous avions loué pour 1 nuit. L\'accueil de Jorge fut extra, sympathique, discret mais disponible. Jolie maison conviviale. Heureuse surprise au petit-déjeuner : confitures, brioche et yaourts faits maison. Le concept authentique de la maison d\'hôte. Nous y retournerons volontiers.', 9.0, '2025-01-01', 1, 0];

$reviews[] = ['booking', 'bb', 'Matthieu', 'France', 'Excellent accueil, on ne manque de rien, les chambres sont impeccablement tenues. Le petit déjeuner, copieux, est remarquable par sa qualité, avec de nombreux produits faits maison. Nous recommandons vivement !', 10.0, '2024-12-01', 1, 1];

$reviews[] = ['booking', 'bb', 'Touria', 'France', 'Nous avons apprécié l\'endroit, très agréable. M. Jorge était à notre écoute, disponible et surtout très discret. Le petit déjeuner est formidable et très frais. Je recommande énormément.', 9.0, '2025-10-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Th', 'France', 'Bonne literie, excellent petit déjeuner.', 7.0, '2025-04-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Jeroen', 'Pays-Bas', 'This is the best accommodation we stayed in Southern France. Jorge is so friendly and welcomed us all with kindness. He magically prepared alternative delicious breakfasts and surprised our child with cookies. The rooms are super clean, we enjoyed the swimming pool and airco. We would love to come back!', 10.0, '2025-06-01', 1, 0];

$reviews[] = ['booking', 'bb', 'Giorgos', 'Grèce', 'The property was very clean and beautifully situated in Bedarrides, next to Chateauneuf du Pape. The host, Jorge, is an amazing person, very friendly and always made the ultimate effort to please us. The breakfast he prepared for us was insane!! We also loved spending time at the pool. Would definitely recommend!', 10.0, '2025-06-01', 1, 1];

$reviews[] = ['booking', 'bb', 'Michael', 'Allemagne', 'Jorge, the host has given us a lot of tips and had a lot of local knowledge to share. Great hospitality! We felt like home and had wonderful days in the Provence. The breakfast was magnifique! All home made or bio products with wonderful taste.', 10.0, '2025-04-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Lauren', 'France', 'Everything! We had a wonderful stay!', 10.0, '2025-04-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Elias', 'Pays-Bas', 'Alles prima verzorgd.', 10.0, '2025-04-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Molto', 'Espagne', 'Todo en general perfecto.', 10.0, '2025-01-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Slops', 'France', '', 8.0, '2025-06-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Niedergesteln', 'Suisse', '', 10.0, '2025-06-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Misao', 'France', '', 10.0, '2025-02-01', 0, 0];

$reviews[] = ['booking', 'bb', 'Alice', 'Allemagne', '', 5.0, '2026-02-01', 0, 0];

// ═══════════════════════════════════════════════════════
// GOOGLE — (7 reviews, note 5/5)
// ═══════════════════════════════════════════════════════

$reviews[] = ['google', 'bb', 'RUGBY A 5', '', 'Tout était parfait, de l\'accueil au confort au petit déjeuner. Établissement plus propre qu\'un hôtel, cadre super. Jorge est un hôte exceptionnel.', 5.0, '2025-04-01', 0, 0];

$reviews[] = ['google', 'bb', 'Bruno DHL', '', 'Super accueil de Jorge et un petit déjeuner excellent !!!! Au calme malgré la proximité de l\'autoroute. Belle villa avec piscine.', 5.0, '2025-04-01', 0, 0];

$reviews[] = ['google', 'bb', 'Laurence Papoutchian', '', 'Nous avons passé un très bon séjour dans cette maison d\'hôte. Le lieu est agréable et l\'accueil sympathique et chaleureux. Nous recommandons cette adresse.', 5.0, '2022-05-01', 0, 0];

$reviews[] = ['google', 'bb', 'Christel Joliveau', '', 'Séjour très agréable et petit déjeuner au top. La brioche perdue !', 5.0, '2025-04-01', 0, 0];

$reviews[] = ['google', 'bb', 'Achim Donald', '', 'Jorge, notre hôte, nous a donné de précieux conseils et a partagé avec nous une connaissance approfondie des lieux que nous avons visités. Un accueil exceptionnel ! Nous nous sommes sentis comme chez nous.', 5.0, '2025-04-01', 0, 0];

$reviews[] = ['google', 'bb', 'Enyrd Weis', '', 'Séjour merveilleux à la Villa Plaisance ! J\'ai particulièrement apprécié le petit-déjeuner copieux, la piscine et les échanges agréables avec les hôtes. Je recommande vivement !', 5.0, '2022-05-01', 0, 0];

$reviews[] = ['google', 'bb', 'Raphaël Saunier', '', '', 5.0, '2022-10-01', 0, 0];

// ═══════════════════════════════════════════════════════
// INSERT ALL
// ═══════════════════════════════════════════════════════

$count = 0;
foreach ($reviews as $r) {
    [$platform, $offer, $author, $origin, $content, $rating, $date, $featured, $carousel] = $r;

    // Skip empty reviews for display but still insert for count
    Database::query(
        "INSERT INTO vp_reviews (platform, offer, author, origin, content, rating, review_date, featured, home_carousel, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'published')",
        [$platform, $offer, $author, $origin, $content, $rating, $date, $featured, $carousel]
    );
    $count++;
}

echo "Inserted {$count} reviews.\n";

// Summary
$airbnbBB = Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_reviews WHERE platform='airbnb' AND offer='bb'");
$airbnbVilla = Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_reviews WHERE platform='airbnb' AND offer='villa'");
$booking = Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_reviews WHERE platform='booking'");
$google = Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_reviews WHERE platform='google'");

echo "  Airbnb chambres: {$airbnbBB['cnt']}\n";
echo "  Airbnb villa: {$airbnbVilla['cnt']}\n";
echo "  Booking: {$booking['cnt']}\n";
echo "  Google: {$google['cnt']}\n";
echo "Done.\n";
