<?php
$offer = $offer ?? 'bb';
$heading = $heading ?? '';
$lang = LangService::get();

$rooms = [];
try {
    $rooms = Database::fetchAll(
        "SELECT * FROM vp_pieces WHERE offer = ? AND lang = ? ORDER BY position ASC",
        [$offer, $lang]
    );
} catch (\Throwable) {}
if (empty($rooms)) return;
?>
<section class="section section-alt" id="chambres-<?= $offer ?>">
    <div class="container">
        <?php if ($heading): ?>
        <h2><?= htmlspecialchars($heading) ?></h2>
        <?php endif; ?>
        <div class="rooms-grid">
            <?php foreach ($rooms as $room): ?>
            <div class="room-card">
                <div class="room-image">
                    <?php
                    $imgList = json_decode($room['images'] ?? '[]', true) ?: [];
                    if (empty($imgList) && !empty($room['image'])) $imgList = [$room['image']];
                    if (empty($imgList)) $imgList = [strtolower(str_replace(' ', '-', $room['name'])) . '.webp'];
                    $altText = htmlspecialchars($room['name'] . ' — Villa Plaisance');

                    if (count($imgList) > 1):
                        $cid = 'room-carousel-' . $room['id'];
                    ?>
                    <div class="carousel room-carousel" id="<?= $cid ?>" aria-label="Photos <?= htmlspecialchars($room['name']) ?>">
                        <div class="carousel-track">
                            <?php foreach ($imgList as $img): ?>
                            <div class="carousel-slide">
                                <?= ImageService::img($img, $altText, 800, 600) ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-prev" aria-label="Précédent">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <button class="carousel-next" aria-label="Suivant">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                        <div class="carousel-counter"><span class="carousel-current">01</span> / <span class="carousel-total"><?= str_pad((string)count($imgList), 2, '0', STR_PAD_LEFT) ?></span></div>
                    </div>
                    <?php else: ?>
                    <?= ImageService::img($imgList[0], $altText, 800, 600) ?>
                    <?php endif; ?>
                </div>
                <div class="room-body">
                    <h3><?= htmlspecialchars($room['name']) ?></h3>
                    <?php if (!empty($room['sous_titre'])): ?>
                    <p class="room-subtitle"><?= htmlspecialchars($room['sous_titre']) ?></p>
                    <?php endif; ?>
                    <p><?= htmlspecialchars($room['description']) ?></p>
                    <?php if (!empty($room['equip'])):
                        $equipIconMap = [
                            'lit' => 'icon-lit', 'lit double' => 'icon-lit', 'lits jumeaux' => 'icon-lit',
                            'climatisation' => 'icon-climatisation', 'clim' => 'icon-climatisation',
                            'tv' => 'icon-tv', 'télévision' => 'icon-tv',
                            'wifi' => 'icon-wifi', 'wi-fi' => 'icon-wifi',
                            'bibliothèque' => 'icon-bibliotheque',
                            'vue jardin' => 'icon-vue-jardin', 'vue sur jardin' => 'icon-vue-jardin',
                            'jardin' => 'icon-jardin',
                            'clic-clac' => 'icon-clic-clac', 'canapé' => 'icon-clic-clac',
                            'salle de bain' => 'icon-salle-de-bain', 'salle de bains' => 'icon-salle-de-bain',
                            'douche' => 'icon-douche',
                            'vintage' => 'icon-vintage',
                            'cuisine' => 'icon-cuisine', 'kitchenette' => 'icon-cuisine',
                            'parking' => 'icon-parking',
                            'barbecue' => 'icon-barbecue',
                            'linge' => 'icon-linge', 'linge de maison' => 'icon-linge', 'serviettes' => 'icon-serviette',
                            'animaux' => 'icon-animaux', 'animaux acceptés' => 'icon-animaux',
                            'piscine' => 'icon-piscine',
                            'petit-déjeuner' => 'icon-petit-dejeuner', 'petit déjeuner' => 'icon-petit-dejeuner',
                            'transat' => 'icon-transat', 'transats' => 'icon-transat',
                            'vélo' => 'icon-velo', 'vélos' => 'icon-velo',
                            'soleil' => 'icon-soleil', 'terrasse' => 'icon-soleil',
                        ];
                    ?>
                    <ul class="room-equip">
                        <?php foreach (explode(',', $room['equip']) as $eq):
                            $eqTrim = trim($eq);
                            $eqKey = mb_strtolower($eqTrim);
                            $eqIcon = $equipIconMap[$eqKey] ?? null;
                        ?>
                        <li><?php if ($eqIcon): ?><?= ImageService::icon($eqIcon, 16, 'equip-icon') ?><?php endif; ?><?= htmlspecialchars($eqTrim) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if (!empty($room['note'])): ?>
                    <p class="room-note"><?= htmlspecialchars($room['note']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
