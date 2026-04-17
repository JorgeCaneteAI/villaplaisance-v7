<?php
/**
 * Vue : calendrier mensuel des réservations.
 * @var int $year
 * @var int $month
 * @var string $mois_nom
 * @var array $weeks
 * @var array $resa_by_day
 * @var array $couleurs
 * @var \DateTimeImmutable $today
 * @var int $prev_year @var int $prev_month @var int $next_year @var int $next_month
 */
use App\Services\ReservationConstants;
?>
<div class="calendrier">
    <header class="calendrier__nav">
        <a href="/admin/calendrier/<?= $prev_year ?>/<?= $prev_month ?>" class="btn">&larr; <?= ReservationConstants::MOIS_FR[$prev_month] ?></a>
        <h1><?= htmlspecialchars($mois_nom) ?> <?= $year ?></h1>
        <a href="/admin/calendrier/<?= $next_year ?>/<?= $next_month ?>" class="btn"><?= ReservationConstants::MOIS_FR[$next_month] ?> &rarr;</a>
    </header>

    <div class="calendrier__toolbar">
        <a href="/admin/calendrier/saisie" class="btn btn-primary">+ Nouvelle résa</a>
        <a href="/admin/calendrier/liste" class="btn">Liste</a>
        <a href="/admin/calendrier/annee/<?= $year ?>" class="btn">Vue annuelle</a>
        <a href="/admin/calendrier/print/<?= $year ?>/<?= $month ?>" class="btn">Imprimer</a>
        <a href="/admin/calendrier/export/pdf/<?= $year ?>/<?= $month ?>" class="btn">PDF</a>
    </div>

    <?php
    $csrf = $_SESSION['csrf_token'] ?? ($_SESSION['csrf_token'] = bin2hex(random_bytes(32)));
    $fmtAge = function(float $m): string {
        if ($m < 60) return round($m) . ' min';
        if ($m < 1440) return round($m / 60, 1) . ' h';
        return round($m / 1440) . ' j';
    };
    $badgeClass = 'sync-unknown';
    $badgeText = 'Sync jamais effectuée';
    if (!empty($last_sync_at)) {
        $syncAge = max(0, (time() - strtotime($last_sync_at)) / 60);
        if ((int) $last_sync_ok === 0) {
            $badgeClass = 'sync-error';
            $badgeText = 'Dernière sync KO il y a ' . $fmtAge($syncAge);
        } elseif ($syncAge < 60) {
            $badgeClass = 'sync-ok'; $badgeText = 'Sync il y a ' . $fmtAge($syncAge);
        } elseif ($syncAge < 1440) {
            $badgeClass = 'sync-warn'; $badgeText = 'Sync il y a ' . $fmtAge($syncAge);
        } else {
            $badgeClass = 'sync-error'; $badgeText = 'Sync il y a ' . $fmtAge($syncAge);
        }
    }
    ?>
    <div class="sync-bar">
        <span class="sync-badge <?= $badgeClass ?>"><?= htmlspecialchars($badgeText) ?></span>
        <form method="post" action="/admin/calendrier/sync" style="display:inline">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <button type="submit" class="btn">Sync iCal</button>
        </form>
        <a href="/admin/calendrier/logs" class="btn">Logs</a>
    </div>

    <table class="calendrier__grid">
        <thead>
            <tr>
                <?php foreach (ReservationConstants::JOURS_FR as $jour): ?>
                    <th><?= $jour ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($weeks as $week): ?>
                <tr>
                    <?php foreach ($week as $day): ?>
                        <?php
                        $isCurrent = (int) $day->format('n') === $month;
                        $key = $day->format('Y-m-d');
                        $isToday = $day->format('Y-m-d') === $today->format('Y-m-d');
                        $classes = ['cell', $isCurrent ? 'current' : 'outside'];
                        if ($isToday) $classes[] = 'today';
                        ?>
                        <td class="<?= implode(' ', $classes) ?>">
                            <div class="day-num"><?= (int) $day->format('j') ?></div>
                            <?php if ($isCurrent && isset($resa_by_day[$key])): ?>
                                <?php foreach ($resa_by_day[$key] as $r): ?>
                                    <a href="/admin/calendrier/saisie/<?= (int) $r['id'] ?>"
                                       class="resa"
                                       title="<?= htmlspecialchars($r['commentaire'] ?? '') ?>"
                                       style="background: <?= htmlspecialchars($r['couleur']['bg']) ?>; color: <?= htmlspecialchars($r['couleur']['text']) ?>;">
                                        <strong><?= htmlspecialchars($r['code']) ?></strong>
                                        &middot; <?= htmlspecialchars($r['nom_client']) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
.calendrier__nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.calendrier__nav h1 { margin: 0; }
.calendrier__toolbar { display: flex; gap: 8px; margin: 12px 0 20px; flex-wrap: wrap; }
.calendrier__grid { width: 100%; border-collapse: collapse; table-layout: fixed; }
.calendrier__grid th { background: #2C2C2A; color: #fff; padding: 8px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
.calendrier__grid td.cell { border: 1px solid #e0e0e0; vertical-align: top; height: 110px; width: 14.28%; padding: 4px; }
.calendrier__grid td.outside { background: #f5f5f5; }
.calendrier__grid td.today { background: #fffbe0; }
.calendrier__grid .day-num { font-weight: 700; font-size: 12px; color: #222; margin-bottom: 2px; }
.calendrier__grid td.outside .day-num { color: #bbb; }
.calendrier__grid .resa { display: block; padding: 3px 5px; margin-top: 2px; border-radius: 3px; font-size: 10.5px; text-decoration: none; line-height: 1.2; }
.calendrier__grid .resa:hover { opacity: 0.85; }
.sync-bar { margin: 8px 0 20px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
.sync-badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; }
.sync-ok    { background: #E0F5E0; color: #2d7a2d; }
.sync-warn  { background: #FFF3CD; color: #856404; }
.sync-error { background: #F8D7DA; color: #721C24; }
.sync-unknown { background: #E9ECEF; color: #495057; }
</style>
