<div class="page-header">
    <h1>Tableau de bord</h1>
    <p style="color:#888;font-size:0.85rem;margin-top:0.25rem;">Vue d'ensemble du site Villa Plaisance</p>
</div>

<!-- ═══ Stats principales ═══ -->
<div class="stats-cards" style="grid-template-columns: repeat(6, 1fr);">
    <a href="/admin/articles" class="stat-card stat-card-link">
        <div class="stat-number"><?= $stats['articles_published'] ?></div>
        <div class="stat-label">Articles publiés</div>
        <div style="font-size:0.7rem;color:#aaa;margin-top:0.25rem;"><?= $stats['articles_draft'] ?> brouillons</div>
    </a>
    <a href="/admin/pages" class="stat-card stat-card-link">
        <div class="stat-number"><?= $stats['pages'] ?></div>
        <div class="stat-label">Pages CMS</div>
    </a>
    <a href="/admin/sections" class="stat-card stat-card-link">
        <div class="stat-number"><?= $stats['sections'] ?></div>
        <div class="stat-label">Sections</div>
    </a>
    <a href="/admin/messages" class="stat-card stat-card-link">
        <div class="stat-number"><?= $stats['messages_unread'] ?></div>
        <div class="stat-label">Messages non lus</div>
        <div style="font-size:0.7rem;color:#aaa;margin-top:0.25rem;"><?= $stats['messages'] ?> total</div>
    </a>
    <a href="/admin/avis" class="stat-card stat-card-link">
        <div class="stat-number"><?= $stats['reviews'] ?></div>
        <div class="stat-label">Avis clients</div>
    </a>
    <a href="/admin/media" class="stat-card stat-card-link">
        <div class="stat-number"><?= $stats['media'] ?></div>
        <div class="stat-label">Médias</div>
    </a>
</div>

