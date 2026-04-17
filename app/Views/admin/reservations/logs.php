<?php /** @var array $logs */ ?>
<div class="logs">
    <header class="logs__header">
        <h1>Logs de synchronisation iCal</h1>
        <a href="/admin/calendrier" class="btn">Retour au calendrier</a>
    </header>
    <p>Les 50 dernières exécutions (cron + manuel).</p>

    <table class="table-logs">
        <thead>
            <tr>
                <th>Début</th><th>Fin</th><th>+</th><th>~</th><th>&minus;</th><th>Trigger</th><th>Erreurs</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr class="<?= $log['errors'] ? 'row-error' : '' ?>">
                    <td><?= htmlspecialchars($log['started_at']) ?></td>
                    <td><?= htmlspecialchars($log['ended_at'] ?? '—') ?></td>
                    <td class="num"><?= (int) $log['created'] ?></td>
                    <td class="num"><?= (int) $log['updated'] ?></td>
                    <td class="num"><?= (int) $log['deleted'] ?></td>
                    <td><?= htmlspecialchars($log['triggered_by']) ?></td>
                    <td><pre><?= htmlspecialchars($log['errors'] ?? '') ?></pre></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($logs)): ?>
                <tr><td colspan="7" class="empty">Aucun log pour le moment.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.logs__header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.table-logs { width: 100%; border-collapse: collapse; font-size: 13px; }
.table-logs th, .table-logs td { padding: 6px 8px; text-align: left; border-bottom: 1px solid #eee; vertical-align: top; }
.table-logs th { background: #2C2C2A; color: #fff; font-size: 11px; text-transform: uppercase; }
.table-logs .num { text-align: right; font-variant-numeric: tabular-nums; }
.table-logs pre { white-space: pre-wrap; font-size: 11px; margin: 0; font-family: inherit; }
.table-logs .row-error { background: #fff5f5; }
.table-logs .empty { text-align: center; padding: 24px; color: #888; font-style: italic; }
</style>
