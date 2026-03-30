<?php
declare(strict_types=1);

/**
 * Fill all EN and ES translations
 * Usage: php seeds/016_fill_translations.php
 */

define('ROOT', dirname(__DIR__));
require ROOT . '/config.php';

echo "=== Fill EN/ES Translations ===\n\n";

// ─── HELPER ───
function updateSection(string $pageSlug, int $position, string $lang, array $content, string $title = ''): void
{
    $s = Database::fetchOne(
        "SELECT id, content FROM vp_sections WHERE page_slug = ? AND lang = ? AND position = ?",
        [$pageSlug, $lang, $position]
    );
    if (!$s) { echo "  SKIP section {$pageSlug}:{$position}:{$lang} (not found)\n"; return; }
    // Merge with existing content (preserve shared fields)
    $existing = json_decode($s['content'] ?? '{}', true) ?: [];
    $merged = array_merge($existing, $content);
    Database::update('vp_sections', [
        'content' => json_encode($merged, JSON_UNESCAPED_UNICODE),
        'title' => $title ?: ($s['title'] ?? ''),
    ], 'id = ?', [$s['id']]);
}

function updateFaq(string $pageSlug, int $position, string $lang, string $question, string $answer): void
{
    $f = Database::fetchOne(
        "SELECT id FROM vp_faq WHERE page_slug = ? AND lang = ? AND position = ?",
        [$pageSlug, $lang, $position]
    );
    if (!$f) return;
    Database::update('vp_faq', ['question' => $question, 'answer' => $answer], 'id = ?', [$f['id']]);
}

function updateStat(int $position, string $lang, string $label, string $sublabel): void
{
    $s = Database::fetchOne("SELECT id FROM vp_stats WHERE lang = ? AND position = ?", [$lang, $position]);
    if (!$s) return;
    Database::update('vp_stats', ['label' => $label, 'sublabel' => $sublabel], 'id = ?', [$s['id']]);
}

function updateProx(int $position, string $lang, string $name, string $distance, string $description): void
{
    $p = Database::fetchOne("SELECT id FROM vp_proximites WHERE lang = ? AND position = ?", [$lang, $position]);
    if (!$p) return;
    Database::update('vp_proximites', ['name' => $name, 'distance' => $distance, 'description' => $description], 'id = ?', [$p['id']]);
}

function updatePiece(string $offer, int $position, string $lang, array $fields): void
{
    $p = Database::fetchOne(
        "SELECT id FROM vp_pieces WHERE offer = ? AND position = ? AND lang = ?",
        [$offer, $position, $lang]
    );
    if (!$p) return;
    Database::update('vp_pieces', $fields, 'id = ?', [$p['id']]);
}

function updateAmenity(string $category, int $position, string $lang, string $name, string $desc = ''): void
{
    $a = Database::fetchOne(
        "SELECT id FROM vp_amenities WHERE category = ? AND position = ? AND lang = ?",
        [$category, $position, $lang]
    );
    if (!$a) return;
    Database::update('vp_amenities', ['name' => $name, 'description' => $desc], 'id = ?', [$a['id']]);
}

// ═══════════════════════════════════════════
//  SECTIONS — ENGLISH
// ═══════════════════════════════════════════
echo "--- Sections EN ---\n";

// Accueil
updateSection('accueil', 1, 'en', [
    'title' => 'Villa Plaisance — B&B & Private Villa in Provence',
    'subtitle' => 'Bédarrides, in the heart of the Golden Triangle · 9.4/10 Booking · Superhost Airbnb',
    'buttons' => [['text' => 'Check availability', 'url' => '/contact', 'style' => 'primary']],
], 'Hero Home');

updateSection('accueil', 2, 'en', [
    'heading' => 'One house, two ways to stay',
    'text' => 'Nestled in the heart of Provence\'s Golden Triangle, Villa Plaisance is a charming house in Bédarrides — 15 min from Avignon, 8 min from Châteauneuf-du-Pape, 18 min from Orange. From September to June, we welcome guests in B&B rooms with homemade breakfast and shared pool. In July and August, the entire villa is yours exclusively: four bedrooms, a private 12×6 m pool, a garden beneath the olive trees. The place is peaceful. The village is lively. The countryside is on foot, the TGV 15 minutes away.',
], 'Identity');

updateSection('accueil', 4, 'en', [
    'heading' => 'Bed & Breakfast — September to June',
    'text' => 'You stay in our home. Two connecting, air-conditioned rooms with private bathroom are exclusively yours: the Green Room and the Blue Room. Homemade breakfast, shared pool, and our recommendations for the area. The charm of Provence without the hassle of planning.',
], 'B&B Offer');

updateSection('accueil', 5, 'en', [
    'heading' => 'The Villa exclusively — July & August',
    'text' => 'You have the villa and grounds all to yourselves. 4 bedrooms, 2 bathrooms, fully equipped kitchen, shaded terrace and private 12×6 m pool. Ideal for a family holiday or a group of friends up to 10 people.',
], 'Villa Offer');

updateSection('accueil', 6, 'en', ['heading' => 'In the heart of the Golden Triangle'], 'Golden Triangle');
updateSection('accueil', 7, 'en', ['heading' => 'What our guests say'], 'Guest Reviews');
updateSection('accueil', 8, 'en', ['heading' => 'Journal'], 'Latest articles');
updateSection('accueil', 9, 'en', ['heading' => 'Frequently asked questions'], 'FAQ Home');
updateSection('accueil', 10, 'en', [
    'heading' => 'Your stay in Provence starts here',
    'text' => 'Choose the option that suits you and book in a few clicks. Direct booking = best rate guaranteed.',
    'buttons' => [['text' => 'Check availability', 'url' => '/contact', 'style' => 'primary']],
], 'CTA Contact');

// Chambres d'hôtes
updateSection('chambres-d-hotes', 1, 'en', [
    'title' => 'Bed & Breakfast — Your Provençal retreat',
    'subtitle' => 'Two private rooms with breakfast · September to June · Rated 9.4/10',
    'buttons' => [['text' => 'Book your stay', 'url' => '/contact', 'style' => 'primary']],
], 'Hero Rooms');

