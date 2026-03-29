<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class ReglageController extends AdminBaseController
{
    public function index(): void
    {
        $settings = [];
        try {
            $rows = \Database::fetchAll("SELECT * FROM vp_settings");
            foreach ($rows as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (\Throwable) {}

        $bookingBB = [];
        $bookingVilla = [];
        try {
            $bookingBB = \Database::fetchAll("SELECT * FROM vp_booking_links WHERE offer = 'bb' ORDER BY position ASC");
            $bookingVilla = \Database::fetchAll("SELECT * FROM vp_booking_links WHERE offer = 'villa' ORDER BY position ASC");
        } catch (\Throwable) {}

        $socials = [];
        try {
            $socials = \Database::fetchAll("SELECT * FROM vp_social_links ORDER BY position ASC");
        } catch (\Throwable) {}

        $amenities = [];
        try {
            $rows = \Database::fetchAll("SELECT * FROM vp_amenities ORDER BY category, position ASC");
            foreach ($rows as $row) {
                $amenities[$row['category']][] = $row;
            }
        } catch (\Throwable) {}

        $csrf = $this->csrf();
        $this->render('admin/reglages/index', compact('settings', 'bookingBB', 'bookingVilla', 'socials', 'amenities', 'csrf'));
    }

    public function save(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/reglages');
            return;
        }

        $keys = ['phone', 'email', 'address', 'site_name'];

        try {
            foreach ($keys as $key) {
                $value = trim($_POST[$key] ?? '');
                $existing = \Database::fetchOne("SELECT id FROM vp_settings WHERE setting_key = ?", [$key]);
                if ($existing) {
                    \Database::update('vp_settings', ['setting_value' => $value], 'setting_key = ?', [$key]);
                } else {
                    \Database::insert('vp_settings', ['setting_key' => $key, 'setting_value' => $value]);
                }
            }
            $this->flash('success', 'Informations générales enregistrées.');
        } catch (\Throwable $e) {
            $this->flash('error', 'Erreur : ' . $e->getMessage());
        }

        $this->redirect('/admin/reglages');
    }

    // --- Booking links ---

    public function addBooking(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/reglages');
            return;
        }

        $offer = $_POST['offer'] ?? 'bb';
        $name = trim($_POST['platform_name'] ?? '');
        $url = trim($_POST['url'] ?? '');

        if ($name === '' || $url === '') {
            $this->flash('error', 'Nom et URL requis.');
            $this->redirect('/admin/reglages');
            return;
        }

        $max = \Database::fetchOne("SELECT MAX(position) as mx FROM vp_booking_links WHERE offer = ?", [$offer]);
        \Database::insert('vp_booking_links', [
            'offer' => $offer,
            'platform_name' => $name,
            'url' => $url,
            'position' => ($max['mx'] ?? 0) + 1,
        ]);

        $this->flash('success', "Lien {$name} ajouté.");
        $this->redirect('/admin/reglages');
    }

    public function updateBooking(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/reglages');
            return;
        }

        \Database::update('vp_booking_links', [
            'platform_name' => trim($_POST['platform_name'] ?? ''),
            'url' => trim($_POST['url'] ?? ''),
        ], 'id = ?', [$id]);

        $this->flash('success', 'Lien mis à jour.');
        $this->redirect('/admin/reglages');
    }

    public function deleteBooking(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/reglages');
            return;
        }

        \Database::delete('vp_booking_links', 'id = ?', [$id]);
        $this->flash('success', 'Lien supprimé.');
        $this->redirect('/admin/reglages');
    }

    // --- Social links ---

    public function addSocial(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/reglages');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $url = trim($_POST['url'] ?? '');

        if ($name === '' || $url === '') {
            $this->flash('error', 'Nom et URL requis.');
            $this->redirect('/admin/reglages');
            return;
        }

        $max = \Database::fetchOne("SELECT MAX(position) as mx FROM vp_social_links");
        \Database::insert('vp_social_links', [
            'name' => $name,
            'url' => $url,
            'icon' => strtolower($name),
            'position' => ($max['mx'] ?? 0) + 1,
        ]);

        $this->flash('success', "Réseau {$name} ajouté.");
        $this->redirect('/admin/reglages');
    }

    public function updateSocial(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/reglages');
            return;
        }

        \Database::update('vp_social_links', [
            'name' => trim($_POST['name'] ?? ''),
            'url' => trim($_POST['url'] ?? ''),
            'icon' => strtolower(trim($_POST['name'] ?? '')),
        ], 'id = ?', [$id]);

        $this->flash('success', 'Réseau mis à jour.');
        $this->redirect('/admin/reglages');
    }

    public function deleteSocial(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/reglages');
            return;
        }

        \Database::delete('vp_social_links', 'id = ?', [$id]);
        $this->flash('success', 'Réseau supprimé.');
        $this->redirect('/admin/reglages');
    }

    // --- Amenities ---

    public function addAmenity(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/reglages');
            return;
        }

        $category = trim($_POST['category'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '') ?: null;

        if ($category === '' || $name === '') {
            $this->flash('error', 'Catégorie et nom requis.');
            $this->redirect('/admin/reglages');
            return;
        }

        $max = \Database::fetchOne("SELECT MAX(position) as mx FROM vp_amenities WHERE category = ?", [$category]);
        \Database::insert('vp_amenities', [
            'category' => $category,
            'name' => $name,
            'description' => $description,
            'offer_bb' => isset($_POST['offer_bb']) ? 1 : 0,
            'offer_villa' => isset($_POST['offer_villa']) ? 1 : 0,
            'position' => ($max['mx'] ?? 0) + 1,
        ]);

        $this->flash('success', "Équipement « {$name} » ajouté.");
        $this->redirect('/admin/reglages');
    }

    public function updateAmenity(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/reglages');
            return;
        }

        \Database::update('vp_amenities', [
            'category' => trim($_POST['category'] ?? ''),
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? '') ?: null,
        ], 'id = ?', [$id]);

        $this->flash('success', 'Équipement mis à jour.');
        $this->redirect('/admin/reglages');
    }

    public function toggleAmenity(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/reglages');
            return;
        }

        $field = $_POST['field'] ?? '';
        if (!in_array($field, ['offer_bb', 'offer_villa'], true)) {
            $this->flash('error', 'Champ invalide.');
            $this->redirect('/admin/reglages');
            return;
        }

        $item = \Database::fetchOne("SELECT * FROM vp_amenities WHERE id = ?", [$id]);
        if ($item) {
            $newVal = $item[$field] ? 0 : 1;
            \Database::update('vp_amenities', [$field => $newVal], 'id = ?', [$id]);
        }

        $this->redirect('/admin/reglages');
    }

    public function deleteAmenity(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/reglages');
            return;
        }

        \Database::delete('vp_amenities', 'id = ?', [$id]);
        $this->flash('success', 'Équipement supprimé.');
        $this->redirect('/admin/reglages');
    }
}
