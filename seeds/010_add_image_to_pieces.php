<?php
declare(strict_types=1);

/**
 * Ajoute la colonne image à vp_pieces
 * Usage: php seeds/010_add_image_to_pieces.php
 */

define('ROOT', dirname(__DIR__));
require ROOT . '/config.php';

$pdo = Database::getInstance();

try {
    $pdo->exec("ALTER TABLE vp_pieces ADD COLUMN image VARCHAR(255) DEFAULT NULL AFTER note");
    echo "✅ Colonne 'image' ajoutée à vp_pieces\n";
} catch (\Throwable $e) {
    if (str_contains($e->getMessage(), 'Duplicate column')) {
        echo "ℹ️  Colonne 'image' existe déjà\n";
    } else {
        echo "❌ Erreur : " . $e->getMessage() . "\n";
    }
}
