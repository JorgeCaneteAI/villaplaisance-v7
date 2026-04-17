<?php
/**
 * Vue : liste filtrable des réservations.
 * @var array $reservations
 * @var array $filters
 * @var array $proprietes
 * @var array $sources
 * @var array $statuts
 */
?>
<div class="liste">
    <header class="liste__header">
        <h1>Réservations — <?= count($reservations) ?> résultat<?= count($reservations) > 1 ? 's' : '' ?></h1>
        <div class="liste__toolbar">
            <a href="/admin/calendrier" class="btn">Calendrier</a>
            <a href="/admin/calendrier/saisie" class="btn btn-primary">+ Nouvelle résa</a>
        </div>
    </header>

    <form method="get" class="filtres">
        <select name="propriete">
            <option value="">Toutes les propriétés</option>
            <?php foreach ($proprietes as $code => $nom): ?>
                <option value="<?= htmlspecialchars($code) ?>" <?= $filters['propriete'] === $code ? 'selected' : '' ?>>
                    <?= htmlspecialchars($nom) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="source">
            <option value="">Toutes les sources</option>
            <?php foreach (array_keys($sources) as $s): ?>
                <option value="<?= htmlspecialchars($s) ?>" <?= $filters['source'] === $s ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
            <?php endforeach; ?>
        </select>

        <select name="statut">
            <option value="">Tous les statuts</option>
            <?php foreach ($statuts as $s): ?>
                <option value="<?= htmlspecialchars($s) ?>" <?= $filters['statut'] === $s ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="month" name="mois" value="<?= htmlspecialchars($filters['mois']) ?>" title="Mois d'arrivée">
        <input type="search" name="search" placeholder="Nom client..." value="<?= htmlspecialchars($filters['search']) ?>">

        <button type="submit" class="btn">Filtrer</button>
        <a href="/admin/calendrier/liste" class="btn">Réinitialiser</a>
    </form>

    <table class="table-resa">
        <thead>
            <tr>
                <th>Code</th>
                <th>Client</th>
                <th>Propriété</th>
                <th>Source</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Nuits</th>
                <th>Occ.</th>
                <th>Statut</th>
                <th>Montant</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $r): ?>
                <?php
                $color = $sources[$r['source']] ?? ['bg' => '#888', 'text' => '#fff'];
                $totalOcc = (int) $r['adultes'] + (int) $r['enfants'] + (int) $r['bebes'];
                ?>
                <tr>
                    <td><code><?= htmlspecialchars($r['code']) ?></code></td>
                    <td><?= htmlspecialchars($r['nom_client']) ?></td>
                    <td><?= htmlspecialchars($r['propriete']) ?></td>
                    <td><span class="badge" style="background: <?= htmlspecialchars($color['bg']) ?>; color: <?= htmlspecialchars($color['text']) ?>">
                        <?= htmlspecialchars($r['source']) ?>
                    </span></td>
                    <td><?= htmlspecialchars($r['arrivee']) ?></td>
                    <td><?= htmlspecialchars($r['depart']) ?></td>
                    <td><?= (int) $r['duree'] ?></td>
                    <td title="Adultes · Enfants · Bébés · Animaux"><?= $totalOcc ?><?= (int) $r['animaux'] ? ' +' . (int) $r['animaux'] . '🐾' : '' ?></td>
                    <td><?= htmlspecialchars($r['statut']) ?></td>
                    <td class="montant"><?= $r['montant'] !== null ? number_format((float) $r['montant'], 2, ',', ' ') . ' €' : '—' ?></td>
                    <td><a href="/admin/calendrier/saisie/<?= (int) $r['id'] ?>" class="btn btn-sm">Éditer</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($reservations)): ?>
                <tr><td colspan="11" class="empty">Aucune réservation ne correspond à ces filtres.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.liste__header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px; }
.liste__header h1 { margin: 0; }
.liste__toolbar { display: flex; gap: 8px; }
.filtres { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; padding: 12px; background: #f5f5f5; border-radius: 6px; }
.filtres select, .filtres input { padding: 6px; border: 1px solid #ccc; border-radius: 4px; font-size: 13px; }
.table-resa { width: 100%; border-collapse: collapse; font-size: 13px; }
.table-resa th, .table-resa td { padding: 8px 6px; text-align: left; border-bottom: 1px solid #eee; }
.table-resa th { background: #2C2C2A; color: #fff; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
.table-resa tbody tr:hover { background: #fafafa; }
.table-resa .badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 11px; font-weight: 600; }
.table-resa .montant { text-align: right; white-space: nowrap; }
.table-resa .empty { text-align: center; padding: 24px; color: #888; font-style: italic; }
.btn-sm { padding: 4px 10px; font-size: 12px; }
@media (max-width: 900px) {
    .table-resa { font-size: 12px; }
    .table-resa th, .table-resa td { padding: 6px 4px; }
}
</style>
