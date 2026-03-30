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

        // Stats
        $stats = [
            'total' => count($reviews),
            'airbnb_bb' => 0, 'airbnb_villa' => 0, 'booking' => 0, 'google' => 0,
            'avg_airbnb' => 0, 'avg_booking' => 0,
        ];
        $airbnbRatings = [];
        $bookingRatings = [];
        foreach ($reviews as $r) {
            if ($r['platform'] === 'airbnb' && $r['offer'] === 'bb') { $stats['airbnb_bb']++; $airbnbRatings[] = (float)$r['rating']; }
            elseif ($r['platform'] === 'airbnb' && $r['offer'] === 'villa') { $stats['airbnb_villa']++; $airbnbRatings[] = (float)$r['rating']; }
            elseif ($r['platform'] === 'booking') { $stats['booking']++; $bookingRatings[] = (float)$r['rating']; }
            elseif ($r['platform'] === 'google') { $stats['google']++; }
        }
        $stats['avg_airbnb'] = $airbnbRatings ? round(array_sum($airbnbRatings) / count($airbnbRatings), 2) : 0;
        $stats['avg_booking'] = $bookingRatings ? round(array_sum($bookingRatings) / count($bookingRatings), 1) : 0;

        $csrf = $this->csrf();
        $this->render('admin/avis/index', compact('reviews', 'csrf', 'stats'));
    }

    public function create(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/avis');
            return;
        }

        $data = [
            'platform' => $_POST['platform'] ?? 'airbnb',
            'offer' => $_POST['offer'] ?? 'bb',
            'author' => trim($_POST['author'] ?? ''),
            'origin' => trim($_POST['origin'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'rating' => (float)($_POST['rating'] ?? 5.0),
            'review_date' => $_POST['review_date'] ?: null,
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'home_carousel' => isset($_POST['home_carousel']) ? 1 : 0,
            'status' => $_POST['status'] ?? 'published',
        ];

        if ($data['author'] === '') {
            $this->flash('error', 'Le nom de l\'auteur est obligatoire.');
            $this->redirect('/admin/avis');
            return;
        }

        \Database::query(
            "INSERT INTO vp_reviews (platform, offer, author, origin, content, rating, review_date, featured, home_carousel, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['platform'], $data['offer'], $data['author'], $data['origin'], $data['content'], $data['rating'], $data['review_date'], $data['featured'], $data['home_carousel'], $data['status']]
        );

        $this->flash('success', 'Avis ajouté.');
        $this->redirect('/admin/avis');
    }

    public function edit(int $id): void
    {
        $review = \Database::fetchOne("SELECT * FROM vp_reviews WHERE id = ?", [$id]);
        if (!$review) {
            $this->flash('error', 'Avis introuvable.');
            $this->redirect('/admin/avis');
            return;
        }

        $csrf = $this->csrf();
        $this->render('admin/avis/edit', compact('review', 'csrf'));
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/avis');
            return;
        }

        $data = [
            'platform' => $_POST['platform'] ?? 'airbnb',
            'offer' => $_POST['offer'] ?? 'bb',
            'author' => trim($_POST['author'] ?? ''),
            'origin' => trim($_POST['origin'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'rating' => (float)($_POST['rating'] ?? 5.0),
            'review_date' => $_POST['review_date'] ?: null,
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'home_carousel' => isset($_POST['home_carousel']) ? 1 : 0,
            'status' => $_POST['status'] ?? 'published',
        ];

        \Database::update('vp_reviews', $data, 'id = ?', [$id]);
        $this->flash('success', 'Avis mis à jour.');
        $this->redirect('/admin/avis');
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
