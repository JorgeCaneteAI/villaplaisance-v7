<?php
/**
 * Vue : gestion des appareils de confiance.
 * @var array $devices
 */
$csrf = $_SESSION['csrf_token'] ?? ($_SESSION['csrf_token'] = bin2hex(random_bytes(32)));
?>
<div class="securite">
    <header class="securite__header">
        <h1>Sécurité — Appareils de confiance</h1>
    </header>

    <p class="securite__intro">
        Les appareils listés ci-dessous sont autorisés à se connecter sans redemander le code PIN,
        pendant 6 mois à partir de la validation initiale. Révoque un appareil si tu ne le reconnais pas
        ou si tu l'as perdu.
    </p>

    <table class="table-devices">
        <thead>
            <tr>
                <th>Appareil</th>
                <th>Créé le</th>
                <th>Dernière utilisation</th>
                <th>Expire le</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($devices as $d): ?>
                <tr>
                    <td class="ua"><?= htmlspecialchars($d['user_agent'] ?? '(inconnu)') ?></td>
                    <td><?= htmlspecialchars($d['created_at']) ?></td>
                    <td><?= htmlspecialchars($d['last_used'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($d['expires_at']) ?></td>
                    <td>
                        <form method="post" action="/admin/securite/revoke/<?= (int) $d['id'] ?>"
                              onsubmit="return confirm('Révoquer cet appareil ? Il faudra ressaisir le PIN à la prochaine connexion.');"
                              style="display:inline">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                            <button type="submit" class="btn btn-danger">Révoquer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($devices)): ?>
                <tr><td colspan="5" class="empty">Aucun appareil de confiance enregistré.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.securite__header { margin-bottom: 16px; }
.securite__intro { max-width: 700px; color: #555; margin-bottom: 20px; line-height: 1.5; }
.table-devices { width: 100%; border-collapse: collapse; font-size: 13px; }
.table-devices th, .table-devices td { padding: 10px 8px; text-align: left; border-bottom: 1px solid #eee; }
.table-devices th { background: #2C2C2A; color: #fff; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
.table-devices .ua { font-family: monospace; font-size: 11px; color: #666; max-width: 400px; word-break: break-word; }
.table-devices .empty { text-align: center; padding: 24px; color: #888; font-style: italic; }
.btn-danger { background: #d9534f; color: #fff; border: 1px solid #c9302c; }
.btn-danger:hover { background: #c9302c; }
</style>
