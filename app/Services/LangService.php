<?php
declare(strict_types=1);

class LangService
{
    private static string $currentLang = 'fr';
    private static array $translations = [];
    private static array $slugMap = [];

    public static function current(): string
    {
        return self::$currentLang;
    }

    public static function init(string $lang = 'fr'): void
    {
        if (!in_array($lang, SUPPORTED_LANGS, true)) {
            $lang = DEFAULT_LANG;
        }
        self::$currentLang = $lang;

        $file = ROOT . '/app/Lang/' . $lang . '.php';
        if (file_exists($file)) {
            self::$translations = require $file;
        }

        // Load slug map for this lang
        $slugFile = ROOT . '/app/Lang/slugs.php';
        if (file_exists($slugFile)) {
            self::$slugMap = require $slugFile;
        }
    }

    public static function get(): string
    {
        return self::$currentLang;
    }

    public static function t(string $key, array $params = []): string
    {
        $text = self::$translations[$key] ?? $key;
        foreach ($params as $k => $v) {
            $text = str_replace(':' . $k, (string)$v, $text);
        }
        return $text;
    }

    public static function url(string $page, ?string $lang = null): string
    {
        $lang = $lang ?? self::$currentLang;
        $slug = self::$slugMap[$lang][$page] ?? $page;

        if ($lang === DEFAULT_LANG) {
            return '/' . ltrim($slug, '/');
        }
        return '/' . $lang . '/' . ltrim($slug, '/');
    }

    public static function switchLangUrl(string $targetLang, string $currentPage): string
    {
        return self::url($currentPage, $targetLang);
    }

    public static function getAllLangs(): array
    {
        return SUPPORTED_LANGS;
    }
}

// Global helper
function t(string $key, array $params = []): string
{
    return LangService::t($key, $params);
}
