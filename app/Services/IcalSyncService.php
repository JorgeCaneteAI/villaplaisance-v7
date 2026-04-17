<?php
declare(strict_types=1);

namespace App\Services;

/**
 * Service de synchronisation des flux iCal (Airbnb + Booking) vers vp_reservations.
 * Parseur iCal maison (portage direct de sync_ical.py de l'app Flask legacy).
 *
 * Méthodes publiques *Public suffixées pour les tests — pas d'API officielle.
 */
class IcalSyncService
{
    public static function parseIcalPublic(string $text): array
    {
        return self::parseIcal($text);
    }

    public static function isRealReservationPublic(array $e, string $s): bool
    {
        return self::isRealReservation($e, $s);
    }

    public static function parseDatePublic(string $s): string
    {
        return self::parseDate($s);
    }

    /**
     * Parse un texte iCal (RFC 5545 partiel) et retourne la liste des VEVENT valides.
     * Gère les continuations de ligne (lignes commençant par espace/tab) et les paramètres
     * de clé (ex: DTSTART;VALUE=DATE:20260501 → clé base DTSTART, valeur 20260501).
     */
    private static function parseIcal(string $text): array
    {
        $events = [];
        $current = [];
        $inEvent = false;
        $pendingKey = null;

        foreach (preg_split("/\r\n|\n|\r/", $text) as $rawLine) {
            if ($rawLine !== '' && ($rawLine[0] === ' ' || $rawLine[0] === "\t") && $pendingKey && $inEvent) {
                $current[$pendingKey] = ($current[$pendingKey] ?? '') . substr($rawLine, 1);
                continue;
            }

            $line = trim($rawLine);
            $pendingKey = null;

            if ($line === 'BEGIN:VEVENT') {
                $inEvent = true;
                $current = [];
            } elseif ($line === 'END:VEVENT') {
                if ($inEvent && !empty($current['uid']) && !empty($current['dtstart']) && !empty($current['dtend'])) {
                    $events[] = $current;
                }
                $inEvent = false;
                $current = [];
            } elseif ($inEvent && str_contains($line, ':')) {
                [$key, $val] = explode(':', $line, 2);
                $keyBase = strtoupper(explode(';', $key)[0]);
                $mapping = [
                    'UID' => 'uid',
                    'DTSTART' => 'dtstart',
                    'DTEND' => 'dtend',
                    'SUMMARY' => 'summary',
                    'DESCRIPTION' => 'description',
                ];
                if (isset($mapping[$keyBase])) {
                    $field = $mapping[$keyBase];
                    $current[$field] = trim($val);
                    $pendingKey = $field;
                }
            }
        }
        return $events;
    }

    /**
     * Convertit une date iCal (YYYYMMDD ou YYYYMMDDTHHMMSSZ) en format MySQL YYYY-MM-DD.
     */
    private static function parseDate(string $s): string
    {
        $s = str_replace('Z', '', explode('T', $s)[0]);
        return substr($s, 0, 4) . '-' . substr($s, 4, 2) . '-' . substr($s, 6, 2);
    }

    /**
     * Airbnb : seuls les SUMMARY=Reserved sont de vraies résas (les "Not available" sont des blocages).
     * Booking : tout CLOSED - Not available ou similaire est une résa ou blocage à importer.
     */
    private static function isRealReservation(array $event, string $source): bool
    {
        if ($source === 'Airbnb') {
            return ($event['summary'] ?? '') === 'Reserved';
        }
        return true; // Booking : tout est à prendre
    }
}