updateSection('chambres-d-hotes', 2, 'en', [
    'heading' => 'B&B in Bédarrides',
    'text' => 'At Villa Plaisance, we welcome guests from September to June in two charming rooms designed for comfort and tranquillity. Each room has its own private bathroom, air conditioning, and Wi-Fi. Homemade breakfast is served every morning on the terrace or in the veranda. The shared pool is available from May to October.',
], 'Intro Rooms');

updateSection('chambres-d-hotes', 4, 'en', [
    'heading' => 'Homemade breakfast included',
    'text' => 'Every morning from 7:30 to 10:00, on the terrace or in the veranda depending on the season. Artisanal jams (figs, apricots, lavender), fresh bread, pastries, seasonal fruit, cheeses, eggs from the garden, juice, coffee, tea.',
], 'Breakfast');

updateSection('chambres-d-hotes', 5, 'en', ['heading' => 'Amenities & services included'], 'Room amenities');
updateSection('chambres-d-hotes', 6, 'en', ['heading' => 'Practical information'], 'Practical info rooms');
updateSection('chambres-d-hotes', 7, 'en', ['heading' => 'Frequently asked questions — B&B'], 'FAQ Rooms');
updateSection('chambres-d-hotes', 8, 'en', [
    'heading' => 'Book your B&B room',
    'text' => 'September to June · Breakfast included · Direct booking = best rate guaranteed.',
    'buttons' => [['text' => 'Check availability', 'url' => '/contact', 'style' => 'primary']],
], 'CTA Rooms');

// Contact
updateSection('contact', 1, 'en', [
    'title' => 'Let\'s talk about your stay',
    'subtitle' => 'Reply within 24 hours · Direct booking = best rate guaranteed',
], 'Hero Contact');

// Espaces extérieurs
updateSection('espaces-exterieurs', 1, 'en', [
    'title' => 'Garden, Pool & Terraces — Outdoor charm',
    'subtitle' => '1,500 m² of greenery, fenced pool, shaded terrace and vineyard views',
], 'Hero Outdoors');

updateSection('espaces-exterieurs', 2, 'en', [
    'heading' => 'Outside, you\'re still at home',
    'text' => 'Villa Plaisance\'s garden is a natural extension of the house. 1,500 m² of greenery, century-old olive trees, lavender, rosemary, a vegetable garden in summer. The fenced pool overlooks the vineyards. The shaded terrace is where everything happens: breakfast, aperitifs, dinner under the stars.',
], 'Intro Outdoors');

updateSection('espaces-exterieurs', 3, 'en', [
    'heading' => '12×6 m pool — fenced and secure',
    'text' => 'The pool measures 12 by 6 metres, fully fenced for children\'s safety. Sun loungers, parasols, outdoor shower and garden furniture around the pool. Open from May to October.',
], 'Pool');

updateSection('espaces-exterieurs', 4, 'en', [
    'heading' => 'Shaded terraces facing the vineyards',
    'text' => 'A 40 m² covered terrace facing south, perfect for outdoor dining, sunset aperitifs and quiet mornings with coffee. Direct view of the vineyards of Châteauneuf-du-Pape.',
], 'Terraces');

updateSection('espaces-exterieurs', 5, 'en', [
    'heading' => 'Provençal garden — olive trees, lavender and herbs',
    'text' => 'A landscaped garden with century-old olive trees, lavender, old roses and aromatic herbs. BBQ area with charcoal grill, pétanque court, swing for children.',
], 'Garden');

updateSection('espaces-exterieurs', 6, 'en', ['heading' => 'Outdoor amenities'], 'Outdoor amenities');
updateSection('espaces-exterieurs', 8, 'en', [
    'heading' => 'Enjoy the outdoors in Provence',
    'text' => 'B&B (Sept–June) or private villa (Jul–Aug) — the outdoor spaces are always included. Direct booking = best rate guaranteed.',
    'buttons' => [['text' => 'Check availability', 'url' => '/contact', 'style' => 'primary']],
], 'CTA Outdoors');

// Journal
updateSection('journal', 1, 'en', [
    'title' => 'The Villa Plaisance Journal',
    'subtitle' => 'Stories, tips and glimpses of Provence',
], 'Hero Journal');

// Villa
updateSection('location-villa-provence', 1, 'en', [
    'title' => 'The Entire Villa — All yours',
    'subtitle' => '4 bedrooms · Private pool 12×6 m · Up to 10 guests · July & August',
    'buttons' => [['text' => 'Book the villa', 'url' => '/contact', 'style' => 'primary']],
], 'Hero Villa');

updateSection('location-villa-provence', 2, 'en', [
    'heading' => 'The entire villa in full autonomy',
    'text' => 'In July and August, Villa Plaisance opens its doors as a full rental. Four bedrooms, ten guests, a private pool, a Provençal garden, a fully equipped kitchen. You are at home. We remain available if needed, but you set your own pace.',
], 'Intro Villa');

updateSection('location-villa-provence', 4, 'en', [
    'heading' => 'Private fenced pool 12×6 m',
    'text' => 'Exclusively reserved for your group, 24/7. Sun loungers, parasols, garden table, outdoor lounge and solar shower. Fenced for children\'s safety.',
], 'Pool');

updateSection('location-villa-provence', 5, 'en', ['heading' => 'Villa spaces'], 'Villa spaces');
updateSection('location-villa-provence', 6, 'en', ['heading' => 'Practical information'], 'Practical info villa');
updateSection('location-villa-provence', 8, 'en', ['heading' => 'Frequently asked questions — Entire Villa'], 'FAQ Villa');
updateSection('location-villa-provence', 9, 'en', [
    'heading' => 'Your summer in Provence starts here',
    'text' => 'Book your week securely or contact us for a personalised quote. Direct booking = best rate guaranteed.',
    'buttons' => [['text' => 'Check availability', 'url' => '/contact', 'style' => 'primary']],
], 'CTA Villa');

// Sur place
updateSection('sur-place', 1, 'en', [
    'title' => 'Things to do around Bédarrides',
    'subtitle' => 'Our tested recommendations — 8 min from Châteauneuf-du-Pape, 15 min from Avignon',
], 'Hero Nearby');

echo "  EN sections done\n";

