<div class="page-header">
    <h1>Pages CMS</h1>
</div>

<p class="text-muted mb-2">Gérez les blocs de contenu et les métadonnées SEO de chaque page.</p>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Page</th>
                <th>Slug (URL)</th>
                <th>Meta Title</th>
                <th class="text-center">Blocs</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $page):
                $count = $sectionCounts[$page['slug']] ?? 0;
            ?>
            <tr>
                <td><strong><?= htmlspecialchars($page['title']) ?></strong></td>
                <td><code class="text-sm">/<?= htmlspecialchars($page['slug']) ?></code></td>
                <td class="text-sm"><?= htmlspecialchars(mb_strimwidth($page['meta_title'] ?? '', 0, 50, '…')) ?></td>
                <td class="text-center">
                    <?php if ($count > 0): ?>
                    <span class="badge badge-success"><?= $count ?></span>
                    <?php else: ?>
                    <span class="badge badge-warning">0</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="/admin/sections/page/<?= htmlspecialchars($page['slug']) ?>" class="btn btn-sm">Blocs</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
