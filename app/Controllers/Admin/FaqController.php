<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class FaqController extends AdminBaseController
{
    public function save(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->back();
            return;
        }

        $fields = [
            'question' => trim($_POST['question'] ?? ''),
            'answer' => trim($_POST['answer'] ?? ''),
        ];

        \Database::update('vp_faq', $fields, 'id = ?', [$id]);
        $this->flash('success', 'FAQ mise à jour.');
        $this->back();
    }

    public function create(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->back();
            return;
        }

        $pageSlug = $_POST['page_slug'] ?? '';
        $max = \Database::fetchOne(
            "SELECT MAX(position) as mx FROM vp_faq WHERE page_slug = ? AND lang = 'fr'",
            [$pageSlug]
        );
        $position = ($max['mx'] ?? 0) + 1;

        // Create for all supported languages
        foreach (SUPPORTED_LANGS as $lang) {
            \Database::query(
                "INSERT INTO vp_faq (page_slug, lang, question, answer, position, active) VALUES (?, ?, ?, ?, ?, 1)",
                [$pageSlug, $lang, '', '', $position]
            );
        }

        $this->flash('success', 'FAQ ajoutée (FR/EN/ES).');
        $this->back();
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->back();
            return;
        }

        // Get the FAQ to find page_slug + position, then delete all languages
        $faq = \Database::fetchOne("SELECT * FROM vp_faq WHERE id = ?", [$id]);
        if ($faq) {
            \Database::query(
                "DELETE FROM vp_faq WHERE page_slug = ? AND position = ?",
                [$faq['page_slug'], $faq['position']]
            );
        }

        $this->flash('success', 'FAQ supprimée (toutes langues).');
        $this->back();
    }

    private function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '/admin/sections';
        $this->redirect(parse_url($ref, PHP_URL_PATH));
    }
}
