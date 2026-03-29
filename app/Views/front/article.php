<!-- Breadcrumb -->
<nav class="breadcrumb" aria-label="Fil d'Ariane">
    <div class="container">
        <ol>
            <li><a href="<?= LangService::url('accueil') ?>"><?= t('nav.home') ?></a></li>
            <?php if (($article['type'] ?? '') === 'sur-place'): ?>
            <li><a href="<?= LangService::url('sur-place') ?>"><?= t('nav.surplace') ?></a></li>
            <?php else: ?>
            <li><a href="<?= LangService::url('journal') ?>"><?= t('nav.journal') ?></a></li>
            <?php endif; ?>
            <li aria-current="page"><?= htmlspecialchars($article['title']) ?></li>
        </ol>
    </div>
</nav>

<!-- Article -->
<article class="section article-full">
    <div class="container container-narrow">
        <header class="article-header">
            <?php if (!empty($article['category'])): ?>
            <span class="article-category"><?= htmlspecialchars($article['category']) ?></span>
            <?php endif; ?>
            <h1><?= htmlspecialchars($article['title']) ?></h1>
            <?php if (!empty($article['excerpt'])): ?>
            <p class="article-excerpt"><?= htmlspecialchars($article['excerpt']) ?></p>
            <?php endif; ?>
            <div class="article-meta">
                <?php if (!empty($article['published_at'])): ?>
                <time datetime="<?= $article['published_at'] ?>"><?= t('published_on', ['date' => date('d/m/Y', strtotime($article['published_at']))]) ?></time>
                <?php endif; ?>
            </div>
        </header>

        <!-- Cover image -->
        <?php if (!empty($article['cover_image'])): ?>
        <div class="article-cover">
            <?= ImageService::img($article['cover_image'], htmlspecialchars($article['title']), 1200, 630) ?>
        </div>
        <?php endif; ?>

        <!-- Content blocks -->
        <div class="article-content">
            <?php if (!empty($contentBlocks)): ?>
                <?php foreach ($contentBlocks as $block): ?>
                    <?php if (is_string($block)): ?>
                        <?= $block ?>
                    <?php elseif (is_array($block)): ?>
                        <?php if (($block['type'] ?? '') === 'heading'): ?>
                            <h2><?= htmlspecialchars($block['text'] ?? '') ?></h2>
                        <?php elseif (($block['type'] ?? '') === 'paragraph'): ?>
                            <p><?= $block['text'] ?? '' ?></p>
                        <?php elseif (($block['type'] ?? '') === 'image'): ?>
                            <figure>
                                <?= ImageService::img($block['src'] ?? '', htmlspecialchars($block['alt'] ?? ''), 1200, 800) ?>
                                <?php if (!empty($block['caption'])): ?>
                                <figcaption><?= htmlspecialchars($block['caption']) ?></figcaption>
                                <?php endif; ?>
                            </figure>
                        <?php elseif (($block['type'] ?? '') === 'quote'): ?>
                            <blockquote><p><?= htmlspecialchars($block['text'] ?? '') ?></p></blockquote>
                        <?php elseif (($block['type'] ?? '') === 'list'): ?>
                            <ul>
                                <?php foreach (($block['items'] ?? []) as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?= nl2br(htmlspecialchars($article['content'] ?? '')) ?></p>
            <?php endif; ?>
        </div>

        <!-- Back -->
        <footer class="article-footer">
            <?php if (($article['type'] ?? '') === 'sur-place'): ?>
            <a href="<?= LangService::url('sur-place') ?>" class="btn-secondary">&larr; <?= t('back') ?></a>
            <?php else: ?>
            <a href="<?= LangService::url('journal') ?>" class="btn-secondary">&larr; <?= t('back') ?></a>
            <?php endif; ?>
        </footer>
    </div>
</article>
