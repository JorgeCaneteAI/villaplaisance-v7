<?php
declare(strict_types=1);

// ── Helpers ──────────────────────────────────────────────────────────────────

function countryFlag(string $code): string {
    $code = strtoupper($code);
    if (strlen($code) !== 2) return '🌐';
    $chars = str_split($code);
    return mb_chr(ord($chars[0]) + 127397) . mb_chr(ord($chars[1]) + 127397);
}

$countryNames = [
    'FR'=>'France','BE'=>'Belgique','CH'=>'Suisse','LU'=>'Luxembourg',
    'GB'=>'Royaume-Uni','DE'=>'Allemagne','NL'=>'Pays-Bas','IT'=>'Italie',
    'ES'=>'Espagne','PT'=>'Portugal','US'=>'États-Unis','CA'=>'Canada',
    'AU'=>'Australie','JP'=>'Japon','SE'=>'Suède','NO'=>'Norvège',
    'DK'=>'Danemark','FI'=>'Finlande','AT'=>'Autriche','PL'=>'Pologne',
    'CZ'=>'Tchéquie','RU'=>'Russie','CN'=>'Chine','IN'=>'Inde',
    'BR'=>'Brésil','MX'=>'Mexique','AR'=>'Argentine','ZA'=>'Afrique du Sud',
    'MA'=>'Maroc','TN'=>'Tunisie','DZ'=>'Algérie','SG'=>'Singapour',
    'IE'=>'Irlande','IL'=>'Israël','TR'=>'Turquie','GR'=>'Grèce',
    'HU'=>'Hongrie','RO'=>'Roumanie','HR'=>'Croatie','SK'=>'Slovaquie',
];

$trendHtml = function(array $t, string $label = ''): string {
    if ($t['dir'] === 'flat' || $t['pct'] === 0) {
        return '<span style="color:#aaa;font-size:0.72rem">= stable</span>';
    }
    $color  = $t['dir'] === 'up' ? '#27AE60' : '#E74C3C';
    $arrow  = $t['dir'] === 'up' ? '↑' : '↓';
    $suffix = $label ? " $label" : '';
    return "<span style=\"color:$color;font-size:0.72rem;font-weight:600\">$arrow {$t['pct']}%$suffix vs mois préc.</span>";
};
?>

<div class="page-header">
    <h1>Statistiques</h1>
    <p style="color:#888;font-size:0.85rem">Données internes — fenêtre glissante 30 jours</p>
</div>

