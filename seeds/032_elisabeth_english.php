<?php
declare(strict_types=1);

/**
 * Seed 032 — Passage en anglais de l'itinéraire Elisabeth-J
 * One-shot.
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

echo "=== Seed 032 : itinéraire Elisabeth-J en anglais ===\n";

$itinerary = Database::fetchOne("SELECT id FROM vp_itineraries WHERE slug = 'elisabeth-j'");
if (!$itinerary) {
    echo "❌ Itinéraire elisabeth-j introuvable\n";
    exit;
}
$id = (int)$itinerary['id'];

// Mettre à jour l'intro en anglais
Database::query(
    "UPDATE vp_itineraries SET intro_text = ?, lang = 'en' WHERE id = ?",
    [
        'Here is the itinerary we have prepared for your last day in Provence. From Châteauneuf-du-Pape to the Pont du Gard, through Uzès — a beautiful loop before heading to Alès. Have a wonderful trip!',
        $id,
    ]
);
echo "✅ Intro mise à jour en anglais\n";

// Supprimer les étapes existantes et recréer en anglais
Database::query("DELETE FROM vp_itinerary_steps WHERE itinerary_id = ?", [$id]);

$steps = [
    ['10:30 AM',  'Departure from Villa Plaisance', null,             'Head towards Châteauneuf-du-Pape (~20 min drive).', 1, 44.0393, 4.8963],
    ['~11:00 AM', 'Château La Gardine',             '~1h',            'Wine tasting in a stunning estate, rated 4.8/5 on Google. The visit is free and usually possible without a booking, but a quick call ahead is recommended: +33 4 90 83 73 20. Open on Mondays from 10 AM.', 2, 44.0533, 4.8361],
    ['~12:30 PM', 'Pont du Gard',                   '~1.5–2h with lunch', 'Lunch at the on-site restaurant, then visit the Roman aqueduct. Paid parking on site.', 3, 43.9475, 4.5356],
    ['~3:00 PM',  'Haribo Museum, Uzès',            '~45 min',        'Note: closed on Mondays — but tomorrow is Thursday, so it\'s open. Hours: 10 AM – 7 PM. The factory outlet is the main attraction (sweets cheaper than in supermarkets).', 4, 44.0118, 4.4197],
    ['~3:50 PM',  'Uzès Old Town',                  '~45 min',        'Stroll around Place aux Herbes, Tour Fenestrelle, charming old streets. Uzès is just 5 min from the Haribo Museum.', 5, 44.0123, 4.4214],
    ['~6:00 PM',  'Arrival in Alès',                null,             'Drive from Uzès to Alès: ~35 min.', 6, 44.1258, 4.0817],
];

foreach ($steps as $s) {
    Database::insert('vp_itinerary_steps', [
        'itinerary_id' => $id,
        'time_label'   => $s[0],
        'title'        => $s[1],
        'duration'     => $s[2],
        'description'  => $s[3],
        'position'     => $s[4],
        'lat'          => $s[5],
        'lng'          => $s[6],
    ]);
}
echo "✅ " . count($steps) . " étapes recréées en anglais\n";

echo "=== Seed 032 terminé ===\n";
