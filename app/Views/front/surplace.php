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
        <nav class="category-nav" aria-label="Filtrer par catégorie">
            <a href="<?= LangService::url('sur-place') ?>" class="category-tag active">Tous</a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= LangService::url('sur-place') ?>?cat=<?= urlencode($cat) ?>" class="category-tag"><?= htmlspecialchars($cat) ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</section>
<?php endif; ?>

<!-- Liste articles sur place -->
<section class="section">
    <div class="container">
        <?php if (empty($articles)): ?>
        <p class="text-center text-muted">Aucune fiche publiée pour le moment.</p>
        <?php else: ?>
        <div class="articles-grid articles-grid-large">
            <?php foreach ($articles as $article): ?>
            <article class="article-card">
                <div class="article-image">
                    <?= ImageService::img($article['cover_image'] ?? 'surplace-placeholder.webp', htmlspecialchars($article['title']), 800, 500) ?>
                </div>
                <div class="article-body">
                    <?php if (!empty($article['category'])): ?>
                    <span class="article-category"><?= htmlspecialchars($article['category']) ?></span>
                    <?php endif; ?>
                    <h2><a href="/sur-place/<?= htmlspecialchars($article['slug']) ?>"><?= htmlspecialchars($article['title']) ?></a></h2>
                    <p><?= htmlspecialchars($article['excerpt'] ?? '') ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
