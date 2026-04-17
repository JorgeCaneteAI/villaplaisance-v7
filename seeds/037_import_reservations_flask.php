<?php
declare(strict_types=1);

/**
 * Seed 037 — Import des réservations depuis la BDD SQLite de l'app Flask legacy.
 * Lecture via PDO sqlite → écriture dans vp_reservations MySQL.
 *
 * Migration one-shot. Partiellement idempotent :
 *   - Résas iCal (ical_uid non vide) : dédupliquées via UNIQUE KEY uniq_ical(source, ical_uid).
 *   - Résas manuelles (ical_uid vide/NULL) : seraient dupliquées sur un re-run.
 *     → Garde anti-re-run : avorte si vp_reservations contient déjà des lignes,
 *       sauf si FORCE=1 dans l'environnement.
 *
 * Usage :
 *   FLASK_DB_PATH=/path/vers/reservations.db php seeds/037_import_reservations_flask.php
 *   (variable d'env peut aussi être mise dans .env ; à retirer après exécution)
 * Re-run forcé : FORCE=1 FLASK_DB_PATH=... php seeds/037_import_reservations_flask.php
 */

require __DIR__ . '/../config.php';

$sqlitePath = $_ENV['FLASK_DB_PATH'] ?? '';
if ($sqlitePath === '' || !file_exists($sqlitePath)) {
    fwrite(STDERR, "ERR: FLASK_DB_PATH invalide ou fichier introuvable : $sqlitePath\n");
    exit(1);
}

// Garde anti-re-run (voir docblock).
$existingCount = (int) \Database::fetchOne("SELECT COUNT(*) AS c FROM vp_reservations")['c'];
if ($existingCount > 0 && empty($_ENV['FORCE'])) {
    fwrite(STDERR, "ERR: vp_reservations contient déjà $existingCount ligne(s). "
                 . "Relance avec FORCE=1 pour re-importer (risque de doublons sur les résas manuelles).\n");
    exit(1);
}

$src = new PDO("sqlite:$sqlitePath");
$src->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$rows = $src->query('SELECT * FROM reservations')->fetchAll(PDO::FETCH_ASSOC);

// Sanity check : la BDD source doit contenir au moins 1 ligne.
if (count($rows) === 0) {
    fwrite(STDERR, "ERR: la source SQLite $sqlitePath ne contient aucune réservation — fichier vide ou corrompu ?\n");
    exit(2);
}

$imported = 0;
$skipped = 0;

foreach ($rows as $r) {
    // Idempotence : si la résa vient d'iCal (ical_uid non vide) et qu'elle
    // existe déjà en BDD avec cette même combinaison (source, ical_uid),
    // on la saute. La UNIQUE KEY uniq_ical l'empêcherait de toute façon.
    if (!empty($r['ical_uid'])) {
        $existing = \Database::fetchOne(
            "SELECT id FROM vp_reservations WHERE source = ? AND ical_uid = ?",
            [$r['source'], $r['ical_uid']]
        );
        if ($existing) {
            $skipped++;
            continue;
        }
    }

    \Database::insert('vp_reservations', [
        'code'            => $r['code'] ?? '',
        'nom_client'      => $r['nom_client'],
        'propriete'       => $r['propriete'],
        'source'          => $r['source'],
        'arrivee'         => $r['arrivee'],
        'depart'          => $r['depart'],
        'duree'           => $r['duree'] ?? null,
        'adultes'         => $r['adultes'] ?? 0,
        'enfants'         => $r['enfants'] ?? 0,
        'bebes'           => $r['bebes'] ?? 0,
        'animaux'         => $r['animaux'] ?? 0,
        'animaux_details' => $r['animaux_details'] ?? '',
        'provenance'      => $r['provenance'] ?? '',
        'commentaire'     => $r['commentaire'] ?? '',
        'prive'           => !empty($r['prive']) ? 1 : 0,
        'statut'          => $r['statut'] ?? 'Confirmée',
        'numero_resa'     => $r['numero_resa'] ?? '',
        'montant'         => $r['montant'] ?? null,
        'ical_uid'        => $r['ical_uid'] ?? null,
    ]);
    $imported++;
}

echo "✓ Import terminé : $imported réservation(s) importée(s), $skipped ignorée(s) (déjà présentes)\n";
echo "  Source : $sqlitePath\n";
