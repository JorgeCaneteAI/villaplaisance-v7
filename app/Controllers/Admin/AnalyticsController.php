<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class AnalyticsController extends AdminBaseController
{
    public function index(): void
    {
        // ── KPIs rapides ────────────────────────────────────────────────
        $today = \Database::fetchOne(
            "SELECT COUNT(*) as total, COUNT(DISTINCT visitor_id) as uniques
             FROM vp_pageviews WHERE DATE(created_at) = CURDATE()"
        );
        $week = \Database::fetchOne(
            "SELECT COUNT(*) as total, COUNT(DISTINCT visitor_id) as uniques
             FROM vp_pageviews WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)"
        );

        // ── Période courante vs précédente (tendance) ───────────────────
        $current30 = \Database::fetchOne(
            "SELECT COUNT(*) as total, COUNT(DISTINCT visitor_id) as uniques
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)"
        );
        $previous30 = \Database::fetchOne(
            "SELECT COUNT(*) as total, COUNT(DISTINCT visitor_id) as uniques
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)
               AND created_at <  DATE_SUB(CURDATE(), INTERVAL 30 DAY)"
        );

        // Helper tendance : retourne ['pct' => int, 'dir' => 'up'|'down'|'flat']
        $trend = function(int $curr, int $prev): array {
            if ($prev === 0) return ['pct' => 0, 'dir' => 'flat'];
            $pct = round(($curr - $prev) / $prev * 100);
            return ['pct' => abs($pct), 'dir' => $pct > 0 ? 'up' : ($pct < 0 ? 'down' : 'flat')];
        };
        $trendViews   = $trend((int)($current30['total'] ?? 0),   (int)($previous30['total'] ?? 0));
        $trendUniques = $trend((int)($current30['uniques'] ?? 0), (int)($previous30['uniques'] ?? 0));

        // ── Pages par visiteur (engagement) ────────────────────────────
        $engagement = \Database::fetchOne(
            "SELECT COUNT(*) as total, COUNT(DISTINCT visitor_id) as uniques
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)"
        );
        $pagesPerVisitor = ($engagement['uniques'] ?? 0) > 0
            ? round((int)$engagement['total'] / (int)$engagement['uniques'], 1)
            : 0;

        // ── Nouveaux vs visiteurs récurrents ────────────────────────────
        $returningData = \Database::fetchOne(
            "SELECT
               SUM(CASE WHEN first_ever < DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as returning_v,
               SUM(CASE WHEN first_ever >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as new_v
             FROM (
               SELECT v30.visitor_id, MIN(all_v.created_at) as first_ever
               FROM (
                 SELECT DISTINCT visitor_id
                 FROM vp_pageviews
                 WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
               ) v30
               JOIN vp_pageviews all_v ON all_v.visitor_id = v30.visitor_id
               GROUP BY v30.visitor_id
             ) sub"
        );
        $newVisitors       = (int)($returningData['new_v'] ?? 0);
        $returningVisitors = (int)($returningData['returning_v'] ?? 0);
        $totalVisitors30   = $newVisitors + $returningVisitors;
        $returningPct      = $totalVisitors30 > 0 ? round($returningVisitors / $totalVisitors30 * 100) : 0;

        // ── Taux de contact (proxy intention de réservation) ────────────
        $contactVisits = \Database::fetchOne(
            "SELECT COUNT(*) as cnt, COUNT(DISTINCT visitor_id) as uniques
             FROM vp_pageviews
             WHERE page_url LIKE '%contact%'
               AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)"
        );
        $contactRate = (int)($current30['uniques'] ?? 0) > 0
            ? round((int)($contactVisits['uniques'] ?? 0) / (int)$current30['uniques'] * 100, 1)
            : 0;

        // ── Graphique 30 jours ─────────────────────────────────────────
        $chartData = \Database::fetchAll(
            "SELECT DATE(created_at) as day, COUNT(*) as views, COUNT(DISTINCT visitor_id) as uniques
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY DATE(created_at)
             ORDER BY day ASC"
        );
        $chart = [];
        $start = new \DateTime('-29 days');
        $end   = new \DateTime('now');
        $dataByDay = [];
        foreach ($chartData as $row) {
            $dataByDay[$row['day']] = $row;
        }
        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end->modify('+1 day'));
        foreach ($period as $date) {
            $d = $date->format('Y-m-d');
            $chart[] = [
                'day'     => $d,
                'label'   => $date->format('d/m'),
                'views'   => (int)($dataByDay[$d]['views'] ?? 0),
                'uniques' => (int)($dataByDay[$d]['uniques'] ?? 0),
            ];
        }

        // ── Heures de pointe (0-23h) ────────────────────────────────────
        $hourlyRaw = \Database::fetchAll(
            "SELECT HOUR(created_at) as h, COUNT(*) as cnt
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY HOUR(created_at)
             ORDER BY h"
        );
        $hourlyMap = [];
        foreach ($hourlyRaw as $row) {
            $hourlyMap[(int)$row['h']] = (int)$row['cnt'];
        }
        $hourly = [];
        for ($h = 0; $h <= 23; $h++) {
            $hourly[$h] = $hourlyMap[$h] ?? 0;
        }

        // ── Jours de la semaine ─────────────────────────────────────────
        // DAYOFWEEK : 1=dim, 2=lun … 7=sam
        $dowRaw = \Database::fetchAll(
            "SELECT DAYOFWEEK(created_at) as dow, COUNT(*) as cnt
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY DAYOFWEEK(created_at)
             ORDER BY dow"
        );
        $dowLabels = [1 => 'Dim', 2 => 'Lun', 3 => 'Mar', 4 => 'Mer', 5 => 'Jeu', 6 => 'Ven', 7 => 'Sam'];
        $dowMap    = [];
        foreach ($dowRaw as $row) {
            $dowMap[(int)$row['dow']] = (int)$row['cnt'];
        }
        $dow = [];
        foreach ($dowLabels as $num => $label) {
            $dow[] = ['label' => $label, 'cnt' => $dowMap[$num] ?? 0];
        }

        // ── Sources de trafic ───────────────────────────────────────────
        $topReferrers = \Database::fetchAll(
            "SELECT referrer, COUNT(*) as hits
             FROM vp_pageviews
             WHERE referrer IS NOT NULL AND referrer != ''
               AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY referrer
             ORDER BY hits DESC
             LIMIT 10"
        );
        $directTraffic = \Database::fetchOne(
            "SELECT COUNT(*) as cnt, COUNT(DISTINCT visitor_id) as uniques
             FROM vp_pageviews
             WHERE (referrer IS NULL OR referrer = '')
               AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)"
        );

        // ── Origines géographiques ──────────────────────────────────────
        $countryStats = [];
        try {
            $countryStats = \Database::fetchAll(
                "SELECT country, COUNT(*) as cnt, COUNT(DISTINCT visitor_id) as uniques
                 FROM vp_pageviews
                 WHERE country IS NOT NULL
                   AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                 GROUP BY country
                 ORDER BY cnt DESC
                 LIMIT 10"
            );
        } catch (\Throwable) {}

        // ── Top pages ───────────────────────────────────────────────────
        $topPages = \Database::fetchAll(
            "SELECT page_url, COUNT(*) as views, COUNT(DISTINCT visitor_id) as uniques
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY page_url
             ORDER BY views DESC
             LIMIT 10"
        );
        $totalViews30 = (int)($current30['total'] ?? 1);

        // ── Articles populaires ─────────────────────────────────────────
        $topArticles = [];
        try {
            $topArticles = \Database::fetchAll(
                "SELECT a.title, a.slug, a.type, COUNT(*) as views
                 FROM vp_pageviews pv
                 JOIN vp_articles a ON CONCAT('/', a.type, '/', a.slug) = pv.page_url AND a.lang = 'fr'
                 WHERE pv.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                 GROUP BY a.id
                 ORDER BY views DESC
                 LIMIT 10"
            );
        } catch (\Throwable) {}

        // ── Langues ─────────────────────────────────────────────────────
        $langStats = \Database::fetchAll(
            "SELECT lang, COUNT(*) as cnt
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY lang
             ORDER BY cnt DESC"
        );

        // ── Appareils ───────────────────────────────────────────────────
        $devices = \Database::fetchAll(
            "SELECT device_type, COUNT(*) as cnt
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY device_type"
        );
        $deviceTotal = array_sum(array_column($devices, 'cnt'));
        $deviceMap   = [];
        foreach ($devices as $d) {
            $deviceMap[$d['device_type']] = (int)$d['cnt'];
        }

        $csrf = $this->csrf();

        $this->render('admin/analytics', compact(
            // KPIs
            'today', 'week', 'current30', 'previous30',
            'trendViews', 'trendUniques',
            'pagesPerVisitor',
            'newVisitors', 'returningVisitors', 'returningPct', 'totalVisitors30',
            'contactVisits', 'contactRate',
            // Graphique
            'chart',
            // Temporel
            'hourly', 'dow',
            // Sources
            'topReferrers', 'directTraffic',
            // Géo
            'countryStats',
            // Contenu
            'topPages', 'totalViews30', 'topArticles',
            // Profil
            'langStats', 'devices', 'deviceTotal', 'deviceMap',
            'csrf'
        ));
    }
}
