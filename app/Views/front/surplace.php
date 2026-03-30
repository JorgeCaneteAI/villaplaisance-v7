<!-- Breadcrumb -->
<nav class="breadcrumb" aria-label="Fil d'Ariane">
    <div class="container">
        <ol>
            <li><a href="<?= LangService::url('accueil') ?>"><?= t('nav.home') ?></a></li>
            <li aria-current="page"><?= t('nav.surplace') ?></li>
        </ol>
    </div>
</nav>

<?php
// Render hero from CMS
$heroSections = BlockService::getSections('sur-place', $lang);
foreach ($heroSections as $section) {
    echo BlockService::renderBlock($section);
}
?>

<!-- Filtres catégories -->
<?php if (!empty($categories)): ?>
<section class="section-compact">
    <div class="container">
        <nav class="category-nav category-nav-surplace" aria-label="Filtrer par catégorie">
            <a href="<?= LangService::url('sur-place') ?>" class="category-tag active">Tous</a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= LangService::url('sur-place') ?>?cat=<?= urlencode($cat) ?>" class="category-tag"><?= htmlspecialchars($cat) ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</section>
<?php endif; ?>

<!-- Liste articles sur place -->
<section class="section section-magazine">
    <div class="container">
        <?php if (empty($articles)): ?>
        <p class="text-center text-muted">Aucune fiche publiée pour le moment.</p>
        <?php else: ?>
        <div class="magazine-grid magazine-surplace">
            <?php foreach ($articles as $i => $article): ?>
            <a href="/sur-place/<?= htmlspecialchars($article['slug']) ?>" class="mag-card<?= $i === 0 ? ' mag-card-featured' : '' ?>">
                <div class="mag-card-image">
                    <?= ImageService::img($article['cover_image'] ?? 'surplace-placeholder.webp', htmlspecialchars($article['title']), ($i === 0 ? 1200 : 800), ($i === 0 ? 700 : 500)) ?>
                </div>
                <div class="mag-card-overlay"></div>
                <div class="mag-card-content">
                    <?php if (!empty($article['category'])): ?>
                    <span class="mag-card-tag"><?= htmlspecialchars($article['category']) ?></span>
                    <?php endif; ?>
                    <h2 class="mag-card-title"><?= htmlspecialchars($article['title']) ?></h2>
                    <p class="mag-card-excerpt"><?= htmlspecialchars($article['excerpt'] ?? '') ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
