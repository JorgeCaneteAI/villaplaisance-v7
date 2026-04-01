<?php declare(strict_types=1); ?>

<div class="page-header" style="display:flex;justify-content:space-between;align-items:center">
    <h1>Itinéraires personnalisés</h1>
    <a href="/admin/itineraires/create" class="btn-primary">+ Nouvel itinéraire</a>
</div>

<?php if (!empty($flash['success'])): ?>
<div class="alert alert-success"><?= htmlspecialchars($flash['success']) ?></div>
<?php endif; ?>
<?php if (!empty($flash['error'])): ?>
<div class="alert alert-error"><?= htmlspecialchars($flash['error']) ?></div>
<?php endif; ?>

<?php if (empty($itineraries)): ?>
<div class="admin-card">
    <p class="text-muted">Aucun itinéraire pour le moment. Créez-en un pour vos hôtes.</p>
</div>
<?php else: ?>
<div class="admin-card">
<table class="admin-table">
    <thead>
    <tr>
        <th>Nom de l'hôte</th>
        <th>Lien</th>
        <th>Date</th>
        <th style="text-align:center">Étapes</th>
        <th style="text-align:center">Statut</th>
        <th style="text-align:right">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($itineraries as $it):
        $url = '/itineraire/' . htmlspecialchars($it['slug']);
        $isActive = $it['status'] === 'active';
    ?>
    <tr<?= !$isActive ? ' style="opacity:0.5"' : '' ?>>
        <td><strong><?= htmlspecialchars($it['guest_name']) ?></strong></td>
        <td>
            <a href="<?= $url ?>" target="_blank" style="font-size:0.85rem;color:var(--admin-accent)"><?= $url ?></a>
            <button type="button" onclick="navigator.clipboard.writeText('<?= APP_URL . $url ?>').then(()=>alert('Lien copié !'))"
                    style="border:none;background:none;cursor:pointer;font-size:0.75rem;color:#888;margin-left:0.25rem"
                    title="Copier le lien complet">📋</button>
        </td>
        <td style="font-size:0.85rem;color:#888">
            <?= $it['itinerary_date'] ? date('d/m/Y', strtotime($it['itinerary_date'])) : '—' ?>
        </td>
        <td style="text-align:center"><?= (int)$it['step_count'] ?></td>
        <td style="text-align:center">
            <span class="analytics-badge analytics-badge-<?= $isActive ? 'green' : 'gray' ?>"><?= $isActive ? 'actif' : 'archivé' ?></span>
        </td>
        <td style="text-align:right;white-space:nowrap">
            <a href="/admin/itineraires/<?= $it['id'] ?>/edit" class="btn-secondary" style="font-size:0.8rem;padding:0.3rem 0.6rem">Modifier</a>
            <form method="post" action="/admin/itineraires/<?= $it['id'] ?>/toggle" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <button type="submit" class="btn-secondary" style="font-size:0.8rem;padding:0.3rem 0.6rem"><?= $isActive ? 'Archiver' : 'Réactiver' ?></button>
            </form>
            <form method="post" action="/admin/itineraires/<?= $it['id'] ?>/delete" style="display:inline"
                  onsubmit="return confirm('Supprimer cet itinéraire ?')">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <button type="submit" class="btn-danger" style="font-size:0.8rem;padding:0.3rem 0.6rem">Suppr.</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
