<?php
declare(strict_types=1);

/**
 * Seed 021 — Livret d'accueil : ajout EN/ES + mot de passe + source messages
 */

require __DIR__ . '/../config.php';

echo "=== Seed 021 — Livret multilang ===\n";

// 1. Add 'source' column to vp_messages if not exists
try {
    $cols = Database::fetchAll("SHOW COLUMNS FROM vp_messages LIKE 'source'");
    if (empty($cols)) {
        Database::query("ALTER TABLE vp_messages ADD COLUMN source ENUM('contact','livret') NOT NULL DEFAULT 'contact' AFTER lang");
        echo "✓ Colonne 'source' ajoutée à vp_messages\n";
    } else {
        echo "– Colonne 'source' existe déjà\n";
    }
} catch (\Throwable $e) {
    echo "✗ Erreur vp_messages: " . $e->getMessage() . "\n";
}

// 2. Insert livret password in vp_settings
try {
    $existing = Database::fetchOne("SELECT * FROM vp_settings WHERE setting_key = 'livret_password'");
    if (!$existing) {
        Database::insert('vp_settings', [
            'setting_key' => 'livret_password',
            'setting_value' => 'bienvenue2026',
        ]);
        echo "✓ Mot de passe livret inséré (bienvenue2026)\n";
    } else {
        echo "– Mot de passe livret existe déjà\n";
    }
} catch (\Throwable $e) {
    echo "✗ Erreur vp_settings: " . $e->getMessage() . "\n";
}

// 3. Create EN and ES rows for each FR livret section
try {
    $frSections = Database::fetchAll("SELECT * FROM vp_livret WHERE lang = 'fr' ORDER BY type, position");
    $count = 0;
    foreach ($frSections as $fr) {
        foreach (['en', 'es'] as $lang) {
            $exists = Database::fetchOne(
                "SELECT id FROM vp_livret WHERE type = ? AND position = ? AND lang = ?",
                [$fr['type'], $fr['position'], $lang]
            );
            if (!$exists) {
                Database::insert('vp_livret', [
                    'type' => $fr['type'],
                    'section_title' => '',
                    'content' => '',
                    'position' => $fr['position'],
                    'active' => $fr['active'],
                    'lang' => $lang,
                ]);
                $count++;
            }
        }
    }
    echo "✓ {$count} sections EN/ES créées pour le livret\n";
} catch (\Throwable $e) {
    echo "✗ Erreur livret sections: " . $e->getMessage() . "\n";
}

echo "=== Seed 021 terminé ===\n";
