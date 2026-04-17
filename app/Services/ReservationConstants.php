<?php
declare(strict_types=1);

/**
 * Constantes partagées du module calendrier de réservations :
 * propriétés, sources, statuts, mois, jours — utilisées par le
 * controller, les vues, le service de sync iCal et l'export PDF.
 */

namespace App\Services;

class ReservationConstants
{
    public const PROPRIETES = [
        'VP-BB'  => "Villa Plaisance — Chambres d'hôtes",
        'VP-ETE' => 'Villa Plaisance — Maison entière',
        'AV-ANN' => 'Studio Avignon',
    ];

    public const SOURCES = [
        'Airbnb'  => ['bg' => '#FF5A5F', 'text' => '#ffffff'],
        'Booking' => ['bg' => '#003580', 'text' => '#ffffff'],
        'Direct'  => ['bg' => '#639922', 'text' => '#ffffff'],
        'Privée'  => ['bg' => '#888780', 'text' => '#ffffff'],
        'Absence' => ['bg' => '#2C2C2A', 'text' => '#ffffff'],
    ];

    public const STATUTS = ['Confirmée', 'Option', 'Annulée'];

    public const MOIS_FR = ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    public const JOURS_FR = ['LUN', 'MAR', 'MER', 'JEU', 'VEN', 'SAM', 'DIM'];

    // Abréviations 2 lettres pour les vues compactes (vue annuelle, mini-cal).
    // Nécessaire parce que substr(JOURS_FR[i], 0, 1) donne 'L M M J V S D',
    // avec deux M indistinguables pour Mardi/Mercredi.
    public const JOURS_FR_COURT = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'];
}
