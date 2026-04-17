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
}
