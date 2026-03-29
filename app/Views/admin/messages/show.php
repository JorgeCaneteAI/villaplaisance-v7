<div class="page-header">
    <h1>Message de <?= htmlspecialchars($message['name']) ?></h1>
    <a href="/admin/messages" class="btn">Retour</a>
</div>

<div class="admin-card">
    <p><strong>De :</strong> <?= htmlspecialchars($message['name']) ?> &lt;<?= htmlspecialchars($message['email']) ?>&gt;</p>
    <p><strong>Sujet :</strong> <?= htmlspecialchars($message['subject'] ?: '(sans sujet)') ?></p>
    <p><strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($message['created_at'])) ?></p>
    <p><strong>Langue :</strong> <?= htmlspecialchars($message['lang'] ?? 'fr') ?></p>
    <p><strong>IP :</strong> <?= htmlspecialchars($message['ip'] ?? '') ?></p>

    <hr style="margin:1rem 0;border:none;border-top:1px solid var(--admin-border)">

    <div style="white-space:pre-wrap;line-height:1.6"><?= htmlspecialchars($message['message']) ?></div>
</div>

<div class="mt-2 btn-group">
    <a href="mailto:<?= htmlspecialchars($message['email']) ?>?subject=Re: <?= rawurlencode($message['subject'] ?? 'Villa Plaisance') ?>" class="btn btn-primary">Répondre par email</a>
    <form method="POST" action="/admin/messages/<?= $message['id'] ?>/delete" onsubmit="return confirm('Supprimer ce message ?')">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <button type="submit" class="btn btn-danger">Supprimer</button>
    </form>
</div>
