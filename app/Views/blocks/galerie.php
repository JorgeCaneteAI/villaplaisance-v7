<?php
$heading = $heading ?? '';
$images = $images ?? [];
if (empty($images)) {
    $images = [
        ['src' => 'jardin-1.webp', 'alt' => 'Jardin provençal'],
        ['src' => 'piscine-2.webp', 'alt' => 'Piscine'],
        ['src' => 'terrasse-1.webp', 'alt' => 'Terrasse'],
        ['src' => 'oliviers-1.webp', 'alt' => 'Oliviers'],
        ['src' => 'potager-1.webp', 'alt' => 'Potager'],
        ['src' => 'nuit-1.webp', 'alt' => 'Villa de nuit'],
    ];
}
$uid = 'carousel-' . substr(md5(json_encode($images)), 0, 6);
?>
<section class="section" id="galerie">
    <div class="container">
        <?php if ($heading): ?>
        <div class="carousel-header">
            <h2><?= htmlspecialchars($heading) ?></h2>
            <div class="carousel-nav" aria-label="Navigation galerie">
                <button class="carousel-btn carousel-prev" data-carousel="<?= $uid ?>" aria-label="Précédent">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button class="carousel-btn carousel-next" data-carousel="<?= $uid ?>" aria-label="Suivant">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </div>
        <?php endif; ?>
        <div class="carousel" id="<?= $uid ?>" role="region" aria-label="Galerie photos">
            <div class="carousel-track">
                <?php foreach ($images as $img):
                    $src = is_array($img) ? ($img['src'] ?? '') : $img;
                    $alt = is_array($img) ? ($img['alt'] ?? 'Villa Plaisance') : 'Villa Plaisance';
                ?>
                <div class="carousel-slide">
                    <?= ImageService::img($src, htmlspecialchars($alt), 800, 600) ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="carousel-counter" data-carousel="<?= $uid ?>">
            <span class="carousel-current">01</span>
            <span class="carousel-separator">/</span>
            <span class="carousel-total"><?= str_pad((string)count($images), 2, '0', STR_PAD_LEFT) ?></span>
        </div>
    </div>
</section>
