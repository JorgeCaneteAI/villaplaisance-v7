<?php
$heading = $heading ?? '';
$text = $text ?? '';
$image = $image ?? '';
$image_alt = $image_alt ?? '';
$lead = $lead ?? false;
$cta_text = $cta_text ?? '';
$cta_url = $cta_url ?? '';

// Normalize image: can be array (multi) or string
$proseImages = is_array($image) ? $image : ($image ? [$image] : []);
$proseImages = array_filter($proseImages);
$hasImages = !empty($proseImages);
?>
<section class="section">
    <div class="container prose-section">
        <?php if ($hasImages): ?>
        <div class="prose-grid">
            <div class="prose-text">
                <h2 class="prose-heading"><?= htmlspecialchars($heading) ?></h2>
                <p<?= $lead ? ' class="lead"' : '' ?>><?= nl2br(htmlspecialchars($text)) ?></p>
                <?php if ($cta_text && $cta_url): ?>
                <a href="<?= htmlspecialchars($cta_url) ?>" class="btn-primary mt-1"><?= htmlspecialchars($cta_text) ?></a>
                <?php endif; ?>
            </div>
            <div class="prose-image">
                <?php if (count($proseImages) > 1):
                    $puid = 'prose-carousel-' . substr(md5(json_encode($proseImages)), 0, 6);
                ?>
                <div class="carousel room-carousel" id="<?= $puid ?>" aria-label="Photos">
                    <div class="carousel-track">
                        <?php foreach ($proseImages as $img): ?>
                        <div class="carousel-slide">
                            <?= ImageService::img($img, htmlspecialchars($image_alt ?: $heading), 800, 600) ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-prev" aria-label="Precedent">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <button class="carousel-next" aria-label="Suivant">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                    <div class="carousel-counter"><span class="carousel-current">01</span> / <span class="carousel-total"><?= str_pad((string)count($proseImages), 2, '0', STR_PAD_LEFT) ?></span></div>
                </div>
                <?php else: ?>
                <?= ImageService::img($proseImages[0], htmlspecialchars($image_alt ?: $heading), 800, 600) ?>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <?php if ($heading): ?>
        <h2><?= htmlspecialchars($heading) ?></h2>
        <?php endif; ?>
        <p<?= $lead ? ' class="lead"' : '' ?>><?= nl2br(htmlspecialchars($text)) ?></p>
        <?php if ($cta_text && $cta_url): ?>
        <a href="<?= htmlspecialchars($cta_url) ?>" class="btn-primary mt-1"><?= htmlspecialchars($cta_text) ?></a>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