// ═══════════════════════════════════════════
//  SECTIONS — ESPAÑOL
// ═══════════════════════════════════════════
echo "--- Sections ES ---\n";

// Accueil
updateSection('accueil', 1, 'es', [
    'title' => 'Villa Plaisance — B&B y Villa privada en Provenza',
    'subtitle' => 'Bédarrides, en el corazón del Triángulo de Oro · 9,4/10 Booking · Superhost Airbnb',
    'buttons' => [['text' => 'Consultar disponibilidad', 'url' => '/contact', 'style' => 'primary']],
], 'Hero Inicio');

updateSection('accueil', 2, 'es', [
    'heading' => 'Una casa, dos formas de alojarse',
    'text' => 'En el corazón del Triángulo de Oro provenzal, Villa Plaisance es una casa con encanto en Bédarrides — a 15 min de Aviñón, 8 min de Châteauneuf-du-Pape, 18 min de Orange. De septiembre a junio, recibimos huéspedes en habitaciones B&B con desayuno casero y piscina compartida. En julio y agosto, la villa entera es suya en exclusiva: cuatro habitaciones, piscina privada de 12×6 m, jardín bajo los olivos.',
], 'Identidad');

updateSection('accueil', 4, 'es', [
    'heading' => 'B&B — de septiembre a junio',
    'text' => 'Se aloja en nuestra casa. Dos habitaciones comunicantes y climatizadas con baño privado son exclusivamente suyas: la Habitación Verde y la Habitación Azul. Desayuno casero, piscina compartida y nuestras recomendaciones de la zona.',
], 'Oferta B&B');

updateSection('accueil', 5, 'es', [
    'heading' => 'La Villa en exclusiva — julio y agosto',
    'text' => 'Dispone de la villa y los exteriores en exclusiva. 4 habitaciones, 2 baños, cocina equipada, terraza sombreada y piscina privada de 12×6 m. Ideal para vacaciones familiares o un grupo de amigos de hasta 10 personas.',
], 'Oferta Villa');

updateSection('accueil', 6, 'es', ['heading' => 'En el corazón del Triángulo de Oro'], 'Triángulo de Oro');
updateSection('accueil', 7, 'es', ['heading' => 'Lo que dicen nuestros huéspedes'], 'Opiniones');
updateSection('accueil', 8, 'es', ['heading' => 'Diario'], 'Últimos artículos');
updateSection('accueil', 9, 'es', ['heading' => 'Preguntas frecuentes'], 'FAQ Inicio');
updateSection('accueil', 10, 'es', [
    'heading' => 'Su estancia en Provenza empieza aquí',
    'text' => 'Elija la opción que más le convenga y reserve en pocos clics. Reserva directa = mejor tarifa garantizada.',
    'buttons' => [['text' => 'Consultar disponibilidad', 'url' => '/contact', 'style' => 'primary']],
], 'CTA Contacto');

// Chambres d'hôtes
updateSection('chambres-d-hotes', 1, 'es', [
    'title' => 'B&B — Su retiro provenzal',
    'subtitle' => 'Dos habitaciones privadas con desayuno · De septiembre a junio · Nota 9,4/10',
    'buttons' => [['text' => 'Reservar su estancia', 'url' => '/contact', 'style' => 'primary']],
], 'Hero Habitaciones');

updateSection('chambres-d-hotes', 2, 'es', [
    'heading' => 'B&B en Bédarrides',
    'text' => 'En Villa Plaisance, recibimos huéspedes de septiembre a junio en dos habitaciones con encanto diseñadas para el confort y la tranquilidad. Cada habitación tiene baño privado, aire acondicionado y Wi-Fi. El desayuno casero se sirve cada mañana en la terraza o en la galería.',
], 'Intro Habitaciones');

updateSection('chambres-d-hotes', 4, 'es', [
    'heading' => 'Desayuno casero incluido',
    'text' => 'Cada mañana de 7:30 a 10:00, en la terraza o en la galería según la temporada. Mermeladas artesanales (higos, albaricoques, lavanda), pan fresco, bollería, fruta de temporada, quesos, huevos del jardín, zumo, café, té.',
], 'Desayuno');

updateSection('chambres-d-hotes', 5, 'es', ['heading' => 'Equipamientos y servicios incluidos'], 'Equipamientos');
updateSection('chambres-d-hotes', 6, 'es', ['heading' => 'Información práctica'], 'Info práctica');
updateSection('chambres-d-hotes', 7, 'es', ['heading' => 'Preguntas frecuentes — B&B'], 'FAQ Habitaciones');
updateSection('chambres-d-hotes', 8, 'es', [
    'heading' => 'Reserve su habitación B&B',
    'text' => 'De septiembre a junio · Desayuno incluido · Reserva directa = mejor tarifa garantizada.',
    'buttons' => [['text' => 'Consultar disponibilidad', 'url' => '/contact', 'style' => 'primary']],
], 'CTA Habitaciones');

// Contact
updateSection('contact', 1, 'es', [
    'title' => 'Hablemos de su estancia',
    'subtitle' => 'Respuesta en 24 horas · Reserva directa = mejor tarifa garantizada',
], 'Hero Contacto');

// Espaces extérieurs
updateSection('espaces-exterieurs', 1, 'es', [
    'title' => 'Jardín, piscina y terrazas — Encanto al aire libre',
    'subtitle' => '1.500 m² de vegetación, piscina vallada, terraza sombreada y vistas a los viñedos',
], 'Hero Exteriores');

updateSection('espaces-exterieurs', 2, 'es', [
    'heading' => 'Fuera, sigue siendo su casa',
    'text' => 'El jardín de Villa Plaisance es una extensión natural de la casa. 1.500 m² de vegetación, olivos centenarios, lavanda, romero, huerto en verano. La piscina vallada da a los viñedos. La terraza sombreada es donde todo sucede: desayuno, aperitivos, cena bajo las estrellas.',
], 'Intro Exteriores');

updateSection('espaces-exterieurs', 3, 'es', [
    'heading' => 'Piscina de 12×6 m — vallada y segura',
    'text' => 'La piscina mide 12 por 6 metros, completamente vallada para la seguridad de los niños. Tumbonas, sombrillas, ducha exterior y mobiliario de jardín. Abierta de mayo a octubre.',
], 'Piscina');

