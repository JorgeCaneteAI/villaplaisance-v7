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

        $sql .= " ORDER BY arrivee";
        return \Database::fetchAll($sql, $params);
    }

    public static function getById(int $id): ?array
    {
        return \Database::fetchOne("SELECT * FROM vp_reservations WHERE id = ?", [$id]);
    }

    public static function create(array $data): int
    {
        $code = self::generateCode(
            (int) ($data['adultes'] ?? 0),
            (int) ($data['enfants'] ?? 0),
            (int) ($data['bebes'] ?? 0),
            (int) ($data['animaux'] ?? 0),
            $data['propriete'] ?? ''
        );
        $duree = self::calculerDuree($data['arrivee'] ?? '', $data['depart'] ?? '');

        return \Database::insert('vp_reservations', [
            'code'            => $code,
            'nom_client'      => strtoupper(trim($data['nom_client'] ?? '')),
            'propriete'       => $data['propriete'] ?? '',
            'source'          => $data['source'] ?? '',
            'arrivee'         => $data['arrivee'] ?? null,
            'depart'          => $data['depart'] ?? null,
            'duree'           => $duree,
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
            'montant'         => !empty($data['montant']) ? $data['montant'] : null,
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $code = self::generateCode(
            (int) ($data['adultes'] ?? 0),
            (int) ($data['enfants'] ?? 0),
            (int) ($data['bebes'] ?? 0),
            (int) ($data['animaux'] ?? 0),
            $data['propriete'] ?? ''
        );
        $duree = self::calculerDuree($data['arrivee'] ?? '', $data['depart'] ?? '');

        \Database::update('vp_reservations', [
            'code'            => $code,
            'nom_client'      => strtoupper(trim($data['nom_client'] ?? '')),
            'propriete'       => $data['propriete'] ?? '',
            'source'          => $data['source'] ?? '',
            'arrivee'         => $data['arrivee'] ?? null,
            'depart'          => $data['depart'] ?? null,
            'duree'           => $duree,
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
            'montant'         => !empty($data['montant']) ? $data['montant'] : null,
        ], 'id = ?', [$id]);
    }

    public static function delete(int $id): void
    {
        \Database::delete('vp_reservations', 'id = ?', [$id]);
    }
}
