<?php
$title = $title ?? t('site.name');
$subtitle = $subtitle ?? '';
$cta_text = $cta_text ?? '';
$cta_url = $cta_url ?? '';
$image = $image ?? 'hero.webp';
$image_alt = $image_alt ?? 'Villa Plaisance';
$compact = $compact ?? false;

// Normalize image: can be array (multi-image) or string
$heroImages = is_array($image) ? $image : [$image];
$heroImages = array_filter($heroImages);
if (empty($heroImages)) $heroImages = ['hero.webp'];
?>
<?php if ($compact): ?>
<section class="hero hero-page hero-compact">
    <div class="container">
        <h1><?= htmlspecialchars($title) ?></h1>
        <?php if ($subtitle): ?>
        <p class="hero-subtitle"><?= htmlspecialchars($subtitle) ?></p>
        <?php endif; ?>
    </div>
</section>
<?php else: ?>
<section class="hero <?= empty($cta_text) ? 'hero-page' : '' ?>">
    <div class="hero-image">
        <?php if (count($heroImages) > 1): ?>
        <div class="hero-slideshow" aria-label="Photos">
            <?php foreach ($heroImages as $i => $img): ?>
            <div class="hero-slide<?= $i === 0 ? ' active' : '' ?>">
                <?= ImageService::img($img, htmlspecialchars($image_alt), 1920, 1080, false, 'hero-img') ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <?= ImageService::img($heroImages[0], htmlspecialchars($image_alt), 1920, 1080, false, 'hero-img') ?>
        <?php endif; ?>
        <div class="hero-overlay"></div>
    </div>
    <div class="hero-content container">
        <h1 class="hero-title"><?= htmlspecialchars($title) ?></h1>
        <?php if ($subtitle): ?>
        <p class="hero-subtitle"><?= htmlspecialchars($subtitle) ?></p>
        <?php endif; ?>
        <?php if ($cta_text && $cta_url): ?>
        <a href="<?= htmlspecialchars($cta_url) ?>" class="btn-primary"><?= htmlspecialchars($cta_text) ?></a>
        <?php endif; ?>
    </div>
    <div class="hero-scroll" aria-hidden="true">
        <span>Scroll</span>
        <div class="hero-scroll-line"></div>
    </div>
</section>
<?php endif; ?>
