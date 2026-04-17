<?php
declare(strict_types=1);

/**
 * Smoke test : vérifie que la UNIQUE KEY uniq_ical(source, ical_uid) empêche
 * l'insertion de doublons iCal. Garantie d'intégrité pour IcalSyncService::syncFeed.
 */

require __DIR__ . '/../config.php';

$fails = 0;

// Cleanup préventif (si les tests précédents ont laissé des artefacts)
\Database::query("DELETE FROM vp_reservations WHERE ical_uid LIKE 'test-dedupe-%'");

// 1. Insert d'une résa fictive avec un UID
try {
    \Database::insert('vp_reservations', [
        'code'       => '0000-VP-BB',
        'nom_client' => 'TEST-DEDUPE',
        'propriete'  => 'VP-BB',
        'source'     => 'Airbnb',
        'arrivee'    => '2028-01-01',
        'depart'     => '2028-01-05',
        'duree'      => 4,
        'statut'     => 'Confirmée',
        'ical_uid'   => 'test-dedupe-001',
    ]);
    echo "✓ 1er INSERT avec ical_uid=test-dedupe-001 réussi\n";
} catch (\PDOException $e) {
    echo "FAIL inattendu : " . $e->getMessage() . "\n";
    $fails++;
}

// 2. Tenter de réinsérer la même combinaison (source, ical_uid) — doit échouer
try {
    \Database::insert('vp_reservations', [
        'code'       => '0000-VP-BB',
        'nom_client' => 'TEST-DEDUPE-BIS',
        'propriete'  => 'VP-BB',
        'source'     => 'Airbnb',
        'arrivee'    => '2028-01-01',
        'depart'     => '2028-01-05',
        'duree'      => 4,
        'statut'     => 'Confirmée',
        'ical_uid'   => 'test-dedupe-001',
    ]);
    echo "FAIL: doublon INSERT a réussi — UNIQUE KEY défaillante\n";
    $fails++;
} catch (\PDOException $e) {
    if (str_contains($e->getMessage(), 'Duplicate') || str_contains($e->getMessage(), 'uniq_ical')) {
        echo "✓ 2e INSERT même UID rejeté par UNIQUE KEY (attendu)\n";
    } else {
        echo "FAIL: erreur inattendue : " . $e->getMessage() . "\n";
        $fails++;
    }
}

// 3. Insérer la même UID mais source DIFFÉRENTE — doit réussir (UNIQUE est sur source+ical_uid, pas sur ical_uid seul)
try {
    \Database::insert('vp_reservations', [
        'code'       => '0000-VP-BB',
        'nom_client' => 'TEST-DEDUPE-BOOKING',
        'propriete'  => 'VP-BB',
        'source'     => 'Booking',
        'arrivee'    => '2028-01-01',
        'depart'     => '2028-01-05',
        'duree'      => 4,
        'statut'     => 'Confirmée',
        'ical_uid'   => 'test-dedupe-001',
    ]);
    echo "✓ Même UID avec source différente accepté (Airbnb + Booking peuvent avoir la même UID)\n";
} catch (\PDOException $e) {
    echo "FAIL: UNIQUE KEY trop large, rejette Airbnb vs Booking : " . $e->getMessage() . "\n";
    $fails++;
}

// 4. Insérer une résa sans ical_uid (NULL) — doit réussir même si une autre NULL existe déjà
try {
    \Database::insert('vp_reservations', [
        'code'       => '0000-VP-BB',
        'nom_client' => 'TEST-DEDUPE-MANUAL-1',
        'propriete'  => 'VP-BB',
        'source'     => 'Direct',
        'arrivee'    => '2028-02-01',
        'depart'     => '2028-02-05',
        'duree'      => 4,
        'statut'     => 'Confirmée',
        'ical_uid'   => null,
    ]);
    \Database::insert('vp_reservations', [
        'code'       => '0000-VP-BB',
        'nom_client' => 'TEST-DEDUPE-MANUAL-2',
        'propriete'  => 'VP-BB',
        'source'     => 'Direct',
        'arrivee'    => '2028-02-10',
        'depart'     => '2028-02-15',
        'duree'      => 5,
        'statut'     => 'Confirmée',
        'ical_uid'   => null,
    ]);
    echo "✓ 2 résas manuelles (ical_uid=NULL) coexistent — MySQL traite NULL comme distinct dans UNIQUE\n";
} catch (\PDOException $e) {
    echo "FAIL: NULL ical_uid bloquée par UNIQUE : " . $e->getMessage() . "\n";
    $fails++;
}

// Cleanup
\Database::query("DELETE FROM vp_reservations WHERE nom_client LIKE 'TEST-DEDUPE%'");

if ($fails === 0) {
    echo "OK: dédupe iCal par UNIQUE KEY (source, ical_uid) fonctionne correctement\n";
    exit(0);
}
echo "$fails test(s) ont échoué\n";
exit(1);
