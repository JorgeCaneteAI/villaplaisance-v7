<?php if (!$profile) return; ?>

<!-- Hero compact -->
<section class="hero hero-page hero-compact">
    <div class="container">
        <h1><?= htmlspecialchars($profile['name']) ?></h1>
        <p class="hero-subtitle"><?= htmlspecialchars($profile['subtitle']) ?></p>
    </div>
</section>

<!-- Portrait + Intro -->
<section class="section hote-intro">
    <div class="container">
        <div class="hote-intro-grid">
            <?php if (!empty($profile['photo'])): ?>
            <div class="hote-photo">
                <?= \ImageService::img($profile['photo'], htmlspecialchars($profile['name']), 500, 600) ?>
            </div>
            <?php endif; ?>
            <div class="hote-bio">
                <p class="hote-hello"><?= $lang === 'fr' ? 'Enchanté !' : ($lang === 'es' ? '¡Encantado!' : 'Nice to meet you!') ?></p>
                <p class="hote-intro-text"><?= nl2br(htmlspecialchars($profile['intro'])) ?></p>
                <?php if (!empty($profile['quote'])): ?>
                <blockquote class="hote-quote">
                    <p><?= htmlspecialchars($profile['quote']) ?></p>
                </blockquote>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- CV Blocks -->
<?php if (!empty($blocks)): ?>
<section class="section hote-cv">
    <div class="container container-narrow">
        <div class="hote-cv-sections">
            <?php
            $langSuffix = $lang;
            foreach ($blocks as $block):
                $title = $block["title_{$langSuffix}"] ?: $block['title_fr'];
                $content = $block["content_{$langSuffix}"] ?: $block['content_fr'];
                if (empty($content)) continue;
            ?>
            <div class="hote-cv-block<?= !empty($block['image']) ? ' hote-cv-block-with-image' : '' ?>">
                <?php if (!empty($block['image'])): ?>
                <div class="hote-cv-image">
                    <?= \ImageService::img($block['image'], htmlspecialchars($title), 600, 400) ?>
                </div>
                <?php endif; ?>
                <div class="hote-cv-text">
                    <div class="hote-cv-label"><?= htmlspecialchars($title) ?></div>
                    <div class="hote-cv-content"><?= nl2br(htmlspecialchars($content)) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Reviews about Jorge -->
<?php if (!empty($reviews)): ?>
<section class="section section-alt hote-reviews">
    <div class="container">
        <h2 class="text-center"><?= $lang === 'fr' ? 'Ce que disent les voyageurs' : ($lang === 'es' ? 'Lo que dicen los viajeros' : 'What travellers say') ?></h2>
        <div class="hote-reviews-grid">
            <?php foreach ($reviews as $review): ?>
            <blockquote class="hote-review-card">
                <p class="hote-review-text"><?= preg_replace('/\b(Jorge|Georges|George)\b/iu', '<strong>$1</strong>', htmlspecialchars($review['content'])) ?></p>
                <footer>
                    <cite><?= htmlspecialchars($review['author']) ?></cite>
                    <?php if (!empty($review['platform'])): ?>
                    <span class="hote-review-platform"><?= htmlspecialchars($review['platform']) ?></span>
                    <?php endif; ?>
                </footer>
            </blockquote>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
