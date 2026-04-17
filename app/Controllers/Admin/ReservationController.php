<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Services\ReservationService;
use App\Services\ReservationConstants;

class ReservationController extends AdminBaseController
{
    /**
     * Normalise et valide un couple (year, month). Défauts sur aujourd'hui
     * si null. 404 explicite si le couple est hors range réaliste — on ne
     * veut pas silencieusement remapper /admin/calendrier/2026/13 vers
     * avril, ça casserait la mental-model du back-button.
     */
    private static function validateYearMonth(?int $year, ?int $month): array
    {
        $today = new \DateTimeImmutable('today');
        $year = $year ?? (int) $today->format('Y');
        $month = $month ?? (int) $today->format('n');

        if ($year < 2000 || $year > 2100 || $month < 1 || $month > 12) {
            http_response_code(404);
            echo '<h1>404 — Période hors range</h1>';
            echo '<p><a href="/admin/calendrier">Retour au calendrier</a></p>';
            exit;
        }

        return [$year, $month];
    }

    public function mois(?int $year = null, ?int $month = null): void
    {
        [$year, $month] = self::validateYearMonth($year, $month);
        $today = new \DateTimeImmutable('today');

        $prevYear = $month === 1 ? $year - 1 : $year;
        $prevMonth = $month === 1 ? 12 : $month - 1;
        $nextYear = $month === 12 ? $year + 1 : $year;
        $nextMonth = $month === 12 ? 1 : $month + 1;

        $data = ReservationService::buildCalendarData($year, $month);

        $this->render('admin/reservations/index', [
            'year'         => $year,
            'month'        => $month,
            'mois_nom'     => ReservationConstants::MOIS_FR[$month],
            'weeks'        => $data['weeks'],
            'resa_by_day'  => $data['resa_by_day'],
            'couleurs'     => $data['couleurs'],
            'today'        => $today,
            'prev_year'    => $prevYear,
            'prev_month'   => $prevMonth,
            'next_year'    => $nextYear,
            'next_month'   => $nextMonth,
        ]);
    }

    public function annee(?int $year = null): void
    {
        $today = new \DateTimeImmutable('today');
        $year = $year ?? (int) $today->format('Y');

        // Same bounds as validateYearMonth, but for year only
        if ($year < 2000 || $year > 2100) {
            http_response_code(404);
            echo '<h1>404 — Année hors range</h1>';
            echo '<p><a href="/admin/calendrier">Retour au calendrier</a></p>';
            exit;
        }

        $moisData = [];
        for ($m = 1; $m <= 12; $m++) {
            $d = ReservationService::buildCalendarData($year, $m);
            $moisData[] = [
                'month'       => $m,
                'nom'         => ReservationConstants::MOIS_FR[$m],
                'weeks'       => $d['weeks'],
                'resa_by_day' => $d['resa_by_day'],
            ];
        }

        $this->render('admin/reservations/annee', [
            'year'      => $year,
            'mois_data' => $moisData,
            'today'     => $today,
            'prev_year' => $year - 1,
            'next_year' => $year + 1,
            'couleurs'  => ReservationConstants::SOURCES,
        ]);
    }
}
