<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
require ROOT . '/config.php';

$pdo = Database::getInstance();

try {
    $pdo->exec("ALTER TABLE vp_pieces ADD COLUMN images TEXT DEFAULT NULL AFTER image");
    echo "✅ Colonne 'images' ajoutée à vp_pieces\n";
} catch (\Throwable $e) {
    if (str_contains($e->getMessage(), 'Duplicate column')) {
        echo "ℹ️  Colonne 'images' existe déjà\n";
    } else {
        echo "❌ Erreur : " . $e->getMessage() . "\n";
    }
}