updateSection('espaces-exterieurs', 4, 'es', [
    'heading' => 'Terrazas sombreadas frente a los viñedos',
    'text' => 'Terraza cubierta de 40 m² orientada al sur, perfecta para comer al aire libre, aperitivos al atardecer y mañanas tranquilas con café. Vista directa a los viñedos de Châteauneuf-du-Pape.',
], 'Terrazas');

updateSection('espaces-exterieurs', 5, 'es', [
    'heading' => 'Jardín provenzal — olivos, lavanda y hierbas aromáticas',
    'text' => 'Un jardín arbolado con olivos centenarios, lavanda, rosales antiguos y hierbas aromáticas. Zona de barbacoa con parrilla de carbón, campo de petanca, columpio para niños.',
], 'Jardín');

updateSection('espaces-exterieurs', 6, 'es', ['heading' => 'Equipamientos exteriores'], 'Equipamientos ext.');
updateSection('espaces-exterieurs', 8, 'es', [
    'heading' => 'Disfrute de los exteriores en Provenza',
    'text' => 'B&B (sept–jun) o villa privada (jul–ago) — los espacios exteriores siempre están incluidos. Reserva directa = mejor tarifa garantizada.',
    'buttons' => [['text' => 'Consultar disponibilidad', 'url' => '/contact', 'style' => 'primary']],
], 'CTA Exteriores');

// Journal
updateSection('journal', 1, 'es', [
    'title' => 'El Diario de Villa Plaisance',
    'subtitle' => 'Relatos, consejos y miradas a la Provenza',
], 'Hero Diario');

// Villa
updateSection('location-villa-provence', 1, 'es', [
    'title' => 'La Villa entera — Solo para usted',
    'subtitle' => '4 habitaciones · Piscina privada 12×6 m · Hasta 10 personas · Julio y agosto',
    'buttons' => [['text' => 'Reservar la villa', 'url' => '/contact', 'style' => 'primary']],
], 'Hero Villa');

updateSection('location-villa-provence', 2, 'es', [
    'heading' => 'La villa entera con total autonomía',
    'text' => 'En julio y agosto, Villa Plaisance abre sus puertas en alquiler completo. Cuatro habitaciones, diez personas, piscina privada, jardín provenzal, cocina totalmente equipada. Está en su casa. Seguimos disponibles si lo necesita, pero usted marca su propio ritmo.',
], 'Intro Villa');

updateSection('location-villa-provence', 4, 'es', [
    'heading' => 'Piscina privada vallada de 12×6 m',
    'text' => 'Reservada exclusivamente para su grupo, 24/7. Tumbonas, sombrillas, mesa de jardín, salón exterior y ducha solar. Vallada para la seguridad de los niños.',
], 'Piscina');

updateSection('location-villa-provence', 5, 'es', ['heading' => 'Los espacios de la villa'], 'Espacios villa');
updateSection('location-villa-provence', 6, 'es', ['heading' => 'Información práctica'], 'Info práctica');
updateSection('location-villa-provence', 8, 'es', ['heading' => 'Preguntas frecuentes — Villa entera'], 'FAQ Villa');
updateSection('location-villa-provence', 9, 'es', [
    'heading' => 'Su verano en Provenza empieza aquí',
    'text' => 'Reserve su semana de forma segura o contáctenos para un presupuesto personalizado. Reserva directa = mejor tarifa garantizada.',
    'buttons' => [['text' => 'Consultar disponibilidad', 'url' => '/contact', 'style' => 'primary']],
], 'CTA Villa');

// Sur place
updateSection('sur-place', 1, 'es', [
    'title' => 'Qué descubrir cerca de Bédarrides',
    'subtitle' => 'Nuestras direcciones probadas — a 8 min de Châteauneuf-du-Pape, 15 min de Aviñón',
], 'Hero Alrededores');

echo "  ES sections done\n";

// ═══════════════════════════════════════════
//  STATS
// ═══════════════════════════════════════════
echo "--- Stats ---\n";
updateStat(1, 'en', 'Distinct offers', 'B&B Sept–Jun · Private villa Jul–Aug');
updateStat(2, 'en', 'Booking.com rating', 'Airbnb Superhost');
updateStat(3, 'en', 'Max guests', 'Private villa with exclusive pool');
updateStat(4, 'en', 'UNESCO sites nearby', 'Avignon · Orange · Pont du Gard');

updateStat(1, 'es', 'Ofertas distintas', 'B&B sept–jun · Villa privada jul–ago');
updateStat(2, 'es', 'Nota Booking.com', 'Superhost Airbnb');
updateStat(3, 'es', 'Personas máx.', 'Villa privada con piscina exclusiva');
updateStat(4, 'es', 'Sitios UNESCO cerca', 'Aviñón · Orange · Pont du Gard');
echo "  Stats done\n";

// ═══════════════════════════════════════════
//  FAQ
// ═══════════════════════════════════════════
echo "--- FAQ EN ---\n";

// Accueil EN
updateFaq('accueil', 1, 'en', 'Where is Villa Plaisance located?', 'Villa Plaisance is in Bédarrides, Vaucluse (84370), in the heart of Provence\'s Golden Triangle — 8 minutes from Châteauneuf-du-Pape, 15 min from Avignon, 18 min from Orange. Easy access via the A7 motorway (Avignon-Nord exit).');
updateFaq('accueil', 2, 'en', 'What is the difference between B&B and villa rental?', 'From September to June, we welcome guests in B&B rooms (2 rooms, breakfast included, shared pool). In July and August, the entire villa is rented exclusively: 4 bedrooms, private pool, full kitchen.');
updateFaq('accueil', 3, 'en', 'Is there a swimming pool?', 'Yes. The pool measures 12 by 6 metres, fenced and secure. In B&B mode, it is shared with other guests. In villa rental, it is exclusively yours.');

