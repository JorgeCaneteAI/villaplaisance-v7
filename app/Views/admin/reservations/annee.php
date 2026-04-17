<?php
/**
 * Vue : calendrier annuel (12 mini-calendriers).
 * @var int $year
 * @var array $mois_data
 * @var \DateTimeImmutable $today
 * @var int $prev_year @var int $next_year
 * @var array $couleurs
 */
use App\Services\ReservationConstants;
?>
<div class="annee">
    <header class="annee__nav">
        <a href="/admin/calendrier/annee/<?= $prev_year ?>" class="btn">&larr; <?= $prev_year ?></a>
        <h1><?= $year ?></h1>
        <a href="/admin/calendrier/annee/<?= $next_year ?>" class="btn"><?= $next_year ?> &rarr;</a>
    </header>

    <div class="annee__toolbar">
        <a href="/admin/calendrier/<?= $year ?>/<?= (int) $today->format('n') ?>" class="btn">Vue mensuelle</a>
        <a href="/admin/calendrier/liste?mois=<?= $year ?>" class="btn">Liste <?= $year ?></a>
    </div>

    <div class="annee__grid">
        <?php foreach ($mois_data as $m): ?>
            <div class="annee__mois">
                <h2>
                    <a href="/admin/calendrier/<?= $year ?>/<?= (int) $m['month'] ?>">
                        <?= htmlspecialchars($m['nom']) ?>
                    </a>
                </h2>
                <table class="mini-cal">
                    <thead>
                        <tr>
                            <?php foreach (ReservationConstants::JOURS_FR as $jour): ?>
                                <th><?= substr($jour, 0, 1) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($m['weeks'] as $week): ?>
                            <tr>
                                <?php foreach ($week as $day): ?>
                                    <?php
                                    $isCurrent = (int) $day->format('n') === (int) $m['month'];
                                    $key = $day->format('Y-m-d');
                                    $firstResa = $isCurrent && !empty($m['resa_by_day'][$key]) ? $m['resa_by_day'][$key][0] : null;
                                    $bg = $firstResa ? $firstResa['couleur']['bg'] : '';
                                    $style = $bg ? "background:" . htmlspecialchars($bg) . ";color:#fff;" : '';
                                    ?>
                                    <td class="<?= $isCurrent ? 'current' : 'outside' ?>"
                                        style="<?= $style ?>">
                                        <?= $isCurrent ? (int) $day->format('j') : '' ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.annee__nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.annee__nav h1 { margin: 0; font-size: 32px; }
.annee__toolbar { display: flex; gap: 8px; margin-bottom: 20px; }
.annee__grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
@media (max-width: 900px) { .annee__grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px) { .annee__grid { grid-template-columns: repeat(2, 1fr); } }
.annee__mois h2 { font-size: 14px; margin: 0 0 6px; text-transform: uppercase; letter-spacing: 0.5px; }
.annee__mois h2 a { color: inherit; text-decoration: none; }
.annee__mois h2 a:hover { text-decoration: underline; }
.mini-cal { width: 100%; border-collapse: collapse; font-size: 10px; table-layout: fixed; }
.mini-cal th { padding: 2px; background: #2C2C2A; color: #fff; font-weight: 600; }
.mini-cal td { text-align: center; padding: 3px 1px; border: 1px solid #eee; }
.mini-cal td.outside { color: #bbb; }
</style>
