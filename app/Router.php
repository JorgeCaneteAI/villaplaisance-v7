<?php
declare(strict_types=1);

namespace App;

class Router
{
    private string $lang = 'fr';
    private string $uri = '';

    public function dispatch(): void
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $requestUri = '/' . trim($requestUri, '/');

        // Extract language prefix
        if (preg_match('#^/(en|es|de)(/.*)?$#', $requestUri, $m)) {
            $this->lang = $m[1];
            $requestUri = $m[2] ?? '/';
            if ($requestUri === '') $requestUri = '/';
        }

        \LangService::init($this->lang);
        $this->uri = $requestUri;

        // Admin routes
        if (str_starts_with($this->uri, '/admin')) {
            $this->dispatchAdmin();
            return;
        }

        // Static files (sitemap, robots, llms.txt)
        if ($this->uri === '/sitemap.xml') {
            $this->serveSitemap();
            return;
        }
        if ($this->uri === '/robots.txt') {
            $this->serveRobots();
            return;
        }
        if ($this->uri === '/llms.txt') {
            $this->serveLlms();
            return;
        }

        // Front routes
        $this->dispatchFront();
    }

    private function dispatchFront(): void
    {
        $uri = $this->uri;
        $slugMap = $this->getSlugToPageMap();

        // Normalize: strip trailing slash for matching
        $normalized = rtrim($uri, '/');
        if ($normalized === '') $normalized = '/';

        // Direct route matching
        $routes = [
            '/' => ['Controllers\\Front\\HomeController', 'index'],
            '/chambres-d-hotes' => ['Controllers\\Front\\ChambresController', 'index'],
            '/location-villa-provence' => ['Controllers\\Front\\VillaController', 'index'],
            '/espaces-exterieurs' => ['Controllers\\Front\\ExterieursController', 'index'],
            '/journal' => ['Controllers\\Front\\JournalController', 'index'],
            '/sur-place' => ['Controllers\\Front\\SurPlaceController', 'index'],
            '/contact' => ['Controllers\\Front\\ContactController', 'index'],
            '/mentions-legales' => ['Controllers\\Front\\LegalController', 'mentions'],
            '/politique-confidentialite' => ['Controllers\\Front\\LegalController', 'confidentialite'],
            '/plan-du-site' => ['Controllers\\Front\\LegalController', 'planDuSite'],
        ];

        // Multilingual slug resolution
        if ($this->lang !== 'fr') {
            foreach ($slugMap as $slug => $page) {
                if ('/' . $slug === $normalized) {
                    if (isset($routes['/' . $page]) || isset($routes[$page])) {
                        $route = $routes['/' . $page] ?? $routes[$page];
                        $this->callController($route[0], $route[1]);
                        return;
                    }
                }
            }
        }

        // FR routes
        if (isset($routes[$normalized])) {
            $this->callController($routes[$normalized][0], $routes[$normalized][1]);
            return;
        }

        // Dynamic routes: /journal/{slug}
        if (preg_match('#^/journal/([a-z0-9-]+)$#', $normalized, $m)) {
            $this->callController('Controllers\\Front\\JournalController', 'show', ['slug' => $m[1]]);
            return;
        }

        // Dynamic routes: /sur-place/{slug}
        if (preg_match('#^/sur-place/([a-z0-9-]+)$#', $normalized, $m)) {
            $this->callController('Controllers\\Front\\SurPlaceController', 'show', ['slug' => $m[1]]);
            return;
        }

        // 404
        $this->callController('Controllers\\Front\\HomeController', 'notFound');
    }

    private function dispatchAdmin(): void
    {
        $uri = $this->uri;

        // Auth routes (no session check)
        if ($uri === '/admin/login') {
            $this->callController('Controllers\\Admin\\AuthController', $_SERVER['REQUEST_METHOD'] === 'POST' ? 'login' : 'showLogin');
            return;
        }
        if ($uri === '/admin/logout') {
            $this->callController('Controllers\\Admin\\AuthController', 'logout');
            return;
        }
        if ($uri === '/admin/forgot-password') {
            $this->callController('Controllers\\Admin\\AuthController', $_SERVER['REQUEST_METHOD'] === 'POST' ? 'forgotPassword' : 'showForgotPassword');
            return;
        }
        if ($uri === '/admin/reset-password' || str_starts_with($uri, '/admin/reset-password')) {
            $this->callController('Controllers\\Admin\\AuthController', $_SERVER['REQUEST_METHOD'] === 'POST' ? 'resetPassword' : 'showResetPassword');
            return;
        }

        // Check auth for all other admin routes
        if (empty($_SESSION['admin_authenticated'])) {
            header('Location: /admin/login');
            exit;
        }

        // Admin routes
        $adminRoutes = [
            '/admin' => ['Controllers\\Admin\\DashboardController', 'index'],
            '/admin/dashboard' => ['Controllers\\Admin\\DashboardController', 'index'],
            '/admin/articles' => ['Controllers\\Admin\\ArticleController', 'index'],
            '/admin/articles/create' => ['Controllers\\Admin\\ArticleController', 'create'],
            '/admin/messages' => ['Controllers\\Admin\\MessageController', 'index'],
            '/admin/livret' => ['Controllers\\Admin\\LivretController', 'index'],
            '/admin/reglages' => ['Controllers\\Admin\\ReglageController', 'index'],
            '/admin/media' => ['Controllers\\Admin\\MediaController', 'index'],
            '/admin/avis' => ['Controllers\\Admin\\AvisController', 'index'],
            '/admin/pages' => ['Controllers\\Admin\\PageController', 'index'],
            '/admin/sections' => ['Controllers\\Admin\\SectionController', 'index'],
            '/admin/pieces' => ['Controllers\\Admin\\PieceController', 'index'],
        ];

        $normalized = rtrim($uri, '/');
        if ($normalized === '') $normalized = '/admin';

        if (isset($adminRoutes[$normalized])) {
            $method = $_SERVER['REQUEST_METHOD'] === 'POST' ? 'store' : $adminRoutes[$normalized][1];
            // For specific pages, POST goes to store
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($normalized, ['/admin/reglages', '/admin/contact'])) {
                $method = 'store';
            }
            $this->callController($adminRoutes[$normalized][0], $adminRoutes[$normalized][1]);
            return;
        }

        // Dynamic admin routes
        if (preg_match('#^/admin/articles/(\d+)/edit$#', $normalized, $m)) {
            $this->callController('Controllers\\Admin\\ArticleController', 'edit', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/articles/(\d+)/update$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ArticleController', 'update', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/articles/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ArticleController', 'delete', ['id' => (int)$m[1]]);
            return;
        }
        if ($normalized === '/admin/articles/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ArticleController', 'store');
            return;
        }

        // Messages
        if (preg_match('#^/admin/messages/(\d+)$#', $normalized, $m)) {
            $this->callController('Controllers\\Admin\\MessageController', 'show', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/messages/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\MessageController', 'delete', ['id' => (int)$m[1]]);
            return;
        }

        // Avis
        if (preg_match('#^/admin/avis/(\d+)/toggle$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\AvisController', 'toggle', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/avis/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\AvisController', 'delete', ['id' => (int)$m[1]]);
            return;
        }

        // Livret
        if ($normalized === '/admin/livret/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\LivretController', 'save');
            return;
        }

        // Media
        if ($normalized === '/admin/media/upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\MediaController', 'upload');
            return;
        }
        if (preg_match('#^/admin/media/(\d+)/edit$#', $normalized, $m)) {
            $this->callController('Controllers\\Admin\\MediaController', 'edit', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/media/(\d+)/update$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\MediaController', 'update', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/media/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\MediaController', 'delete', ['id' => (int)$m[1]]);
            return;
        }

        // Reglages save
        if ($normalized === '/admin/reglages/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ReglageController', 'save');
            return;
        }

        // Reglages — Booking links
        if ($normalized === '/admin/reglages/booking/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ReglageController', 'addBooking');
            return;
        }
        if (preg_match('#^/admin/reglages/booking/(\d+)/update$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ReglageController', 'updateBooking', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/reglages/booking/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ReglageController', 'deleteBooking', ['id' => (int)$m[1]]);
            return;
        }

        // Reglages — Social links
        if ($normalized === '/admin/reglages/social/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ReglageController', 'addSocial');
            return;
        }
        if (preg_match('#^/admin/reglages/social/(\d+)/update$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ReglageController', 'updateSocial', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/reglages/social/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ReglageController', 'deleteSocial', ['id' => (int)$m[1]]);
            return;
        }

        // Reglages — Amenities
        if ($normalized === '/admin/reglages/amenity/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ReglageController', 'addAmenity');
            return;
        }
        if (preg_match('#^/admin/reglages/amenity/(\d+)/update$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ReglageController', 'updateAmenity', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/reglages/amenity/(\d+)/toggle$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ReglageController', 'toggleAmenity', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/reglages/amenity/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ReglageController', 'deleteAmenity', ['id' => (int)$m[1]]);
            return;
        }

        // Pieces (rooms/spaces)
        if (preg_match('#^/admin/pieces/(\d+)/save$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\PieceController', 'save', ['id' => (int)$m[1]]);
            return;
        }
        if ($normalized === '/admin/pieces/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\PieceController', 'create');
            return;
        }
        if (preg_match('#^/admin/pieces/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\PieceController', 'delete', ['id' => (int)$m[1]]);
            return;
        }

        // API — media list for picker
        if ($normalized === '/admin/api/media-list') {
            header('Content-Type: application/json');
            $dir = ROOT . '/public/uploads';
            $files = [];
            if (is_dir($dir)) {
                foreach (scandir($dir) as $f) {
                    if (str_ends_with($f, '.webp') || str_ends_with($f, '.jpg') || str_ends_with($f, '.png')) {
                        $files[] = $f;
                    }
                }
            }
            sort($files);
            echo json_encode($files);
            return;
        }

        // Sections (CMS blocks)
        if (preg_match('#^/admin/sections/(\d+)/edit$#', $normalized, $m)) {
            $this->callController('Controllers\\Admin\\SectionController', 'edit', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/sections/(\d+)/save$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\SectionController', 'save', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/sections/(\d+)/toggle$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\SectionController', 'toggle', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/sections/(\d+)/move/(up|down)$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\SectionController', 'move', ['id' => (int)$m[1], 'direction' => $m[2]]);
            return;
        }
        if ($normalized === '/admin/sections/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\SectionController', 'create');
            return;
        }
        if (preg_match('#^/admin/sections/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\SectionController', 'delete', ['id' => (int)$m[1]]);
            return;
        }
        // Sections filtered by page
        if (preg_match('#^/admin/sections/page/([a-z0-9-]+)$#', $normalized, $m)) {
            $this->callController('Controllers\\Admin\\SectionController', 'index', ['page_slug' => $m[1]]);
            return;
        }

        // 404 admin
        http_response_code(404);
        echo '<h1>404 — Page admin introuvable</h1>';
    }

    private function callController(string $class, string $method, array $params = []): void
    {
        $fullClass = 'App\\' . $class;
        if (!class_exists($fullClass)) {
            http_response_code(500);
            echo "<h1>500 — Controller introuvable : {$fullClass}</h1>";
            return;
        }
        $controller = new $fullClass();
        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo "<h1>500 — Méthode introuvable : {$fullClass}::{$method}</h1>";
            return;
        }
        $controller->$method(...array_values($params));
    }

    private function getSlugToPageMap(): array
    {
        $slugFile = ROOT . '/app/Lang/slugs.php';
        if (!file_exists($slugFile)) return [];
        $map = require $slugFile;
        return $map[$this->lang] ?? [];
    }

    private function serveSitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        require ROOT . '/app/Views/seo/sitemap.php';
    }

    private function serveRobots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        require ROOT . '/app/Views/seo/robots.php';
    }

    private function serveLlms(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        require ROOT . '/app/Views/seo/llms.php';
    }
}
