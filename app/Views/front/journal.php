<!-- Breadcrumb -->
<nav class="breadcrumb" aria-label="Fil d'Ariane">
    <div class="container">
        <ol>
            <li><a href="<?= LangService::url('accueil') ?>"><?= t('nav.home') ?></a></li>
            <li aria-current="page"><?= t('nav.journal') ?></li>
        </ol>
    </div>
</nav>

<?php
// Render hero from CMS
$heroSections = BlockService::getSections('journal', $lang);
foreach ($heroSections as $section) {
    echo BlockService::renderBlock($section);
}
?>

<!-- Filtres catégories -->
<?php if (!empty($categories)): ?>
<section class="section-compact">
    <div class="container">
        <nav class="category-nav" aria-label="Filtrer par catégorie">
            <a href="<?= LangService::url('journal') ?>" class="category-tag active">Tous</a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= LangService::url('journal') ?>?cat=<?= urlencode($cat) ?>" class="category-tag"><?= htmlspecialchars($cat) ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</section>
<?php endif; ?>

<!-- Liste articles -->
<section class="section section-magazine">
    <div class="container">
        <?php if (empty($articles)): ?>
        <p class="text-center text-muted">Aucun article publié pour le moment.</p>
        <?php else: ?>
        <div class="magazine-grid">
            <?php foreach ($articles as $i => $article): ?>
            <a href="/journal/<?= htmlspecialchars($article['slug']) ?>" class="mag-card<?= $i === 0 ? ' mag-card-featured' : '' ?>">
                <div class="mag-card-image">
                    <?= ImageService::img($article['cover_image'] ?? 'journal-placeholder.webp', htmlspecialchars($article['title']), ($i === 0 ? 1200 : 800), ($i === 0 ? 700 : 500)) ?>
                </div>
                <div class="mag-card-overlay"></div>
                <div class="mag-card-content">
                    <?php if (!empty($article['category'])): ?>
                    <span class="mag-card-tag"><?= htmlspecialchars($article['category']) ?></span>
                    <?php endif; ?>
                    <h2 class="mag-card-title"><?= htmlspecialchars($article['title']) ?></h2>
                    <p class="mag-card-excerpt"><?= htmlspecialchars($article['excerpt'] ?? '') ?></p>
                    <?php if (!empty($article['published_at'])): ?>
                    <time class="mag-card-date" datetime="<?= $article['published_at'] ?>"><?= date('d M Y', strtotime($article['published_at'])) ?></time>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
