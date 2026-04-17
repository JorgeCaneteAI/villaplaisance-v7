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
}
