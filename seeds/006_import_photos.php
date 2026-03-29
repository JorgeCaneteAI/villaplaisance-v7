<?php
declare(strict_types=1);

/**
 * Import des photos originales → conversion WebP + médiathèque SEO
 * Usage: php seeds/006_import_photos.php
 */

if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}
require ROOT . '/config.php';

$sourceDir = dirname(ROOT) . '/vrac/photos villa plaisance';
$uploadDir = ROOT . '/public/uploads/';

if (!is_dir($sourceDir)) {
    echo "❌ Dossier source introuvable : {$sourceDir}\n";
    exit(1);
}

// Mapping: folder name → [media folder, SEO prefix, alt_fr base]
$folderMap = [
    '1er etage chambre bleue' => [
        'folder' => 'chambres',
        'prefix' => 'chambre-bleue',
        'alt' => 'Chambre Bleue, premier étage — Villa Plaisance, Bédarrides',
    ],
    '1er etage salon - Salle a manger' => [
        'folder' => 'villa',
        'prefix' => 'salon-salle-a-manger',
        'alt' => 'Salon et salle à manger — Villa Plaisance, Bédarrides',
    ],
    '1er étage chambre verte' => [
        'folder' => 'chambres',
        'prefix' => 'chambre-verte',
        'alt' => 'Chambre Verte, premier étage — Villa Plaisance, Bédarrides',
    ],
    '1er étage cusine' => [
        'folder' => 'villa',
        'prefix' => 'cuisine-equipee',
        'alt' => 'Cuisine entièrement équipée — Villa Plaisance, Bédarrides',
    ],
    '1er étage entree' => [
        'folder' => 'villa',
        'prefix' => 'entree-premier-etage',
        'alt' => 'Entrée et couloir du premier étage — Villa Plaisance, Bédarrides',
    ],
    '1er étage salle de bain chambre dhotes' => [
        'folder' => 'chambres',
        'prefix' => 'salle-de-bain-chambre-hotes',
        'alt' => 'Salle de bain privative, chambre d\'hôtes — Villa Plaisance, Bédarrides',
    ],
    '1er étage salle de bain maison d\'hotes' => [
        'folder' => 'chambres',
        'prefix' => 'salle-de-bain-maison-hotes',
        'alt' => 'Salle de bain de la maison d\'hôtes — Villa Plaisance, Bédarrides',
    ],
    'détails villa plaisance' => [
        'folder' => 'divers',
        'prefix' => 'detail-decoration',
        'alt' => 'Détail de décoration provençale — Villa Plaisance, Bédarrides',
    ],
    'exterieurs villa plaisance' => [
        'folder' => 'exterieurs',
        'prefix' => 'jardin-exterieur',
        'alt' => 'Jardin et espaces extérieurs — Villa Plaisance, Bédarrides',
    ],
    'facade villa plaisance' => [
        'folder' => 'exterieurs',
        'prefix' => 'facade',
        'alt' => 'Façade de Villa Plaisance — maison de charme à Bédarrides, Provence',
    ],
    'pisicne villa plaisance' => [
        'folder' => 'exterieurs',
        'prefix' => 'piscine-privee',
        'alt' => 'Piscine privée clôturée 12x6m — Villa Plaisance, Bédarrides',
    ],
    'plantes villa plaisance' => [
        'folder' => 'exterieurs',
        'prefix' => 'plantes-jardin',
        'alt' => 'Plantes et végétation du jardin provençal — Villa Plaisance, Bédarrides',
    ],
    'rez de jardin bureau' => [
        'folder' => 'villa',
        'prefix' => 'bureau-rez-de-jardin',
        'alt' => 'Bureau au rez-de-jardin — Villa Plaisance, Bédarrides',
    ],
    'rez de jardin chambre 70' => [
        'folder' => 'chambres',
        'prefix' => 'chambre-annees-70',
        'alt' => 'Chambre Années 70, rez-de-jardin — Villa Plaisance, Bédarrides',
    ],
    'rez de jardin chambre arche' => [
        'folder' => 'chambres',
        'prefix' => 'chambre-arche',
        'alt' => 'Chambre de l\'Arche, rez-de-jardin — Villa Plaisance, Bédarrides',
    ],
    'rez de jardin escaliers' => [
        'folder' => 'villa',
        'prefix' => 'escalier-rez-de-jardin',
        'alt' => 'Escalier intérieur — Villa Plaisance, Bédarrides',
    ],
    'vignes villa plaisance' => [
        'folder' => 'exterieurs',
        'prefix' => 'vignes-provence',
        'alt' => 'Vignes autour de Villa Plaisance — Bédarrides, Vaucluse',
    ],
];

$maxWidth = 1920;
$webpQuality = 82;
$totalImported = 0;
$totalErrors = 0;
$totalSaved = 0;

