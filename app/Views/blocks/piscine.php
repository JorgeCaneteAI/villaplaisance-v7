<?php
$heading = $heading ?? t('villa.pool.title');
$text = $text ?? 'Piscine privée de 12 mètres sur 6, clôturée et sécurisée. Ouverte de mi-mai à fin septembre. Transats, parasols, douche extérieure. La piscine est réservée exclusivement aux locataires de la villa.';
$image = $image ?? 'piscine-villa.webp';

$poolImages = is_array($image) ? $image : [$image];
$poolImages = array_filter($poolImages);
if (empty($poolImages)) $poolImages = ['piscine-villa.webp'];
?>
<section class="section" id="piscine">
    <div class="container prose-section">
        <h2><?= htmlspecialchars($heading) ?></h2>
        <div class="prose-grid">
            <div class="prose-text">
                <p><?= nl2br(htmlspecialchars($text)) ?></p>
            </div>
            <div class="prose-image">
                <?php if (count($poolImages) > 1):
                    $puid = 'piscine-carousel-' . substr(md5(json_encode($poolImages)), 0, 6);
                ?>
                <div class="carousel room-carousel" id="<?= $puid ?>" aria-label="Photos piscine">
                    <div class="carousel-track">
                        <?php foreach ($poolImages as $img): ?>
                        <div class="carousel-slide">
                            <?= ImageService::img($img, htmlspecialchars($heading), 800, 600) ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-prev" aria-label="Precedent"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="15 18 9 12 15 6"/></svg></button>
                    <button class="carousel-next" aria-label="Suivant"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9 18 15 12 9 6"/></svg></button>
                    <div class="carousel-counter"><span class="carousel-current">01</span> / <span class="carousel-total"><?= str_pad((string)count($poolImages), 2, '0', STR_PAD_LEFT) ?></span></div>
                </div>
                <?php else: ?>
                <?= ImageService::img($poolImages[0], htmlspecialchars($heading), 800, 600) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
