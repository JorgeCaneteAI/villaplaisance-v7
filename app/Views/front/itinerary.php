<?php
declare(strict_types=1);
$date = $itinerary['itinerary_date'] ? date('d/m/Y', strtotime($itinerary['itinerary_date'])) : '';
$itLang = $itinerary['lang'] ?? 'fr';
$dayNamesFr = ['Sunday'=>'Dimanche','Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi','Thursday'=>'Jeudi','Friday'=>'Vendredi','Saturday'=>'Samedi'];
$dayNamesEn = ['Sunday'=>'Sunday','Monday'=>'Monday','Tuesday'=>'Tuesday','Wednesday'=>'Wednesday','Thursday'=>'Thursday','Friday'=>'Friday','Saturday'=>'Saturday'];
$dayNamesMap = $itLang === 'en' ? $dayNamesEn : $dayNamesFr;
$dayName = $itinerary['itinerary_date'] ? ($dayNamesMap[date('l', strtotime($itinerary['itinerary_date']))] ?? '') : '';

// Titre dynamique : premier → dernier lieu
$firstStep = $steps[0]['title'] ?? '';
$lastStep  = end($steps)['title'] ?? '';
// Nettoyer les préfixes "Départ de" / "Departure from" / "Arrivée à" / "Arrival in"
$originClean = preg_replace('/^(Departure from|Départ( de)?)\s*/i', '', $firstStep);
$destClean   = preg_replace('/^(Arrival in|Arrivée à)\s*/i', '', $lastStep);
$routeTitle  = $originClean . ' → ' . $destClean;

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
$gmapsUrl = 'https://www.google.com/maps/dir/' . implode('/', $gmapsWaypoints);

// Nombre d'arrêts (sans départ ni arrivée)
$stopCount = max(0, count($steps) - 2);
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">

<style>
.itinerary-page {
    max-width: 660px;
    margin: 0 auto;
    padding: 0 1.25rem 3rem;
}

/* ── Hero ── */
.itinerary-hero {
    text-align: left;
    padding: 25vh 0 2rem;
    margin-bottom: 0;
}
.itinerary-hero-label {
    display: inline-block;
    font-family: 'Inter', sans-serif;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: #8B7355;
    background: rgba(139,115,85,0.08);
    padding: 0.3rem 1rem;
    border-radius: 20px;
    margin-bottom: 1rem;
}
.itinerary-hero h1 {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 2rem;
    font-weight: 400;
    color: #2c3e50;
    margin: 0 0 0.6rem;
    line-height: 1.2;
}
.itinerary-hero h1 .route-arrow {
    color: #C5B9A8;
    font-weight: 300;
}
.itinerary-hero-meta {
    font-size: 0.85rem;
    color: #999;
    margin-bottom: 1.2rem;
}
.itinerary-hero-meta strong {
    color: #666;
    font-weight: 500;
}
.itinerary-hero-intro {
    font-size: 0.92rem;
    color: #777;
    line-height: 1.7;
    max-width: 520px;
    margin: 0;
}
.itinerary-hero-stats {
    display: flex;
    justify-content: flex-start;
    gap: 2rem;
    margin-top: 1.5rem;
    padding-top: 1.2rem;
    border-top: 1px solid #eee;
}
.itinerary-hero-stat {
    text-align: center;
}
.itinerary-hero-stat-value {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
    line-height: 1;
}
.itinerary-hero-stat-label {
    font-size: 0.7rem;
    color: #aaa;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-top: 0.25rem;
}

