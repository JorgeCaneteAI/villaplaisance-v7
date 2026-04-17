<?php
declare(strict_types=1);

namespace App\Services;

class ReservationService
{
    private static function encodeVal(int $n): string
    {
        $n = max(0, min(35, $n));
        if ($n < 10) return (string) $n;
        return chr(ord('A') + $n - 10);
    }

    public static function generateCode(int $adultes, int $enfants, int $bebes, int $animaux, string $propriete): string
    {
        $a = self::encodeVal(max(0, $adultes));
        $e = self::encodeVal(max(0, $enfants));
        $b = self::encodeVal(max(0, $bebes));
        $an = self::encodeVal(max(0, $animaux));
        return "{$a}{$e}{$b}{$an}-{$propriete}";
    }

    public static function calculerDuree(string $arrivee, string $depart): int
    {
        if ($arrivee === '' || $depart === '') return 0;
        try {
            $d1 = new \DateTimeImmutable($arrivee);
            $d2 = new \DateTimeImmutable($depart);
        } catch (\Throwable) {
            return 0;
        }
        return (int) $d1->diff($d2)->format('%r%a');
    }

    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT * FROM vp_reservations WHERE 1=1";
        $params = [];

        if (!empty($filters['propriete'])) {
            $sql .= " AND propriete = ?";
            $params[] = $filters['propriete'];
        }
        if (!empty($filters['source'])) {
            $sql .= " AND source = ?";
            $params[] = $filters['source'];
        }
        if (!empty($filters['statut'])) {
            $sql .= " AND statut = ?";
            $params[] = $filters['statut'];
        }
        if (!empty($filters['mois'])) {
            $sql .= " AND DATE_FORMAT(arrivee, '%Y-%m') = ?";
            $params[] = $filters['mois'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND nom_client LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY arrivee, id";
        return \Database::fetchAll($sql, $params);
    }

    public static function getById(int $id): ?array
    {
        return \Database::fetchOne("SELECT * FROM vp_reservations WHERE id = ?", [$id]);
    }

    /**
     * Normalise un payload de saisie vers un tableau prêt pour INSERT/UPDATE :
     * régénère le code, calcule la durée, trim + uppercase le nom_client
     * (via mb_strtoupper pour respecter les accents), applique les défauts.
     * Exclut volontairement ical_uid (géré par le flux de sync iCal).
     */
    private static function buildPayload(array $data): array
    {
        return [
            'code'            => self::generateCode(
                (int) ($data['adultes'] ?? 0),
                (int) ($data['enfants'] ?? 0),
                (int) ($data['bebes'] ?? 0),
                (int) ($data['animaux'] ?? 0),
                $data['propriete'] ?? ''
            ),
            'nom_client'      => mb_strtoupper(trim($data['nom_client'] ?? ''), 'UTF-8'),
            'propriete'       => $data['propriete'] ?? '',
            'source'          => $data['source'] ?? '',
            'arrivee'         => $data['arrivee'] ?? null,
            'depart'          => $data['depart'] ?? null,
            'duree'           => self::calculerDuree($data['arrivee'] ?? '', $data['depart'] ?? ''),
            'adultes'         => (int) ($data['adultes'] ?? 0),
            'enfants'         => (int) ($data['enfants'] ?? 0),
            'bebes'           => (int) ($data['bebes'] ?? 0),
            'animaux'         => (int) ($data['animaux'] ?? 0),
            'animaux_details' => $data['animaux_details'] ?? '',
            'provenance'      => $data['provenance'] ?? '',
            'commentaire'     => $data['commentaire'] ?? '',
            'prive'           => !empty($data['prive']) ? 1 : 0,
            'statut'          => $data['statut'] ?? 'Confirmée',
            'numero_resa'     => $data['numero_resa'] ?? '',
            'montant'         => (isset($data['montant']) && $data['montant'] !== '') ? $data['montant'] : null,
        ];
    }

    public static function create(array $data): int
    {
        return \Database::insert('vp_reservations', self::buildPayload($data));
    }

    public static function update(int $id, array $data): bool
    {
        $affected = \Database::update('vp_reservations', self::buildPayload($data), 'id = ?', [$id]);
        return $affected > 0;
    }

    public static function delete(int $id): bool
    {
        $affected = \Database::delete('vp_reservations', 'id = ?', [$id]);
        return $affected > 0;
    }

    /**
     * Retourne toutes les réservations qui chevauchent le mois donné
     * (arrivée <= dernier jour du mois ET départ > premier jour du mois).
     */
    public static function getForMonth(int $year, int $month): array
    {
        $firstDay = sprintf('%04d-%02d-01', $year, $month);
        $lastDay = date('Y-m-t', strtotime($firstDay));
        return \Database::fetchAll(
            "SELECT * FROM vp_reservations
             WHERE arrivee <= ? AND depart > ?
             ORDER BY arrivee, id",
            [$lastDay, $firstDay]
        );
    }

    /**
     * Construit la grille du calendrier d'un mois :
     * - weeks : tableau de semaines (lun→dim), chaque semaine étant 7 DateTimeImmutable
     *   couvrant le mois (débord possible sur les mois précédent/suivant).
     * - resa_by_day : tableau 'YYYY-MM-DD' → liste de résas affichables ce jour-là,
     *   avec explosion arrivée-incluse / départ-exclu.
     * - couleurs : mapping source → {bg, text}.
     */
    public static function buildCalendarData(int $year, int $month): array
    {
        $reservations = self::getForMonth($year, $month);

        $firstDay = new \DateTimeImmutable(sprintf('%04d-%02d-01', $year, $month));
        $lastDay = new \DateTimeImmutable($firstDay->format('Y-m-t'));

        // Construire les semaines (lundi premier) couvrant tout le mois.
        $weeks = [];
        $cursor = $firstDay->modify('monday this week');
        if ($cursor > $firstDay) {
            $cursor = $cursor->modify('-1 week');
        }
        $endCursor = $lastDay->modify('sunday this week');
        if ($endCursor < $lastDay) {
            $endCursor = $endCursor->modify('+1 week');
        }
        while ($cursor <= $endCursor) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $week[] = $cursor;
                $cursor = $cursor->modify('+1 day');
            }
            $weeks[] = $week;
        }

