<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class AdminItineraryController extends AdminBaseController
{
    public function index(): void
    {
        $itineraries = \Database::fetchAll(
            "SELECT i.*, COUNT(s.id) as step_count
             FROM vp_itineraries i
             LEFT JOIN vp_itinerary_steps s ON s.itinerary_id = i.id
             GROUP BY i.id
             ORDER BY i.created_at DESC"
        );

        $csrf = $this->csrf();
        $this->render('admin/itineraries/index', compact('itineraries', 'csrf'));
    }

    public function create(): void
    {
        $itinerary = [
            'id' => 0, 'slug' => '', 'guest_name' => '',
            'intro_text' => '', 'itinerary_date' => date('Y-m-d'),
            'status' => 'active',
        ];
        $steps = [];
        $csrf = $this->csrf();
        $this->render('admin/itineraries/form', compact('itinerary', 'steps', 'csrf'));
    }

    public function edit(int $id): void
    {
        $itinerary = \Database::fetchOne("SELECT * FROM vp_itineraries WHERE id = ?", [$id]);
        if (!$itinerary) {
            $this->flash('error', 'Itinéraire introuvable.');
            $this->redirect('/admin/itineraires');
            return;
        }
        $steps = \Database::fetchAll(
            "SELECT * FROM vp_itinerary_steps WHERE itinerary_id = ? ORDER BY position ASC",
            [$id]
        );
        $comments = [];
        try {
            $comments = \Database::fetchAll(
                "SELECT * FROM vp_itinerary_comments WHERE itinerary_id = ? ORDER BY created_at DESC",
                [$id]
            );
        } catch (\Throwable) {}
        $csrf = $this->csrf();
        $this->render('admin/itineraries/form', compact('itinerary', 'steps', 'comments', 'csrf'));
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/itineraires/create');
            return;
        }

        $slug = trim($_POST['slug'] ?? '');
        $slug = preg_replace('/[^a-z0-9-]/', '-', strtolower($slug));
        $slug = preg_replace('/-+/', '-', trim($slug, '-'));

        if ($slug === '' || trim($_POST['guest_name'] ?? '') === '') {
            $this->flash('error', 'Le slug et le nom sont requis.');
            $this->redirect('/admin/itineraires/create');
            return;
        }

        $existing = \Database::fetchOne("SELECT id FROM vp_itineraries WHERE slug = ?", [$slug]);
        if ($existing) {
            $this->flash('error', 'Ce slug existe déjà.');
            $this->redirect('/admin/itineraires/create');
            return;
        }

        \Database::insert('vp_itineraries', [
            'slug'           => $slug,
            'guest_name'     => trim($_POST['guest_name']),
            'intro_text'     => trim($_POST['intro_text'] ?? ''),
            'itinerary_date' => $_POST['itinerary_date'] ?: null,
            'status'         => $_POST['status'] ?? 'active',
            'lang'           => $_POST['lang'] ?? 'fr',
        ]);

        $itineraryId = (int)\Database::fetchOne("SELECT id FROM vp_itineraries WHERE slug = ?", [$slug])['id'];
        $this->saveSteps($itineraryId);

        $this->flash('success', 'Itinéraire créé. Lien : /itineraire/' . $slug);
        $this->redirect('/admin/itineraires');
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/itineraires/' . $id . '/edit');
            return;
        }

        $itinerary = \Database::fetchOne("SELECT * FROM vp_itineraries WHERE id = ?", [$id]);
        if (!$itinerary) {
            $this->flash('error', 'Itinéraire introuvable.');
            $this->redirect('/admin/itineraires');
            return;
        }

        $slug = trim($_POST['slug'] ?? '');
        $slug = preg_replace('/[^a-z0-9-]/', '-', strtolower($slug));
        $slug = preg_replace('/-+/', '-', trim($slug, '-'));

        $existing = \Database::fetchOne("SELECT id FROM vp_itineraries WHERE slug = ? AND id != ?", [$slug, $id]);
        if ($existing) {
            $this->flash('error', 'Ce slug est déjà utilisé.');
            $this->redirect('/admin/itineraires/' . $id . '/edit');
            return;
        }

        \Database::query(
            "UPDATE vp_itineraries SET slug = ?, guest_name = ?, intro_text = ?, itinerary_date = ?, status = ?, lang = ? WHERE id = ?",
            [$slug, trim($_POST['guest_name']), trim($_POST['intro_text'] ?? ''), $_POST['itinerary_date'] ?: null, $_POST['status'] ?? 'active', $_POST['lang'] ?? 'fr', $id]
        );

        \Database::query("DELETE FROM vp_itinerary_steps WHERE itinerary_id = ?", [$id]);
        $this->saveSteps($id);

        $this->flash('success', 'Itinéraire mis à jour.');
        $this->redirect('/admin/itineraires');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/itineraires');
            return;
        }
        \Database::query("DELETE FROM vp_itineraries WHERE id = ?", [$id]);
        $this->flash('success', 'Itinéraire supprimé.');
        $this->redirect('/admin/itineraires');
    }

    public function toggle(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/itineraires');
            return;
        }
        $itinerary = \Database::fetchOne("SELECT status FROM vp_itineraries WHERE id = ?", [$id]);
        if ($itinerary) {
            $newStatus = $itinerary['status'] === 'active' ? 'archived' : 'active';
            \Database::query("UPDATE vp_itineraries SET status = ? WHERE id = ?", [$newStatus, $id]);
            $this->flash('success', $newStatus === 'active' ? 'Itinéraire réactivé.' : 'Itinéraire archivé.');
        }
        $this->redirect('/admin/itineraires');
    }

    public function deleteComment(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/itineraires');
            return;
        }

        $comment = \Database::fetchOne("SELECT itinerary_id FROM vp_itinerary_comments WHERE id = ?", [$id]);
        if ($comment) {
            \Database::query("DELETE FROM vp_itinerary_comments WHERE id = ?", [$id]);
            $this->flash('success', 'Commentaire supprimé.');
            $this->redirect('/admin/itineraires/' . $comment['itinerary_id'] . '/edit');
            return;
        }

        $this->flash('error', 'Commentaire introuvable.');
        $this->redirect('/admin/itineraires');
    }

    private function saveSteps(int $itineraryId): void
    {
        $times        = $_POST['step_time'] ?? [];
        $titles       = $_POST['step_title'] ?? [];
        $durations    = $_POST['step_duration'] ?? [];
        $descriptions = $_POST['step_description'] ?? [];
        $images       = $_POST['step_image'] ?? [];
        $links        = $_POST['step_link'] ?? [];

        foreach ($titles as $i => $title) {
            $title = trim($title);
            if ($title === '') continue;
            $data = [
                'itinerary_id' => $itineraryId,
                'time_label'   => trim($times[$i] ?? ''),
                'title'        => $title,
                'duration'     => trim($durations[$i] ?? ''),
                'description'  => trim($descriptions[$i] ?? ''),
                'position'     => $i + 1,
            ];
            $img = trim($images[$i] ?? '');
            if ($img !== '') {
                $data['image'] = $img;
            }
            $link = trim($links[$i] ?? '');
            if ($link !== '') {
                $data['link'] = $link;
            }
            \Database::insert('vp_itinerary_steps', $data);
        }
    }
}
