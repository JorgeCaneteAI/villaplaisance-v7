<?php
$heading = $heading ?? t('chambres.breakfast.title');
$text = $text ?? 'Chaque matin, le petit-déjeuner est préparé avec des produits locaux et de saison. Confitures maison, fruits frais, pain de boulanger, fromages et charcuteries du terroir. Servi en terrasse quand le temps le permet.';
$image = $image ?? 'petit-dejeuner.webp';

$pdImages = is_array($image) ? $image : [$image];
$pdImages = array_filter($pdImages);
if (empty($pdImages)) $pdImages = ['petit-dejeuner.webp'];
?>
<section class="section" id="petit-dejeuner">
    <div class="container prose-section">
        <div class="prose-grid">
            <div class="prose-text">
                <h2 class="prose-heading"><?= htmlspecialchars($heading) ?></h2>
                <p><?= nl2br(htmlspecialchars($text)) ?></p>
            </div>
            <div class="prose-image">
                <?php if (count($pdImages) > 1):
                    $puid = 'pdej-carousel-' . substr(md5(json_encode($pdImages)), 0, 6);
                ?>
                <div class="carousel room-carousel" id="<?= $puid ?>" aria-label="Photos petit-dejeuner">
                    <div class="carousel-track">
                        <?php foreach ($pdImages as $img): ?>
                        <div class="carousel-slide">
                            <?= ImageService::img($img, htmlspecialchars($heading), 800, 600) ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-prev" aria-label="Precedent"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="15 18 9 12 15 6"/></svg></button>
                    <button class="carousel-next" aria-label="Suivant"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9 18 15 12 9 6"/></svg></button>
                    <div class="carousel-counter"><span class="carousel-current">01</span> / <span class="carousel-total"><?= str_pad((string)count($pdImages), 2, '0', STR_PAD_LEFT) ?></span></div>
                </div>
                <?php else: ?>
                <?= ImageService::img($pdImages[0], htmlspecialchars($heading), 800, 600) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
