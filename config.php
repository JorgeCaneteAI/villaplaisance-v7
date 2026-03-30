<?php
declare(strict_types=1);

// Define ROOT if not already defined (for seeds)
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}

// Parse .env
$envFile = ROOT . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (!str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// Constants
define('SUPPORTED_LANGS', ['fr', 'en', 'es']);
define('DEFAULT_LANG', 'fr');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8000');

// Load core services (no namespace, loaded explicitly)
require ROOT . '/app/Services/Database.php';
require ROOT . '/app/Services/LangService.php';
require ROOT . '/app/Services/SeoService.php';
require ROOT . '/app/Services/BlockService.php';
require ROOT . '/app/Services/ImageService.php';
require ROOT . '/app/Services/AnthropicService.php';
require ROOT . '/app/Services/AnalyticsService.php';

// PSR-4 minimal autoloader for App\ namespace
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) return;
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = ROOT . '/app/' . $relative . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Secure session
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => APP_ENV === 'production',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
