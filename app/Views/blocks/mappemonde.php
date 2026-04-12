<?php
declare(strict_types=1);

$headingLines = [
    'Nos clients viennent de…',
    'Our customers come from…',
    'Nuestros clientes vienen de…',
];

$origins = [];
try {
    $rows = Database::fetchAll("SELECT origin, COUNT(*) as cnt FROM vp_reviews WHERE LENGTH(origin) > 0 AND status = 'published' GROUP BY origin ORDER BY cnt DESC");
    foreach ($rows as $r) {
        $origins[$r['origin']] = (int)$r['cnt'];
    }
} catch (\Throwable) {}

// Geocoding lookup
$geocode = [
    'France' => [46.6034, 2.3488],
    'Paris, France' => [48.8566, 2.3522],
    'Austin, Texas' => [30.2672, -97.7431],
    'Burtonsville, Maryland' => [39.1115, -76.9325],
    'Charlotte, Caroline du Nord' => [35.2271, -80.8431],
    'Costa Mesa, Californie' => [33.6412, -117.9187],
    'Géorgie, États-Unis' => [33.7490, -84.3880],
    'Maine, États-Unis' => [45.2538, -69.4455],
    'New York, États-Unis' => [40.7128, -74.0060],
    'New York, New York' => [40.7128, -74.0060],
    'San Francisco, Californie' => [37.7749, -122.4194],
    'Port Townsend, Washington' => [48.1170, -122.7604],
    'Montréal, Canada' => [45.5017, -73.5673],
    'Québec City, Canada' => [46.8139, -71.2080],
    'Allemagne' => [51.1657, 10.4515],
    'Pays-Bas' => [52.1326, 5.2913],
    'Suisse' => [46.8182, 8.2275],
    'Espagne' => [40.4168, -3.7038],
    'Grèce' => [39.0742, 21.8243],
    'Norvège' => [60.4720, 8.4689],
    'Tunisie' => [33.8869, 9.5375],
    'Sydney, Australie' => [-33.8688, 151.2093],
];

// Build pins — merge same coordinates
$coordMap = [];
foreach ($origins as $origin => $count) {
    if (isset($geocode[$origin])) {
        $key = $geocode[$origin][0] . ',' . $geocode[$origin][1];
        if (!isset($coordMap[$key])) {
            $coordMap[$key] = ['lat' => $geocode[$origin][0], 'lng' => $geocode[$origin][1], 'label' => $origin, 'count' => $count];
        } else {
            $coordMap[$key]['count'] += $count;
            if (mb_strlen($origin) < mb_strlen($coordMap[$key]['label'])) {
                $coordMap[$key]['label'] = $origin;
            }
        }
    }
}
$pins = array_values($coordMap);

$villaLat = 44.0410;
$villaLng = 4.8945;
$uniqueOrigins = count($pins);

// Build shuffled city list for display below map
$cityList = array_keys($origins);
shuffle($cityList);
?>
<section class="section section-map" id="mappemonde">
    <!-- Hero : carte pleine largeur -->
    <div class="map-hero">
        <div id="guest-map" class="guest-map"></div>
    </div>

    <!-- Texte sous le hero -->
    <div class="container">
        <h2><?= htmlspecialchars($headingLines[0]) ?><br><span class="map-heading-sub"><?= htmlspecialchars($headingLines[1]) ?><br><?= htmlspecialchars($headingLines[2]) ?></span></h2>
        <div class="map-stats">
            <span class="map-stat">plus de <strong><?= $uniqueOrigins ?></strong> destinations</span>
        </div>
        <?php if (!empty($cityList)): ?>
        <div class="map-cities">
            <?php foreach ($cityList as $i => $city): ?>
                <?php if ($i > 0): ?><span class="map-city-sep">&middot;</span><?php endif; ?>
                <span class="map-city"><?= htmlspecialchars($city) ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
    (function() {
        var map = L.map('guest-map', {
            center: [38, -15],
            zoom: 3,
            minZoom: 2,
            maxZoom: 12,
            scrollWheelZoom: false,
            zoomControl: true
        });

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        // Villa Plaisance pin — no text, just dot
        var homeIcon = L.divIcon({
            className: 'map-pin-home',
            html: '<div class="pin-home-inner"></div>',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });
        L.marker([<?= $villaLat ?>, <?= $villaLng ?>], {icon: homeIcon, zIndexOffset: 1000})
            .addTo(map)
            .bindTooltip('Villa Plaisance — Bédarrides', {direction: 'top', offset: [0, -10]});

        // Guest origin pins — no text, tooltip on hover
        var pins = <?= json_encode($pins, JSON_UNESCAPED_UNICODE) ?>;
        var bounds = [[<?= $villaLat ?>, <?= $villaLng ?>]];
        pins.forEach(function(pin) {
            var guestIcon = L.divIcon({
                className: 'map-pin-guest',
                html: '<div class="pin-guest-inner"></div>',
                iconSize: [10, 10],
                iconAnchor: [5, 5]
            });
            L.marker([pin.lat, pin.lng], {icon: guestIcon})
                .addTo(map)
                .bindTooltip(pin.label, {direction: 'top', offset: [0, -8]});
            bounds.push([pin.lat, pin.lng]);
        });

        if (bounds.length > 1) {
            map.fitBounds(bounds, {padding: [40, 40], maxZoom: 5});
        }
    })();
    </script>
</section>
