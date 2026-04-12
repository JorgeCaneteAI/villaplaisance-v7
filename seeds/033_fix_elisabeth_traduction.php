<?php
declare(strict_types=1);

/**
 * Seed 033 — Corriger l'avis d'Elisabeth (Norvège) : traduction FR
 * One-shot.
 */

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';

try {
    Database::query(
        "UPDATE vp_reviews SET content = ? WHERE author = 'Elisabeth' AND origin = 'Norvège'",
        ['Avec deux amies et mon chien, nous avons passé un séjour absolument merveilleux chez Jorge ! C\'est un hôte fantastique qui se soucie vraiment de ses invités. Le petit-déjeuner était incroyable — jus frais, confitures maison, croissants et bien plus encore. Jorge nous a aussi donné d\'excellents conseils sur les restaurants et les endroits à visiter dans la région. La maison est magnifique, la piscine superbe, et l\'emplacement parfait pour explorer la Provence. Le séjour a dépassé toutes nos attentes, c\'était même mieux que de séjourner à l\'hôtel !']
    );
    echo "✅  Avis Elisabeth (Norvège) traduit en français.\n";
} catch (\Throwable $e) {
    echo "❌  Erreur : " . $e->getMessage() . "\n";
}

echo "\n=== Seed 033 terminé ===\n";
