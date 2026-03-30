<?php
declare(strict_types=1);

class BlockService
{
    public static function getSections(string $pageSlug, string $lang = 'fr'): array
    {
        try {
            return Database::fetchAll(
                "SELECT * FROM vp_sections WHERE page_slug = ? AND lang = ? AND active = 1 ORDER BY position ASC",
                [$pageSlug, $lang]
            );
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function getAllSections(string $pageSlug, string $lang = 'fr'): array
    {
        try {
            return Database::fetchAll(
                "SELECT * FROM vp_sections WHERE page_slug = ? AND lang = ? ORDER BY position ASC",
                [$pageSlug, $lang]
            );
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function getSection(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM vp_sections WHERE id = ?", [$id]);
    }

    public static function saveSection(int $id, array $data): int
    {
        return Database::update('vp_sections', $data, 'id = ?', [$id]);
    }

    public static function createSection(array $data): int
    {
        return Database::insert('vp_sections', $data);
    }

    public static function deleteSection(int $id): int
    {
        return Database::delete('vp_sections', 'id = ?', [$id]);
    }

    public static function moveSection(int $id, string $direction): void
    {
        $section = self::getSection($id);
        if (!$section) return;

        $currentPos = (int)$section['position'];
        $newPos = $direction === 'up' ? $currentPos - 1 : $currentPos + 1;

        // Swap with neighbor
        $neighbor = Database::fetchOne(
            "SELECT id FROM vp_sections WHERE page_slug = ? AND lang = ? AND position = ?",
            [$section['page_slug'], $section['lang'], $newPos]
        );

        if ($neighbor) {
            Database::update('vp_sections', ['position' => $currentPos], 'id = ?', [$neighbor['id']]);
            Database::update('vp_sections', ['position' => $newPos], 'id = ?', [$id]);
        }
    }

    public static function toggleSection(int $id): void
    {
        $section = self::getSection($id);
        if (!$section) return;
        $newActive = $section['active'] ? 0 : 1;
        Database::update('vp_sections', ['active' => $newActive], 'id = ?', [$id]);
    }

    public static function renderBlock(array $section): string
    {
        $type = $section['block_type'];
        $data = json_decode($section['content'] ?? '{}', true) ?: [];
        $data['section'] = $section;

        $template = ROOT . '/app/Views/blocks/' . $type . '.php';
        if (!file_exists($template)) {
            return "<!-- Block type '{$type}' not found -->";
        }

        ob_start();
        extract($data);
        require $template;
        return ob_get_clean();
    }

    public static function getPage(string $slug, string $lang = 'fr'): ?array
    {
        try {
            return Database::fetchOne(
                "SELECT * FROM vp_pages WHERE slug = ? AND lang = ?",
                [$slug, $lang]
            );
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function getBlockTypes(): array
    {
        return [
            'hero' => 'Hero (H1 + CTA)',
            'prose' => 'Texte (H2 + paragraphe)',
            'cartes' => 'Cartes (pièces/espaces)',
            'liste' => 'Liste (items)',
            'tableau' => 'Tableau (lignes)',
            'cta' => 'Call to Action',
            'avis' => 'Avis clients',
            'faq' => 'FAQ',
            'stats' => 'Chiffres clés',
            'territoire' => 'Territoire (lieux)',
            'galerie' => 'Galerie photos',
            'articles' => 'Articles (extraits)',
            'petit-dejeuner' => 'Petit-déjeuner',
            'piscine' => 'Piscine',
            'mappemonde' => 'Mappemonde (origines clients)',
        ];
    }
}
