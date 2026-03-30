<div class="page-header">
    <h1>Redirections 301</h1>
</div>

<!-- Add new redirect -->
<div class="admin-card">
    <h2>Ajouter une redirection</h2>
    <form method="POST" action="/admin/redirects/create">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <div style="display:grid;grid-template-columns:2fr 2fr 100px 2fr auto;gap:0.75rem;align-items:end;">
            <div class="form-group" style="margin:0;">
                <label>URL source</label>
                <input type="text" name="url_from" placeholder="villaplaisance.fr/ancienne-page" required>
            </div>
            <div class="form-group" style="margin:0;">
                <label>URL destination</label>
                <input type="text" name="url_to" placeholder="/nouvelle-page" required>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Code</label>
                <select name="status_code">
                    <option value="301">301</option>
                    <option value="302">302</option>
                    <option value="307">307</option>
                    <option value="308">308</option>
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Note</label>
                <input type="text" name="note" placeholder="Description optionnelle">
            </div>
            <button type="submit" class="btn btn-primary" style="height:36px;">Ajouter</button>
        </div>
    </form>
</div>

<!-- Existing redirects -->
<div class="admin-card">
    <h2 style="display:flex;align-items:center;gap:0.5rem;">
        Liste des redirections
        <span style="font-size:0.75rem;color:#888;font-weight:400;margin-left:auto;"><?= count($redirects) ?> redirection(s) &mdash; sync .htaccess automatique</span>
    </h2>

    <?php if (empty($redirects)): ?>
        <p style="color:#888;">Aucune redirection configurée.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>URL source</th>
                    <th>URL destination</th>
                    <th>Code</th>
                    <th>Note</th>
                    <th>Actif</th>
                    <th style="width:180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($redirects as $r): ?>
                <tr id="row-<?= $r['id'] ?>" class="<?= $r['active'] ? '' : 'redirect-inactive' ?>">
                    <td>
                        <form method="POST" action="/admin/redirects/<?= $r['id'] ?>/update" style="display:contents;" id="form-<?= $r['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="text" name="url_from" value="<?= htmlspecialchars($r['url_from']) ?>" class="redirect-input" style="font-family:monospace;font-size:0.78rem;">
                    </td>
                    <td>
                            <input type="text" name="url_to" value="<?= htmlspecialchars($r['url_to']) ?>" class="redirect-input" style="font-family:monospace;font-size:0.78rem;">
                    </td>
                    <td>
                            <select name="status_code" class="redirect-input" style="width:70px;">
                                <?php foreach ([301, 302, 307, 308] as $code): ?>
                                <option value="<?= $code ?>" <?= (int)$r['status_code'] === $code ? 'selected' : '' ?>><?= $code ?></option>
                                <?php endforeach; ?>
                            </select>
                    </td>
                    <td>
                            <input type="text" name="note" value="<?= htmlspecialchars($r['note'] ?? '') ?>" class="redirect-input" style="font-size:0.78rem;">
                            <input type="hidden" name="active" value="<?= $r['active'] ?>">
                        </form>
                    </td>
                    <td style="text-align:center;">
                        <form method="POST" action="/admin/redirects/<?= $r['id'] ?>/toggle" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <button type="submit" class="btn btn-sm" title="<?= $r['active'] ? 'Désactiver' : 'Activer' ?>" style="<?= $r['active'] ? 'color:var(--admin-success);' : 'color:#ccc;' ?>">
                                <?= $r['active'] ? '&#10003;' : '&#10007;' ?>
                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.3rem;">
                            <button type="submit" form="form-<?= $r['id'] ?>" class="btn btn-sm btn-primary">Sauver</button>
                            <form method="POST" action="/admin/redirects/<?= $r['id'] ?>/delete" onsubmit="return confirm('Supprimer cette redirection ?');" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Suppr.</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="admin-card" style="background:#f8f9fb;">
    <p style="font-size:0.8rem;color:#888;">
        <strong>Info :</strong> Chaque modification est automatiquement synchronisée dans le fichier <code>.htaccess</code>.
        Les codes : <strong>301</strong> = permanent, <strong>302</strong> = temporaire, <strong>307</strong> = temporaire (POST conservé), <strong>308</strong> = permanent (POST conservé).
    </p>
</div>
