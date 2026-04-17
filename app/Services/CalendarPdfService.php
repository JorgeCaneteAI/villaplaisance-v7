<?php
declare(strict_types=1);

namespace App\Services;

/**
 * Génération de PDF calendriers mensuels et annuels via FPDF.
 * Portage fidèle de export_pdf.py (reportlab) de l'app Flask legacy.
 * A4 paysage, bandes colorées par source, calcul dynamique des cellules.
 *
 * Note encoding : FPDF 1.x ne supporte pas UTF-8 nativement. Les polices
 * DejaVuSans vendorées sont compilées en cp1252 ; tout texte passé aux
 * primitives FPDF est converti UTF-8→CP1252 via self::enc().
 */
class CalendarPdfService
{
    private const EXPORTS_DIR = ROOT . '/public/uploads/exports';
    private const FPDF_PATH = ROOT . '/app/Services/lib/fpdf/fpdf.php';
    private const FONT_DIR = ROOT . '/app/Services/lib/fpdf/font';

    public static function exportMonth(int $year, int $month): string
    {
        self::ensureFpdfLoaded();
        @mkdir(self::EXPORTS_DIR, 0755, true);

        $pdf = new \FPDF('L', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false);
        self::registerFonts($pdf);
        $pdf->SetTitle(self::enc("Reservations " . ReservationConstants::MOIS_FR[$month] . " $year"));
        $pdf->AddPage();
        self::drawMonth($pdf, $year, $month);

        $path = self::EXPORTS_DIR . "/reservations_{$year}_" . sprintf('%02d', $month) . ".pdf";
        $pdf->Output('F', $path);
        return $path;
    }

    public static function exportYear(int $year): string
    {
        self::ensureFpdfLoaded();
        @mkdir(self::EXPORTS_DIR, 0755, true);

        $pdf = new \FPDF('L', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false);
        self::registerFonts($pdf);
        $pdf->SetTitle(self::enc("Reservations $year"));

        for ($m = 1; $m <= 12; $m++) {
            $pdf->AddPage();
            self::drawMonth($pdf, $year, $m);
        }

        $path = self::EXPORTS_DIR . "/reservations_{$year}.pdf";
        $pdf->Output('F', $path);
        return $path;
    }

    private static function ensureFpdfLoaded(): void
    {
        if (!defined('FPDF_FONTPATH')) {
            define('FPDF_FONTPATH', self::FONT_DIR . '/');
        }
        if (!class_exists('\\FPDF')) {
            require_once self::FPDF_PATH;
        }
    }

    private static function registerFonts(\FPDF $pdf): void
    {
        $pdf->AddFont('DejaVuSans', '', 'DejaVuSans.php');
        $pdf->AddFont('DejaVuSans', 'B', 'DejaVuSans-Bold.php');
    }

    /**
     * Convertit UTF-8 → CP1252 pour FPDF 1.x (polices cp1252).
     * //TRANSLIT remplace les glyphes hors cp1252 (ex: ●, €) par un équivalent.
     */
    private static function enc(string $s): string
    {
        $out = @iconv('UTF-8', 'windows-1252//TRANSLIT//IGNORE', $s);
        return $out === false ? $s : $out;
    }

    private static function hexToRgb(string $hex): array
    {
        $h = ltrim($hex, '#');
        return [hexdec(substr($h, 0, 2)), hexdec(substr($h, 2, 2)), hexdec(substr($h, 4, 2))];
    }

    private static function truncate(\FPDF $pdf, string $text, float $maxWidth): string
    {
        while ($text !== '' && $pdf->GetStringWidth($text) > $maxWidth) {
            $text = mb_substr($text, 0, -1);
        }
        return $text;
    }