// Chambres EN
updateFaq('chambres-d-hotes', 1, 'en', 'Is breakfast included?', 'Yes, homemade breakfast is included in the B&B rate. Served from 7:30 to 10:00 on the terrace or in the veranda, with artisanal jams, fresh bread, pastries, seasonal fruit, cheeses, garden eggs, juice, coffee and tea.');
updateFaq('chambres-d-hotes', 2, 'en', 'Are the rooms air-conditioned?', 'Yes, both rooms (Green and Blue) have reversible air conditioning and free Wi-Fi.');
updateFaq('chambres-d-hotes', 3, 'en', 'Can you accommodate children in B&B?', 'Yes, the Blue Room has a sofa bed for an extra person, making it ideal for families.');
updateFaq('chambres-d-hotes', 4, 'en', 'When are the B&B rooms available?', 'The B&B rooms are open from September to June. In July and August, the villa is rented exclusively.');
updateFaq('chambres-d-hotes', 5, 'en', 'How do I get to Villa Plaisance?', 'Villa Plaisance is 15 min from Avignon via the RN7 or A7 motorway (Avignon-Nord exit). By train: Avignon-Centre or Avignon TGV station, then taxi (15 min).');
updateFaq('chambres-d-hotes', 6, 'en', 'Is there parking?', 'Yes, free private parking is available on site for all guests.');
updateFaq('chambres-d-hotes', 7, 'en', 'Is the pool available throughout the B&B season?', 'The shared pool (12×6 m) is available from May to October depending on weather. Sun loungers and parasols are provided.');
updateFaq('chambres-d-hotes', 8, 'en', 'How do I book the B&B?', 'You can book via Airbnb or Booking.com for secure payment, or contact us directly for a preferential rate. Direct booking = best rate guaranteed.');

// Villa EN
updateFaq('location-villa-provence', 1, 'en', 'How many guests can the villa accommodate?', 'The entire villa accommodates up to 10 people in 4 bedrooms: a master suite with king bed and private bathroom, the Blue Room, the Arch Room and the 70s Room.');
updateFaq('location-villa-provence', 2, 'en', 'Is the pool private in villa rental?', 'Yes, in villa rental the 12×6 m pool is exclusively reserved for your group, 24/7. No other family has access. It can be heated on request.');
updateFaq('location-villa-provence', 3, 'en', 'Is the kitchen fully equipped?', 'Yes, the kitchen is fully equipped: oven, hob, dishwasher, microwave, fridge, utensils and tableware for 10 people.');
updateFaq('location-villa-provence', 4, 'en', 'Is linen provided?', 'Yes, bed sheets, bath towels and pool towels are provided and changed weekly.');
updateFaq('location-villa-provence', 5, 'en', 'What is the minimum rental period?', 'In high season (July–August), the minimum stay is one week, Saturday to Saturday.');
updateFaq('location-villa-provence', 6, 'en', 'Are there shops nearby?', 'Yes, Bédarrides has bakeries, a small supermarket, restaurants and a pharmacy. The nearest supermarket is in Sorgues, 5 minutes by car.');
updateFaq('location-villa-provence', 7, 'en', 'Is there a cleaning or private chef service?', 'End-of-stay cleaning is included. Mid-stay cleaning can be added. We can also arrange a private chef or catering service.');
updateFaq('location-villa-provence', 8, 'en', 'How do I book the villa?', 'You can book via Abritel or Booking.com for secure online payment, or contact us directly for a personalised quote, specific dates or a multi-week discount.');

echo "--- FAQ ES ---\n";

// Accueil ES
updateFaq('accueil', 1, 'es', '¿Dónde se encuentra Villa Plaisance?', 'Villa Plaisance está en Bédarrides, Vaucluse (84370), en el corazón del Triángulo de Oro provenzal — a 8 minutos de Châteauneuf-du-Pape, 15 min de Aviñón, 18 min de Orange.');
updateFaq('accueil', 2, 'es', '¿Cuál es la diferencia entre B&B y villa entera?', 'De septiembre a junio, recibimos huéspedes en habitaciones B&B (2 habitaciones, desayuno incluido, piscina compartida). En julio y agosto, la villa entera se alquila en exclusiva: 4 habitaciones, piscina privada, cocina equipada.');
updateFaq('accueil', 3, 'es', '¿Hay piscina?', 'Sí. La piscina mide 12 por 6 metros, está vallada y es segura. En modo B&B se comparte con otros huéspedes. En alquiler de villa, es exclusivamente suya.');

// Chambres ES
updateFaq('chambres-d-hotes', 1, 'es', '¿El desayuno está incluido?', 'Sí, el desayuno casero está incluido en la tarifa B&B. Se sirve de 7:30 a 10:00 en la terraza o en la galería, con mermeladas artesanales, pan fresco, bollería, fruta de temporada, quesos, huevos del jardín, zumo, café y té.');
updateFaq('chambres-d-hotes', 2, 'es', '¿Las habitaciones tienen aire acondicionado?', 'Sí, ambas habitaciones (Verde y Azul) disponen de aire acondicionado reversible y Wi-Fi gratuito.');
updateFaq('chambres-d-hotes', 3, 'es', '¿Se admiten niños en B&B?', 'Sí, la Habitación Azul dispone de un sofá cama para una persona adicional, ideal para familias.');
updateFaq('chambres-d-hotes', 4, 'es', '¿En qué periodo están disponibles las habitaciones?', 'Las habitaciones B&B están abiertas de septiembre a junio. En julio y agosto, la villa se alquila en exclusiva.');
updateFaq('chambres-d-hotes', 5, 'es', '¿Cómo llegar a Villa Plaisance?', 'Villa Plaisance está a 15 min de Aviñón por la RN7 o la autopista A7 (salida Avignon-Nord). En tren: estación Avignon-Centre o Avignon TGV, luego taxi (15 min).');
updateFaq('chambres-d-hotes', 6, 'es', '¿Hay aparcamiento?', 'Sí, hay un aparcamiento privado gratuito en el recinto para todos los huéspedes.');
updateFaq('chambres-d-hotes', 7, 'es', '¿La piscina está disponible toda la temporada B&B?', 'La piscina compartida (12×6 m) está disponible de mayo a octubre según las condiciones meteorológicas. Se proporcionan tumbonas y sombrillas.');
updateFaq('chambres-d-hotes', 8, 'es', '¿Cómo reservar el B&B?', 'Puede reservar a través de Airbnb o Booking.com para un pago seguro, o contactarnos directamente para una tarifa preferente. Reserva directa = mejor tarifa garantizada.');

