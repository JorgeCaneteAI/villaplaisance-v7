<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class SectionController extends AdminBaseController
{
    public function index(string $page_slug = ''): void
    {
        $pages = [];
        try {
            $pages = \Database::fetchAll("SELECT DISTINCT slug FROM vp_pages WHERE lang = 'fr' ORDER BY slug ASC");
        } catch (\Throwable) {}

        $sections = [];
        if ($page_slug !== '') {
            $sections = \BlockService::getAllSections($page_slug, 'fr');
        }

        $blockTypes = \BlockService::getBlockTypes();
        $csrf = $this->csrf();

        $body_class = '';
        $preview_url = '';
        if ($page_slug !== '') {
            $frontUrls = [
                'accueil'                    => '/',
                'chambres-d-hotes'           => '/chambres-d-hotes',
                'location-villa-provence'    => '/location-villa-provence',
                'espaces-exterieurs'         => '/espaces-exterieurs',
                'journal'                    => '/journal',
                'sur-place'                  => '/sur-place',
                'contact'                    => '/contact',
                'mentions-legales'           => '/mentions-legales',
                'politique-confidentialite'  => '/politique-confidentialite',
                'plan-du-site'               => '/plan-du-site',
            ];
            $preview_url = $frontUrls[$page_slug] ?? '/' . $page_slug;
            $body_class = 'has-preview';
        }

        // Load pieces for "cartes" blocks inline editing
        $pieces = [];
        try {
            $pieces = \Database::fetchAll("SELECT * FROM vp_pieces WHERE lang = 'fr' ORDER BY offer ASC, position ASC");
        } catch (\Throwable) {}

        $this->render('admin/sections/index', compact('pages', 'sections', 'page_slug', 'blockTypes', 'csrf', 'body_class', 'preview_url', 'pieces'));
    }

    public function edit(int $id): void
    {
        $section = \BlockService::getSection($id);
        if (!$section) {
            $this->flash('error', 'Section introuvable.');
            $this->redirect('/admin/sections');
            return;
        }

        $blockTypes = \BlockService::getBlockTypes();
        $csrf = $this->csrf();

        $this->render('admin/sections/edit', compact('section', 'blockTypes', 'csrf'));
    }

    public function save(int $id): void
    {
        $section = \BlockService::getSection($id);
        $pageSlug = $section['page_slug'] ?? '';

        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/sections/page/' . $pageSlug);
            return;
        }

        // Build content JSON from typed fields or raw content
        if (isset($_POST['fields']) && is_array($_POST['fields'])) {
            $fields = $_POST['fields'];
            // Decode JSON fields (arrays stored as JSON strings)
            foreach ($fields as $key => $val) {
                if (is_string($val) && str_starts_with(trim($val), '[')) {
                    $decoded = json_decode($val, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $fields[$key] = $decoded;
                    }
                }
                // Convert numeric strings for checkbox/number fields
                if ($val === '0' || $val === '1') {
                    // Keep as-is for checkboxes — they'll be cast properly in the template
                }
            }
            $contentJson = json_encode($fields, JSON_UNESCAPED_UNICODE);
        } else {
            $contentJson = $_POST['content'] ?? '{}';
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'block_type' => $_POST['block_type'] ?? $section['block_type'] ?? 'prose',
            'content' => $contentJson,
            'active' => isset($_POST['active']) ? 1 : 0,
        ];

        \BlockService::saveSection($id, $data);
        $this->flash('success', 'Section « ' . $data['title'] . ' » mise à jour.');
        $this->redirect('/admin/sections/page/' . $pageSlug);
    }

    public function create(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/sections');
            return;
        }

        $pageSlug = $_POST['page_slug'] ?? '';
        // Get max position
        $max = \Database::fetchOne(
            "SELECT MAX(position) as mx FROM vp_sections WHERE page_slug = ? AND lang = 'fr'",
            [$pageSlug]
        );

        $data = [
            'page_slug' => $pageSlug,
            'lang' => 'fr',
            'block_type' => $_POST['block_type'] ?? 'prose',
            'title' => trim($_POST['title'] ?? 'Nouvelle section'),
            'content' => '{}',
            'position' => ($max['mx'] ?? 0) + 1,
            'active' => 1,
        ];

        \BlockService::createSection($data);
        $this->flash('success', 'Section créée.');
        $this->redirect('/admin/sections/page/' . $pageSlug);
    }

    public function toggle(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/sections');
            return;
        }

        $section = \BlockService::getSection($id);
        \BlockService::toggleSection($id);
        $this->flash('success', 'Visibilité modifiée.');
        $this->redirect('/admin/sections/page/' . ($section['page_slug'] ?? ''));
    }

    public function move(int $id, string $direction): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/sections');
            return;
        }

        $section = \BlockService::getSection($id);
        \BlockService::moveSection($id, $direction);
        $this->redirect('/admin/sections/page/' . ($section['page_slug'] ?? ''));
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/sections');
            return;
        }

        $section = \BlockService::getSection($id);
        \BlockService::deleteSection($id);
        $this->flash('success', 'Section supprimée.');
        $this->redirect('/admin/sections/page/' . ($section['page_slug'] ?? ''));
    }
}
