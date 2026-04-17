<?php
declare(strict_types=1);

namespace App\Services;

class ReservationService
{
    private static function encodeVal(int $n): string
    {
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
        try {
            $d1 = new \DateTimeImmutable($arrivee);
            $d2 = new \DateTimeImmutable($depart);
        } catch (\Throwable) {
            return 0;
        }
        return (int) $d1->diff($d2)->format('%r%a');
    }
}
