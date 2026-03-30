<?php declare(strict_types=1); ?>

<div class="page-header">
    <h1>Statistiques du site</h1>
    <p style="color:#888;font-size:0.85rem">Trafic des 30 derniers jours — données internes</p>
</div>

<!-- Overview cards -->
<div class="stats-cards" style="margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-number"><?= number_format((int)($today['total'] ?? 0)) ?></div>
        <div class="stat-label">Pages vues aujourd'hui</div>
        <div style="font-size:0.75rem;color:#888;margin-top:0.25rem"><?= number_format((int)($today['uniques'] ?? 0)) ?> visiteurs uniques</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= number_format((int)($week['total'] ?? 0)) ?></div>
        <div class="stat-label">Cette semaine</div>
        <div style="font-size:0.75rem;color:#888;margin-top:0.25rem"><?= number_format((int)($week['uniques'] ?? 0)) ?> uniques</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= number_format((int)($month['total'] ?? 0)) ?></div>
        <div class="stat-label">Ce mois</div>
        <div style="font-size:0.75rem;color:#888;margin-top:0.25rem"><?= number_format((int)($month['uniques'] ?? 0)) ?> uniques</div>
    </div>
    <div class="stat-card">
        <?php
        $desktopPct = $deviceTotal > 0 ? round(($deviceMap['desktop'] ?? 0) / $deviceTotal * 100) : 0;
        $mobilePct = $deviceTotal > 0 ? round(($deviceMap['mobile'] ?? 0) / $deviceTotal * 100) : 0;
        $tabletPct = $deviceTotal > 0 ? round(($deviceMap['tablet'] ?? 0) / $deviceTotal * 100) : 0;
        ?>
        <div class="stat-number" style="font-size:1.4rem"><?= $mobilePct ?>% <span style="font-size:0.8rem;font-weight:400;color:#888">mobile</span></div>
        <div class="stat-label">Appareils</div>
        <div style="display:flex;gap:4px;margin-top:0.5rem;height:8px;border-radius:4px;overflow:hidden">
            <div style="flex:<?= $desktopPct ?>;background:var(--admin-accent)" title="Desktop <?= $desktopPct ?>%"></div>
            <div style="flex:<?= $mobilePct ?>;background:#E67E22" title="Mobile <?= $mobilePct ?>%"></div>
            <div style="flex:<?= max($tabletPct, 0) ?>;background:#88A398" title="Tablet <?= $tabletPct ?>%"></div>
        </div>
        <div style="font-size:0.7rem;color:#888;margin-top:0.3rem">
            <span style="color:var(--admin-accent)">■</span> Desktop <?= $desktopPct ?>%
            <span style="color:#E67E22;margin-left:0.5rem">■</span> Mobile <?= $mobilePct ?>%
            <span style="color:#88A398;margin-left:0.5rem">■</span> Tablet <?= $tabletPct ?>%
        </div>
    </div>
</div>

<!-- 30-day chart -->
<div class="admin-card" style="margin-bottom:1.5rem">
    <h2>Visites — 30 derniers jours</h2>
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

<!-- Two columns: Top pages + Top referrers -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem">
    <div class="admin-card">
        <h2>Pages les plus vues</h2>
        <?php if (empty($topPages)): ?>
        <p class="text-muted">Aucune donnée</p>
        <?php else: ?>
        <table class="admin-table">
            <thead><tr><th>Page</th><th style="text-align:right">Vues</th><th style="text-align:right">Uniques</th></tr></thead>
            <tbody>
            <?php foreach ($topPages as $p): ?>
            <tr>
                <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= htmlspecialchars($p['page_url']) ?>"><?= htmlspecialchars($p['page_url']) ?></td>
                <td style="text-align:right;font-weight:600"><?= number_format((int)$p['views']) ?></td>
                <td style="text-align:right;color:#888"><?= number_format((int)$p['uniques']) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <div class="admin-card">
        <h2>Sources de trafic</h2>
        <?php if (empty($topReferrers)): ?>
        <p class="text-muted">Aucune donnée</p>
        <?php else: ?>
        <table class="admin-table">
            <thead><tr><th>Referrer</th><th style="text-align:right">Visites</th></tr></thead>
            <tbody>
            <?php foreach ($topReferrers as $r):
                $domain = parse_url($r['referrer'], PHP_URL_HOST) ?: $r['referrer'];
            ?>
            <tr>
                <td style="max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= htmlspecialchars($r['referrer']) ?>"><?= htmlspecialchars($domain) ?></td>
                <td style="text-align:right;font-weight:600"><?= number_format((int)$r['hits']) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- Two columns: Top articles + Languages -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem">
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
                <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($a['title']) ?></td>
                <td><span class="analytics-badge analytics-badge-<?= $a['type'] === 'journal' ? 'blue' : 'green' ?>"><?= htmlspecialchars($a['type']) ?></span></td>
                <td style="text-align:right;font-weight:600"><?= number_format((int)$a['views']) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <div class="admin-card">
        <h2>Langues</h2>
        <?php if (empty($langStats)): ?>
        <p class="text-muted">Aucune donnée</p>
        <?php else: ?>
        <?php $langTotal = array_sum(array_column($langStats, 'cnt')); ?>
        <?php $langColors = ['fr' => 'var(--admin-accent)', 'en' => '#E67E22', 'es' => '#88A398', 'de' => '#8E44AD']; ?>
        <?php foreach ($langStats as $ls):
            $pct = $langTotal > 0 ? round((int)$ls['cnt'] / $langTotal * 100) : 0;
            $color = $langColors[$ls['lang']] ?? '#ccc';
        ?>
        <div style="margin-bottom:0.75rem">
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;margin-bottom:0.25rem">
                <span style="font-weight:600;text-transform:uppercase"><?= htmlspecialchars($ls['lang']) ?></span>
                <span style="color:#888"><?= number_format((int)$ls['cnt']) ?> (<?= $pct ?>%)</span>
            </div>
            <div style="height:8px;background:#f0f0f0;border-radius:4px;overflow:hidden">
                <div style="height:100%;width:<?= $pct ?>%;background:<?= $color ?>;border-radius:4px"></div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
