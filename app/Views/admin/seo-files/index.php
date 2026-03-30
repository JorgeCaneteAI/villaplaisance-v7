<div class="page-header">
    <h1>Fichiers SEO</h1>
</div>

<!-- File list -->
<div class="admin-card">
    <h2>Fichiers gérés</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Fichier</th>
                <th>Type</th>
                <th>Dernière modification</th>
                <th>Aperçu</th>
                <th style="width:180px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Sitemap (always auto-generated) -->
            <tr>
                <td>
                    <code style="font-size:0.85rem;">sitemap.xml</code>
                </td>
                <td><span class="badge badge-info">Auto-généré</span></td>
                <td style="font-size:0.78rem;color:#888;">Dynamique (temps réel)</td>
                <td><a href="/sitemap.xml" target="_blank" class="btn btn-sm">Voir</a></td>
                <td><span style="font-size:0.75rem;color:#888;">Non éditable (pages + articles)</span></td>
            </tr>
            <?php foreach ($files as $f): ?>
            <tr>
                <td>
                    <code style="font-size:0.85rem;"><?= htmlspecialchars($f['filename']) ?></code>
                </td>
                <td>
                    <span class="badge" style="background:#e8f0fe;color:var(--admin-accent);font-size:0.7rem;">Personnalisé</span>
                </td>
                <td style="font-size:0.78rem;color:#888;"><?= date('d/m/Y H:i', strtotime($f['updated_at'])) ?></td>
                <td>
                    <?php
                    $url = '/' . $f['filename'];
                    ?>
                    <a href="<?= $url ?>" target="_blank" class="btn btn-sm">Voir</a>
                </td>
                <td>
                    <div style="display:flex;gap:0.3rem;">
                        <a href="/admin/seo-files/<?= $f['id'] ?>/edit" class="btn btn-sm btn-primary">Éditer</a>
                        <form method="POST" action="/admin/seo-files/<?= $f['id'] ?>/delete" onsubmit="return confirm('Supprimer ce fichier ?');" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Suppr.</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <!-- .htaccess (read-only info) -->
            <tr>
                <td><code style="font-size:0.85rem;">.htaccess</code></td>
                <td><span class="badge" style="background:#f0f0f0;color:#888;font-size:0.7rem;">Système</span></td>
                <td style="font-size:0.78rem;color:#888;"><?= $htaccessExists ? 'Présent' : 'Absent' ?></td>
                <td>&mdash;</td>
                <td><span style="font-size:0.75rem;color:#888;">Géré via Redirections</span></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Add new file -->
<div class="admin-card">
    <h2>Ajouter un fichier SEO</h2>
    <form method="POST" action="/admin/seo-files/create">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <div class="form-group">
            <label>Nom du fichier</label>
            <input type="text" name="filename" placeholder="humans.txt, ads.txt, security.txt..." style="max-width:300px;">
        </div>
        <div class="form-group">
            <label>Contenu</label>
            <textarea name="content" rows="6" style="font-family:monospace;font-size:0.85rem;" placeholder="Contenu du fichier..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Créer le fichier</button>
    </form>
</div>

<div class="admin-card" style="background:#f8f9fb;">
    <p style="font-size:0.8rem;color:#888;">
        <strong>Info :</strong> Les fichiers SEO sont servis dynamiquement à la racine du site.
        Le <code>sitemap.xml</code> est auto-généré à partir des pages et articles publiés.
        Le <code>robots.txt</code> et <code>llms.txt</code> sont éditables depuis cette interface.
        Vous pouvez ajouter d'autres fichiers comme <code>ads.txt</code>, <code>humans.txt</code> ou <code>security.txt</code>.
    </p>
</div>
