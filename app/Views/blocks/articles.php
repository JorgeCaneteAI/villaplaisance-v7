<?php
$heading = $heading ?? t('home.journal.title');
$type = $type ?? 'journal';
$limit = $limit ?? 3;
$lang = LangService::get();

$articles = [];
try {
    $articles = Database::fetchAll(
        "SELECT * FROM vp_articles WHERE type = ? AND lang = ? AND status = 'published' ORDER BY published_at DESC LIMIT " . (int)$limit,
        [$type, $lang]
    );
} catch (\Throwable) {}
if (empty($articles)) return;

$baseUrl = $type === 'journal' ? '/journal/' : '/sur-place/';
?>
<section class="section section-alt" id="journal">
    <div class="container">
        <h2><?= htmlspecialchars($heading) ?></h2>
        <div class="articles-grid">
            <?php foreach ($articles as $article): ?>
            <article class="article-card">
                <div class="article-image">
                    <?= ImageService::img($article['cover_image'] ?? 'journal-placeholder.webp', htmlspecialchars($article['title']), 800, 500) ?>
                </div>
                <div class="article-body">
                    <?php if (!empty($article['category'])): ?>
                    <span class="article-category"><?= htmlspecialchars($article['category']) ?></span>
                    <?php endif; ?>
                    <h3><a href="<?= $baseUrl . htmlspecialchars($article['slug']) ?>"><?= htmlspecialchars($article['title']) ?></a></h3>
                    <p><?= htmlspecialchars($article['excerpt'] ?? '') ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <p class="text-center" style="margin-top:var(--space-6)">
            <a href="<?= LangService::url($type === 'journal' ? 'journal' : 'sur-place') ?>" class="btn-secondary"><?= t('readmore') ?></a>
        </p>
    </div>
</section>
