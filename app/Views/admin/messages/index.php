<div class="page-header">
    <h1>Messages</h1>
</div>

<?php if (empty($messages)): ?>
<p class="text-muted">Aucun message reçu.</p>
<?php else: ?>
<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Sujet</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $msg): ?>
            <tr>
                <td><a href="/admin/messages/<?= $msg['id'] ?>"><?= htmlspecialchars($msg['name']) ?></a></td>
                <td class="text-sm"><?= htmlspecialchars($msg['email']) ?></td>
                <td><?= htmlspecialchars($msg['subject'] ?: '(sans sujet)') ?></td>
                <td class="text-sm text-muted"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></td>
                <td>
                    <?php if (empty($msg['read_at'])): ?>
                    <span class="badge badge-warning">Non lu</span>
                    <?php else: ?>
                    <span class="badge badge-success">Lu</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="btn-group">
                        <a href="/admin/messages/<?= $msg['id'] ?>" class="btn btn-sm">Lire</a>
                        <form method="POST" action="/admin/messages/<?= $msg['id'] ?>/delete" onsubmit="return confirm('Supprimer ce message ?')">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Suppr.</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
