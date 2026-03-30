<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class DashboardController extends AdminBaseController
{
    public function index(): void
    {
        // ─── Content stats ───
        $stats = [];
        try {
            $stats['articles_total'] = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_articles WHERE lang='fr'")['cnt'] ?? 0);
            $stats['articles_published'] = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_articles WHERE status='published' AND lang='fr'")['cnt'] ?? 0);
            $stats['articles_draft'] = $stats['articles_total'] - $stats['articles_published'];
            $stats['messages'] = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_messages")['cnt'] ?? 0);
            $stats['messages_unread'] = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_messages WHERE read_at IS NULL")['cnt'] ?? 0);
            $stats['reviews'] = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_reviews")['cnt'] ?? 0);
            $stats['pages'] = (int)(\Database::fetchOne("SELECT COUNT(DISTINCT slug) as cnt FROM vp_pages WHERE lang='fr'")['cnt'] ?? 0);
            $stats['sections'] = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_sections WHERE lang='fr'")['cnt'] ?? 0);
            $stats['pieces'] = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_pieces WHERE lang='fr'")['cnt'] ?? 0);
            $stats['faq'] = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_faq WHERE lang='fr'")['cnt'] ?? 0);
            $stats['media'] = 0;
            $uploadDir = ROOT . '/public/uploads';
            if (is_dir($uploadDir)) {
                $stats['media'] = count(array_filter(scandir($uploadDir), fn($f) => str_ends_with($f, '.webp') || str_ends_with($f, '.jpg') || str_ends_with($f, '.png')));
            }
        } catch (\Throwable) {
            $stats = array_fill_keys(['articles_total','articles_published','articles_draft','messages','messages_unread','reviews','pages','sections','pieces','faq','media'], 0);
        }

        // ─── Translation coverage ───
        $translations = [];
        try {
            foreach (['en', 'es'] as $l) {
                $filled = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_articles WHERE lang=? AND title != ''", [$l])['cnt'] ?? 0);
                $total = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_articles WHERE lang=?", [$l])['cnt'] ?? 0);
                $translations[$l] = ['filled' => $filled, 'total' => $total, 'pct' => $total > 0 ? round($filled / $total * 100) : 0];
            }
            // Sections translation
            foreach (['en', 'es'] as $l) {
                $filledSec = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_sections WHERE lang=? AND content != '{}' AND content != '[]'", [$l])['cnt'] ?? 0);
                $totalSec = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_sections WHERE lang=?", [$l])['cnt'] ?? 0);
                $translations[$l . '_sections'] = ['filled' => $filledSec, 'total' => $totalSec, 'pct' => $totalSec > 0 ? round($filledSec / $totalSec * 100) : 0];
            }
        } catch (\Throwable) {}

        // ─── SEO health ───
        $seo = [];
        try {
            $seo['articles_with_meta'] = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_articles WHERE lang='fr' AND meta_title != '' AND meta_desc != ''")['cnt'] ?? 0);
            $seo['articles_without_meta'] = $stats['articles_total'] - $seo['articles_with_meta'];
            $seo['articles_with_cover'] = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_articles WHERE lang='fr' AND cover_image != '' AND cover_image IS NOT NULL")['cnt'] ?? 0);
            $seo['articles_with_gso'] = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_articles WHERE lang='fr' AND gso_desc != '' AND gso_desc IS NOT NULL")['cnt'] ?? 0);
            $seo['pages_with_meta'] = (int)(\Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_pages WHERE lang='fr' AND meta_title != '' AND meta_desc != ''")['cnt'] ?? 0);
            $seo['pages_total'] = $stats['pages'];
            $seo['meta_pct'] = $stats['articles_total'] > 0 ? round($seo['articles_with_meta'] / $stats['articles_total'] * 100) : 0;
            $seo['gso_pct'] = $stats['articles_total'] > 0 ? round($seo['articles_with_gso'] / $stats['articles_total'] * 100) : 0;
            $seo['cover_pct'] = $stats['articles_total'] > 0 ? round($seo['articles_with_cover'] / $stats['articles_total'] * 100) : 0;
        } catch (\Throwable) {
            $seo = array_fill_keys(['articles_with_meta','articles_without_meta','articles_with_cover','articles_with_gso','pages_with_meta','pages_total','meta_pct','gso_pct','cover_pct'], 0);
        }

        // ─── Redirections ───
        $redirects = [];
        try {
            $redirects = \Database::fetchAll("SELECT * FROM vp_redirects ORDER BY id ASC");
        } catch (\Throwable) {}

        // ─── SEO files status ───
        $seoFiles = [
            'sitemap.xml' => ['exists' => true, 'url' => '/sitemap.xml', 'type' => 'Auto-généré'],
        ];
        try {
            $dbFiles = \Database::fetchAll("SELECT filename FROM vp_seo_files ORDER BY filename ASC");
            foreach ($dbFiles as $f) {
                $seoFiles[$f['filename']] = ['exists' => true, 'url' => '/' . $f['filename'], 'type' => 'Éditable'];
            }
        } catch (\Throwable) {}
        $seoFiles['.htaccess'] = ['exists' => file_exists(ROOT . '/public/.htaccess'), 'url' => null, 'type' => 'Système'];

        // ─── Recent messages ───
        $recentMessages = [];
        try {
            $recentMessages = \Database::fetchAll("SELECT * FROM vp_messages ORDER BY created_at DESC LIMIT 5");
        } catch (\Throwable) {}

        // ─── Articles needing attention ───
        $articlesAttention = [];
        try {
            $articlesAttention = \Database::fetchAll(
                "SELECT id, title, slug, type, meta_title, meta_desc, cover_image, gso_desc FROM vp_articles WHERE lang='fr' AND (meta_title = '' OR meta_title IS NULL OR meta_desc = '' OR meta_desc IS NULL OR cover_image = '' OR cover_image IS NULL) ORDER BY created_at DESC LIMIT 10"
            );
        } catch (\Throwable) {}

        $this->render('admin/dashboard', compact('stats', 'translations', 'seo', 'redirects', 'seoFiles', 'recentMessages', 'articlesAttention'));
    }
}