<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 1 — KPIs + tendances
     ══════════════════════════════════════════════════════════════════════════ -->
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:0.75rem;margin-bottom:1.5rem">

    <!-- Pages vues -->
    <div class="stat-card">
        <div class="stat-number"><?= number_format((int)($current30['total'] ?? 0)) ?></div>
        <div class="stat-label">Pages vues <span style="color:#bbb;font-weight:400">30j</span></div>
        <div style="margin-top:0.3rem"><?= $trendHtml($trendViews) ?></div>
        <div style="font-size:0.7rem;color:#aaa;margin-top:0.2rem">
            Aujourd'hui : <?= number_format((int)($today['total'] ?? 0)) ?>
            &nbsp;·&nbsp; Semaine : <?= number_format((int)($week['total'] ?? 0)) ?>
        </div>
    </div>

    <!-- Visiteurs uniques -->
    <div class="stat-card">
        <div class="stat-number"><?= number_format((int)($current30['uniques'] ?? 0)) ?></div>
        <div class="stat-label">Visiteurs uniques <span style="color:#bbb;font-weight:400">30j</span></div>
        <div style="margin-top:0.3rem"><?= $trendHtml($trendUniques) ?></div>
        <div style="font-size:0.7rem;color:#aaa;margin-top:0.2rem">
            Aujourd'hui : <?= number_format((int)($today['uniques'] ?? 0)) ?>
        </div>
    </div>

    <!-- Pages / visiteur -->
    <div class="stat-card">
        <div class="stat-number"><?= $pagesPerVisitor ?></div>
        <div class="stat-label">Pages / visite</div>
        <div style="font-size:0.72rem;color:#888;margin-top:0.3rem">
            <?php if ($pagesPerVisitor >= 3): ?>
            <span style="color:#27AE60">✓ bonne exploration</span>
            <?php elseif ($pagesPerVisitor >= 2): ?>
            <span style="color:#E67E22">navigation moyenne</span>
            <?php else: ?>
            <span style="color:#E74C3C">visite courte</span>
            <?php endif; ?>
        </div>
        <div style="font-size:0.7rem;color:#aaa;margin-top:0.2rem">Engagement contenu</div>
    </div>

    <!-- Récurrents -->
    <div class="stat-card">
        <div class="stat-number"><?= $returningPct ?>%</div>
        <div class="stat-label">Visiteurs récurrents</div>
        <div style="font-size:0.72rem;color:#888;margin-top:0.3rem">
            <?= number_format($returningVisitors) ?> retours
            &nbsp;·&nbsp; <?= number_format($newVisitors) ?> nouveaux
        </div>
        <?php if ($totalVisitors30 > 0): ?>
        <div style="display:flex;height:5px;border-radius:3px;overflow:hidden;margin-top:0.5rem;background:#f0f0f0">
            <div style="width:<?= $returningPct ?>%;background:#88A398"></div>
            <div style="flex:1;background:#E67E22;opacity:0.5"></div>
        </div>
        <div style="font-size:0.65rem;color:#aaa;margin-top:0.2rem">
            <span style="color:#88A398">■</span> Récurrents
            &nbsp;<span style="color:#E67E22;opacity:0.8">■</span> Nouveaux
        </div>
        <?php endif; ?>
    </div>

    <!-- Taux de contact -->
    <div class="stat-card">
        <div class="stat-number"><?= $contactRate ?>%</div>
        <div class="stat-label">Taux de contact</div>
        <div style="font-size:0.72rem;color:#888;margin-top:0.3rem">
            <?= number_format((int)($contactVisits['uniques'] ?? 0)) ?> visiteurs → /contact
        </div>
        <div style="font-size:0.7rem;color:#aaa;margin-top:0.2rem">Proxy intention réservation</div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 2 — Graphique 30 jours
     ══════════════════════════════════════════════════════════════════════════ -->
