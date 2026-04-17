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

        // Serve custom SEO files from database (ads.txt, humans.txt, security.txt, etc.)
        if (preg_match('#^/([a-z0-9._-]+\.txt)$#i', $this->uri, $m)) {
            if ($this->serveSeoFile($m[1])) {
                return;
            }
        }

        // Front routes
        $this->dispatchFront();
    }

    private function dispatchFront(): void
    {
        $uri = $this->uri;

        // Track page view
        \AnalyticsService::track($uri);
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
            '/livret' => ['Controllers\\Front\\LivretController', 'index'],
            '/votre-hote' => ['Controllers\\Front\\HoteController', 'index'],
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

        // Dynamic routes: /itineraire/{slug}/comment (POST)
        if (preg_match('#^/itineraire/([a-z0-9-]+)/comment$#i', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Front\\ItineraryController', 'comment', ['slug' => strtolower($m[1])]);
            return;
        }

        // Dynamic routes: /itineraire/{slug}
        if (preg_match('#^/itineraire/([a-z0-9-]+)$#i', $normalized, $m)) {
            $this->callController('Controllers\\Front\\ItineraryController', 'show', ['slug' => strtolower($m[1])]);
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
        if ($uri === '/admin/pin') {
            $this->callController('Controllers\\Admin\\AuthController', $_SERVER['REQUEST_METHOD'] === 'POST' ? 'verifyPin' : 'showPin');
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

        $normalized = rtrim($uri, '/');
        if ($normalized === '') $normalized = '/admin';

        // Sécurité — appareils de confiance
        if ($normalized === '/admin/securite') {
            (new \App\Controllers\Admin\SecuriteController())->index();
            return;
        }
        if (preg_match('#^/admin/securite/revoke/(\d+)$#', $normalized, $m)
            && $_SERVER['REQUEST_METHOD'] === 'POST') {
            (new \App\Controllers\Admin\SecuriteController())->revoke((int) $m[1]);
            return;
        }

        // Admin routes
        $adminRoutes = [
            '/admin' => ['Controllers\\Admin\\DashboardController', 'index'],
            '/admin/dashboard' => ['Controllers\\Admin\\DashboardController', 'index'],
            '/admin/analytics' => ['Controllers\\Admin\\AnalyticsController', 'index'],
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
            '/admin/redirects' => ['Controllers\\Admin\\RedirectController', 'index'],
            '/admin/seo-files' => ['Controllers\\Admin\\SeoFileController', 'index'],
            '/admin/host' => ['Controllers\\Admin\\HostController', 'index'],
            '/admin/itineraires' => ['Controllers\\Admin\\AdminItineraryController', 'index'],
            '/admin/itineraires/create' => ['Controllers\\Admin\\AdminItineraryController', 'create'],
        ];

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

        // Avis CRUD
        if ($normalized === '/admin/avis/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\AvisController', 'create');
            return;
        }
        if (preg_match('#^/admin/avis/(\d+)/edit$#', $normalized, $m)) {
            $this->callController('Controllers\\Admin\\AvisController', 'edit', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/avis/(\d+)/update$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\AvisController', 'update', ['id' => (int)$m[1]]);
            return;
        }
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
        if ($normalized === '/admin/livret/save-password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\LivretController', 'savePassword');
            return;
        }
        if (preg_match('#^/admin/livret/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\LivretController', 'delete', ['id' => (int)$m[1]]);
            return;
        }

        // Host profile
        if ($normalized === '/admin/host/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\HostController', 'save');
            return;
        }
        if ($normalized === '/admin/host/block/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\HostController', 'createBlock');
            return;
        }
        if (preg_match('#^/admin/host/block/(\d+)/update$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\HostController', 'updateBlock', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/host/block/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\HostController', 'deleteBlock', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/host/block/(\d+)/move$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\HostController', 'moveBlock', ['id' => (int)$m[1]]);
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
        if ($normalized === '/admin/reglages/pin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ReglageController', 'savePin');
            return;
        }
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

        // API — AI article generation
        if ($normalized === '/admin/api/generate-article' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            @ini_set('display_errors', '0');
            ob_start();
            header('Content-Type: application/json');
            $input = json_decode(file_get_contents('php://input'), true);
            $type = $input['type'] ?? 'journal';
            $title = trim($input['title'] ?? '');
            $subtitle = trim($input['subtitle'] ?? '');
            $category = trim($input['category'] ?? '');
            if ($title === '') {
                echo json_encode(['error' => 'Titre requis']);
                return;
            }
            $result = \AnthropicService::generateArticle($type, $title, $subtitle, $category);
            ob_end_clean();
            if ($result === null) {
                echo json_encode(['error' => 'Erreur API Anthropic. Vérifiez votre clé API.']);
            } elseif (isset($result['_error'])) {
                echo json_encode(['error' => $result['_error']]);
            } else {
                echo json_encode(['success' => true, 'data' => $result]);
            }
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

        // Itineraires CRUD
        if (preg_match('#^/admin/itineraires/(\d+)/edit$#', $normalized, $m)) {
            $this->callController('Controllers\\Admin\\AdminItineraryController', 'edit', ['id' => (int)$m[1]]);
            return;
        }
        if ($normalized === '/admin/itineraires/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\AdminItineraryController', 'store');
            return;
        }
        if (preg_match('#^/admin/itineraires/(\d+)/update$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\AdminItineraryController', 'update', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/itineraires/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\AdminItineraryController', 'delete', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/itineraires/(\d+)/toggle$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\AdminItineraryController', 'toggle', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/itineraires/comment/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\AdminItineraryController', 'deleteComment', ['id' => (int)$m[1]]);
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

        // Proximites CRUD
        if (preg_match('#^/admin/proximites/(\d+)/save$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ProximiteController', 'save', ['id' => (int)$m[1]]);
            return;
        }
        if ($normalized === '/admin/proximites/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ProximiteController', 'create');
            return;
        }
        if (preg_match('#^/admin/proximites/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\ProximiteController', 'delete', ['id' => (int)$m[1]]);
            return;
        }

        // FAQ CRUD
        if (preg_match('#^/admin/faq/(\d+)/save$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\FaqController', 'save', ['id' => (int)$m[1]]);
            return;
        }
        if ($normalized === '/admin/faq/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\FaqController', 'create');
            return;
        }
        if (preg_match('#^/admin/faq/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\FaqController', 'delete', ['id' => (int)$m[1]]);
            return;
        }

        // Stats CRUD
        if (preg_match('#^/admin/stats/(\d+)/save$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\StatController', 'save', ['id' => (int)$m[1]]);
            return;
        }
        if ($normalized === '/admin/stats/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\StatController', 'create');
            return;
        }
        if (preg_match('#^/admin/stats/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\StatController', 'delete', ['id' => (int)$m[1]]);
            return;
        }

        // Redirects CRUD
        if ($normalized === '/admin/redirects/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\RedirectController', 'create');
            return;
        }
        if (preg_match('#^/admin/redirects/(\d+)/update$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\RedirectController', 'update', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/redirects/(\d+)/toggle$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\RedirectController', 'toggle', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/redirects/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\RedirectController', 'delete', ['id' => (int)$m[1]]);
            return;
        }

        // SEO Files CRUD
        if ($normalized === '/admin/seo-files/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\SeoFileController', 'create');
            return;
        }
        if (preg_match('#^/admin/seo-files/(\d+)/edit$#', $normalized, $m)) {
            $this->callController('Controllers\\Admin\\SeoFileController', 'edit', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/seo-files/(\d+)/update$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\SeoFileController', 'update', ['id' => (int)$m[1]]);
            return;
        }
        if (preg_match('#^/admin/seo-files/(\d+)/delete$#', $normalized, $m) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->callController('Controllers\\Admin\\SeoFileController', 'delete', ['id' => (int)$m[1]]);
            return;
        }

        // Calendrier réservations
        if ($normalized === '/admin/calendrier') {
            (new \App\Controllers\Admin\ReservationController())->mois();
            return;
        }
        if ($normalized === '/admin/calendrier/sync' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            (new \App\Controllers\Admin\ReservationController())->sync();
            return;
        }
        if ($normalized === '/admin/calendrier/logs') {
            (new \App\Controllers\Admin\ReservationController())->logs();
            return;
        }
        if ($normalized === '/admin/calendrier/api/code') {
            (new \App\Controllers\Admin\ReservationController())->apiCode();
            return;
        }
        if (preg_match('#^/admin/calendrier/api/quick-update/(\d+)$#', $normalized, $m)
            && $_SERVER['REQUEST_METHOD'] === 'POST') {
            (new \App\Controllers\Admin\ReservationController())->apiQuickUpdate((int) $m[1]);
            return;
        }
        if (preg_match('#^/admin/calendrier/saisie(?:/(\d+))?$#', $normalized, $m)) {
            $id = isset($m[1]) ? (int) $m[1] : null;
            $ctrl = new \App\Controllers\Admin\ReservationController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $ctrl->saveSaisie($id);
            } else {
                $ctrl->showSaisie($id);
            }
            return;
        }
        if (preg_match('#^/admin/calendrier/supprimer/(\d+)$#', $normalized, $m)
            && $_SERVER['REQUEST_METHOD'] === 'POST') {
            (new \App\Controllers\Admin\ReservationController())->supprimer((int) $m[1]);
            return;
        }
        if ($normalized === '/admin/calendrier/liste') {
            (new \App\Controllers\Admin\ReservationController())->liste();
            return;
        }
        if (preg_match('#^/admin/calendrier/annee(?:/(\d{4}))?$#', $normalized, $m)) {
            $year = isset($m[1]) && $m[1] !== '' ? (int) $m[1] : null;
            (new \App\Controllers\Admin\ReservationController())->annee($year);
            return;
        }
        if (preg_match('#^/admin/calendrier/print/(\d{4})/(\d{1,2})$#', $normalized, $m)) {
            (new \App\Controllers\Admin\ReservationController())->printMois((int) $m[1], (int) $m[2]);
            return;
        }
        if (preg_match('#^/admin/calendrier/export/pdf/(\d{4})/(\d{1,2})$#', $normalized, $m)) {
            (new \App\Controllers\Admin\ReservationController())->exportPdfMois((int) $m[1], (int) $m[2]);
            return;
        }
        if (preg_match('#^/admin/calendrier/export/pdf/annee/(\d{4})$#', $normalized, $m)) {
            (new \App\Controllers\Admin\ReservationController())->exportPdfAnnee((int) $m[1]);
            return;
        }
        if (preg_match('#^/admin/calendrier/(\d{4})/(\d{1,2})$#', $normalized, $m)) {
            (new \App\Controllers\Admin\ReservationController())->mois((int) $m[1], (int) $m[2]);
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
        try {
            $file = \Database::fetchOne("SELECT content FROM vp_seo_files WHERE filename = 'robots.txt'");
            if ($file) {
                $base = APP_ENV === 'production' ? 'https://villaplaisance.fr' : APP_URL;
                echo str_replace('{{BASE_URL}}', $base, $file['content']);
                return;
            }
        } catch (\Throwable) {}
        require ROOT . '/app/Views/seo/robots.php';
    }

    private function serveLlms(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        try {
            $file = \Database::fetchOne("SELECT content FROM vp_seo_files WHERE filename = 'llms.txt'");
            if ($file) {
                echo $file['content'];
                return;
            }
        } catch (\Throwable) {}
        require ROOT . '/app/Views/seo/llms.php';
    }

    private function serveSeoFile(string $filename): bool
    {
        try {
            $file = \Database::fetchOne("SELECT content FROM vp_seo_files WHERE filename = ?", [$filename]);
            if ($file) {
                header('Content-Type: text/plain; charset=utf-8');
                $base = APP_ENV === 'production' ? 'https://villaplaisance.fr' : APP_URL;
                echo str_replace('{{BASE_URL}}', $base, $file['content']);
                return true;
            }
        } catch (\Throwable) {}
        return false;
    }
}
