<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class AvisController extends AdminBaseController
{
    public function index(): void
    {
        $reviews = [];
        try {
            $reviews = \Database::fetchAll("SELECT * FROM vp_reviews ORDER BY review_date DESC");
        } catch (\Throwable) {}

        $csrf = $this->csrf();
        $this->render('admin/avis/index', compact('reviews', 'csrf'));
    }

    public function toggle(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/avis');
            return;
        }

        $review = \Database::fetchOne("SELECT * FROM vp_reviews WHERE id = ?", [$id]);
        if ($review) {
            $newStatus = $review['status'] === 'published' ? 'hidden' : 'published';
            \Database::update('vp_reviews', ['status' => $newStatus], 'id = ?', [$id]);
            $this->flash('success', 'Statut de l\'avis modifié.');
        }
        $this->redirect('/admin/avis');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/avis');
            return;
        }

        \Database::delete('vp_reviews', 'id = ?', [$id]);
        $this->flash('success', 'Avis supprimé.');
        $this->redirect('/admin/avis');
    }
}
