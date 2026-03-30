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
    <div class="hero-content">
        <?php
            $fullTitle = htmlspecialchars($title);
            $splitPos = mb_strpos($fullTitle, '—');
            $bigPart = $splitPos !== false ? trim(mb_substr($fullTitle, 0, $splitPos)) : $fullTitle;
            $smallPart = $splitPos !== false ? trim(mb_substr($fullTitle, $splitPos + 1)) : '';
        ?>
        <div class="hero-title-wrap">
            <h1 class="hero-title"><?php
                $bigWords = explode(' ', $bigPart);
                foreach ($bigWords as $i => $w) {
                    $cls = $i % 2 === 0 ? 'word word-sans' : 'word word-serif';
                    echo '<span class="' . $cls . '">' . $w . '</span> ';
                }
            ?></h1>
        </div>
        <div class="hero-badges">
            <span class="hero-badge">Bédarrides, au c&oelig;ur du Triangle d'Or</span>
            <span class="hero-badge-sep">&middot;</span>
            <span class="hero-badge"><strong>9.4</strong>/10 Booking</span>
            <span class="hero-badge-sep">&middot;</span>
            <span class="hero-badge">Superhost Airbnb</span>
        </div>
        <?php if ($smallPart): ?>
        <div class="hero-title-sub"><?= $smallPart ?></div>
        <?php endif; ?>
        <?php if ($cta_text && $cta_url): ?>
        <div class="hero-cta">
            <a href="<?= htmlspecialchars($cta_url) ?>" class="btn-primary"><?= htmlspecialchars($cta_text) ?></a>
        </div>
        <?php endif; ?>
    </div>
    <div class="hero-scroll" aria-hidden="true">
        <span>Scroll</span>
        <div class="hero-scroll-line"></div>
    </div>
</section>
<?php endif; ?>