// Villa ES
updateFaq('location-villa-provence', 1, 'es', '¿Cuántas personas puede alojar la villa?', 'La villa entera acoge hasta 10 personas en 4 habitaciones: una suite principal con cama king y baño privado, la Habitación Azul, la Habitación Arco y la Habitación 70.');
updateFaq('location-villa-provence', 2, 'es', '¿La piscina es privada en alquiler de villa?', 'Sí, en alquiler de villa la piscina de 12×6 m está reservada exclusivamente para su grupo, 24/7. Se puede calentar bajo petición.');
updateFaq('location-villa-provence', 3, 'es', '¿La cocina está equipada?', 'Sí, la cocina está totalmente equipada: horno, placa, lavavajillas, microondas, frigorífico, utensilios y vajilla para 10 personas.');
updateFaq('location-villa-provence', 4, 'es', '¿Se proporciona ropa de cama?', 'Sí, se proporcionan sábanas, toallas de baño y toallas de piscina, con cambio semanal.');
updateFaq('location-villa-provence', 5, 'es', '¿Cuál es la duración mínima del alquiler?', 'En temporada alta (julio–agosto), la estancia mínima es de una semana, de sábado a sábado.');
updateFaq('location-villa-provence', 6, 'es', '¿Hay tiendas cerca?', 'Sí, Bédarrides tiene panaderías, supermercado, restaurantes y farmacia. El supermercado más cercano está en Sorgues, a 5 minutos en coche.');
updateFaq('location-villa-provence', 7, 'es', '¿Hay servicio de limpieza o chef privado?', 'La limpieza de fin de estancia está incluida. Se puede añadir limpieza intermedia. También podemos organizar un chef privado o servicio de catering.');
updateFaq('location-villa-provence', 8, 'es', '¿Cómo reservar la villa entera?', 'Puede reservar a través de Abritel o Booking.com para un pago seguro en línea, o contactarnos directamente para un presupuesto personalizado.');

echo "  FAQ done\n";

// ═══════════════════════════════════════════
//  PROXIMITES
// ═══════════════════════════════════════════
echo "--- Proximites ---\n";
$proxData = [
    1 => ['en' => ['Châteauneuf-du-Pape', '8 min', 'Vineyards and heritage'], 'es' => ['Châteauneuf-du-Pape', '8 min', 'Viñedos y patrimonio']],
    2 => ['en' => ['Avignon', '15 min', 'Palace of the Popes, bridge, festival'], 'es' => ['Aviñón', '15 min', 'Palacio de los Papas, puente, festival']],
    3 => ['en' => ['Orange', '18 min', 'Roman ancient theatre'], 'es' => ['Orange', '18 min', 'Teatro romano antiguo']],
    4 => ['en' => ['L\'Isle-sur-la-Sorgue', '25 min', 'Antiques and markets'], 'es' => ['L\'Isle-sur-la-Sorgue', '25 min', 'Antigüedades y mercados']],
    5 => ['en' => ['Pont du Gard', '30 min', 'Roman aqueduct — UNESCO'], 'es' => ['Pont du Gard', '30 min', 'Acueducto romano — UNESCO']],
    6 => ['en' => ['Vaison-la-Romaine', '35 min', 'Gallo-Roman archaeological site'], 'es' => ['Vaison-la-Romaine', '35 min', 'Yacimiento arqueológico galo-romano']],
    7 => ['en' => ['Gordes', '42 min', 'Most beautiful villages of France'], 'es' => ['Gordes', '42 min', 'Pueblos más bonitos de Francia']],
    8 => ['en' => ['Les Baux-de-Provence', '45 min', 'Hilltop village, Carrières de Lumières'], 'es' => ['Les Baux-de-Provence', '45 min', 'Pueblo encaramado, Carrières de Lumières']],
    9 => ['en' => ['Mont Ventoux', '45 min', 'Giant of Provence'], 'es' => ['Mont Ventoux', '45 min', 'El Gigante de Provenza']],
];
foreach ($proxData as $pos => $langs) {
    foreach ($langs as $lang => $d) {
        updateProx($pos, $lang, $d[0], $d[1], $d[2]);
    }
}
echo "  Proximites done\n";

// ═══════════════════════════════════════════
//  PIECES
// ═══════════════════════════════════════════
echo "--- Pieces ---\n";

// BB pieces
updatePiece('bb', 1, 'en', ['name' => 'Green Room', 'sous_titre' => 'Double bed, garden view', 'description' => 'Bright room with a 160×200 double bed, overlooking the garden and olive trees. Cocooning atmosphere, simplicity and calm. Reversible air conditioning, TV.', 'equip' => '160×200 bed, Garden view, Reversible AC, TV, Wi-Fi', 'note' => 'Connecting to the Blue Room']);
updatePiece('bb', 1, 'es', ['name' => 'Habitación Verde', 'sous_titre' => 'Cama doble, vista al jardín', 'description' => 'Habitación luminosa con cama doble de 160×200, con vistas al jardín y los olivos. Ambiente acogedor, sobriedad y calma. Aire acondicionado reversible, TV.', 'equip' => 'Cama 160×200, Vista al jardín, Aire acondicionado, TV, Wi-Fi', 'note' => 'Comunicante con la Habitación Azul']);

updatePiece('bb', 2, 'en', ['name' => 'Blue Room', 'sous_titre' => 'Library, family-friendly', 'description' => 'Two 90×200 twin beds (can be joined), a sofa bed for a third person. A 300-book library. The readers\' and dreamers\' room.', 'equip' => '2 twin 90×200 beds (joinable), Sofa bed (1 pers.), 300-book library, Reversible AC, Wi-Fi', 'note' => 'Room / mini lounge with library and sofa']);
updatePiece('bb', 2, 'es', ['name' => 'Habitación Azul', 'sous_titre' => 'Biblioteca, ideal familias', 'description' => 'Dos camas de 90×200 unibles en cama grande 180. Un sofá cama para una tercera persona. Biblioteca de 300 libros. La habitación de los lectores y soñadores.', 'equip' => '2 camas 90×200 unibles, Sofá cama (1 pers.), Biblioteca 300 libros, Aire acondicionado, Wi-Fi', 'note' => 'Habitación / mini salón con biblioteca y sofá']);