echo "=== Import photos Villa Plaisance ===\n";
echo "Source : {$sourceDir}\n";
echo "Destination : {$uploadDir}\n";
echo "Max largeur : {$maxWidth}px | Qualité WebP : {$webpQuality}\n\n";

foreach ($folderMap as $dirName => $config) {
    $dirPath = $sourceDir . '/' . $dirName;
    if (!is_dir($dirPath)) {
        echo "⚠ Dossier introuvable : {$dirName}\n";
        continue;
    }

    $images = glob($dirPath . '/*.{jpg,jpeg,JPG,JPEG,png,PNG}', GLOB_BRACE);
    if (empty($images)) {
        echo "— {$dirName} : aucune image\n";
        continue;
    }

    echo "\n📁 {$dirName} ({$config['folder']}) — " . count($images) . " photos\n";

    $counter = 1;
    foreach ($images as $imgPath) {
        $originalName = basename($imgPath);
        $seoFilename = 'villa-plaisance-' . $config['prefix'] . '-' . str_pad((string)$counter, 2, '0', STR_PAD_LEFT);
        $webpFilename = $seoFilename . '.webp';

        // Skip if already imported
        $existing = Database::fetchOne("SELECT id FROM vp_media WHERE filename = ?", [$webpFilename]);
        if ($existing) {
            echo "  ⏭ {$webpFilename} (déjà importé)\n";
            $counter++;
            continue;
        }

        // Load image
        $mime = mime_content_type($imgPath);
        $srcImage = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($imgPath),
            'image/png' => @imagecreatefrompng($imgPath),
            default => false,
        };

        if (!$srcImage) {
            echo "  ❌ {$originalName} — impossible de charger\n";
            $totalErrors++;
            $counter++;
            continue;
        }

        // Get dimensions and resize if needed
        $origW = imagesx($srcImage);
        $origH = imagesy($srcImage);

        if ($origW > $maxWidth) {
            $newW = $maxWidth;
            $newH = (int)round($origH * ($maxWidth / $origW));
            $resized = imagecreatetruecolor($newW, $newH);
            imagecopyresampled($resized, $srcImage, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
            $srcImage = $resized;
            $finalW = $newW;
            $finalH = $newH;
        } else {
            $finalW = $origW;
            $finalH = $origH;
        }

        // Save as WebP
        $destPath = $uploadDir . $webpFilename;
        $result = @imagewebp($srcImage, $destPath, $webpQuality);

        if (!$result || !file_exists($destPath)) {
            echo "  ❌ {$originalName} — échec conversion WebP\n";
            $totalErrors++;
            $counter++;
            continue;
        }

        $fileSize = filesize($destPath);
        $origSize = filesize($imgPath);
        $savings = $origSize > 0 ? round((1 - $fileSize / $origSize) * 100) : 0;

        // Generate alt with counter variation
        $altFr = $config['alt'];
        if (count($images) > 1) {
            // Vary the alt slightly for each image in a series
            $altFr = str_replace(' — Villa Plaisance', " (vue {$counter}) — Villa Plaisance", $altFr);
        }

        // Insert into DB
        try {
            Database::insert('vp_media', [
                'filename' => $webpFilename,
                'original_name' => $originalName,
                'alt_fr' => $altFr,
                'title' => ucfirst(str_replace('-', ' ', $config['prefix'])) . ' ' . $counter,
                'mime_type' => 'image/webp',
                'file_size' => $fileSize,
                'width' => $finalW,
                'height' => $finalH,
                'folder' => $config['folder'],
                'seo_filename' => $seoFilename,
                'tags' => $config['folder'] . ', ' . str_replace('-', ', ', $config['prefix']),
            ]);

            $sizeKo = round($fileSize / 1024);
            echo "  ✅ {$webpFilename} — {$finalW}×{$finalH} — {$sizeKo} Ko (−{$savings}%)\n";
            $totalImported++;
            $totalSaved += ($origSize - $fileSize);
        } catch (\Throwable $e) {
            echo "  ❌ {$webpFilename} — erreur BDD : {$e->getMessage()}\n";
            @unlink($destPath);
            $totalErrors++;
        }

        $counter++;
    }
}

echo "\n=== Résultat ===\n";
echo "✅ {$totalImported} photos importées\n";
if ($totalErrors > 0) echo "❌ {$totalErrors} erreurs\n";
echo "💾 Espace économisé : " . round($totalSaved / 1024 / 1024, 1) . " Mo\n";

// Stats finales
$stats = Database::fetchOne("SELECT COUNT(*) as total, COALESCE(SUM(file_size), 0) as size FROM vp_media");
echo "📊 Médiathèque : {$stats['total']} fichiers, " . round(($stats['size'] ?? 0) / 1024 / 1024, 1) . " Mo\n";

echo "\n=== Import terminé ===\n";
