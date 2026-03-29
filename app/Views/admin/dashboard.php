<div class="page-header">
    <h1>Dashboard</h1>
</div>

<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-number"><?= $stats['articles_published'] ?? 0 ?></div>
        <div class="stat-label">Articles publiés</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['messages_unread'] ?? 0 ?></div>
        <div class="stat-label">Messages non lus</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['reviews'] ?? 0 ?></div>
        <div class="stat-label">Avis clients</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['pages'] ?? 0 ?></div>
        <div class="stat-label">Pages CMS</div>
    </div>
</div>

<div class="admin-card">
    <h2>Derniers messages</h2>
    <?php if (empty($recentMessages)): ?>
    <p class="text-muted">Aucun message pour le moment.</p>
    <?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>De</th>
                <th>Sujet</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentMessages as $msg): ?>
            <tr>
                <td><a href="/admin/messages/<?= $msg['id'] ?>"><?= htmlspecialchars($msg['name']) ?></a></td>
                <td><?= htmlspecialchars($msg['subject'] ?: '(sans sujet)') ?></td>
                <td class="text-sm text-muted"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></td>
                <td>
                    <?php if (empty($msg['read_at'])): ?>
                    <span class="badge badge-warning">Non lu</span>
                    <?php else: ?>
                    <span class="badge badge-success">Lu</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
