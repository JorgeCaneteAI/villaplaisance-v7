<div class="page-header">
    <h1>Articles</h1>
    <div class="btn-group">
        <a href="/admin/articles/create?type=journal" class="btn btn-primary">+ Journal</a>
        <a href="/admin/articles/create?type=sur-place" class="btn btn-primary">+ Sur place</a>
    </div>
</div>

<div class="tab-nav">
    <a href="/admin/articles" class="tab-link <?= $type === 'all' ? 'active' : '' ?>">Tous</a>
    <a href="/admin/articles?type=journal" class="tab-link <?= $type === 'journal' ? 'active' : '' ?>">Journal</a>
    <a href="/admin/articles?type=sur-place" class="tab-link <?= $type === 'sur-place' ? 'active' : '' ?>">Sur place</a>
</div>

<?php if (empty($articles)): ?>
<p class="text-muted">Aucun article.</p>
<?php else: ?>
<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Type</th>
                <th>Catégorie</th>
                <th>Langues</th>
                <th>Statut</th>
                <th>SEO</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article):
                $langCount = $translationCounts[$article['slug']] ?? 1;
                $hasSeo = !empty($article['meta_title']) && !empty($article['meta_desc']);
            ?>
            <tr>
                <td>
                    <a href="/admin/articles/<?= $article['id'] ?>/edit" class="article-title-link">
                        <?= htmlspecialchars(mb_substr($article['title'], 0, 50)) ?>
                    </a>
                    <?php if (!empty($article['cover_image'])): ?>
                    <span style="color:var(--accent);font-size:0.65rem" title="Image de couverture">📷</span>
                    <?php endif; ?>
                </td>
                <td><span class="badge <?= $article['type'] === 'journal' ? 'badge-info' : 'badge-accent' ?>"><?= htmlspecialchars($article['type']) ?></span></td>
                <td class="text-sm"><?= htmlspecialchars($article['category'] ?? '—') ?></td>
                <td>
                    <span class="badge <?= $langCount >= 3 ? 'badge-success' : ($langCount >= 2 ? 'badge-warning' : 'badge-danger') ?>">
                        <?= $langCount ?>/3
                    </span>
                </td>
                <td>
                    <?php if ($article['status'] === 'published'): ?>
                    <span class="badge badge-success">Publié</span>
                    <?php else: ?>
                    <span class="badge badge-warning">Brouillon</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($hasSeo): ?>
                    <span class="badge badge-success" title="Meta title + desc renseignés">OK</span>
                    <?php else: ?>
                    <span class="badge badge-danger" title="SEO incomplet">!</span>
                    <?php endif; ?>
                </td>
                <td class="text-sm text-muted"><?= $article['published_at'] ? date('d/m/Y', strtotime($article['published_at'])) : '—' ?></td>
                <td>
                    <div class="btn-group">
                        <a href="/admin/articles/<?= $article['id'] ?>/edit" class="btn btn-sm">Éditer</a>
                        <form method="POST" action="/admin/articles/<?= $article['id'] ?>/delete" onsubmit="return confirm('Supprimer cet article et toutes ses traductions ?')">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                            <button type="submit" class="btn btn-sm btn-danger">✕</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