// Villa pieces
updatePiece('villa', 1, 'en', ['name' => 'Green Room', 'sous_titre' => 'Double bed, garden view', 'description' => '160×200 bed, garden and olive tree views. Reversible AC, TV. Ground floor.', 'equip' => '160×200 bed, Garden view, AC, TV, Wi-Fi', 'note' => 'Ground floor']);
updatePiece('villa', 1, 'es', ['name' => 'Habitación Verde', 'sous_titre' => 'Cama doble, vista al jardín', 'description' => 'Cama 160×200, vistas al jardín y los olivos. Aire acondicionado reversible, TV. Planta baja.', 'equip' => 'Cama 160×200, Vista al jardín, Aire acond., TV, Wi-Fi', 'note' => 'Planta baja']);

updatePiece('villa', 2, 'en', ['name' => 'Blue Room', 'sous_titre' => '300-book library', 'description' => 'Two 90×200 twin beds (joinable), sofa bed, 300-book library. Reversible AC.', 'equip' => '2 twin 90×200 beds (joinable), Sofa bed, 300-book library, AC, Wi-Fi', 'note' => '']);
updatePiece('villa', 2, 'es', ['name' => 'Habitación Azul', 'sous_titre' => 'Biblioteca 300 libros', 'description' => 'Dos camas 90×200 unibles, sofá cama, biblioteca de 300 libros. Aire acondicionado reversible.', 'equip' => '2 camas 90×200 unibles, Sofá cama, Biblioteca 300 libros, Aire acond., Wi-Fi', 'note' => '']);

updatePiece('villa', 3, 'en', ['name' => 'Arch Room', 'sous_titre' => 'Midnight blue arch, floor-to-ceiling bookshelves', 'description' => '140×180 bed under a large midnight blue arch. Floor-to-ceiling bookshelves on both sides. Ground floor with garden view.', 'equip' => '140×180 bed, Midnight blue arch, Floor-to-ceiling bookshelves, Garden view, AC', 'note' => 'Ground floor · Direct garden access']);
updatePiece('villa', 3, 'es', ['name' => 'Habitación Arco', 'sous_titre' => 'Arco azul noche, estanterías de suelo a techo', 'description' => 'Cama 140×180 bajo un gran arco pintado en azul noche. Estanterías de suelo a techo a ambos lados. Planta baja con vista al jardín.', 'equip' => 'Cama 140×180, Arco azul noche, Estanterías suelo-techo, Vista al jardín, Aire acond.', 'note' => 'Planta baja · Acceso directo al jardín']);

updatePiece('villa', 4, 'en', ['name' => '70s Room', 'sous_titre' => 'Vintage 70s furniture', 'description' => 'Double bed, vintage 70s furniture. Direct garden access through French doors. The villa\'s most unusual room.', 'equip' => 'Double bed, Vintage furniture, Direct garden access, AC', 'note' => 'Direct garden access']);
updatePiece('villa', 4, 'es', ['name' => 'Habitación 70', 'sous_titre' => 'Mobiliario vintage años 70', 'description' => 'Cama doble, mobiliario vintage de los años 70. Acceso directo al jardín por una puerta-ventana. La habitación más singular de la villa.', 'equip' => 'Cama doble, Mobiliario vintage, Acceso directo jardín, Aire acond.', 'note' => 'Acceso directo al jardín']);

updatePiece('villa', 5, 'en', ['name' => 'Kitchen', 'sous_titre' => 'Fully equipped', 'description' => 'The kitchen is fully equipped: oven, hob, dishwasher, microwave, fridge, utensils and tableware for 10.', 'equip' => '', 'note' => '']);
updatePiece('villa', 5, 'es', ['name' => 'Cocina', 'sous_titre' => 'Totalmente equipada', 'description' => 'La cocina está totalmente equipada: horno, placa, lavavajillas, microondas, frigorífico, utensilios y vajilla para 10.', 'equip' => '', 'note' => '']);

updatePiece('villa', 6, 'en', ['name' => 'Living / Dining Room', 'sous_titre' => 'Togetherness made simple', 'description' => 'Spacious living and dining area for the whole group.', 'equip' => '', 'note' => '']);
updatePiece('villa', 6, 'es', ['name' => 'Salón / Comedor', 'sous_titre' => 'Convivencia con sencillez', 'description' => 'Amplio salón comedor para todo el grupo.', 'equip' => '', 'note' => '']);

echo "  Pieces done\n";

// ═══════════════════════════════════════════
//  AMENITIES
// ═══════════════════════════════════════════
echo "--- Amenities ---\n";

