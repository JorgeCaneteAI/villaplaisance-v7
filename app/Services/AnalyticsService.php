<?php
declare(strict_types=1);

class AnalyticsService
{
    private static string $ga4Id = '';
    private static bool $ga4Loaded = false;

    private static array $botPatterns = [
        'bot', 'crawl', 'spider', 'slurp', 'Googlebot', 'Bingbot',
        'Baiduspider', 'YandexBot', 'DuckDuckBot', 'facebookexternalhit',
        'Twitterbot', 'LinkedInBot', 'WhatsApp', 'Semrush', 'AhrefsBot',
        'MJ12bot', 'Bytespider', 'GPTBot', 'ClaudeBot',
    ];

    public static function track(string $pageUrl): void
    {
        // Skip admin browsing
        if (!empty($_SESSION['admin_authenticated'])) {
            return;
        }

        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        // Skip bots
        foreach (self::$botPatterns as $pattern) {
            if (stripos($ua, $pattern) !== false) {
                return;
            }
        }

        // Skip assets & API
        if (preg_match('#\.(css|js|jpg|jpeg|png|webp|gif|svg|ico|woff2?|ttf|map)$#i', $pageUrl)) {
            return;
        }

        // Set or read visitor cookie
        $visitorRaw = $_COOKIE['vp_vid'] ?? '';
        if ($visitorRaw === '') {
            $visitorRaw = bin2hex(random_bytes(16));
            setcookie('vp_vid', $visitorRaw, [
                'expires' => time() + 365 * 86400,
                'path' => '/',
                'secure' => APP_ENV === 'production',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }

        $visitorId = hash('sha256', $visitorRaw);
        $ipHash = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '');
        $referrer = $_SERVER['HTTP_REFERER'] ?? null;
        $deviceType = self::detectDevice($ua);
        $lang = \LangService::current();

        try {
            \Database::insert('vp_pageviews', [
                'visitor_id' => $visitorId,
                'page_url' => mb_substr($pageUrl, 0, 500),
                'referrer' => $referrer ? mb_substr($referrer, 0, 500) : null,
                'user_agent' => $ua ? mb_substr($ua, 0, 500) : null,
                'device_type' => $deviceType,
                'ip_hash' => $ipHash,
                'lang' => $lang,
            ]);
        } catch (\Throwable) {
            // Silently fail — analytics should never break the site
        }
    }

    public static function getGA4Id(): string
    {
        if (!self::$ga4Loaded) {
            try {
                $row = \Database::fetchOne("SELECT setting_value FROM vp_settings WHERE setting_key = 'ga4_measurement_id'");
                self::$ga4Id = $row['setting_value'] ?? '';
            } catch (\Throwable) {
                self::$ga4Id = '';
            }
            self::$ga4Loaded = true;
        }
        return self::$ga4Id;
    }

    private static function detectDevice(string $ua): string
    {
        if (preg_match('/Mobile|Android.*Mobile|iPhone|iPod/i', $ua)) {
            return 'mobile';
        }
        if (preg_match('/iPad|Android(?!.*Mobile)|Tablet/i', $ua)) {
            return 'tablet';
        }
        return 'desktop';
    }
}
