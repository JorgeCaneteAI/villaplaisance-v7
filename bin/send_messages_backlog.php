#!/usr/bin/env php
<?php
declare(strict_types=1);

/**
 * Script CLI one-shot : envoie une copie de chaque message déjà présent
 * dans vp_messages à jorge@canete.fr. Utile pour récupérer le backlog
 * avant la mise en place de la notif mail automatique sur le formulaire
 * de contact.
 *
 * Usage :
 *   php bin/send_messages_backlog.php             # dry-run (liste seulement)
 *   php bin/send_messages_backlog.php --send      # envoi réel
 *   php bin/send_messages_backlog.php --send --since=2025-01-01
 */

require __DIR__ . '/../config.php';

$args = $argv;
array_shift($args);
$send  = in_array('--send', $args, true);
$since = null;
foreach ($args as $arg) {
    if (str_starts_with($arg, '--since=')) {
        $since = substr($arg, strlen('--since='));
    }
}

$sql    = "SELECT * FROM vp_messages";
$params = [];
if ($since !== null) {
    $sql     .= " WHERE created_at >= ?";
    $params[] = $since;
}
$sql .= " ORDER BY created_at ASC";

$messages = \Database::fetchAll($sql, $params);
$total    = count($messages);

if ($total === 0) {
    echo "Aucun message à envoyer.\n";
    exit(0);
}

echo sprintf("%d message(s) à traiter%s.\n",
    $total,
    $since ? " (depuis $since)" : ''
);

$to       = 'jorge@canete.fr';
$ok       = 0;
$fail     = 0;

foreach ($messages as $msg) {
    $id        = (int)($msg['id'] ?? 0);
    $name      = (string)($msg['name'] ?? '');
    $email     = (string)($msg['email'] ?? '');
    $subject   = (string)($msg['subject'] ?? '');
    $message   = (string)($msg['message'] ?? '');
    $lang      = (string)($msg['lang'] ?? '');
    $ip        = (string)($msg['ip'] ?? '');
    $createdAt = (string)($msg['created_at'] ?? '');
    $readAt    = (string)($msg['read_at'] ?? '');

    $cleanSubject = $subject !== '' ? $subject : '(sans objet)';
    $line = sprintf("  #%d  %s  %-20s  %s",
        $id,
        $createdAt,
        mb_strimwidth($name, 0, 20, '…'),
        mb_strimwidth($cleanSubject, 0, 60, '…')
    );

    if (!$send) {
        echo $line . "\n";
        continue;
    }

    $mailSubject = mb_encode_mimeheader(
        'Villa Plaisance — Archive message #' . $id . ' : ' . $cleanSubject,
        'UTF-8'
    );

    $body = "Copie d'un message déjà reçu via le formulaire de contact.\n"
        . "(envoyé depuis le script bin/send_messages_backlog.php)\n\n"
        . "ID         : " . $id . "\n"
        . "Reçu le    : " . $createdAt . "\n"
        . "Lu le      : " . ($readAt !== '' ? $readAt : 'non lu') . "\n"
        . "Nom        : " . $name . "\n"
        . "Email      : " . $email . "\n"
        . "Sujet      : " . $cleanSubject . "\n"
        . "Langue     : " . $lang . "\n"
        . "IP         : " . $ip . "\n\n"
        . "------------------------------------------------------------\n"
        . $message . "\n"
        . "------------------------------------------------------------\n\n"
        . "Répondre à ce mail enverra au visiteur (" . $email . ").\n"
        . "Voir aussi : " . (\defined('APP_URL') ? APP_URL : '') . "/admin/messages/" . $id;

    $replyToName = preg_replace('/[\r\n]+/', ' ', $name);
    $headers = "From: Villa Plaisance <contact@villaplaisance.fr>\r\n"
        . "Reply-To: " . $replyToName . " <" . $email . ">\r\n"
        . "Content-Type: text/plain; charset=UTF-8\r\n"
        . "X-Mailer: Villa Plaisance Backlog\r\n";

    $sent = @mail($to, $mailSubject, $body, $headers);
    if ($sent) {
        $ok++;
        echo $line . "  → envoyé\n";
    } else {
        $fail++;
        echo $line . "  → ÉCHEC\n";
    }

    usleep(250000); // 0.25s entre 2 envois pour ménager le MTA
}

if (!$send) {
    echo "\nDry-run terminé. Relancer avec --send pour envoyer réellement.\n";
    exit(0);
}

echo sprintf("\nTerminé : %d envoyé(s), %d échec(s).\n", $ok, $fail);
exit($fail === 0 ? 0 : 1);
