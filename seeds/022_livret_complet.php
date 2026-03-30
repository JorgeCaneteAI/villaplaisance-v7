<?php
declare(strict_types=1);

/**
 * Seed 022 — Livret d'accueil complet FR/EN/ES pour BB et Villa
 */

require __DIR__ . '/../config.php';

echo "=== Seed 022 — Livret complet ===\n";

// Clear existing livret content
Database::query("DELETE FROM vp_livret");
echo "✓ Anciennes sections supprimées\n";

$sections = [

    // ═══════════════════════════════════════════
    // CHAMBRES D'HÔTES (BB)
    // ═══════════════════════════════════════════

    ['bb', 1, [
        'fr' => ['Bienvenue', "Bienvenue à Villa Plaisance ! Nous sommes ravis de vous accueillir dans notre maison provençale à Bédarrides, au cœur du triangle d'or Avignon–Orange–Châteauneuf-du-Pape.\n\nN'hésitez pas à nous solliciter pour quoi que ce soit : recommandations, itinéraires, réservations de restaurants. Nous sommes là pour rendre votre séjour agréable.\n\nBon séjour en Provence !"],
        'en' => ['Welcome', "Welcome to Villa Plaisance! We are delighted to have you in our Provençal home in Bédarrides, in the heart of the golden triangle: Avignon–Orange–Châteauneuf-du-Pape.\n\nPlease don't hesitate to ask us for anything: recommendations, itineraries, restaurant reservations. We're here to make your stay wonderful.\n\nEnjoy your time in Provence!"],
        'es' => ['Bienvenida', "¡Bienvenidos a Villa Plaisance! Estamos encantados de recibirles en nuestra casa provenzal en Bédarrides, en el corazón del triángulo de oro Aviñón–Orange–Châteauneuf-du-Pape.\n\nNo duden en pedirnos lo que necesiten: recomendaciones, itinerarios, reservas de restaurantes. Estamos aquí para que su estancia sea agradable.\n\n¡Feliz estancia en Provenza!"],
    ]],

    ['bb', 2, [
        'fr' => ['Votre chambre', "Chaque chambre dispose de la climatisation réversible (chaud/froid), du Wifi, d'une salle de bain privative et de linge de toilette.\n\nChambre Verte — Lit 160×200, vue jardin, rez-de-chaussée, TV.\nChambre Bleue — 2 lits 90×200 jumelables + clic-clac, bibliothèque 300 livres.\n\nMerci de ne pas déplacer les meubles et de laisser la chambre dans un état correct à votre départ."],
        'en' => ['Your room', "Each room has reversible air conditioning (heating/cooling), WiFi, a private bathroom and towels.\n\nGreen Room — Queen bed (160×200), garden view, ground floor, TV.\nBlue Room — 2 single beds (90×200, joinable) + sofa bed, 300-book library.\n\nPlease do not move furniture and leave the room tidy when you check out."],
        'es' => ['Su habitación', "Cada habitación dispone de aire acondicionado reversible (frío/calor), Wifi, baño privado y toallas.\n\nHabitación Verde — Cama 160×200, vista al jardín, planta baja, TV.\nHabitación Azul — 2 camas 90×200 unibles + sofá cama, biblioteca de 300 libros.\n\nPor favor, no muevan los muebles y dejen la habitación ordenada al salir."],
    ]],

    ['bb', 3, [
        'fr' => ['Petit-déjeuner', "Servi chaque matin de 8h30 à 10h00, en terrasse (selon la météo) ou sous la véranda.\n\nAu menu : confitures artisanales (figue, abricot, lavande), pain frais, viennoiseries, fruits de saison, fromages locaux, œufs, jus de fruits, café, thé.\n\nTout est préparé maison avec des produits locaux et de saison. Le menu change chaque jour.\n\nMerci de nous prévenir la veille si vous avez des allergies ou restrictions alimentaires."],
        'en' => ['Breakfast', "Served every morning from 8:30am to 10:00am, on the terrace (weather permitting) or in the veranda.\n\nOn the menu: artisanal jams (fig, apricot, lavender), fresh bread, pastries, seasonal fruit, local cheeses, eggs, fruit juice, coffee, tea.\n\nEverything is homemade with local, seasonal produce. The menu changes daily.\n\nPlease let us know the evening before if you have any allergies or dietary requirements."],
        'es' => ['Desayuno', "Se sirve cada mañana de 8:30 a 10:00, en la terraza (según el tiempo) o en la galería.\n\nEn el menú: mermeladas artesanales (higo, albaricoque, lavanda), pan fresco, bollería, fruta de temporada, quesos locales, huevos, zumo de frutas, café, té.\n\nTodo es casero con productos locales y de temporada. El menú cambia cada día.\n\nPor favor, avísennos la noche anterior si tienen alergias o restricciones alimentarias."],
    ]],

    ['bb', 4, [
        'fr' => ['Wifi', "Réseau : VillaPlaisance\nMot de passe : (affiché dans votre chambre)\n\nLe Wifi couvre toute la maison et le jardin. En cas de souci de connexion, n'hésitez pas à nous en parler."],
        'en' => ['WiFi', "Network: VillaPlaisance\nPassword: (displayed in your room)\n\nWiFi covers the entire house and garden. If you experience any connection issues, please let us know."],
        'es' => ['Wifi', "Red: VillaPlaisance\nContraseña: (indicada en su habitación)\n\nEl Wifi cubre toda la casa y el jardín. Si tienen algún problema de conexión, no duden en avisarnos."],
    ]],

    ['bb', 5, [
        'fr' => ['Piscine', "La piscine (12m × 6m) est partagée avec les autres hôtes.\n\nHoraires : 9h – 20h\nDouche obligatoire avant la baignade\nServiettes de piscine fournies (sur les transats)\nParasols et chaises longues à disposition\n\nSaison piscine : mai à octobre.\n\nPour votre sécurité, la piscine est clôturée. Merci de bien refermer le portillon après votre passage."],
        'en' => ['Swimming pool', "The pool (12m × 6m) is shared with other guests.\n\nHours: 9am – 8pm\nShower required before swimming\nPool towels provided (on the sun loungers)\nParasols and lounge chairs available\n\nPool season: May to October.\n\nFor safety, the pool is fenced. Please close the gate behind you."],
        'es' => ['Piscina', "La piscina (12m × 6m) es compartida con los demás huéspedes.\n\nHorario: 9:00 – 20:00\nDucha obligatoria antes del baño\nToallas de piscina proporcionadas (en las tumbonas)\nSombrillas y tumbonas a disposición\n\nTemporada de piscina: mayo a octubre.\n\nPor seguridad, la piscina está vallada. Por favor, cierren la puerta al pasar."],
    ]],

    ['bb', 6, [
        'fr' => ['Espaces extérieurs', "Le jardin de 1 500 m² est à votre disposition : oliviers centenaires, lavande, romarin, herbes aromatiques, potager d'été.\n\nVous trouverez aussi :\n— Une terrasse couverte de 40 m² orientée sud\n— Un barbecue (charbon fourni, merci de nettoyer après usage)\n— Un terrain de pétanque (boules dans le coffre à côté)\n— Une balançoire pour les enfants\n\nMerci de respecter le calme du jardin, surtout après 22h."],
        'en' => ['Outdoor spaces', "The 1,500 m² garden is at your disposal: century-old olive trees, lavender, rosemary, aromatic herbs, summer vegetable garden.\n\nYou will also find:\n— A 40 m² covered south-facing terrace\n— A barbecue (charcoal provided, please clean after use)\n— A pétanque court (boules in the chest nearby)\n— A swing for children\n\nPlease respect the quiet of the garden, especially after 10pm."],
        'es' => ['Espacios exteriores', "El jardín de 1.500 m² está a su disposición: olivos centenarios, lavanda, romero, hierbas aromáticas, huerto de verano.\n\nTambién encontrarán:\n— Una terraza cubierta de 40 m² orientada al sur\n— Una barbacoa (carbón proporcionado, limpiar después de usar)\n— Una cancha de petanca (bolas en el cofre al lado)\n— Un columpio para los niños\n\nPor favor, respeten la tranquilidad del jardín, especialmente después de las 22:00."],
    ]],

    ['bb', 7, [
        'fr' => ['Parking', "Un parking privé gratuit est disponible dans la propriété. Vous pouvez vous garer librement à votre arrivée.\n\nSi vous arrivez en train, la gare de Bédarrides est à 10 minutes à pied. Nous pouvons venir vous chercher sur demande."],
        'en' => ['Parking', "Free private parking is available on the property. You can park freely upon arrival.\n\nIf you arrive by train, Bédarrides station is a 10-minute walk. We can pick you up on request."],
        'es' => ['Aparcamiento', "Hay aparcamiento privado gratuito en la propiedad. Pueden aparcar libremente a su llegada.\n\nSi llegan en tren, la estación de Bédarrides está a 10 minutos a pie. Podemos recogerles si lo solicitan."],
    ]],

    ['bb', 8, [
        'fr' => ['Aux alentours', "Villa Plaisance est idéalement située dans le « triangle d'or » de la Provence :\n\n— Châteauneuf-du-Pape : 8 min (dégustations de vin)\n— Avignon : 15 min (Palais des Papes, Pont d'Avignon)\n— Orange : 18 min (Théâtre antique romain)\n— L'Isle-sur-la-Sorgue : 25 min (antiquaires, marché le dimanche)\n— Pont du Gard : 30 min (aqueduc romain, UNESCO)\n— Fontaine de Vaucluse : 30 min\n— Vaison-la-Romaine : 35 min\n— Gordes : 42 min (village perché)\n— Les Baux-de-Provence : 45 min (Carrières de Lumières)\n— Mont Ventoux : 45 min\n\nN'hésitez pas à nous demander nos bonnes adresses !"],
        'en' => ['Nearby', "Villa Plaisance is ideally located in Provence's \"golden triangle\":\n\n— Châteauneuf-du-Pape: 8 min (wine tasting)\n— Avignon: 15 min (Papal Palace, Pont d'Avignon)\n— Orange: 18 min (Roman Theatre)\n— L'Isle-sur-la-Sorgue: 25 min (antiques, Sunday market)\n— Pont du Gard: 30 min (Roman aqueduct, UNESCO)\n— Fontaine de Vaucluse: 30 min\n— Vaison-la-Romaine: 35 min\n— Gordes: 42 min (hilltop village)\n— Les Baux-de-Provence: 45 min (Carrières de Lumières)\n— Mont Ventoux: 45 min\n\nAsk us for our favourite local tips!"],
        'es' => ['Alrededores', "Villa Plaisance está idealmente situada en el \"triángulo de oro\" de la Provenza:\n\n— Châteauneuf-du-Pape: 8 min (catas de vino)\n— Aviñón: 15 min (Palacio de los Papas, Puente de Aviñón)\n— Orange: 18 min (Teatro Romano)\n— L'Isle-sur-la-Sorgue: 25 min (anticuarios, mercado dominical)\n— Pont du Gard: 30 min (acueducto romano, UNESCO)\n— Fontaine de Vaucluse: 30 min\n— Vaison-la-Romaine: 35 min\n— Gordes: 42 min (pueblo en la colina)\n— Les Baux-de-Provence: 45 min (Carrières de Lumières)\n— Mont Ventoux: 45 min\n\n¡Pídannos nuestras mejores direcciones!"],
    ]],

    ['bb', 9, [
        'fr' => ['Marchés provençaux', "Les marchés sont l'âme de la Provence. Voici ceux à ne pas manquer :\n\n— Lundi : Bédarrides (petit marché local, place du village)\n— Mardi : Vaison-la-Romaine\n— Mercredi : Sorgues\n— Jeudi : Orange, Villeneuve-lès-Avignon\n— Vendredi : Carpentras (le plus grand, avec les truffes en saison)\n— Samedi : Avignon (Les Halles, marché couvert toute l'année)\n— Dimanche : L'Isle-sur-la-Sorgue (brocante + alimentaire, incontournable)\n\nArrivez avant 10h pour profiter pleinement de l'ambiance."],
        'en' => ['Provençal markets', "Markets are the soul of Provence. Here are the ones not to miss:\n\n— Monday: Bédarrides (small local market, village square)\n— Tuesday: Vaison-la-Romaine\n— Wednesday: Sorgues\n— Thursday: Orange, Villeneuve-lès-Avignon\n— Friday: Carpentras (the largest, with truffles in season)\n— Saturday: Avignon (Les Halles, covered market year-round)\n— Sunday: L'Isle-sur-la-Sorgue (antiques + food market, a must!)\n\nArrive before 10am to enjoy the atmosphere at its best."],
        'es' => ['Mercados provenzales', "Los mercados son el alma de la Provenza. Estos son los imprescindibles:\n\n— Lunes: Bédarrides (pequeño mercado local, plaza del pueblo)\n— Martes: Vaison-la-Romaine\n— Miércoles: Sorgues\n— Jueves: Orange, Villeneuve-lès-Avignon\n— Viernes: Carpentras (el más grande, con trufas en temporada)\n— Sábado: Aviñón (Les Halles, mercado cubierto todo el año)\n— Domingo: L'Isle-sur-la-Sorgue (antigüedades + alimentación, ¡imprescindible!)\n\nLleguen antes de las 10:00 para disfrutar plenamente del ambiente."],
    ]],

    ['bb', 10, [
        'fr' => ['Restaurants que nous aimons', "Voici quelques adresses testées et approuvées :\n\n— Le Numéro 3 (Bédarrides) — Bistrot au bord de l'Ouvèze, cuisine locale\n— Impérial Bus Diner (Sorgues) — Burgers généreux, ambiance décontractée\n— La Mère Germaine (Châteauneuf-du-Pape) — Gastronomique, vue vignes\n— Le Bercail (Avignon) — Terrasse sur l'île de la Barthelasse\n— Christian Étienne (Avignon) — Étoilé Michelin, face au Palais\n\nPensez à réserver, surtout en saison. Nous pouvons vous aider."],
        'en' => ['Restaurants we love', "Here are some tried and tested addresses:\n\n— Le Numéro 3 (Bédarrides) — Bistro on the Ouvèze river, local cuisine\n— Impérial Bus Diner (Sorgues) — Generous burgers, relaxed atmosphere\n— La Mère Germaine (Châteauneuf-du-Pape) — Fine dining, vineyard views\n— Le Bercail (Avignon) — Terrace on Barthelasse island\n— Christian Étienne (Avignon) — Michelin-starred, facing the Palace\n\nRemember to book, especially in high season. We can help with reservations."],
        'es' => ['Restaurantes que nos gustan', "Estas son algunas direcciones probadas y aprobadas:\n\n— Le Numéro 3 (Bédarrides) — Bistró a orillas del Ouvèze, cocina local\n— Impérial Bus Diner (Sorgues) — Hamburguesas generosas, ambiente relajado\n— La Mère Germaine (Châteauneuf-du-Pape) — Gastronómico, vistas a viñedos\n— Le Bercail (Aviñón) — Terraza en la isla de la Barthelasse\n— Christian Étienne (Aviñón) — Estrella Michelin, frente al Palacio\n\nRecuerden reservar, sobre todo en temporada alta. Podemos ayudarles."],
    ]],

    ['bb', 11, [
        'fr' => ['Numéros utiles', "Urgences : 112 (européen) / 15 (SAMU) / 18 (pompiers)\nPolice : 17\nPharmacies de garde : 3237\n\nHôpital le plus proche : Centre Hospitalier d'Avignon — 20 min\nAdresse : 305 Rue Raoul Follereau, 84000 Avignon\n\nPharmacie Bédarrides : Place de la Mairie (horaires affichés en vitrine)\nMédecin Bédarrides : Cabinet médical, rue de la République\n\nVos hôtes : contactez-nous à tout moment en cas de besoin."],
        'en' => ['Useful numbers', "Emergencies: 112 (European) / 15 (ambulance) / 18 (fire)\nPolice: 17\nOn-call pharmacies: 3237\n\nNearest hospital: Centre Hospitalier d'Avignon — 20 min\nAddress: 305 Rue Raoul Follereau, 84000 Avignon\n\nBédarrides pharmacy: Place de la Mairie (hours posted on window)\nBédarrides doctor: Medical practice, rue de la République\n\nYour hosts: contact us at any time if you need anything."],
        'es' => ['Números útiles', "Urgencias: 112 (europeo) / 15 (ambulancia) / 18 (bomberos)\nPolicía: 17\nFarmacias de guardia: 3237\n\nHospital más cercano: Centre Hospitalier d'Avignon — 20 min\nDirección: 305 Rue Raoul Follereau, 84000 Avignon\n\nFarmacia Bédarrides: Place de la Mairie (horarios en el escaparate)\nMédico Bédarrides: Consultorio médico, rue de la République\n\nSus anfitriones: contáctennos en cualquier momento si necesitan algo."],
    ]],

    ['bb', 12, [
        'fr' => ['Départ', "Le jour du départ, merci de :\n— Laisser les clés dans la chambre\n— Fermer les volets et fenêtres\n— Éteindre la climatisation\n\nPas besoin de faire le ménage, mais nous apprécions une chambre laissée en ordre.\n\nN'oubliez pas de nous laisser un avis sur Airbnb ou Booking — cela nous aide énormément !\n\nMerci pour votre visite et à bientôt en Provence."],
        'en' => ['Checkout', "On departure day, please:\n— Leave the keys in the room\n— Close the shutters and windows\n— Turn off the air conditioning\n\nNo need to clean, but we appreciate a tidy room.\n\nDon't forget to leave us a review on Airbnb or Booking — it helps us enormously!\n\nThank you for your visit and see you again in Provence."],
        'es' => ['Salida', "El día de la salida, por favor:\n— Dejen las llaves en la habitación\n— Cierren las persianas y ventanas\n— Apaguen el aire acondicionado\n\nNo es necesario limpiar, pero agradecemos una habitación ordenada.\n\n¡No olviden dejarnos una reseña en Airbnb o Booking — nos ayuda mucho!\n\nGracias por su visita y hasta pronto en Provenza."],
    ]],

    // ═══════════════════════════════════════════
    // VILLA ENTIÈRE
    // ═══════════════════════════════════════════

    ['villa', 1, [
        'fr' => ['Bienvenue', "Bienvenue à Villa Plaisance ! Toute la maison est à vous pour la durée de votre séjour.\n\nVous disposez de 4 chambres, d'une piscine privée de 12m × 6m, d'un jardin de 1 500 m², d'une cuisine entièrement équipée et de plusieurs terrasses.\n\nLa villa accueille jusqu'à 10 personnes. Le séjour est autonome : vous avez les clés, l'espace et la liberté.\n\nCe livret contient tout ce qu'il faut savoir. Bonne découverte de la Provence !"],
        'en' => ['Welcome', "Welcome to Villa Plaisance! The entire house is yours for the duration of your stay.\n\nYou have 4 bedrooms, a private 12m × 6m pool, a 1,500 m² garden, a fully equipped kitchen and several terraces.\n\nThe villa accommodates up to 10 guests. Your stay is self-catering: you have the keys, the space and the freedom.\n\nThis booklet contains everything you need to know. Enjoy discovering Provence!"],
        'es' => ['Bienvenida', "¡Bienvenidos a Villa Plaisance! Toda la casa es suya durante su estancia.\n\nDisponen de 4 habitaciones, una piscina privada de 12m × 6m, un jardín de 1.500 m², una cocina totalmente equipada y varias terrazas.\n\nLa villa acoge hasta 10 personas. La estancia es autónoma: tienen las llaves, el espacio y la libertad.\n\nEsta guía contiene todo lo que necesitan saber. ¡Disfruten de la Provenza!"],
    ]],

    ['villa', 2, [
        'fr' => ['Les chambres', "La villa dispose de 4 chambres, toutes climatisées :\n\n1. Chambre Verte — Lit 160×200, vue jardin, rez-de-chaussée, TV\n2. Chambre Bleue — 2 lits 90×200 jumelables + clic-clac, bibliothèque 300 livres\n3. Chambre Arche — Lit 140×180, arche bleue nuit peinte, bibliothèques sol-plafond, accès jardin\n4. Chambre 70 — Lit double, mobilier vintage années 70, porte-fenêtre sur jardin\n\nDraps et serviettes fournis, changés une fois par semaine.\n\nMerci de ne pas déplacer les meubles entre les chambres."],
        'en' => ['Bedrooms', "The villa has 4 bedrooms, all air-conditioned:\n\n1. Green Room — Queen bed (160×200), garden view, ground floor, TV\n2. Blue Room — 2 single beds (90×200, joinable) + sofa bed, 300-book library\n3. Arch Room — Double bed (140×180), blue night arch, floor-to-ceiling bookshelves, garden access\n4. Room 70 — Double bed, vintage 1970s furniture, French doors to garden\n\nSheets and towels provided, changed once a week.\n\nPlease do not move furniture between rooms."],
        'es' => ['Habitaciones', "La villa dispone de 4 habitaciones, todas con aire acondicionado:\n\n1. Habitación Verde — Cama 160×200, vista al jardín, planta baja, TV\n2. Habitación Azul — 2 camas 90×200 unibles + sofá cama, biblioteca de 300 libros\n3. Habitación Arco — Cama 140×180, arco azul noche, estanterías de suelo a techo, acceso al jardín\n4. Habitación 70 — Cama doble, mobiliario vintage años 70, puerta ventana al jardín\n\nSábanas y toallas proporcionadas, se cambian una vez por semana.\n\nPor favor, no muevan los muebles entre habitaciones."],
    ]],

    ['villa', 3, [
        'fr' => ['Cuisine', "La cuisine est entièrement équipée pour 10 personnes :\n\nFour, plaques de cuisson, micro-ondes, lave-vaisselle, réfrigérateur-congélateur, cafetière, bouilloire, grille-pain.\n\nVaisselle, couverts, verres, casseroles, poêles, plats : tout est en place.\n\nÉpices de base, huile d'olive, sel, poivre, vinaigre sont à votre disposition.\n\nMerci de :\n— Lancer le lave-vaisselle chaque soir\n— Laisser la cuisine propre à votre départ\n— Jeter les denrées périssables non utilisées\n\nLe supermarché le plus proche est à Sorgues (5 min en voiture)."],
        'en' => ['Kitchen', "The kitchen is fully equipped for 10 people:\n\nOven, hob, microwave, dishwasher, fridge-freezer, coffee maker, kettle, toaster.\n\nPlates, cutlery, glasses, pots, pans, baking dishes: everything is in place.\n\nBasic spices, olive oil, salt, pepper, vinegar are provided.\n\nPlease:\n— Run the dishwasher every evening\n— Leave the kitchen clean on departure\n— Discard unused perishable food\n\nThe nearest supermarket is in Sorgues (5 min by car)."],
        'es' => ['Cocina', "La cocina está totalmente equipada para 10 personas:\n\nHorno, placa de cocción, microondas, lavavajillas, frigorífico-congelador, cafetera, hervidor, tostadora.\n\nPlatos, cubiertos, vasos, ollas, sartenes, fuentes: todo está en su sitio.\n\nEspecias básicas, aceite de oliva, sal, pimienta, vinagre están a su disposición.\n\nPor favor:\n— Pongan el lavavajillas cada noche\n— Dejen la cocina limpia al salir\n— Tiren los alimentos perecederos no utilizados\n\nEl supermercado más cercano está en Sorgues (5 min en coche)."],
    ]],

    ['villa', 4, [
        'fr' => ['Wifi', "Réseau : VillaPlaisance\nMot de passe : (affiché dans le salon)\n\nLe Wifi couvre toute la maison et le jardin. En cas de souci de connexion, redémarrez la box (salon, meuble TV) en la débranchant 30 secondes."],
        'en' => ['WiFi', "Network: VillaPlaisance\nPassword: (displayed in the living room)\n\nWiFi covers the entire house and garden. If you experience connection issues, restart the router (living room, TV unit) by unplugging it for 30 seconds."],
        'es' => ['Wifi', "Red: VillaPlaisance\nContraseña: (indicada en el salón)\n\nEl Wifi cubre toda la casa y el jardín. Si tienen problemas de conexión, reinicien el router (salón, mueble de TV) desenchufándolo 30 segundos."],
    ]],

    ['villa', 5, [
        'fr' => ['Piscine', "La piscine (12m × 6m) est privée et réservée exclusivement à votre groupe.\n\nHoraires recommandés : 9h – 21h\nServiettes de piscine fournies (dans le coffre à côté de la piscine)\nDouche extérieure disponible\nTransats, parasols et mobilier de jardin autour du bassin\n\nIMPORTANT :\n— La piscine est clôturée. Merci de toujours refermer le portillon.\n— Les enfants doivent être surveillés en permanence par un adulte.\n— Pas de plongeons (profondeur maximale : 1m80).\n— Pas de verre au bord de la piscine (utilisez les gobelets en plastique fournis)."],
        'en' => ['Swimming pool', "The pool (12m × 6m) is private, reserved exclusively for your group.\n\nRecommended hours: 9am – 9pm\nPool towels provided (in the chest next to the pool)\nOutdoor shower available\nSun loungers, parasols and garden furniture around the pool\n\nIMPORTANT:\n— The pool is fenced. Please always close the gate.\n— Children must be supervised by an adult at all times.\n— No diving (maximum depth: 1.80m).\n— No glass by the pool (use the plastic cups provided)."],
        'es' => ['Piscina', "La piscina (12m × 6m) es privada, reservada exclusivamente para su grupo.\n\nHorario recomendado: 9:00 – 21:00\nToallas de piscina proporcionadas (en el cofre junto a la piscina)\nDucha exterior disponible\nTumbonas, sombrillas y mobiliario de jardín alrededor\n\nIMPORTANTE:\n— La piscina está vallada. Por favor, cierren siempre la puerta.\n— Los niños deben estar vigilados por un adulto en todo momento.\n— No tirarse de cabeza (profundidad máxima: 1,80m).\n— No llevar vasos de cristal a la piscina (usen los vasos de plástico proporcionados)."],
    ]],

    ['villa', 6, [
        'fr' => ['Espaces extérieurs', "Le jardin de 1 500 m² est entièrement à vous :\n\n— Terrasse couverte de 40 m² (orientée sud, idéale pour les repas)\n— Barbecue charbon (charbon fourni dans le coffre, merci de nettoyer la grille après usage)\n— Terrain de pétanque (boules dans le coffre en bois)\n— Balançoire pour les enfants\n— Oliviers centenaires, lavande, romarin, potager\n— Vue sur les vignes de Châteauneuf-du-Pape\n\nMerci de ramasser les jouets et objets chaque soir pour éviter que le vent ne les emporte."],
        'en' => ['Outdoor spaces', "The 1,500 m² garden is entirely yours:\n\n— 40 m² covered terrace (south-facing, ideal for meals)\n— Charcoal barbecue (charcoal provided in the chest, please clean the grill after use)\n— Pétanque court (boules in the wooden chest)\n— Swing for children\n— Century-old olive trees, lavender, rosemary, vegetable garden\n— Views over Châteauneuf-du-Pape vineyards\n\nPlease collect toys and belongings each evening to prevent the wind carrying them away."],
        'es' => ['Espacios exteriores', "El jardín de 1.500 m² es enteramente suyo:\n\n— Terraza cubierta de 40 m² (orientada al sur, ideal para las comidas)\n— Barbacoa de carbón (carbón proporcionado en el cofre, limpien la parrilla después de usar)\n— Cancha de petanca (bolas en el cofre de madera)\n— Columpio para los niños\n— Olivos centenarios, lavanda, romero, huerto\n— Vistas a los viñedos de Châteauneuf-du-Pape\n\nPor favor, recojan los juguetes y objetos cada noche para evitar que el viento se los lleve."],
    ]],

    ['villa', 7, [
        'fr' => ['Poubelles et recyclage', "Deux types de poubelles :\n— Bac jaune : recyclables (plastique, carton, métal, papier)\n— Bac noir : ordures ménagères\n\nLe point de collecte est à 50 mètres de la maison (tourner à droite en sortant du portail).\n\nJours de collecte :\n— Bac jaune : mercredi\n— Bac noir : lundi et vendredi\n\nMerci de sortir les poubelles la veille au soir et de les rentrer après le passage."],
        'en' => ['Waste and recycling', "Two types of bins:\n— Yellow bin: recyclables (plastic, cardboard, metal, paper)\n— Black bin: general household waste\n\nThe collection point is 50 metres from the house (turn right when leaving the gate).\n\nCollection days:\n— Yellow bin: Wednesday\n— Black bin: Monday and Friday\n\nPlease take the bins out the evening before and bring them back after collection."],
        'es' => ['Basura y reciclaje', "Dos tipos de contenedores:\n— Contenedor amarillo: reciclables (plástico, cartón, metal, papel)\n— Contenedor negro: basura doméstica general\n\nEl punto de recogida está a 50 metros de la casa (giren a la derecha al salir del portón).\n\nDías de recogida:\n— Contenedor amarillo: miércoles\n— Contenedor negro: lunes y viernes\n\nPor favor, saquen los contenedores la noche anterior y guárdenlos después de la recogida."],
    ]],

    ['villa', 8, [
        'fr' => ['Parking', "Un parking privé gratuit est disponible dans la propriété. Vous pouvez garer jusqu'à 3 véhicules dans l'enceinte.\n\nSi vous venez en train : gare TGV d'Avignon à 15 min en voiture, ou gare de Bédarrides à 10 min à pied."],
        'en' => ['Parking', "Free private parking is available on the property. You can park up to 3 vehicles within the grounds.\n\nIf arriving by train: Avignon TGV station is 15 min by car, or Bédarrides station is a 10-minute walk."],
        'es' => ['Aparcamiento', "Hay aparcamiento privado gratuito en la propiedad. Pueden aparcar hasta 3 vehículos dentro del recinto.\n\nSi llegan en tren: la estación TGV de Aviñón está a 15 min en coche, o la estación de Bédarrides a 10 min a pie."],
    ]],

    ['villa', 9, [
        'fr' => ['Aux alentours', "Villa Plaisance est idéalement située dans le « triangle d'or » de la Provence :\n\n— Châteauneuf-du-Pape : 8 min (dégustations de vin)\n— Avignon : 15 min (Palais des Papes, Pont d'Avignon)\n— Orange : 18 min (Théâtre antique romain)\n— L'Isle-sur-la-Sorgue : 25 min (antiquaires, marché le dimanche)\n— Pont du Gard : 30 min (aqueduc romain, UNESCO)\n— Fontaine de Vaucluse : 30 min\n— Vaison-la-Romaine : 35 min\n— Gordes : 42 min (village perché)\n— Les Baux-de-Provence : 45 min (Carrières de Lumières)\n— Mont Ventoux : 45 min\n— Roussillon : 40 min (sentier des ocres)\n— Parc Spirou : 15 min (parc d'attractions familial à Monteux)\n\nN'hésitez pas à nous demander nos itinéraires préférés !"],
        'en' => ['Nearby', "Villa Plaisance is ideally located in Provence's \"golden triangle\":\n\n— Châteauneuf-du-Pape: 8 min (wine tasting)\n— Avignon: 15 min (Papal Palace, Pont d'Avignon)\n— Orange: 18 min (Roman Theatre)\n— L'Isle-sur-la-Sorgue: 25 min (antiques, Sunday market)\n— Pont du Gard: 30 min (Roman aqueduct, UNESCO)\n— Fontaine de Vaucluse: 30 min\n— Vaison-la-Romaine: 35 min\n— Gordes: 42 min (hilltop village)\n— Les Baux-de-Provence: 45 min (Carrières de Lumières)\n— Mont Ventoux: 45 min\n— Roussillon: 40 min (ochre trail)\n— Parc Spirou: 15 min (family theme park in Monteux)\n\nAsk us for our favourite itineraries!"],
        'es' => ['Alrededores', "Villa Plaisance está idealmente situada en el \"triángulo de oro\" de la Provenza:\n\n— Châteauneuf-du-Pape: 8 min (catas de vino)\n— Aviñón: 15 min (Palacio de los Papas, Puente de Aviñón)\n— Orange: 18 min (Teatro Romano)\n— L'Isle-sur-la-Sorgue: 25 min (anticuarios, mercado dominical)\n— Pont du Gard: 30 min (acueducto romano, UNESCO)\n— Fontaine de Vaucluse: 30 min\n— Vaison-la-Romaine: 35 min\n— Gordes: 42 min (pueblo en la colina)\n— Les Baux-de-Provence: 45 min (Carrières de Lumières)\n— Mont Ventoux: 45 min\n— Roussillon: 40 min (sendero de los ocres)\n— Parc Spirou: 15 min (parque de atracciones familiar en Monteux)\n\n¡Pídannos nuestros itinerarios favoritos!"],
    ]],

    ['villa', 10, [
        'fr' => ['Marchés provençaux', "Les marchés sont l'âme de la Provence. Voici ceux à ne pas manquer :\n\n— Lundi : Bédarrides (petit marché local, place du village)\n— Mardi : Vaison-la-Romaine\n— Mercredi : Sorgues\n— Jeudi : Orange, Villeneuve-lès-Avignon\n— Vendredi : Carpentras (le plus grand, avec les truffes en saison)\n— Samedi : Avignon (Les Halles, marché couvert toute l'année)\n— Dimanche : L'Isle-sur-la-Sorgue (brocante + alimentaire, incontournable)\n\nAstuce : arrivez avant 10h et apportez vos sacs !"],
        'en' => ['Provençal markets', "Markets are the soul of Provence. Here are the must-visits:\n\n— Monday: Bédarrides (small local market, village square)\n— Tuesday: Vaison-la-Romaine\n— Wednesday: Sorgues\n— Thursday: Orange, Villeneuve-lès-Avignon\n— Friday: Carpentras (the largest, with truffles in season)\n— Saturday: Avignon (Les Halles, covered market year-round)\n— Sunday: L'Isle-sur-la-Sorgue (antiques + food market, a must!)\n\nTip: arrive before 10am and bring your own bags!"],
        'es' => ['Mercados provenzales', "Los mercados son el alma de la Provenza. Estos son los imprescindibles:\n\n— Lunes: Bédarrides (pequeño mercado local, plaza del pueblo)\n— Martes: Vaison-la-Romaine\n— Miércoles: Sorgues\n— Jueves: Orange, Villeneuve-lès-Avignon\n— Viernes: Carpentras (el más grande, con trufas en temporada)\n— Sábado: Aviñón (Les Halles, mercado cubierto todo el año)\n— Domingo: L'Isle-sur-la-Sorgue (antigüedades + alimentación, ¡imprescindible!)\n\nConsejo: lleguen antes de las 10:00 y lleven sus propias bolsas."],
    ]],

    ['villa', 11, [
        'fr' => ['Restaurants que nous aimons', "Voici quelques adresses testées et approuvées :\n\n— Le Numéro 3 (Bédarrides) — Bistrot au bord de l'Ouvèze\n— Impérial Bus Diner (Sorgues) — Burgers généreux, ambiance décontractée\n— La Mère Germaine (Châteauneuf-du-Pape) — Gastronomique, vue vignes\n— Le Bercail (Avignon) — Terrasse sur l'île de la Barthelasse\n— Christian Étienne (Avignon) — Étoilé Michelin, face au Palais\n\nPour les pizzas à emporter : [à compléter]\nPensez à réserver, surtout en juillet-août."],
        'en' => ['Restaurants we love', "Here are some tried and tested addresses:\n\n— Le Numéro 3 (Bédarrides) — Bistro on the Ouvèze river\n— Impérial Bus Diner (Sorgues) — Generous burgers, relaxed vibe\n— La Mère Germaine (Châteauneuf-du-Pape) — Fine dining, vineyard views\n— Le Bercail (Avignon) — Terrace on Barthelasse island\n— Christian Étienne (Avignon) — Michelin-starred, facing the Palace\n\nFor takeaway pizza: [to be added]\nRemember to book, especially in July-August."],
        'es' => ['Restaurantes que nos gustan', "Estas son algunas direcciones probadas y aprobadas:\n\n— Le Numéro 3 (Bédarrides) — Bistró a orillas del Ouvèze\n— Impérial Bus Diner (Sorgues) — Hamburguesas generosas, ambiente relajado\n— La Mère Germaine (Châteauneuf-du-Pape) — Gastronómico, vistas a viñedos\n— Le Bercail (Aviñón) — Terraza en la isla de la Barthelasse\n— Christian Étienne (Aviñón) — Estrella Michelin, frente al Palacio\n\nPara pizza para llevar: [por añadir]\nRecuerden reservar, especialmente en julio-agosto."],
    ]],

    ['villa', 12, [
        'fr' => ['Numéros utiles', "Urgences : 112 (européen) / 15 (SAMU) / 18 (pompiers)\nPolice : 17\nPharmacies de garde : 3237\n\nHôpital le plus proche : Centre Hospitalier d'Avignon — 20 min\nAdresse : 305 Rue Raoul Follereau, 84000 Avignon\n\nPharmacie Bédarrides : Place de la Mairie\nMédecin Bédarrides : Cabinet médical, rue de la République\nVétérinaire (si vous voyagez avec un animal) : Clinique de Sorgues — 10 min\n\nVos hôtes : joignables par téléphone ou via le formulaire en bas de cette page."],
        'en' => ['Useful numbers', "Emergencies: 112 (European) / 15 (ambulance) / 18 (fire)\nPolice: 17\nOn-call pharmacies: 3237\n\nNearest hospital: Centre Hospitalier d'Avignon — 20 min\nAddress: 305 Rue Raoul Follereau, 84000 Avignon\n\nBédarrides pharmacy: Place de la Mairie\nBédarrides doctor: Medical practice, rue de la République\nVet (if travelling with a pet): Sorgues clinic — 10 min\n\nYour hosts: reachable by phone or via the form at the bottom of this page."],
        'es' => ['Números útiles', "Urgencias: 112 (europeo) / 15 (ambulancia) / 18 (bomberos)\nPolicía: 17\nFarmacias de guardia: 3237\n\nHospital más cercano: Centre Hospitalier d'Avignon — 20 min\nDirección: 305 Rue Raoul Follereau, 84000 Avignon\n\nFarmacia Bédarrides: Place de la Mairie\nMédico Bédarrides: Consultorio médico, rue de la République\nVeterinario (si viajan con mascota): Clínica de Sorgues — 10 min\n\nSus anfitriones: contactables por teléfono o mediante el formulario al final de esta página."],
    ]],

    ['villa', 13, [
        'fr' => ['Départ', "Le départ se fait le samedi avant 10h.\n\nMerci de :\n— Lancer un dernier cycle de lave-vaisselle\n— Vider le réfrigérateur (poubelle noire pour les déchets)\n— Rassembler les draps et serviettes usagés au pied de chaque lit\n— Fermer toutes les fenêtres et volets\n— Éteindre la climatisation dans chaque pièce\n— Laisser les clés sur la table de la cuisine\n— Refermer le portail en partant\n\nUn état des lieux sera fait après votre départ.\n\nMerci pour votre séjour et n'oubliez pas de nous laisser un avis ! À bientôt en Provence."],
        'en' => ['Checkout', "Checkout is on Saturday before 10am.\n\nPlease:\n— Run a final dishwasher cycle\n— Empty the fridge (black bin for waste)\n— Gather used sheets and towels at the foot of each bed\n— Close all windows and shutters\n— Turn off air conditioning in every room\n— Leave the keys on the kitchen table\n— Close the gate when leaving\n\nAn inventory check will be carried out after your departure.\n\nThank you for your stay and don't forget to leave us a review! See you again in Provence."],
        'es' => ['Salida', "La salida es el sábado antes de las 10:00.\n\nPor favor:\n— Pongan un último ciclo de lavavajillas\n— Vacíen el frigorífico (contenedor negro para los desechos)\n— Junten las sábanas y toallas usadas al pie de cada cama\n— Cierren todas las ventanas y persianas\n— Apaguen el aire acondicionado en cada habitación\n— Dejen las llaves en la mesa de la cocina\n— Cierren el portón al salir\n\nSe realizará un inventario después de su partida.\n\nGracias por su estancia y ¡no olviden dejarnos una reseña! Hasta pronto en Provenza."],
    ]],
];

$count = 0;
foreach ($sections as [$type, $position, $langs]) {
    foreach ($langs as $lang => [$title, $content]) {
        Database::insert('vp_livret', [
            'type' => $type,
            'section_title' => $title,
            'content' => $content,
            'position' => $position,
            'active' => 1,
            'lang' => $lang,
        ]);
        $count++;
    }
}

echo "✓ {$count} sections insérées\n";
echo "=== Seed 022 terminé ===\n";
