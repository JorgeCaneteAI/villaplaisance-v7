<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Services\ReservationService;
use App\Services\ReservationConstants;

class ReservationController extends AdminBaseController
{
    public function mois(?int $year = null, ?int $month = null): void
    {
        $today = new \DateTimeImmutable('today');
        $year = $year ?? (int) $today->format('Y');
        $month = $month ?? (int) $today->format('n');

        // Normalise out-of-range month
        if ($month < 1 || $month > 12) {
            $month = (int) $today->format('n');
        }

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
}
