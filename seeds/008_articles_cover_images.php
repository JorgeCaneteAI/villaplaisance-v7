<?php
declare(strict_types=1);

/**
 * Seed 008 — Associer les cover images aux articles
 * Photos Unsplash, converties en WebP, < 200 Ko
 * À exécuter UNE SEULE FOIS
 */

require __DIR__ . '/../config.php';

echo "=== Seed 008 : Cover images articles ===\n\n";

// slug => cover_image filename
$covers = [
    // --- Journal (10) ---
    'le-tourisme-de-masse-est-une-arnaque'           => 'article-tourisme-masse.webp',
    'louer-maison-plutot-hotel-voyage'               => 'article-louer-maison-provence.webp',
    'vie-proprietaire-chambre-hotes'                  => 'article-vie-proprietaire-bb.webp',
    'recevoir-des-inconnus-chez-soi'                  => 'article-recevoir-inconnus.webp',
    'chateauneuf-du-pape-2026'                        => 'article-chateauneuf-du-pape.webp',
    'provence-vignerons-autrement'                    => 'article-vignerons-provence.webp',
    'duree-ideale-sejour-provence'                    => 'article-duree-sejour-provence.webp',
    'deconnecter-provence'                            => 'article-deconnecter-provence.webp',
    'bedarrides-provence-authentique'                 => 'article-bedarrides-provence.webp',
    'touriste-2026-nouvelles-attentes'                => 'article-touriste-2026.webp',

    // --- Sur Place (9) ---
    'courses-bedarrides-sorgues'                      => 'article-courses-bedarrides.webp',
    'artisans-savonnerie-chocolaterie'                => 'article-savonnerie-chocolaterie.webp',
    'fontaine-de-vaucluse-guide-pratique'             => 'article-fontaine-vaucluse.webp',
    'sentier-des-ocres-roussillon'                    => 'article-sentier-ocres-roussillon.webp',
    'chateau-la-gardine-chateauneuf-du-pape'          => 'article-chateau-gardine-degustation.webp',
    'parc-spirou-provence-monteux'                    => 'article-parc-spirou-provence.webp',
    'ateliers-creatifs-enfants-provence'              => 'article-ateliers-creatifs-enfants.webp',
    'imperial-bus-diner-bedarrides'                   => 'article-imperial-bus-diner.webp',
    'le-numero-3-bedarrides'                          => 'article-numero-3-bistrot.webp',
];

$ok = 0;
$fail = 0;

foreach ($covers as $slug => $image) {
    try {
        $affected = Database::query(
            "UPDATE vp_articles SET cover_image = ? WHERE slug = ?",
            [$image, $slug]
        );
        echo "  ✅ {$slug} → {$image}\n";
        $ok++;
    } catch (\Throwable $e) {
        echo "  ⚠ {$slug} : {$e->getMessage()}\n";
        $fail++;
    }
}

echo "\n=== Terminé : {$ok} OK, {$fail} erreurs ===\n";

// --- Attributions Unsplash (à conserver pour mentions légales) ---
// article-tourisme-masse.webp           — Photo by Bernd Dittrich on Unsplash
// article-louer-maison-provence.webp    — Photo by Margit Knobloch on Unsplash
// article-vie-proprietaire-bb.webp      — Photo by Cory Bjork on Unsplash
// article-recevoir-inconnus.webp        — Photo by LeeAnn Cline on Unsplash
// article-chateauneuf-du-pape.webp      — Photo by Dario Krejci on Unsplash
// article-vignerons-provence.webp       — Photo by Boudewijn Huysmans on Unsplash
// article-duree-sejour-provence.webp    — Photo by Grant Sams on Unsplash
// article-deconnecter-provence.webp     — Photo by Maurice Sahl on Unsplash
// article-bedarrides-provence.webp      — Photo by Margit Knobloch on Unsplash
// article-touriste-2026.webp            — Photo by Alex Moliski on Unsplash
// article-courses-bedarrides.webp       — Photo by Melanie Vaz on Unsplash
// article-savonnerie-chocolaterie.webp  — Photo by Jose Antonio Rodriguez Davia on Unsplash
// article-fontaine-vaucluse.webp        — Photo by Alexander Van Steenberge on Unsplash
// article-sentier-ocres-roussillon.webp — Photo by Gaetan Thurin on Unsplash
// article-chateau-gardine-degustation.webp — Photo by Tim Nuerminger on Unsplash
// article-parc-spirou-provence.webp     — Photo by Jing Bo Wang on Unsplash
// article-ateliers-creatifs-enfants.webp — Photo by Nur Demirbas on Unsplash
// article-imperial-bus-diner.webp       — Photo by Jonathan Borba on Unsplash
// article-numero-3-bistrot.webp         — Photo by Béla Edgár Váli on Unsplash
