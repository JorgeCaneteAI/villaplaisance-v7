<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class AnalyticsController extends AdminBaseController
{
    public function index(): void
    {
        // Overview stats
        $today = \Database::fetchOne(
            "SELECT COUNT(*) as total, COUNT(DISTINCT visitor_id) as uniques FROM vp_pageviews WHERE DATE(created_at) = CURDATE()"
        );
        $week = \Database::fetchOne(
            "SELECT COUNT(*) as total, COUNT(DISTINCT visitor_id) as uniques FROM vp_pageviews WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)"
        );
        $month = \Database::fetchOne(
            "SELECT COUNT(*) as total, COUNT(DISTINCT visitor_id) as uniques FROM vp_pageviews WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())"
        );

        // Last 30 days chart
        $chartData = \Database::fetchAll(
            "SELECT DATE(created_at) as day, COUNT(*) as views, COUNT(DISTINCT visitor_id) as uniques
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY DATE(created_at)
             ORDER BY day ASC"
        );

        // Fill missing days
        $chart = [];
        $start = new \DateTime('-29 days');
        $end = new \DateTime('now');
        $dataByDay = [];
        foreach ($chartData as $row) {
            $dataByDay[$row['day']] = $row;
        }
        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end->modify('+1 day'));
        foreach ($period as $date) {
            $d = $date->format('Y-m-d');
            $chart[] = [
                'day' => $d,
                'label' => $date->format('d/m'),
                'views' => (int)($dataByDay[$d]['views'] ?? 0),
                'uniques' => (int)($dataByDay[$d]['uniques'] ?? 0),
            ];
        }

        // Top 10 pages
        $topPages = \Database::fetchAll(
            "SELECT page_url, COUNT(*) as views, COUNT(DISTINCT visitor_id) as uniques
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY page_url
             ORDER BY views DESC
             LIMIT 10"
        );

        // Top referrers
        $topReferrers = \Database::fetchAll(
            "SELECT referrer, COUNT(*) as hits
             FROM vp_pageviews
             WHERE referrer IS NOT NULL AND referrer != '' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY referrer
             ORDER BY hits DESC
             LIMIT 10"
        );

        // Device breakdown
        $devices = \Database::fetchAll(
            "SELECT device_type, COUNT(*) as cnt
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY device_type"
        );
        $deviceTotal = array_sum(array_column($devices, 'cnt'));
        $deviceMap = [];
        foreach ($devices as $d) {
            $deviceMap[$d['device_type']] = (int)$d['cnt'];
        }

        // Top articles (match URLs like /journal/slug or /sur-place/slug)
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

        // Language breakdown
        $langStats = \Database::fetchAll(
            "SELECT lang, COUNT(*) as cnt
             FROM vp_pageviews
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY lang
             ORDER BY cnt DESC"
        );

        $csrf = $this->csrf();

        $this->render('admin/analytics', compact(
            'today', 'week', 'month',
            'chart', 'topPages', 'topReferrers',
            'devices', 'deviceTotal', 'deviceMap',
            'topArticles', 'langStats', 'csrf'
        ));
    }
}