    private static function drawMonth(\FPDF $pdf, int $year, int $month): void
    {
        $W = 297.0;           // A4 landscape mm
        $H = 210.0;
        $MARGIN = 12.0;
        $usableW = $W - 2 * $MARGIN;
        $usableH = $H - 2 * $MARGIN;

        $data = ReservationService::buildCalendarData($year, $month);
        $weeks = $data['weeks'];
        $resaByDay = $data['resa_by_day'];
        $nWeeks = count($weeks);

        $TITLE_H = 20.0;
        $HEADER_H = 7.0;
        $LEGEND_H = 8.0;
        $CELL_H = ($usableH - $TITLE_H - $HEADER_H - $LEGEND_H) / max(1, $nWeeks);
        $CELL_W = $usableW / 7.0;

        // ── TITRE ──
        [$r, $g, $b] = self::hexToRgb('#2C2C2A');
        $pdf->SetFillColor($r, $g, $b);
        $pdf->Rect($MARGIN, $MARGIN, $usableW, $TITLE_H, 'F');
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('DejaVuSans', 'B', 16);
        $pdf->SetXY($MARGIN + 6, $MARGIN + 5);
        $titre = 'RÉSERVATIONS — ' . mb_strtoupper(ReservationConstants::MOIS_FR[$month], 'UTF-8') . ' ' . $year;
        $pdf->Cell($usableW - 12, 10, self::enc($titre));
        $pdf->SetFont('DejaVuSans', '', 8);
        $pdf->SetXY($W - $MARGIN - 80, $MARGIN + 8);
        $pdf->Cell(76, 5, self::enc('Villa Plaisance & Studio Avignon'), 0, 0, 'R');

        // ── EN-TÊTES JOURS ──
        $headerY = $MARGIN + $TITLE_H;
        foreach (ReservationConstants::JOURS_FR as $i => $jour) {
            $x = $MARGIN + $i * $CELL_W;
            [$rh, $gh, $bh] = self::hexToRgb($i >= 5 ? '#3d3d3a' : '#2C2C2A');
            $pdf->SetFillColor($rh, $gh, $bh);
            $pdf->Rect($x, $headerY, $CELL_W, $HEADER_H, 'F');
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('DejaVuSans', 'B', 8);
            $pdf->SetXY($x, $headerY + 1);
            $pdf->Cell($CELL_W, $HEADER_H - 2, self::enc($jour), 0, 0, 'C');
        }

        // ── CELLULES ──
        foreach ($weeks as $weekIdx => $week) {
            $cellY = $MARGIN + $TITLE_H + $HEADER_H + $weekIdx * $CELL_H;
            foreach ($week as $dayIdx => $day) {
                $cellX = $MARGIN + $dayIdx * $CELL_W;
                $isCurrent = (int) $day->format('n') === $month;

                $bgHex = !$isCurrent ? '#f5f5f5' : ($dayIdx >= 5 ? '#fafaf8' : '#ffffff');
                [$rb, $gb, $bb] = self::hexToRgb($bgHex);
                $pdf->SetFillColor($rb, $gb, $bb);
                $pdf->Rect($cellX, $cellY, $CELL_W, $CELL_H, 'F');

                $pdf->SetDrawColor(221, 221, 221);
                $pdf->SetLineWidth(0.3);
                $pdf->Rect($cellX, $cellY, $CELL_W, $CELL_H);

                $grey = $isCurrent ? 34 : 187;
                $pdf->SetTextColor($grey, $grey, $grey);
                $pdf->SetFont('DejaVuSans', 'B', 8);
                $pdf->SetXY($cellX + 2, $cellY + 1);
                $pdf->Cell(10, 4, (string) (int) $day->format('j'));

                $key = $day->format('Y-m-d');
                if ($isCurrent && isset($resaByDay[$key])) {
                    $resas = $resaByDay[$key];
                    $bandH = min(4.0, ($CELL_H - 6) / max(1, count($resas)));
                    $bandH = max(3.0, $bandH);
                    $textW = $CELL_W - 4;
                    $bandY = $cellY + 5;

                    foreach ($resas as $resa) {
                        if ($bandY + $bandH > $cellY + $CELL_H - 1) break;
                        [$rc, $gc, $bc] = self::hexToRgb($resa['couleur']['bg']);
                        $pdf->SetFillColor($rc, $gc, $bc);
                        $pdf->Rect($cellX + 1, $bandY, $CELL_W - 2, $bandH - 0.5, 'F');
                        $pdf->SetTextColor(255, 255, 255);
                        $pdf->SetFont('DejaVuSans', 'B', 6);
                        $pdf->SetXY($cellX + 1.5, $bandY + 0.3);
                        $line = self::enc($resa['code'] . ' · ' . $resa['nom_client']);
                        $pdf->Cell($textW, 2, self::truncate($pdf, $line, $textW));
                        $bandY += $bandH;
                    }
                }
            }
        }

        // ── LÉGENDE ──
        $legY = $H - $MARGIN - 6;
        $legX = $MARGIN;
        foreach (ReservationConstants::SOURCES as $src => $c) {
            [$rl, $gl, $bl] = self::hexToRgb($c['bg']);
            $pdf->SetFillColor($rl, $gl, $bl);
            $pdf->Rect($legX, $legY, 22, 5, 'F');
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('DejaVuSans', 'B', 7);
            $pdf->SetXY($legX, $legY + 0.5);
            $pdf->Cell(22, 4, self::enc('● ' . $src), 0, 0, 'C');
            $legX += 24;
        }
    }
}
