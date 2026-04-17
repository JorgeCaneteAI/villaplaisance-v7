#!/usr/bin/env php
<?php
declare(strict_types=1);

/**
 * Script CLI exécuté par le cron o2switch toutes les 30 min.
 * Lance la sync iCal de tous les flux actifs et logge les résultats.
 * Code de sortie : 0 si aucune erreur, 1 si au moins un flux a échoué.
 *
 * Crontab cPanel :
 *   * /30 * * * * /usr/local/bin/php /home/efkz3012/villaplaisance.fr/bin/sync_ical.php >> /home/efkz3012/logs/ical_sync.log 2>&1
 * (retirer l'espace après le premier *)
 */

require __DIR__ . '/../config.php';

$result = \App\Services\IcalSyncService::syncAll('cron');

printf("[%s] Sync OK — créées: %d, MAJ: %d, supprimées: %d\n",
       date('Y-m-d H:i:s'),
       $result['created'], $result['updated'], $result['deleted']);

foreach ($result['errors'] as $err) {
    fwrite(STDERR, "[" . date('Y-m-d H:i:s') . "] ERR: $err\n");
}

exit(empty($result['errors']) ? 0 : 1);