<!-- ═══ Grid 2 colonnes : SEO + Traductions ═══ -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">

    <!-- SEO Health -->
    <div class="admin-card">
        <h2 style="display:flex;align-items:center;gap:0.5rem;">
            <span style="font-size:1.2rem;">&#128270;</span> Santé SEO
        </h2>
        <div class="dash-seo-grid">
            <div class="dash-seo-item">
                <div class="dash-seo-bar">
                    <div class="dash-seo-fill" style="width:<?= $seo['meta_pct'] ?>%;background:<?= $seo['meta_pct'] >= 80 ? 'var(--admin-success)' : ($seo['meta_pct'] >= 50 ? '#e67e22' : 'var(--admin-error)') ?>;"></div>
                </div>
                <div class="dash-seo-info">
                    <span>Meta title + desc</span>
                    <strong><?= $seo['meta_pct'] ?>%</strong>
                </div>
                <div style="font-size:0.7rem;color:#999;"><?= $seo['articles_with_meta'] ?>/<?= $stats['articles_total'] ?> articles</div>
            </div>
            <div class="dash-seo-item">
                <div class="dash-seo-bar">
                    <div class="dash-seo-fill" style="width:<?= $seo['cover_pct'] ?>%;background:<?= $seo['cover_pct'] >= 80 ? 'var(--admin-success)' : ($seo['cover_pct'] >= 50 ? '#e67e22' : 'var(--admin-error)') ?>;"></div>
                </div>
                <div class="dash-seo-info">
                    <span>Image de couverture</span>
                    <strong><?= $seo['cover_pct'] ?>%</strong>
                </div>
                <div style="font-size:0.7rem;color:#999;"><?= $seo['articles_with_cover'] ?>/<?= $stats['articles_total'] ?> articles</div>
            </div>
            <div class="dash-seo-item">
                <div class="dash-seo-bar">
                    <div class="dash-seo-fill" style="width:<?= $seo['gso_pct'] ?>%;background:<?= $seo['gso_pct'] >= 80 ? 'var(--admin-success)' : ($seo['gso_pct'] >= 50 ? '#e67e22' : 'var(--admin-error)') ?>;"></div>
                </div>
                <div class="dash-seo-info">
                    <span>Description GSO</span>
                    <strong><?= $seo['gso_pct'] ?>%</strong>
                </div>
                <div style="font-size:0.7rem;color:#999;"><?= $seo['articles_with_gso'] ?>/<?= $stats['articles_total'] ?> articles</div>
            </div>
            <div class="dash-seo-item">
                <div class="dash-seo-bar">
                    <div class="dash-seo-fill" style="width:<?= $stats['pages'] > 0 ? round($seo['pages_with_meta'] / $stats['pages'] * 100) : 0 ?>%;background:var(--admin-success);"></div>
                </div>
                <div class="dash-seo-info">
                    <span>Pages avec meta</span>
                    <strong><?= $seo['pages_with_meta'] ?>/<?= $stats['pages'] ?></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Traductions -->
    <div class="admin-card">
        <h2 style="display:flex;align-items:center;gap:0.5rem;">
            <span style="font-size:1.2rem;">&#127760;</span> Couverture traductions
        </h2>
        <?php foreach (['en' => 'Anglais', 'es' => 'Espagnol'] as $code => $label): ?>
        <div style="margin-bottom:1.25rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.4rem;">
                <span style="font-weight:600;font-size:0.85rem;"><?= strtoupper($code) ?> &mdash; <?= $label ?></span>
            </div>
            <!-- Articles -->
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.4rem;">
                <span style="font-size:0.75rem;color:#888;width:70px;">Articles</span>
                <div class="dash-seo-bar" style="flex:1;">
                    <div class="dash-seo-fill" style="width:<?= $translations[$code]['pct'] ?? 0 ?>%;background:<?= ($translations[$code]['pct'] ?? 0) >= 80 ? 'var(--admin-success)' : '#e67e22' ?>;"></div>
                </div>
                <span style="font-size:0.8rem;font-weight:600;width:50px;text-align:right;"><?= $translations[$code]['pct'] ?? 0 ?>%</span>
                <span style="font-size:0.7rem;color:#999;"><?= $translations[$code]['filled'] ?? 0 ?>/<?= $translations[$code]['total'] ?? 0 ?></span>
            </div>
            <!-- Sections -->
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <span style="font-size:0.75rem;color:#888;width:70px;">Sections</span>
                <div class="dash-seo-bar" style="flex:1;">
                    <div class="dash-seo-fill" style="width:<?= $translations[$code . '_sections']['pct'] ?? 0 ?>%;background:<?= ($translations[$code . '_sections']['pct'] ?? 0) >= 80 ? 'var(--admin-success)' : '#e67e22' ?>;"></div>
                </div>
                <span style="font-size:0.8rem;font-weight:600;width:50px;text-align:right;"><?= $translations[$code . '_sections']['pct'] ?? 0 ?>%</span>
                <span style="font-size:0.7rem;color:#999;"><?= $translations[$code . '_sections']['filled'] ?? 0 ?>/<?= $translations[$code . '_sections']['total'] ?? 0 ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ═══ Grid 2 colonnes : Redirections + Fichiers SEO ═══ -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1rem;margin-bottom:1rem;">

    <!-- Redirections 301 -->
    <div class="admin-card">
        <h2 style="display:flex;align-items:center;gap:0.5rem;">
            <span style="font-size:1.2rem;">&#8634;</span> Redirections 301
            <span style="font-size:0.7rem;color:#888;font-weight:400;margin-left:auto;">
                <a href="/admin/redirects" style="color:var(--admin-accent);text-decoration:none;">Gérer &rarr;</a>
            </span>
        </h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Ancienne URL</th>
                    <th>Nouvelle URL</th>
                    <th>Code</th>
                    <th>Actif</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($redirects as $r): ?>
                <tr style="<?= $r['active'] ? '' : 'opacity:0.4;' ?>">
                    <td style="font-family:monospace;font-size:0.78rem;color:#888;"><?= htmlspecialchars($r['url_from']) ?></td>
                    <td style="font-family:monospace;font-size:0.78rem;"><?= htmlspecialchars($r['url_to']) ?></td>
                    <td><span class="badge badge-success"><?= $r['status_code'] ?></span></td>
                    <td style="text-align:center;"><?= $r['active'] ? '<span style="color:var(--admin-success);">&#10003;</span>' : '<span style="color:#ccc;">&#10007;</span>' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Fichiers SEO -->
    <div class="admin-card">
        <h2 style="display:flex;align-items:center;gap:0.5rem;">
            <span style="font-size:1.2rem;">&#128196;</span> Fichiers SEO
            <span style="font-size:0.7rem;color:#888;font-weight:400;margin-left:auto;">
                <a href="/admin/seo-files" style="color:var(--admin-accent);text-decoration:none;">Gérer &rarr;</a>
            </span>
        </h2>
        <div class="dash-seo-files">
            <?php foreach ($seoFiles as $name => $info): ?>
            <div class="dash-file-row">
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <?php if ($info['exists']): ?>
                        <span style="color:var(--admin-success);font-size:1.1rem;">&#10003;</span>
                    <?php else: ?>
                        <span style="color:var(--admin-error);font-size:1.1rem;">&#10007;</span>
                    <?php endif; ?>
                    <code style="font-size:0.85rem;"><?= $name ?></code>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <span class="badge" style="background:<?= $info['type'] === 'Éditable' ? '#e8f0fe' : ($info['type'] === 'Auto-généré' ? '#e8f5e9' : '#f0f0f0') ?>;color:<?= $info['type'] === 'Éditable' ? 'var(--admin-accent)' : ($info['type'] === 'Auto-généré' ? 'var(--admin-success)' : '#888') ?>;font-size:0.65rem;"><?= $info['type'] ?></span>
                    <?php if ($info['url']): ?>
                        <a href="<?= $info['url'] ?>" target="_blank" class="btn btn-sm" style="padding:0.15rem 0.4rem;font-size:0.7rem;">Voir</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top:1.25rem;padding-top:1rem;border-top:1px solid var(--admin-border);">
            <h3 style="font-size:0.85rem;font-weight:600;margin-bottom:0.5rem;">Contenu additionnel</h3>
            <div class="dash-file-row">
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <span style="color:var(--admin-success);font-size:1.1rem;">&#10003;</span>
                    <span style="font-size:0.8rem;">FAQ structurées</span>
                </div>
                <span style="font-size:0.85rem;font-weight:600;"><?= $stats['faq'] ?></span>
            </div>
            <div class="dash-file-row">
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <span style="color:var(--admin-success);font-size:1.1rem;">&#10003;</span>
                    <span style="font-size:0.8rem;">Pièces / blocs</span>
                </div>
                <span style="font-size:0.85rem;font-weight:600;"><?= $stats['pieces'] ?></span>
            </div>
        </div>
    </div>
