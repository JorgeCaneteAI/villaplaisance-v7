<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Services\ReservationService;
use App\Services\ReservationConstants;

class ReservationController extends AdminBaseController
{
    /**
     * Normalise et valide une année. Défaut sur aujourd'hui si null.
     * 404 explicite si hors range réaliste — on ne veut pas silencieusement
     * remapper une URL incohérente (ça casserait la mental-model du back-button).
     */
    private static function validateYear(?int $year): int
    {
        $year = $year ?? (int) (new \DateTimeImmutable('today'))->format('Y');
        if ($year < 2000 || $year > 2100) {
            self::abort404('Année hors range');
        }
        return $year;
    }

    /**
     * Normalise et valide un couple (year, month). Déroule d'abord validateYear,
     * puis vérifie le mois dans [1..12].
     */
    private static function validateYearMonth(?int $year, ?int $month): array
    {
        $year = self::validateYear($year);
        $month = $month ?? (int) (new \DateTimeImmutable('today'))->format('n');
        if ($month < 1 || $month > 12) {
            self::abort404('Mois hors range');
        }
        return [$year, $month];
    }

    private static function abort404(string $reason): void
    {
        http_response_code(404);
        echo '<h1>404 — ' . htmlspecialchars($reason) . '</h1>';
        echo '<p><a href="/admin/calendrier">Retour au calendrier</a></p>';
        exit;
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
        $year = self::validateYear($year);
        $today = new \DateTimeImmutable('today');

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

    public function showSaisie(?int $id = null): void
    {
        $resa = $id ? ReservationService::getById($id) : null;
        if ($id && !$resa) {
            self::abort404('Réservation introuvable');
        }

        $this->render('admin/reservations/saisie', [
            'resa'       => $resa,
            'id'         => $id,
            'proprietes' => ReservationConstants::PROPRIETES,
            'sources'    => ReservationConstants::SOURCES,
            'statuts'    => ReservationConstants::STATUTS,
        ]);
    }

    public function saveSaisie(?int $id = null): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/calendrier/saisie' . ($id ? "/$id" : ''));
            return;
        }

        $data = [
            'nom_client'      => trim($_POST['nom_client'] ?? ''),
            'propriete'       => $_POST['propriete'] ?? '',
            'source'          => $_POST['source'] ?? '',
            'arrivee'         => $_POST['arrivee'] ?? '',
            'depart'          => $_POST['depart'] ?? '',
            'adultes'         => (int) ($_POST['adultes'] ?? 0),
            'enfants'         => (int) ($_POST['enfants'] ?? 0),
            'bebes'           => (int) ($_POST['bebes'] ?? 0),
            'animaux'         => (int) ($_POST['animaux'] ?? 0),
            'animaux_details' => $_POST['animaux_details'] ?? '',
            'provenance'      => $_POST['provenance'] ?? '',
            'commentaire'     => $_POST['commentaire'] ?? '',
            'prive'           => !empty($_POST['prive']),
            'statut'          => $_POST['statut'] ?? 'Confirmée',
            'numero_resa'     => $_POST['numero_resa'] ?? '',
            'montant'         => $_POST['montant'] ?? '',
        ];

        if ($id) {
            $ok = ReservationService::update($id, $data);
            $this->flash($ok ? 'success' : 'error',
                         $ok ? 'Réservation mise à jour.' : 'Réservation introuvable.');
        } else {
            ReservationService::create($data);
            $this->flash('success', 'Réservation créée.');
        }

        // Rediriger vers le mois d'arrivée
        if ($data['arrivee']) {
            [$y, $m] = explode('-', $data['arrivee']);
            $this->redirect('/admin/calendrier/' . (int) $y . '/' . (int) $m);
        } else {
            $this->redirect('/admin/calendrier');
        }
    }

    public function supprimer(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/admin/calendrier');
            return;
        }
        $ok = ReservationService::delete($id);
        $this->flash($ok ? 'success' : 'error',
                     $ok ? 'Réservation supprimée.' : 'Réservation introuvable.');
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/admin/calendrier');
    }

    public function liste(): void
    {
        $filters = [
            'propriete' => $_GET['propriete'] ?? '',
            'source'    => $_GET['source'] ?? '',
            'statut'    => $_GET['statut'] ?? '',
            'mois'      => $_GET['mois'] ?? '',
            'search'    => trim($_GET['search'] ?? ''),
        ];

        $reservations = ReservationService::getAll(array_filter($filters, fn($v) => $v !== ''));

        $this->render('admin/reservations/liste', [
            'reservations' => $reservations,
            'filters'      => $filters,
            'proprietes'   => ReservationConstants::PROPRIETES,
            'sources'      => ReservationConstants::SOURCES,
            'statuts'      => ReservationConstants::STATUTS,
        ]);
    }
}
