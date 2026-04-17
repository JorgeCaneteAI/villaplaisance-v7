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

    /**
     * Synchronise tous les flux iCal actifs depuis vp_ical_feeds.
     * @param string $trigger 'cron' (appelé par bin/sync_ical.php) ou 'manual' (bouton admin).
     * @return array ['created' => int, 'updated' => int, 'deleted' => int, 'errors' => array<string>]
     */
    public static function syncAll(string $trigger = 'manual'): array
    {
        $startedAt = date('Y-m-d H:i:s');
        $feeds = \Database::fetchAll("SELECT * FROM vp_ical_feeds WHERE actif = 1");

        $totalCreated = 0;
        $totalUpdated = 0;
        $totalDeleted = 0;
        $errors = [];

        foreach ($feeds as $feed) {
            try {
                $r = self::syncFeed($feed);
                $totalCreated += $r['created'];
                $totalUpdated += $r['updated'];
                $totalDeleted += $r['deleted'];
                \Database::update('vp_ical_feeds', [
                    'last_sync_at'  => date('Y-m-d H:i:s'),
                    'last_sync_ok'  => 1,
                    'last_sync_msg' => sprintf('+%d / ~%d / -%d', $r['created'], $r['updated'], $r['deleted']),
                ], 'id = ?', [$feed['id']]);
            } catch (\Throwable $e) {
                $msg = "{$feed['propriete']} ({$feed['source']}) : " . $e->getMessage();
                $errors[] = $msg;
                \Database::update('vp_ical_feeds', [
                    'last_sync_at'  => date('Y-m-d H:i:s'),
                    'last_sync_ok'  => 0,
                    'last_sync_msg' => $e->getMessage(),
                ], 'id = ?', [$feed['id']]);
            }
        }

        \Database::insert('vp_ical_sync_log', [
            'started_at'   => $startedAt,
            'ended_at'     => date('Y-m-d H:i:s'),
            'created'      => $totalCreated,
            'updated'      => $totalUpdated,
            'deleted'      => $totalDeleted,
            'errors'       => $errors ? implode("\n", $errors) : null,
            'triggered_by' => $trigger,
        ]);

        return [
            'created' => $totalCreated,
            'updated' => $totalUpdated,
            'deleted' => $totalDeleted,
            'errors'  => $errors,
        ];
    }

    /**
     * Synchronise un flux iCal (Airbnb ou Booking) pour une propriété.
     * Dédupe via (source, ical_uid), met à jour les dates si changées,
     * supprime les résas du flux qui ne sont plus dans le feed.
     * @throws \RuntimeException si le flux ne peut être téléchargé ou parsé.
     */
    private static function syncFeed(array $feed): array
    {
        $url = $feed['url'];
        $propriete = $feed['propriete'];
        $source = $feed['source'];

        $ctx = stream_context_create([
            'http' => [
                'timeout'    => 20,
                'user_agent' => 'VP-V5-iCal-Sync/1.0',
                'method'     => 'GET',
            ],
        ]);
        $text = @file_get_contents($url, false, $ctx);
        if ($text === false) {
            $err = error_get_last();
            throw new \RuntimeException("Impossible de lire le flux : " . ($err['message'] ?? 'unknown error'));
        }

        $events = self::parseIcal($text);
        $created = $updated = $deleted = 0;
        $activeUids = [];

        foreach ($events as $event) {
            if (!self::isRealReservation($event, $source)) continue;

            $uid = $event['uid'];
            $activeUids[] = $uid;
            $arrivee = self::parseDate($event['dtstart']);
            $depart  = self::parseDate($event['dtend']);

            // Numéro de résa Airbnb extrait de la DESCRIPTION
            $numeroResa = '';
            if ($source === 'Airbnb' && !empty($event['description'])) {
                if (preg_match('#/reservations/details/([A-Z0-9]+)#', $event['description'], $m)) {
                    $numeroResa = $m[1];
                }
            }

            $existing = \Database::fetchOne(
                "SELECT id, arrivee, depart FROM vp_reservations WHERE source = ? AND ical_uid = ?",
                [$source, $uid]
            );

            if ($existing) {
                if ($existing['arrivee'] !== $arrivee || $existing['depart'] !== $depart) {
                    $duree = ReservationService::calculerDuree($arrivee, $depart);
                    \Database::update('vp_reservations',
                        ['arrivee' => $arrivee, 'depart' => $depart, 'duree' => $duree],
                        'id = ?', [$existing['id']]);
                    $updated++;
                }
            } else {
                $code = ReservationService::generateCode(0, 0, 0, 0, $propriete);
                $duree = ReservationService::calculerDuree($arrivee, $depart);
                \Database::insert('vp_reservations', [
                    'code'        => $code,
                    'nom_client'  => $source, // placeholder (Jorge renseigne à la main après)
                    'propriete'   => $propriete,
                    'source'      => $source,
                    'arrivee'     => $arrivee,
                    'depart'      => $depart,
                    'duree'       => $duree,
                    'statut'      => 'Confirmée',
                    'numero_resa' => $numeroResa,
                    'ical_uid'    => $uid,
                ]);
                $created++;
            }
        }

        // Supprimer les résas iCal de ce flux qui ne sont plus actives dans le feed
        $dbRows = \Database::fetchAll(
            "SELECT id, ical_uid FROM vp_reservations
             WHERE source = ? AND propriete = ? AND ical_uid IS NOT NULL AND ical_uid != ''",
            [$source, $propriete]
        );
        foreach ($dbRows as $row) {
            if (!in_array($row['ical_uid'], $activeUids, true)) {
                \Database::delete('vp_reservations', 'id = ?', [$row['id']]);
                $deleted++;
            }
        }

        return ['created' => $created, 'updated' => $updated, 'deleted' => $deleted];
    }
}
