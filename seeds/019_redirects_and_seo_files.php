<?php
declare(strict_types=1);

/**
 * Seed 019 — Create vp_redirects and vp_seo_files tables + initial data
 */

require __DIR__ . '/../config.php';

$pdo = Database::getInstance();

// ═══ vp_redirects ═══
$pdo->exec("CREATE TABLE IF NOT EXISTS vp_redirects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url_from VARCHAR(500) NOT NULL,
    url_to VARCHAR(500) NOT NULL,
    status_code INT NOT NULL DEFAULT 301,
    active TINYINT(1) NOT NULL DEFAULT 1,
    note VARCHAR(255) DEFAULT '',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Insert initial redirections (from old villaplaisance.fr)
$redirects = [
    ['villaplaisance.fr/', '/', 301, 'Homepage ancien site'],
    ['villaplaisance.fr/chambre-hotes-bedarrides', '/chambres-d-hotes', 301, 'Ancienne page chambres'],
    ['villaplaisance.fr/maison-hotes-bedarrides', '/location-villa-provence', 301, 'Ancienne page villa'],
    ['villaplaisance.fr/recommandations', '/sur-place', 301, 'Ancienne page recommandations'],
    ['villaplaisance.fr/contact', '/contact', 301, 'Ancienne page contact'],
    ['villaplaisance.fr/politique-confidentialite-bedarrides', '/politique-confidentialite', 301, 'Ancienne page confidentialite'],
    ['villaplaisance.fr/politique-de-cookies-ue', '/politique-confidentialite', 301, 'Ancienne page cookies'],
    ['www.chambres-dhotes.villaplaisance.fr/hello-world', '/', 301, 'Ancien sous-domaine WordPress'],
];

$existing = Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_redirects");
if ((int)($existing['cnt'] ?? 0) === 0) {
    foreach ($redirects as $r) {
        Database::query(
            "INSERT INTO vp_redirects (url_from, url_to, status_code, note) VALUES (?, ?, ?, ?)",
            [$r[0], $r[1], $r[2], $r[3]]
        );
    }
    echo "Inserted " . count($redirects) . " redirections.\n";
} else {
    echo "Redirections already exist, skipping.\n";
}

// ═══ vp_seo_files ═══
$pdo->exec("CREATE TABLE IF NOT EXISTS vp_seo_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(100) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    auto_generate TINYINT(1) NOT NULL DEFAULT 1,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$existingFiles = Database::fetchOne("SELECT COUNT(*) as cnt FROM vp_seo_files");
if ((int)($existingFiles['cnt'] ?? 0) === 0) {
    // robots.txt
    Database::query(
        "INSERT INTO vp_seo_files (filename, content, auto_generate) VALUES (?, ?, ?)",
        ['robots.txt', 'User-agent: *
Allow: /

User-agent: GPTBot
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: ClaudeBot
Allow: /

User-agent: anthropic-ai
Allow: /

User-agent: Google-Extended
Allow: /

Disallow: /admin/
Disallow: /seeds/

Sitemap: {{BASE_URL}}/sitemap.xml', 0]
    );

    // llms.txt
    Database::query(
        "INSERT INTO vp_seo_files (filename, content, auto_generate) VALUES (?, ?, ?)",
        ['llms.txt', '# Villa Plaisance — Bédarrides, Provence

> Chambres d\'hôtes et villa de charme à Bédarrides, Vaucluse (84370), au cœur du Triangle d\'Or provençal.

## Deux offres saisonnières

### Chambres d\'hôtes (septembre à juin)
- 2 chambres climatisées : Chambre Verte (lit 160×200, vue jardin) et Chambre Bleue (2 lits 90×200, bibliothèque 300 livres)
- Petit-déjeuner maison avec produits locaux
- Piscine partagée avec les hôtes
- Accueil personnalisé

### Villa entière en exclusivité (juillet et août)
- 4 chambres : Verte, Bleue, Arche (arche bleue nuit, bibliothèques sol-plafond), Chambre 70 (mobilier vintage)
- Piscine privée clôturée 12×6m
- Cuisine entièrement équipée
- Jardin provençal
- Jusqu\'à 10 personnes
- Gestion autonome

## Localisation — Triangle d\'Or
- Bédarrides, Vaucluse 84370
- 8 min de Châteauneuf-du-Pape
- 15 min d\'Avignon
- 18 min d\'Orange
- 25 min de L\'Isle-sur-la-Sorgue
- 30 min du Pont du Gard
- 42 min de Gordes
- 45 min des Baux-de-Provence

## Contact
- Site : https://villaplaisance.fr
- Email : contact@villaplaisance.fr

## Langues
- Français (langue principale)
- English
- Español
- Deutsch', 0]
    );

    echo "Inserted 2 SEO files (robots.txt, llms.txt).\n";
} else {
    echo "SEO files already exist, skipping.\n";
}

echo "Done.\n";
