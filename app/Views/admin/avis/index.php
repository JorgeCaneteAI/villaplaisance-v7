<div class="page-header">
    <h1>Avis clients</h1>
</div>

<?php if (empty($reviews)): ?>
<p class="text-muted">Aucun avis.</p>
<?php else: ?>
<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Auteur</th>
                <th>Plateforme</th>
                <th>Offre</th>
                <th>Note</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review): ?>
            <tr>
                <td>
                    <?= htmlspecialchars($review['author']) ?>
                    <?php if ($review['origin']): ?>
                    <br><span class="text-sm text-muted"><?= htmlspecialchars($review['origin']) ?></span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($review['platform']) ?></td>
                <td><span class="badge badge-info"><?= $review['offer'] ?></span></td>
                <td><?= $review['rating'] ?>/<?= $review['platform'] === 'Booking' ? '10' : '5' ?></td>
                <td class="text-sm text-muted"><?= $review['review_date'] ? date('m/Y', strtotime($review['review_date'])) : '—' ?></td>
                <td>
                    <?php if ($review['status'] === 'published'): ?>
                    <span class="badge badge-success">Visible</span>
                    <?php else: ?>
                    <span class="badge badge-warning">Masqué</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="btn-group">
                        <form method="POST" action="/admin/avis/<?= $review['id'] ?>/toggle">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                            <button type="submit" class="btn btn-sm"><?= $review['status'] === 'published' ? 'Masquer' : 'Publier' ?></button>
                        </form>
                        <form method="POST" action="/admin/avis/<?= $review['id'] ?>/delete" onsubmit="return confirm('Supprimer cet avis ?')">
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
