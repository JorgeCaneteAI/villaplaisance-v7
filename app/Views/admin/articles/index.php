<div class="page-header">
    <h1>Articles</h1>
    <a href="/admin/articles/create" class="btn btn-primary">Nouvel article</a>
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
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
            <tr>
                <td><a href="/admin/articles/<?= $article['id'] ?>/edit"><?= htmlspecialchars(mb_substr($article['title'], 0, 60)) ?></a></td>
                <td><span class="badge badge-info"><?= htmlspecialchars($article['type']) ?></span></td>
                <td class="text-sm"><?= htmlspecialchars($article['category'] ?? '') ?></td>
                <td>
                    <?php if ($article['status'] === 'published'): ?>
                    <span class="badge badge-success">Publié</span>
                    <?php else: ?>
                    <span class="badge badge-warning">Brouillon</span>
                    <?php endif; ?>
                </td>
                <td class="text-sm text-muted"><?= $article['published_at'] ? date('d/m/Y', strtotime($article['published_at'])) : '—' ?></td>
                <td>
                    <div class="btn-group">
                        <a href="/admin/articles/<?= $article['id'] ?>/edit" class="btn btn-sm">Modifier</a>
                        <form method="POST" action="/admin/articles/<?= $article['id'] ?>/delete" onsubmit="return confirm('Supprimer cet article ?')">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Suppr.</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