$amenityTranslations = [
    'Animaux domestiques' => [
        ['Animaux admis sur demande', 'Sans supplément', 'Pets allowed on request', 'No extra charge', 'Animales admitidos bajo petición', 'Sin suplemento'],
    ],
    'Bien-être' => [
        ['Parasols', '', 'Parasols', '', 'Sombrillas', ''],
        ['Chaises longues', '', 'Sun loungers', '', 'Tumbonas', ''],
    ],
    'Chambre' => [
        ['Linge de maison', '', 'Bed linen', '', 'Ropa de cama', ''],
        ['Armoire ou penderie', '', 'Wardrobe or closet', '', 'Armario o vestidor', ''],
        ['Dressing', '', 'Dressing room', '', 'Vestidor', ''],
        ['Très grands lits (> 2 mètres)', '', 'Extra-large beds (> 2 metres)', '', 'Camas extra grandes (> 2 metros)', ''],
        ['Prise près du lit', '', 'Socket near the bed', '', 'Enchufe junto a la cama', ''],
        ['Canapé-lit', '', 'Sofa bed', '', 'Sofá cama', ''],
    ],
    'En extérieur' => [
        ['Piscine privée clôturée', '', 'Private fenced pool', '', 'Piscina privada vallada', ''],
        ['Jardin provençal', '', 'Provençal garden', '', 'Jardín provenzal', ''],
        ['Terrasse', '', 'Terrace', '', 'Terraza', ''],
        ['Terrasse bien exposée', '', 'Sun-facing terrace', '', 'Terraza bien orientada', ''],
        ['Balcon', '', 'Balcony', '', 'Balcón', ''],
        ['Mobilier extérieur', '', 'Outdoor furniture', '', 'Mobiliario exterior', ''],
        ['Aire de pique-nique', '', 'Picnic area', '', 'Zona de pícnic', ''],
    ],
    'Équipements en chambre' => [
        ['Étendoir', '', 'Drying rack', '', 'Tendedero', ''],
        ['Portant', '', 'Clothes rack', '', 'Perchero', ''],
        ['Fer à repasser', '', 'Iron', '', 'Plancha', ''],
        ['Matériel de repassage', '', 'Ironing facilities', '', 'Equipo de planchado', ''],
    ],
    'Général' => [
        ['Climatisation', '', 'Air conditioning', '', 'Aire acondicionado', ''],
        ['Établissement non-fumeurs', '', 'Non-smoking property', '', 'Establecimiento para no fumadores', ''],
        ['Moustiquaire', '', 'Mosquito net', '', 'Mosquitera', ''],
        ['Sol carrelé / en marbre', '', 'Tiled / marble floor', '', 'Suelo de baldosa / mármol', ''],
        ['Chauffage', '', 'Heating', '', 'Calefacción', ''],
    ],
    'High-tech' => [
        ['Télévision à écran plat', '', 'Flat-screen TV', '', 'Televisión de pantalla plana', ''],
        ['Chaînes du câble', '', 'Cable channels', '', 'Canales por cable', ''],
        ['Radio', '', 'Radio', '', 'Radio', ''],
    ],
    'Internet' => [
        ['Wi-Fi gratuit dans tout l\'établissement', '', 'Free Wi-Fi throughout', '', 'Wi-Fi gratuito en todo el establecimiento', ''],
    ],
    'Langues parlées' => [
        ['Français', '', 'French', '', 'Francés', ''],
        ['Anglais', '', 'English', '', 'Inglés', ''],
        ['Espagnol', '', 'Spanish', '', 'Español', ''],
        ['Italien', '', 'Italian', '', 'Italiano', ''],
    ],
    'Parking' => [
        ['Parking privé gratuit sur place', 'Sans réservation préalable', 'Free private parking on site', 'No reservation needed', 'Aparcamiento privado gratuito', 'Sin reserva previa'],
    ],
    'Piscine extérieure' => [
        ['En saison', '', 'Seasonal', '', 'De temporada', ''],
        ['Tous les âges bienvenus', '', 'All ages welcome', '', 'Todas las edades bienvenidas', ''],
        ['Chaises longues', '', 'Sun loungers', '', 'Tumbonas', ''],
        ['Parasols', '', 'Parasols', '', 'Sombrillas', ''],
    ],
    'Points forts' => [
        ['Piscine extérieure privée', '', 'Private outdoor pool', '', 'Piscina exterior privada', ''],
        ['Parking gratuit', '', 'Free parking', '', 'Aparcamiento gratuito', ''],
        ['Connexion Wi-Fi gratuite', '', 'Free Wi-Fi', '', 'Wi-Fi gratuito', ''],
        ['Chambres familiales', '', 'Family rooms', '', 'Habitaciones familiares', ''],
        ['Petit-déjeuner maison', '', 'Homemade breakfast', '', 'Desayuno casero', ''],
        ['Climatisation', '', 'Air conditioning', '', 'Aire acondicionado', ''],
    ],
    'Pour les familles' => [
        ['Chambres familiales', '', 'Family rooms', '', 'Habitaciones familiares', ''],
        ['Chambre(s) communicante(s)', '', 'Connecting rooms', '', 'Habitaciones comunicantes', ''],
        ['Livres, DVD ou musique pour enfants', '', 'Books, DVDs or music for children', '', 'Libros, DVD o música para niños', ''],
        ['Jeux de société / puzzles', '', 'Board games / puzzles', '', 'Juegos de mesa / puzles', ''],
    ],
    'Salle de bains' => [
        ['Salle de bains privative', '', 'Private bathroom', '', 'Baño privado', ''],
        ['Douche', '', 'Shower', '', 'Ducha', ''],
        ['Baignoire ou douche', '', 'Bathtub or shower', '', 'Bañera o ducha', ''],
        ['Serviettes', '', 'Towels', '', 'Toallas', ''],
        ['Sèche-cheveux', '', 'Hairdryer', '', 'Secador de pelo', ''],
        ['Papier toilette', '', 'Toilet paper', '', 'Papel higiénico', ''],
        ['Bidet', '', 'Bidet', '', 'Bidé', ''],
    ],
    'Sécurité' => [
        ['Clés d\'accès', '', 'Access keys', '', 'Llaves de acceso', ''],
        ['portail commandé à distance', '', 'Remote-controlled gate', '', 'Portal con mando a distancia', ''],
    ],
    'Services' => [
        ['Gamelles pour animaux de compagnie', '', 'Pet bowls', '', 'Cuencos para mascotas', ''],
        ['Salon commun / salle de télévision', '', 'Shared lounge / TV room', '', 'Salón compartido / sala de TV', ''],
        ['Facture fournie sur demande', '', 'Invoice provided on request', '', 'Factura disponible bajo petición', ''],
    ],
    'Vue' => [
        ['Vue sur le jardin', '', 'Garden view', '', 'Vista al jardín', ''],
        ['Vue dégagée', '', 'Open view', '', 'Vista despejada', ''],
    ],
];

foreach ($amenityTranslations as $category => $items) {
    foreach ($items as $i => $item) {
        $pos = $i + 1;
        // Find the FR amenity to get exact position
        $fr = Database::fetchOne(
            "SELECT position FROM vp_amenities WHERE category = ? AND lang = 'fr' AND name = ?",
            [$category, $item[0]]
        );
        if (!$fr) { echo "  SKIP {$category}/{$item[0]} (FR not found)\n"; continue; }
        $pos = $fr['position'];
        updateAmenity($category, $pos, 'en', $item[2], $item[3]);
        updateAmenity($category, $pos, 'es', $item[4], $item[5]);
    }
}

echo "  Amenities done\n";

echo "\n=== All translations filled! ===\n";
