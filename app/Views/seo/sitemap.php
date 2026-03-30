<?php
declare(strict_types=1);
echo '<?xml version="1.0" encoding="UTF-8"?>';
$base = APP_ENV === 'production' ? 'https://villaplaisance.fr' : APP_URL;
$pages = [
    ['slug' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
    ['slug' => '/chambres-d-hotes/', 'priority' => '0.9', 'changefreq' => 'monthly'],
    ['slug' => '/location-villa-provence/', 'priority' => '0.9', 'changefreq' => 'monthly'],
    ['slug' => '/espaces-exterieurs/', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['slug' => '/journal/', 'priority' => '0.8', 'changefreq' => 'weekly'],
    ['slug' => '/sur-place/', 'priority' => '0.7', 'changefreq' => 'weekly'],
    ['slug' => '/contact/', 'priority' => '0.6', 'changefreq' => 'yearly'],
    ['slug' => '/mentions-legales/', 'priority' => '0.3', 'changefreq' => 'yearly'],
    ['slug' => '/politique-confidentialite/', 'priority' => '0.3', 'changefreq' => 'yearly'],
];

$langPrefixes = ['fr' => '', 'en' => '/en', 'es' => '/es', 'de' => '/de'];

// Load articles from DB
$articles = [];
try {
    $articles = Database::fetchAll("SELECT slug, type, updated_at FROM vp_articles WHERE status = 'published'");
} catch (\Throwable $e) {}
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
<?php foreach ($pages as $page): ?>
<?php foreach ($langPrefixes as $lang => $prefix): ?>
    <url>
        <loc><?= htmlspecialchars($base . $prefix . $page['slug']) ?></loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq><?= $page['changefreq'] ?></changefreq>
        <priority><?= $page['priority'] ?></priority>
<?php foreach ($langPrefixes as $altLang => $altPrefix): ?>
        <xhtml:link rel="alternate" hreflang="<?= $altLang ?>" href="<?= htmlspecialchars($base . $altPrefix . $page['slug']) ?>"/>
<?php endforeach; ?>
        <xhtml:link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($base . $page['slug']) ?>"/>
    </url>
<?php endforeach; ?>
<?php endforeach; ?>

<?php foreach ($articles as $art):
    $section = $art['type'] === 'journal' ? 'journal' : 'sur-place';
?>
    <url>
        <loc><?= htmlspecialchars($base . '/' . $section . '/' . $art['slug'] . '/') ?></loc>
        <lastmod><?= htmlspecialchars(substr($art['updated_at'] ?? date('Y-m-d'), 0, 10)) ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
<?php endforeach; ?>
</urlset>