</div>

<!-- ═══ Articles nécessitant attention ═══ -->
<?php if (!empty($articlesAttention)): ?>
<div class="admin-card">
    <h2 style="display:flex;align-items:center;gap:0.5rem;">
        <span style="font-size:1.2rem;">&#9888;</span> Articles incomplets
        <span style="font-size:0.7rem;color:#888;font-weight:400;margin-left:auto;"><?= count($articlesAttention) ?> article(s)</span>
    </h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Type</th>
                <th>Meta title</th>
                <th>Meta desc</th>
                <th>Cover</th>
                <th>GSO</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articlesAttention as $art): ?>
            <tr>
                <td style="font-weight:500;"><?= htmlspecialchars($art['title']) ?></td>
                <td><span class="badge" style="background:#f0f0f0;color:#555;font-size:0.7rem;"><?= $art['type'] ?></span></td>
                <td style="text-align:center;">
                    <?php if (!empty($art['meta_title'])): ?>
                        <span style="color:var(--admin-success);">&#10003;</span>
                    <?php else: ?>
                        <span style="color:var(--admin-error);">&#10007;</span>
                    <?php endif; ?>
                </td>
                <td style="text-align:center;">
                    <?php if (!empty($art['meta_desc'])): ?>
                        <span style="color:var(--admin-success);">&#10003;</span>
                    <?php else: ?>
                        <span style="color:var(--admin-error);">&#10007;</span>
                    <?php endif; ?>
                </td>
                <td style="text-align:center;">
                    <?php if (!empty($art['cover_image'])): ?>
                        <span style="color:var(--admin-success);">&#10003;</span>
                    <?php else: ?>
                        <span style="color:var(--admin-error);">&#10007;</span>
                    <?php endif; ?>
                </td>
                <td style="text-align:center;">
                    <?php if (!empty($art['gso_desc'])): ?>
                        <span style="color:var(--admin-success);">&#10003;</span>
                    <?php else: ?>
                        <span style="color:var(--admin-error);">&#10007;</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="/admin/articles/edit/<?= $art['id'] ?>" class="btn btn-sm btn-primary">Compléter</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<!-- ═══ Derniers messages ═══ -->
<div class="admin-card">
    <h2 style="display:flex;align-items:center;gap:0.5rem;">
        <span style="font-size:1.2rem;">&#9993;</span> Derniers messages
        <?php if ($stats['messages_unread'] > 0): ?>
            <span class="badge badge-warning" style="font-size:0.7rem;"><?= $stats['messages_unread'] ?> non lu(s)</span>
        <?php endif; ?>
    </h2>
    <?php if (empty($recentMessages)): ?>
    <p style="color:#888;font-size:0.85rem;">Aucun message pour le moment.</p>
    <?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>De</th>
                <th>Email</th>
                <th>Sujet</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentMessages as $msg): ?>
            <tr>
                <td style="font-weight:500;"><a href="/admin/messages/<?= $msg['id'] ?>"><?= htmlspecialchars($msg['name']) ?></a></td>
                <td style="font-size:0.78rem;color:#888;"><?= htmlspecialchars($msg['email'] ?? '') ?></td>
                <td><?= htmlspecialchars($msg['subject'] ?: '(sans sujet)') ?></td>
                <td style="font-size:0.78rem;color:#888;"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></td>
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