<div class="admin-card" style="margin-bottom:1.5rem">
    <h2>Évolution — 30 derniers jours</h2>
    <?php $maxViews = max(array_column($chart, 'views') ?: [1]); ?>
    <div class="analytics-chart">
        <?php foreach ($chart as $i => $day): ?>
        <div class="analytics-bar-wrap" title="<?= $day['label'] ?> — <?= $day['views'] ?> vues, <?= $day['uniques'] ?> uniques">
            <div class="analytics-bar" style="height:<?= $maxViews > 0 ? round($day['views'] / $maxViews * 100) : 0 ?>%">
                <?php if ($day['uniques'] > 0 && $maxViews > 0): ?>
                <div class="analytics-bar-uniques" style="height:<?= round($day['uniques'] / $maxViews * 100) ?>%"></div>
                <?php endif; ?>
            </div>
            <?php if ($i % 5 === 0): ?>
            <span class="analytics-bar-label"><?= $day['label'] ?></span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <div style="font-size:0.7rem;color:#888;margin-top:0.5rem">
        <span style="color:var(--admin-accent)">■</span> Pages vues
        <span style="color:rgba(26,110,184,0.4);margin-left:1rem">■</span> Visiteurs uniques
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 3 — QUAND ? Heures de pointe + Jours actifs
     ══════════════════════════════════════════════════════════════════════════ -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1rem;margin-bottom:1.5rem">

    <!-- Heures de pointe -->
    <div class="admin-card">
        <h2>Heures de pointe <span style="font-size:0.75rem;font-weight:400;color:#888">— utile pour répondre aux demandes</span></h2>
        <?php
        $maxHourly = max($hourly ?: [1]);
        $peakHour  = array_search($maxHourly, $hourly);
        ?>
        <div style="display:flex;align-items:flex-end;gap:2px;height:60px;margin-bottom:0.5rem">
        <?php for ($h = 0; $h <= 23; $h++):
            $val  = $hourly[$h];
            $pct  = $maxHourly > 0 ? round($val / $maxHourly * 100) : 0;
            $isPeak = ($h === $peakHour);
        ?>
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:2px">
                <div style="width:100%;height:<?= $pct ?>%;background:<?= $isPeak ? 'var(--admin-accent)' : 'rgba(26,110,184,0.25)' ?>;border-radius:2px 2px 0 0;min-height:<?= $val > 0 ? '2px' : '0' ?>"
                     title="<?= $h ?>h : <?= $val ?> vues"></div>
            </div>
        <?php endfor; ?>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:0.65rem;color:#bbb">
            <?php foreach ([0,3,6,9,12,15,18,21,23] as $h): ?>
            <span><?= $h ?>h</span>
            <?php endforeach; ?>
        </div>
        <?php if ($maxHourly > 0): ?>
        <div style="font-size:0.78rem;color:#888;margin-top:0.6rem">
            Pic de trafic : <strong><?= $peakHour ?>h–<?= $peakHour + 1 ?>h</strong>
            (<?= $maxHourly ?> vues) — soyez disponible pour les messages à cette heure.
        </div>
        <?php endif; ?>
    </div>

    <!-- Jours de la semaine -->
    <div class="admin-card">
        <h2>Jours actifs</h2>
        <?php
        $maxDow = max(array_column($dow, 'cnt') ?: [1]);
        $peakDow = array_reduce($dow, fn($c, $d) => ($d['cnt'] > ($c['cnt'] ?? 0)) ? $d : $c, ['label'=>'','cnt'=>0]);
        ?>
        <?php foreach ($dow as $d):
            $pct = $maxDow > 0 ? round($d['cnt'] / $maxDow * 100) : 0;
            $isPeak = ($d['cnt'] === $maxDow && $maxDow > 0);
        ?>
        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.4rem;font-size:0.82rem">
            <span style="width:2rem;color:#888;flex-shrink:0"><?= $d['label'] ?></span>
            <div style="flex:1;height:10px;background:#f0f0f0;border-radius:5px;overflow:hidden">
                <div style="height:100%;width:<?= $pct ?>%;background:<?= $isPeak ? 'var(--admin-accent)' : 'rgba(26,110,184,0.3)' ?>;border-radius:5px"></div>
            </div>
            <span style="width:2.5rem;text-align:right;color:#888"><?= $d['cnt'] ?></span>
        </div>
        <?php endforeach; ?>
        <?php if ($maxDow > 0): ?>
        <div style="font-size:0.75rem;color:#888;margin-top:0.5rem">
            Jour le plus actif : <strong><?= $peakDow['label'] ?></strong>
        </div>
        <?php endif; ?>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 4 — D'OÙ ? Sources + Géographie
     ══════════════════════════════════════════════════════════════════════════ -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem">

    <!-- Sources de trafic -->
    <div class="admin-card">
        <h2>Sources de trafic</h2>
        <?php
        $directHits  = (int)($directTraffic['cnt'] ?? 0);
        $refHits     = array_sum(array_column($topReferrers, 'hits'));
        $sourceTotal = $directHits + $refHits;
        ?>
        <?php if ($directHits > 0): ?>
        <div style="margin-bottom:0.75rem;padding-bottom:0.75rem;border-bottom:1px solid #f0f0f0">
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;margin-bottom:0.25rem">
                <span>🔗 <strong>Direct / bookmark</strong></span>
                <span style="color:#888">
                    <?= number_format($directHits) ?>
                    <?php if ($sourceTotal > 0): ?>
                    <span style="color:#bbb">(<?= round($directHits / $sourceTotal * 100) ?>%)</span>
                    <?php endif; ?>
                </span>
            </div>
            <div style="height:6px;background:#f0f0f0;border-radius:3px;overflow:hidden">
                <div style="height:100%;width:<?= $sourceTotal > 0 ? round($directHits / $sourceTotal * 100) : 0 ?>%;background:#88A398;border-radius:3px"></div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (empty($topReferrers)): ?>
        <p class="text-muted">Aucune source référente détectée</p>
        <?php else: ?>
        <table class="admin-table">
            <thead><tr><th>Source</th><th style="text-align:right">Visites</th><th style="text-align:right">%</th></tr></thead>
            <tbody>
            <?php foreach ($topReferrers as $r):
                $domain = parse_url($r['referrer'], PHP_URL_HOST) ?: $r['referrer'];
                $pct = $sourceTotal > 0 ? round((int)$r['hits'] / $sourceTotal * 100) : 0;
            ?>
            <tr>
                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= htmlspecialchars($r['referrer']) ?>"><?= htmlspecialchars($domain) ?></td>
                <td style="text-align:right;font-weight:600"><?= number_format((int)$r['hits']) ?></td>
                <td style="text-align:right;color:#aaa"><?= $pct ?>%</td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- Origines géographiques -->
    <div class="admin-card">
        <h2>Origines géographiques</h2>
        <?php if (empty($countryStats)): ?>
        <p class="text-muted">Pas encore de données. Les prochaines visites seront géolocalisées.</p>
        <?php else: ?>
        <?php $countryTotal = array_sum(array_column($countryStats, 'cnt')); ?>
        <?php foreach ($countryStats as $cs):
            $code = strtoupper($cs['country'] ?? '');
            $pct  = $countryTotal > 0 ? round((int)$cs['cnt'] / $countryTotal * 100) : 0;
            $name = $countryNames[$code] ?? $code;
        ?>
        <div style="margin-bottom:0.5rem">
            <div style="display:flex;justify-content:space-between;font-size:0.82rem;margin-bottom:0.2rem">
                <span><?= countryFlag($code) ?> <?= htmlspecialchars($name) ?></span>
                <span style="color:#888"><?= number_format((int)$cs['cnt']) ?> <span style="color:#bbb">(<?= $pct ?>%)</span></span>
            </div>
            <div style="height:5px;background:#f0f0f0;border-radius:3px;overflow:hidden">
                <div style="height:100%;width:<?= $pct ?>%;background:var(--admin-accent);opacity:0.65;border-radius:3px"></div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 5 — QUOI ? Pages + Articles
     ══════════════════════════════════════════════════════════════════════════ -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem">

    <!-- Top pages -->
    <div class="admin-card">
        <h2>Pages les plus vues</h2>
        <?php if (empty($topPages)): ?>
        <p class="text-muted">Aucune donnée</p>
        <?php else: ?>
        <table class="admin-table">
            <thead><tr><th>Page</th><th style="text-align:right">Vues</th><th style="text-align:right">%</th></tr></thead>
            <tbody>
            <?php foreach ($topPages as $p):
                $pct = $totalViews30 > 0 ? round((int)$p['views'] / $totalViews30 * 100) : 0;
            ?>
            <tr>
                <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                    title="<?= htmlspecialchars($p['page_url']) ?>"><?= htmlspecialchars($p['page_url']) ?></td>
                <td style="text-align:right;font-weight:600"><?= number_format((int)$p['views']) ?></td>
                <td style="text-align:right;color:#aaa"><?= $pct ?>%</td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- Articles populaires -->
    <div class="admin-card">
        <h2>Articles populaires</h2>
        <?php if (empty($topArticles)): ?>
        <p class="text-muted">Aucune donnée</p>
        <?php else: ?>
        <table class="admin-table">
            <thead><tr><th>Article</th><th>Type</th><th style="text-align:right">Vues</th></tr></thead>
            <tbody>
            <?php foreach ($topArticles as $a): ?>
            <tr>
                <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($a['title']) ?></td>
                <td><span class="analytics-badge analytics-badge-<?= $a['type'] === 'journal' ? 'blue' : 'green' ?>"><?= htmlspecialchars($a['type']) ?></span></td>
                <td style="text-align:right;font-weight:600"><?= number_format((int)$a['views']) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════════════════════
     SECTION 6 — QUI ? Langues + Appareils
     ══════════════════════════════════════════════════════════════════════════ -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem">

    <!-- Langues -->
    <div class="admin-card">
        <h2>Langues de navigation</h2>
        <?php if (empty($langStats)): ?>
        <p class="text-muted">Aucune donnée</p>
        <?php else: ?>
        <?php
        $langTotal  = array_sum(array_column($langStats, 'cnt'));
        $langColors = ['fr' => 'var(--admin-accent)', 'en' => '#E67E22', 'es' => '#88A398'];
        $langLabels = ['fr' => 'Français', 'en' => 'Anglais', 'es' => 'Espagnol'];
        ?>
        <?php foreach ($langStats as $ls):
            $pct   = $langTotal > 0 ? round((int)$ls['cnt'] / $langTotal * 100) : 0;
            $color = $langColors[$ls['lang']] ?? '#ccc';
            $label = $langLabels[$ls['lang']] ?? strtoupper($ls['lang']);
        ?>
        <div style="margin-bottom:0.75rem">
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;margin-bottom:0.25rem">
                <span style="font-weight:600"><?= $label ?></span>
                <span style="color:#888"><?= number_format((int)$ls['cnt']) ?> <span style="color:#bbb">(<?= $pct ?>%)</span></span>
            </div>
            <div style="height:8px;background:#f0f0f0;border-radius:4px;overflow:hidden">
                <div style="height:100%;width:<?= $pct ?>%;background:<?= $color ?>;border-radius:4px"></div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Appareils -->
    <div class="admin-card">
        <h2>Appareils</h2>
        <?php
        $desktopPct = $deviceTotal > 0 ? round(($deviceMap['desktop'] ?? 0) / $deviceTotal * 100) : 0;
        $mobilePct  = $deviceTotal > 0 ? round(($deviceMap['mobile']  ?? 0) / $deviceTotal * 100) : 0;
        $tabletPct  = $deviceTotal > 0 ? round(($deviceMap['tablet']  ?? 0) / $deviceTotal * 100) : 0;
        $deviceInfo = [
            ['label' => '🖥 Desktop', 'pct' => $desktopPct, 'cnt' => $deviceMap['desktop'] ?? 0, 'color' => 'var(--admin-accent)'],
            ['label' => '📱 Mobile',  'pct' => $mobilePct,  'cnt' => $deviceMap['mobile']  ?? 0, 'color' => '#E67E22'],
            ['label' => '⬜ Tablette','pct' => $tabletPct,  'cnt' => $deviceMap['tablet']  ?? 0, 'color' => '#88A398'],
        ];
        ?>
        <?php foreach ($deviceInfo as $di): ?>
        <div style="margin-bottom:0.75rem">
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;margin-bottom:0.25rem">
                <span style="font-weight:600"><?= $di['label'] ?></span>
                <span style="color:#888"><?= number_format($di['cnt']) ?> <span style="color:#bbb">(<?= $di['pct'] ?>%)</span></span>
            </div>
            <div style="height:8px;background:#f0f0f0;border-radius:4px;overflow:hidden">
                <div style="height:100%;width:<?= $di['pct'] ?>%;background:<?= $di['color'] ?>;border-radius:4px"></div>
            </div>
        </div>
        <?php endforeach; ?>
        <div style="font-size:0.75rem;color:#aaa;margin-top:0.5rem">
            <?php if ($mobilePct > 60): ?>
            Majorité mobile — vérifier le rendu sur petits écrans.
            <?php elseif ($desktopPct > 60): ?>
            Majorité desktop — audience active en journée de travail.
            <?php else: ?>
            Mix équilibré mobile / desktop.
            <?php endif; ?>
        </div>
    </div>

</div>
