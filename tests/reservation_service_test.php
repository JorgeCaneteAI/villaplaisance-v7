<?php
declare(strict_types=1);

require __DIR__ . '/../config.php';

use App\Services\ReservationService;

$fails = 0;

// Test 1 : encodage simple
if (ReservationService::generateCode(2, 1, 0, 2, 'VP-BB') !== '2102-VP-BB') {
    echo "FAIL: generateCode(2,1,0,2,VP-BB) attendu '2102-VP-BB', reçu '" . ReservationService::generateCode(2, 1, 0, 2, 'VP-BB') . "'\n";
    $fails++;
}

// Test 2 : encodage valeurs >= 10 (A=10, B=11, K=20)
if (ReservationService::generateCode(10, 11, 0, 20, 'AV-ANN') !== 'AB0K-AV-ANN') {
    echo "FAIL: generateCode(10,11,0,20,AV-ANN) attendu 'AB0K-AV-ANN'\n";
    $fails++;
}

// Test 3 : valeurs nulles
if (ReservationService::generateCode(0, 0, 0, 0, 'VP-ETE') !== '0000-VP-ETE') {
    echo "FAIL: generateCode avec zéros\n";
    $fails++;
}

// Test 4 : durée simple
if (ReservationService::calculerDuree('2026-04-10', '2026-04-16') !== 6) {
    echo "FAIL: calculerDuree 6 jours\n";
    $fails++;
}

// Test 5 : durée date invalide
if (ReservationService::calculerDuree('', '') !== 0) {
    echo "FAIL: calculerDuree date vide\n";
    $fails++;
}

// Test 6 : négatif clampé à 0
if (ReservationService::generateCode(-3, 0, 0, 0, 'VP-BB') !== '0000-VP-BB') {
    echo "FAIL: generateCode négatif\n"; $fails++;
}

// Test 7 : valeur >= 36 clampée à 35 (Z)
if (ReservationService::generateCode(36, 0, 0, 0, 'VP-BB') !== 'Z000-VP-BB') {
    echo "FAIL: generateCode clamp à 35=Z (reçu " . ReservationService::generateCode(36, 0, 0, 0, 'VP-BB') . ")\n"; $fails++;
}

// Test 8 : même jour = 0 nuit
if (ReservationService::calculerDuree('2026-04-10', '2026-04-10') !== 0) {
    echo "FAIL: calculerDuree même jour\n"; $fails++;
}

// Test 9 : date malformée = 0
if (ReservationService::calculerDuree('not-a-date', '2026-04-10') !== 0) {
    echo "FAIL: calculerDuree date malformée\n"; $fails++;
}

// Test 10 : une date vide = 0 (guard explicite, ne doit plus fallback sur now)
if (ReservationService::calculerDuree('', '2026-04-10') !== 0) {
    echo "FAIL: calculerDuree arrivée vide\n"; $fails++;
}

if ($fails === 0) {
    echo "OK: tous les tests passent\n";
    exit(0);
}
exit(1);