/* ── Carte ── */
.itinerary-map-wrap {
    margin-bottom: 2rem;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #e8e0d8;
}
#itinerary-map {
    height: 300px;
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
    padding: 0.5rem 1.2rem;
    background: #2c3e50;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.82rem;
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
    color: #555;
    border: 1px solid #d0c8be;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.82rem;
    cursor: pointer;
    transition: border-color 0.2s;
}
.btn-maps-outline:hover { border-color: #8B7355; }

/* ── Marqueurs carte ── */
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

/* ── Section titre ── */
.itinerary-section-title {
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #C5B9A8;
    margin-bottom: 1.25rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #f0ebe4;
}

/* ── Timeline ── */
.itinerary-timeline {
    position: relative;
    padding-left: 2.5rem;
    margin-bottom: 2rem;
}
.itinerary-timeline::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: linear-gradient(to bottom, #8B7355 0%, #C5B9A8 40%, #e8e0d8 100%);
}
.itinerary-step {
    position: relative;
    margin-bottom: 2rem;
}
.itinerary-step:last-child {
    margin-bottom: 0;
}
.itinerary-step::before {
    content: '';
    position: absolute;
    left: -2.5rem;
    top: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #C5B9A8;
    z-index: 1;
    transition: border-color 0.2s;
}
.itinerary-step:first-child::before {
    background: #8B7355;
    border-color: #8B7355;
    box-shadow: 0 0 0 4px rgba(139,115,85,0.15);
}
.itinerary-step:last-child::before {
    background: #C5B9A8;
    border-color: #C5B9A8;
    box-shadow: 0 0 0 4px rgba(197,185,168,0.15);
}
.step-time {
    font-family: 'Inter', sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
    color: #8B7355;
    letter-spacing: 0.03em;
    margin-bottom: 0.15rem;
}
.step-title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.3rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.15rem;
    line-height: 1.25;
}
.step-duration {
    display: inline-block;
    font-size: 0.72rem;
    color: #999;
    background: rgba(139,115,85,0.06);
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
    margin-bottom: 0.4rem;
}
.step-desc {
    font-size: 0.88rem;
    color: #666;
    line-height: 1.65;
}
.step-image {
    margin-top: 0.6rem;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.step-image img {
    width: 100%;
    height: auto;
    display: block;
}

/* ── Commentaires ── */
.itinerary-comments {
    margin-top: 2.5rem;
    padding-top: 1.5rem;
}
.comment-item {
    padding: 1rem 0;
    border-bottom: 1px solid #f0ebe4;
}
.comment-item:last-child { border-bottom: none; }
.comment-author {
    font-weight: 600;
    font-size: 0.9rem;
    color: #2c3e50;
}
.comment-date {
    font-size: 0.72rem;
    color: #bbb;
    margin-left: 0.5rem;
}
.comment-text {
    font-size: 0.88rem;
    color: #666;
    line-height: 1.6;
    margin-top: 0.25rem;
}
.comment-form {
    margin-top: 1.5rem;
    padding: 1.25rem;
    background: #fafaf8;
    border-radius: 10px;
    border: 1px solid #e8e0d8;
}
.comment-form input,
.comment-form textarea {
    width: 100%;
    padding: 0.6rem 0.75rem;
    border: 1px solid #d0c8be;
    border-radius: 6px;
    font-family: inherit;
    font-size: 0.88rem;
    margin-bottom: 0.75rem;
    box-sizing: border-box;
}
.comment-form textarea { resize: vertical; min-height: 80px; }
.comment-form button {
    background: #8B7355;
    color: #fff;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
}
.comment-form button:hover { background: #7a6448; }

/* ── Footer ── */
.itinerary-footer {
    text-align: center;
    margin-top: 2rem;
    padding: 1.5rem;
    background: #88A398;
    border-radius: 12px;
}
.itinerary-footer-logo {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.1rem;
    font-weight: 500;
    color: #fff;
    margin-bottom: 0.25rem;
}
.itinerary-footer-logo a {
    color: #fff;
    text-decoration: none;
}
.itinerary-footer-sub {
    font-size: 0.78rem;
    color: rgba(255,255,255,0.8);
}
.itinerary-footer-sub a {
    color: #fff;
    text-decoration: underline;
    text-underline-offset: 2px;
}

/* ── Mobile ── */
@media (max-width: 480px) {
    .itinerary-page { padding: 0 1rem 2rem; }
    .itinerary-hero { padding: 25vh 0.5rem 1.5rem; }
    .itinerary-hero h1 { font-size: 1.6rem; }
    .itinerary-hero-stats { gap: 1.5rem; }
    .step-title { font-size: 1.15rem; }
    #itinerary-map { height: 240px; }
    .itinerary-timeline { padding-left: 2.2rem; }
    .itinerary-step::before { left: -2.2rem; }
}
</style>

<div class="itinerary-page">

    <!-- ═══ Hero ═══ -->
    <header class="itinerary-hero">
        <div class="itinerary-hero-label"><?= $itLang === 'en' ? 'Day Trip' : 'Itinéraire du jour' ?></div>
        <h1><?= htmlspecialchars($originClean) ?> <span class="route-arrow">→</span> <?= htmlspecialchars($destClean) ?></h1>
        <div class="itinerary-hero-meta">
            <?php if ($date): ?>
            <strong><?= $dayName ?> <?= $date ?></strong> ·
            <?php endif; ?>
            <?= $itLang === 'en' ? 'Prepared for' : 'Préparé pour' ?> <strong><?= htmlspecialchars($itinerary['guest_name']) ?></strong>
        </div>
        <?php if (!empty($itinerary['intro_text'])): ?>
        <p class="itinerary-hero-intro"><?= nl2br(htmlspecialchars($itinerary['intro_text'])) ?></p>
        <?php endif; ?>
        <div class="itinerary-hero-stats">
            <div class="itinerary-hero-stat">
                <div class="itinerary-hero-stat-value"><?= count($steps) ?></div>
                <div class="itinerary-hero-stat-label"><?= $itLang === 'en' ? 'stops' : 'étapes' ?></div>
            </div>
            <div class="itinerary-hero-stat">
                <?php
                $firstTime = $steps[0]['time_label'] ?? '';
                $lastTime  = end($steps)['time_label'] ?? '';
                $firstClean = preg_replace('/[^0-9:]/', '', str_replace(['AM','PM','h'], [':00',':00',':'], $firstTime));
                $lastClean  = preg_replace('/[^0-9:]/', '', str_replace(['AM','PM','h'], [':00',':00',':'], $lastTime));
                ?>
                <div class="itinerary-hero-stat-value"><?= htmlspecialchars(trim($firstTime, '~')) ?></div>
                <div class="itinerary-hero-stat-label"><?= $itLang === 'en' ? 'departure' : 'départ' ?></div>
            </div>
            <div class="itinerary-hero-stat">
                <div class="itinerary-hero-stat-value"><?= htmlspecialchars(trim($lastTime, '~')) ?></div>
                <div class="itinerary-hero-stat-label"><?= $itLang === 'en' ? 'arrival' : 'arrivée' ?></div>
            </div>
        </div>
    </header>

    <!-- ═══ Carte ═══ -->
    <?php if ($hasMap): ?>
    <div class="itinerary-map-wrap">
        <div id="itinerary-map"></div>
        <div class="itinerary-map-actions">
            <a href="<?= htmlspecialchars($gmapsUrl) ?>" target="_blank" rel="noopener" class="btn-maps">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <?= $itLang === 'en' ? 'Open in Google Maps' : 'Ouvrir dans Google Maps' ?>
            </a>
            <button type="button" class="btn-maps-outline" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($gmapsUrl) ?>').then(()=>this.textContent='<?= $itLang === 'en' ? 'Copied!' : 'Copié !' ?>')">
                <?= $itLang === 'en' ? 'Copy link' : 'Copier le lien' ?>
            </button>
        </div>
    </div>
    <?php endif; ?>

    <!-- ═══ Timeline ═══ -->
    <div class="itinerary-section-title"><?= $itLang === 'en' ? 'Your route, step by step' : 'Votre parcours, étape par étape' ?></div>
    <div class="itinerary-timeline">
        <?php foreach ($steps as $step): ?>
        <div class="itinerary-step">
            <?php if (!empty($step['time_label'])): ?>
            <div class="step-time"><?= htmlspecialchars($step['time_label']) ?></div>
            <?php endif; ?>
            <?php if (!empty($step['link'])): ?>
            <a href="<?= htmlspecialchars($step['link']) ?>" target="_blank" rel="noopener" class="step-title" style="text-decoration:none;color:#2c3e50"><?= htmlspecialchars($step['title']) ?> <span style="font-size:0.7em;color:#8B7355">&#x2197;</span></a>
            <?php else: ?>
            <div class="step-title"><?= htmlspecialchars($step['title']) ?></div>
            <?php endif; ?>
            <?php if (!empty($step['duration'])): ?>
            <div class="step-duration"><?= htmlspecialchars($step['duration']) ?></div>
            <?php endif; ?>
            <?php if (!empty($step['description'])): ?>
            <p class="step-desc"><?= nl2br(htmlspecialchars($step['description'])) ?></p>
            <?php endif; ?>
            <?php if (!empty($step['image'])): ?>
            <div class="step-image">
                <?= \ImageService::img($step['image'], htmlspecialchars($step['title']), 600, 300) ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- ═══ Commentaires ═══ -->
    <div class="itinerary-comments" id="comments">
        <div class="itinerary-section-title"><?= $itLang === 'en' ? 'Guest comments' : 'Commentaires' ?></div>

        <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $c): ?>
        <div class="comment-item">
            <span class="comment-author"><?= htmlspecialchars($c['guest_name']) ?></span>
            <span class="comment-date"><?= date('d/m/Y', strtotime($c['created_at'])) ?></span>
            <p class="comment-text"><?= nl2br(htmlspecialchars($c['message'])) ?></p>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <form class="comment-form" method="post" action="/itineraire/<?= htmlspecialchars($itinerary['slug']) ?>/comment">
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <input type="text" name="guest_name" placeholder="<?= $itLang === 'en' ? 'Your name' : 'Votre nom' ?>" required>
            <textarea name="message" placeholder="<?= $itLang === 'en' ? 'Leave a comment or a thank you note...' : 'Laissez un commentaire ou un mot de remerciement...' ?>" required></textarea>
            <button type="submit"><?= $itLang === 'en' ? 'Send' : 'Envoyer' ?></button>
        </form>
    </div>

    <!-- ═══ Footer ═══ -->
    <footer class="itinerary-footer">
        <div class="itinerary-footer-logo"><a href="<?= APP_URL ?>">Villa Plaisance</a></div>
        <div class="itinerary-footer-sub">
            <?= $itLang === 'en'
                ? 'Prepared with care by <a href="' . APP_URL . '/votre-hote">Jorge Canete</a> from <a href="' . APP_URL . '">La Villa Plaisance</a>'
                : 'Préparé avec soin par <a href="' . APP_URL . '/votre-hote">Jorge Canete</a>, <a href="' . APP_URL . '">La Villa Plaisance</a>' ?>
        </div>
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

    L.polyline(latlngs, {
        color: '#8B7355',
        weight: 3,
        opacity: 0.7,
        dashArray: '8, 6'
    }).addTo(map);

    var bounds = L.latLngBounds(latlngs);
    map.fitBounds(bounds, { padding: [35, 35] });
})();
</script>
<?php endif; ?>
