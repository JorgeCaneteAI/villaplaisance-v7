<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class PageController extends AdminBaseController
{
    public function index(): void
    {
        $pages = [];
        try {
            $pages = \Database::fetchAll("SELECT * FROM vp_pages WHERE lang = 'fr' ORDER BY id ASC");
        } catch (\Throwable) {}

        // Count sections per page
        $sectionCounts = [];
        try {
            $rows = \Database::fetchAll("SELECT page_slug, COUNT(*) as cnt FROM vp_sections WHERE lang = 'fr' GROUP BY page_slug");
            foreach ($rows as $r) {
                $sectionCounts[$r['page_slug']] = (int)$r['cnt'];
            }
        } catch (\Throwable) {}

        $csrf = $this->csrf();
        $this->render('admin/pages/index', compact('pages', 'sectionCounts', 'csrf'));
    }
}
