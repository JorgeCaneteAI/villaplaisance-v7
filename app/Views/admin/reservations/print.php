<?php
use App\Services\ReservationConstants;
/** @var int $year @var int $month @var string $mois_nom @var array $weeks @var array $resa_by_day */
/** @var array $couleurs @var \DateTimeImmutable $today */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Réservations — <?= htmlspecialchars($mois_nom) ?> <?= $year ?></title>
<style>
@page { size: A4 landscape; margin: 10mm; }
* { box-sizing: border-box; }
body { font-family: Helvetica, Arial, sans-serif; font-size: 10pt; margin: 0; padding: 0; color: #222; }
h1 { background: #2C2C2A; color: #fff; padding: 8px 12px; margin: 0 0 8px; font-size: 14pt; font-weight: 700; }
table.cal { width: 100%; border-collapse: collapse; table-layout: fixed; }
table.cal th { background: #2C2C2A; color: #fff; padding: 4px; font-size: 8pt; text-align: center; }
table.cal th.weekend { background: #3d3d3a; }
table.cal td { border: 0.5px solid #ccc; vertical-align: top; height: 3cm; padding: 2px 3px; overflow: hidden; }
table.cal td.outside { background: #f5f5f5; color: #bbb; }
table.cal td.weekend { background: #fafaf8; }
.day-num { font-weight: 700; font-size: 9pt; }
.resa { display: block; font-size: 7pt; padding: 2px 4px; margin-top: 1px; border-radius: 2px; line-height: 1.25; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.resa strong { font-weight: 700; }
.legend { margin-top: 8px; display: flex; gap: 6px; flex-wrap: wrap; }
.legend span { display: inline-block; padding: 3px 10px; border-radius: 3px; font-size: 8pt; font-weight: 600; }
.actions { margin-bottom: 10px; }
.actions button, .actions a { padding: 6px 12px; font-size: 11pt; background: #2C2C2A; color: #fff; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
.actions a { background: #888; }
@media print { .actions { display: none; } }
</style>
</head>
<body>
<h1>RÉSERVATIONS — <?= htmlspecialchars(strtoupper($mois_nom)) ?> <?= $year ?></h1>

<div class="actions">
    <button onclick="window.print()">Imprimer (Cmd+P)</button>
    <a href="/admin/calendrier/<?= $year ?>/<?= $month ?>">Retour</a>
</div>

<table class="cal">
    <thead>
        <tr>
            <?php foreach (ReservationConstants::JOURS_FR as $i => $jour): ?>
                <th class="<?= $i >= 5 ? 'weekend' : '' ?>"><?= htmlspecialchars($jour) ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($weeks as $week): ?>
            <tr>
                <?php foreach ($week as $i => $day): ?>
                    <?php
                    $isCurrent = (int) $day->format('n') === $month;
                    $isWeekend = $i >= 5;
                    $key = $day->format('Y-m-d');
                    $classes = [];
                    if (!$isCurrent) $classes[] = 'outside';
                    elseif ($isWeekend) $classes[] = 'weekend';
                    ?>
                    <td class="<?= implode(' ', $classes) ?>">
                        <span class="day-num"><?= (int) $day->format('j') ?></span>
                        <?php if ($isCurrent && isset($resa_by_day[$key])): ?>
                            <?php foreach ($resa_by_day[$key] as $r): ?>
                                <span class="resa" style="background: <?= htmlspecialchars($r['couleur']['bg']) ?>; color: <?= htmlspecialchars($r['couleur']['text']) ?>;">
                                    <strong><?= htmlspecialchars($r['code']) ?></strong> · <?= htmlspecialchars($r['nom_client']) ?>
                                </span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="legend">
    <?php foreach ($couleurs as $src => $c): ?>
        <span style="background: <?= htmlspecialchars($c['bg']) ?>; color: <?= htmlspecialchars($c['text']) ?>;">● <?= htmlspecialchars($src) ?></span>
    <?php endforeach; ?>
</div>
</body>
</html>