        $couleurs = [
            'Airbnb'   => ['bg' => '#FF5A5F', 'text' => '#ffffff'],
            'Booking'  => ['bg' => '#003580', 'text' => '#ffffff'],
            'Direct'   => ['bg' => '#639922', 'text' => '#ffffff'],
            'Privée'   => ['bg' => '#888780', 'text' => '#ffffff'],
            'Absence'  => ['bg' => '#2C2C2A', 'text' => '#ffffff'],
        ];

        // Exploser les résas en jours couverts (arrivée incluse, départ exclu).
        $resaByDay = [];
        foreach ($reservations as $r) {
            $arr = new \DateTimeImmutable($r['arrivee']);
            $dep = new \DateTimeImmutable($r['depart']);

            $start = $arr > $firstDay ? $arr : $firstDay;
            $depMinus1 = $dep->modify('-1 day');
            $end = $depMinus1 < $lastDay ? $depMinus1 : $lastDay;

            $d = $start;
            while ($d <= $end) {
                $key = $d->format('Y-m-d');
                $resaByDay[$key][] = [
                    'id'           => (int) $r['id'],
                    'code'         => $r['code'],
                    'nom_client'   => $r['nom_client'],
                    'source'       => $r['source'],
                    'provenance'   => $r['provenance'] ?? '',
                    'commentaire'  => $r['commentaire'] ?? '',
                    'couleur'      => $couleurs[$r['source']] ?? ['bg' => '#888780', 'text' => '#ffffff'],
                    'arrivee'      => $r['arrivee'],
                    'depart'       => $r['depart'],
                    'is_start'     => $d == $arr || $d == $firstDay,
                    'is_end'       => $d == $depMinus1 || $d == $lastDay,
                ];
                $d = $d->modify('+1 day');
            }
        }

        return ['weeks' => $weeks, 'resa_by_day' => $resaByDay, 'couleurs' => $couleurs];
    }
}
