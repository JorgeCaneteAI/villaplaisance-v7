<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class DashboardController extends AdminBaseController
{
    public function index(): void
    {
        $stats = [];

        try {
            $stats['articles'] = \Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_articles")['cnt'] ?? 0;
            $stats['articles_published'] = \Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_articles WHERE status = 'published'")['cnt'] ?? 0;
            $stats['messages'] = \Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_messages")['cnt'] ?? 0;
            $stats['messages_unread'] = \Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_messages WHERE read_at IS NULL")['cnt'] ?? 0;
            $stats['reviews'] = \Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_reviews")['cnt'] ?? 0;
            $stats['pages'] = \Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_pages")['cnt'] ?? 0;
        } catch (\Throwable) {
            $stats = ['articles' => 0, 'articles_published' => 0, 'messages' => 0, 'messages_unread' => 0, 'reviews' => 0, 'pages' => 0];
        }

        $recentMessages = [];
        try {
            $recentMessages = \Database::fetchAll("SELECT * FROM vp_messages ORDER BY created_at DESC LIMIT 5");
        } catch (\Throwable) {}

        $this->render('admin/dashboard', compact('stats', 'recentMessages'));
    }
}
