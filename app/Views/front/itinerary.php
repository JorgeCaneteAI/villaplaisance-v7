<?php
declare(strict_types=1);
$date = $itinerary['itinerary_date'] ? date('d/m/Y', strtotime($itinerary['itinerary_date'])) : '';
$dayNames = ['Sunday'=>'Dimanche','Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi','Thursday'=>'Jeudi','Friday'=>'Vendredi','Saturday'=>'Samedi'];
$dayName = $itinerary['itinerary_date'] ? ($dayNames[date('l', strtotime($itinerary['itinerary_date']))] ?? '') : '';

// Coordonnées pour la carte
$mapPoints = [];
$gmapsWaypoints = [];
foreach ($steps as $s) {
    if (!empty($s['lat']) && !empty($s['lng'])) {
        $mapPoints[] = ['lat' => (float)$s['lat'], 'lng' => (float)$s['lng'], 'title' => $s['title'], 'time' => $s['time_label'] ?? ''];
    }
    $gmapsWaypoints[] = urlencode($s['title']);
}
$hasMap = count($mapPoints) >= 2;

// Lien Google Maps directions
$gmapsUrl = 'https://www.google.com/maps/dir/' . implode('/', $gmapsWaypoints);
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">

<style>
.itinerary-page {
    max-width: 640px;
    margin: 0 auto;
    padding: 2rem 1.25rem 3rem;
}
.itinerary-header {
    text-align: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e8e0d8;
}
.itinerary-header h1 {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.8rem;
    font-weight: 500;
    color: #2c3e50;
    margin: 0 0 0.5rem;
    line-height: 1.3;
}
.itinerary-date {
    font-size: 0.9rem;
    color: #8B7355;
    font-weight: 500;
    margin-bottom: 1rem;
}
.itinerary-intro {
    font-size: 0.95rem;
    color: #666;
    line-height: 1.6;
    font-style: italic;
}

/* Carte */
.itinerary-map-wrap {
    margin-bottom: 1.5rem;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.1);
}
#itinerary-map {
    height: 280px;
    width: 100%;
}
.itinerary-map-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    padding: 0.75rem;
    background: #fafaf8;
    border-top: 1px solid #e8e0d8;
}
.btn-maps {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    background: #2c3e50;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: background 0.2s;
}
.btn-maps:hover { background: #1a252f; }
.btn-maps-outline {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    background: #fff;
    color: #2c3e50;
    border: 1px solid #d0c8be;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.85rem;
    cursor: pointer;
}

/* Marker numéroté */
.step-marker {
    background: #8B7355;
    color: #fff;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 700;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}

/* Timeline */
.itinerary-timeline {
    position: relative;
    padding-left: 2rem;
}
.itinerary-timeline::before {
    content: '';
    position: absolute;
    left: 6px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: linear-gradient(to bottom, #C5B9A8, #e8e0d8);
}
.itinerary-step {
    position: relative;
    margin-bottom: 2rem;
    padding-bottom: 0.5rem;
}
.itinerary-step:last-child {
    margin-bottom: 0;
}
.itinerary-step::before {
    content: '';
    position: absolute;
    left: -2rem;
    top: 6px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #8B7355;
    z-index: 1;
}
.itinerary-step:first-child::before {
    background: #8B7355;
    border-color: #8B7355;
}
.itinerary-step:last-child::before {
    background: #C5B9A8;
    border-color: #C5B9A8;
}
.step-time {
    font-size: 0.8rem;
    font-weight: 700;
    color: #8B7355;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    margin-bottom: 0.2rem;
}
.step-title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.15rem;
    line-height: 1.3;
}
.step-duration {
    font-size: 0.78rem;
    color: #aaa;
    margin-bottom: 0.4rem;
}
.step-desc {
    font-size: 0.9rem;
    color: #555;
    line-height: 1.6;
}

/* Footer */
.itinerary-footer {
    text-align: center;
    margin-top: 3rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e8e0d8;
    color: #aaa;
    font-size: 0.8rem;
}
.itinerary-footer a {
    color: #8B7355;
    text-decoration: none;
}

/* Mobile */
@media (max-width: 480px) {
    .itinerary-page { padding: 1.5rem 1rem 2rem; }
    .itinerary-header h1 { font-size: 1.5rem; }
    .step-title { font-size: 1.1rem; }
    #itinerary-map { height: 220px; }
}
</style>

<div class="itinerary-page">

    <header class="itinerary-header">
        <h1>Votre itinéraire, <?= htmlspecialchars($itinerary['guest_name']) ?></h1>
        <?php if ($date): ?>
        <div class="itinerary-date"><?= $dayName ?> <?= $date ?></div>
        <?php endif; ?>
        <?php if (!empty($itinerary['intro_text'])): ?>
        <p class="itinerary-intro"><?= nl2br(htmlspecialchars($itinerary['intro_text'])) ?></p>
        <?php endif; ?>
    </header>

    <!-- Carte -->
    <?php if ($hasMap): ?>
    <div class="itinerary-map-wrap">
        <div id="itinerary-map"></div>
        <div class="itinerary-map-actions">
            <a href="<?= htmlspecialchars($gmapsUrl) ?>" target="_blank" rel="noopener" class="btn-maps">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Ouvrir dans Google Maps
            </a>
            <button type="button" class="btn-maps-outline" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($gmapsUrl) ?>').then(()=>this.textContent='Copié !')">
                Copier le lien
            </button>
        </div>
    </div>
    <?php endif; ?>

    <div class="itinerary-timeline">
        <?php foreach ($steps as $step): ?>
        <div class="itinerary-step">
            <?php if (!empty($step['time_label'])): ?>
            <div class="step-time"><?= htmlspecialchars($step['time_label']) ?></div>
            <?php endif; ?>
            <div class="step-title"><?= htmlspecialchars($step['title']) ?></div>
            <?php if (!empty($step['duration'])): ?>
            <div class="step-duration"><?= htmlspecialchars($step['duration']) ?></div>
            <?php endif; ?>
            <?php if (!empty($step['description'])): ?>
            <p class="step-desc"><?= nl2br(htmlspecialchars($step['description'])) ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <footer class="itinerary-footer">
        Préparé avec soin par <a href="<?= APP_URL ?>">Villa Plaisance</a><br>
        Chambres d'hôtes &amp; villa — Bédarrides, Provence
    </footer>

</div>

<?php if ($hasMap): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
(function() {
    var points = <?= json_encode($mapPoints, JSON_UNESCAPED_UNICODE) ?>;
    var map = L.map('itinerary-map', { scrollWheelZoom: false, attributionControl: true });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var latlngs = [];
    points.forEach(function(p, i) {
        var icon = L.divIcon({
            className: '',
            html: '<div class="step-marker">' + (i + 1) + '</div>',
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });
        var label = (p.time ? p.time + ' — ' : '') + p.title;
        L.marker([p.lat, p.lng], { icon: icon })
            .bindPopup('<strong>' + label + '</strong>')
            .addTo(map);
        latlngs.push([p.lat, p.lng]);
    });

    // Tracé de la route
    L.polyline(latlngs, {
        color: '#8B7355',
        weight: 3,
        opacity: 0.7,
        dashArray: '8, 6'
    }).addTo(map);

    // Ajuster la vue
    var bounds = L.latLngBounds(latlngs);
    map.fitBounds(bounds, { padding: [30, 30] });
})();
</script>
<?php endif; ?>
