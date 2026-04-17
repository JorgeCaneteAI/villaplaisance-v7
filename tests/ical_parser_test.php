<?php
declare(strict_types=1);

require __DIR__ . '/../config.php';

use App\Services\IcalSyncService;

$fails = 0;

$sampleIcal = "BEGIN:VCALENDAR\r\n"
    . "VERSION:2.0\r\n"
    . "BEGIN:VEVENT\r\n"
    . "DTSTART;VALUE=DATE:20260501\r\n"
    . "DTEND;VALUE=DATE:20260508\r\n"
    . "SUMMARY:Reserved\r\n"
    . "UID:abc-123\r\n"
    . "DESCRIPTION:Reservation URL: https://www.airbnb.fr/hosting/reservations/details/HMABC123\r\n"
    . "END:VEVENT\r\n"
    . "BEGIN:VEVENT\r\n"
    . "DTSTART;VALUE=DATE:20260515\r\n"
    . "DTEND;VALUE=DATE:20260520\r\n"
    . "SUMMARY:Airbnb (Not available)\r\n"
    . "UID:def-456\r\n"
    . "END:VEVENT\r\n"
    . "END:VCALENDAR\r\n";

$events = IcalSyncService::parseIcalPublic($sampleIcal);

if (count($events) !== 2) {
    printf("FAIL: attendu 2 events, reçu %d\n", count($events));
    $fails++;
}
if (($events[0]['summary'] ?? '') !== 'Reserved') {
    echo "FAIL: event 0 summary (reçu '" . ($events[0]['summary'] ?? '') . "')\n";
    $fails++;
}
if (($events[0]['uid'] ?? '') !== 'abc-123') {
    echo "FAIL: event 0 uid (reçu '" . ($events[0]['uid'] ?? '') . "')\n";
    $fails++;
}
if (($events[0]['dtstart'] ?? '') !== '20260501') {
    echo "FAIL: event 0 dtstart (reçu '" . ($events[0]['dtstart'] ?? '') . "')\n";
    $fails++;
}
if (($events[1]['summary'] ?? '') !== 'Airbnb (Not available)') {
    echo "FAIL: event 1 summary (reçu '" . ($events[1]['summary'] ?? '') . "')\n";
    $fails++;
}

// isRealReservation
if (!IcalSyncService::isRealReservationPublic(['summary' => 'Reserved'], 'Airbnb')) {
    echo "FAIL: Airbnb Reserved devrait être une vraie résa\n";
    $fails++;
}
if (IcalSyncService::isRealReservationPublic(['summary' => 'Airbnb (Not available)'], 'Airbnb')) {
    echo "FAIL: Airbnb Not available ne doit pas être importé\n";
    $fails++;
}
if (!IcalSyncService::isRealReservationPublic(['summary' => 'CLOSED - Not available'], 'Booking')) {
    echo "FAIL: Booking tout import\n";
    $fails++;
}

// parseDate
if (IcalSyncService::parseDatePublic('20260501') !== '2026-05-01') {
    echo "FAIL: parseDate basic (reçu '" . IcalSyncService::parseDatePublic('20260501') . "')\n";
    $fails++;
}
if (IcalSyncService::parseDatePublic('20260501T120000Z') !== '2026-05-01') {
    echo "FAIL: parseDate with time (reçu '" . IcalSyncService::parseDatePublic('20260501T120000Z') . "')\n";
    $fails++;
}

if ($fails === 0) {
    echo "OK: parser iCal et filtres OK\n";
    exit(0);
}
exit(1);
