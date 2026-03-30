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

        $langs = SUPPORTED_LANGS;

        // Load sections for all languages
        $sectionsByLang = [];
        if ($page_slug !== '') {
            foreach ($langs as $l) {
                $sectionsByLang[$l] = \BlockService::getAllSections($page_slug, $l);
            }
        }
        // FR sections are the reference
        $sections = $sectionsByLang['fr'] ?? [];

        $blockTypes = \BlockService::getBlockTypes();
        $csrf = $this->csrf();

        $body_class = '';
        $preview_url = '';

        // Load pieces for "cartes" blocks inline editing
        $pieces = [];
        try {
            $pieces = \Database::fetchAll("SELECT * FROM vp_pieces WHERE lang = 'fr' ORDER BY offer ASC, position ASC");
        } catch (\Throwable) {}

        // Load FAQ items grouped by page_slug and lang
        $faqBySlugLang = [];
        try {
            $allFaq = \Database::fetchAll("SELECT * FROM vp_faq ORDER BY page_slug, lang, position");
            foreach ($allFaq as $f) {
                $faqBySlugLang[$f['page_slug']][$f['lang']][] = $f;
            }
        } catch (\Throwable) {}

        // Load proximites grouped by lang
        $proxByLang = [];
        try {
            $allProx = \Database::fetchAll("SELECT * FROM vp_proximites ORDER BY lang, position");
            foreach ($allProx as $px) {
                $proxByLang[$px['lang']][] = $px;
            }
        } catch (\Throwable) {}

        // Load stats grouped by lang
        $statsByLang = [];
        try {
            $allStats = \Database::fetchAll("SELECT * FROM vp_stats ORDER BY lang, position");
            foreach ($allStats as $s) {
                $statsByLang[$s['lang']][] = $s;
            }
        } catch (\Throwable) {}

        $this->render('admin/sections/index', compact('pages', 'sections', 'sectionsByLang', 'langs', 'page_slug', 'blockTypes', 'csrf', 'body_class', 'preview_url', 'pieces', 'faqBySlugLang', 'statsByLang', 'proxByLang'));
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

        // Propagate shared fields to other language sections
        $sharedKeys = ['image', 'image_alt', 'compact', 'lead', 'dark', 'offer', 'images',
                       'limit', 'offer_filter', 'type', 'page_slug', 'style', 'button_url'];
        $savedFields = json_decode($contentJson, true) ?: [];
        $sharedData = [];
        foreach ($sharedKeys as $sk) {
            if (array_key_exists($sk, $savedFields)) {
                $sharedData[$sk] = $savedFields[$sk];
            }
        }
        if (!empty($sharedData) && !empty($section['position'])) {
            $otherSections = \Database::fetchAll(
                "SELECT id, content FROM vp_sections WHERE page_slug = ? AND position = ? AND id != ?",
                [$pageSlug, $section['position'], $id]
            );
            foreach ($otherSections as $other) {
                $otherContent = json_decode($other['content'], true) ?: [];
                foreach ($sharedData as $sk => $sv) {
                    $otherContent[$sk] = $sv;
                }
                \Database::update('vp_sections', [
                    'content' => json_encode($otherContent, JSON_UNESCAPED_UNICODE),
                ], 'id = ?', [$other['id']]);
            }
        }

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

        $position = ($max['mx'] ?? 0) + 1;
        $blockType = $_POST['block_type'] ?? 'prose';
        $title = trim($_POST['title'] ?? 'Nouvelle section');

        // Create section for all supported languages
        foreach (SUPPORTED_LANGS as $lang) {
            $data = [
                'page_slug' => $pageSlug,
                'lang' => $lang,
                'block_type' => $blockType,
                'title' => $title,
                'content' => '{}',
                'position' => $position,
                'active' => 1,
            ];
            \BlockService::createSection($data);
        }
        $this->flash('success', 'Section créée (FR/EN/ES).');
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
