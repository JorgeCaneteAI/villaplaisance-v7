<div class="page-header">
    <h1>Livret d'accueil</h1>
</div>

<!-- Code d'accès -->
<div class="admin-card mb-2">
    <form method="POST" action="/admin/livret/save-password" class="form-inline">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
        <input type="hidden" name="lang" value="fr">
        <label><strong>Code d'accès client :</strong></label>
        <input type="text" name="livret_password" value="<?= htmlspecialchars($password) ?>" style="width:200px;margin:0 0.5rem" required>
        <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
        <span class="text-muted" style="margin-left:1rem;font-size:0.8rem">URL : /livret?type=bb ou /livret?type=villa</span>
    </form>
</div>

<!-- Tabs type -->
<div class="tab-nav">
    <a href="/admin/livret?type=bb" class="tab-link <?= $type === 'bb' ? 'active' : '' ?>">Chambres d'hôtes</a>
    <a href="/admin/livret?type=villa" class="tab-link <?= $type === 'villa' ? 'active' : '' ?>">Villa entière</a>
</div>

<?php if (empty($allSections)): ?>
<p class="text-muted">Aucune section pour ce livret.</p>
<?php else: ?>
<?php foreach ($allSections as $position => $langs): ?>
<div class="admin-card mb-2">
    <div class="livret-trilang-header">
        <span class="text-muted text-sm">Section <?= $position ?></span>
        <!-- Delete button -->
        <?php $anyId = $langs['fr']['id'] ?? ($langs['en']['id'] ?? ($langs['es']['id'] ?? 0)); ?>
        <?php if ($anyId): ?>
        <form method="POST" action="/admin/livret/<?= $anyId ?>/delete" style="margin:0">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
            <input type="hidden" name="lang" value="fr">
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette section dans toutes les langues ?')">Supprimer</button>
        </form>
        <?php endif; ?>
    </div>

    <div class="livret-trilang-grid">
        <?php foreach (['fr' => 'FR', 'en' => 'EN', 'es' => 'ES'] as $l => $label): ?>
        <div class="livret-trilang-col">
            <div class="livret-trilang-label"><?= $label ?></div>
            <?php if (isset($langs[$l])): ?>
            <?php $s = $langs[$l]; ?>
            <form method="POST" action="/admin/livret/save">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
                <input type="hidden" name="lang" value="<?= $l ?>">
                <input type="hidden" name="position" value="<?= $s['position'] ?>">
                <div class="form-group">
                    <input type="text" name="section_title" value="<?= htmlspecialchars($s['section_title']) ?>" placeholder="Titre">
                </div>
                <div class="form-group">
                    <textarea name="content" rows="4" placeholder="Contenu"><?= htmlspecialchars($s['content']) ?></textarea>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem">
                    <label style="font-size:0.8rem"><input type="checkbox" name="active" <?= $s['active'] ? 'checked' : '' ?>> Actif</label>
                    <button type="submit" class="btn btn-primary btn-sm">Sauver</button>
                </div>
            </form>
            <?php else: ?>
            <p class="text-muted text-sm">Non créé</p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<!-- Add section -->
<div class="mt-2">
    <form method="POST" action="/admin/livret/save" class="admin-card">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input type="hidden" name="id" value="0">
        <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
        <input type="hidden" name="lang" value="fr">
        <input type="hidden" name="position" value="0">
        <h2>Ajouter une section</h2>
        <p class="text-muted" style="font-size:0.8rem;margin-bottom:1rem">Crée la section pour les 3 langues. Remplissez le FR ici, puis éditez EN/ES dans les colonnes ci-dessus.</p>
        <div class="form-group">
            <label>Titre (FR)</label>
            <input type="text" name="section_title" required>
        </div>
        <div class="form-group">
            <label>Contenu (FR)</label>
            <textarea name="content" rows="3"></textarea>
        </div>
        <label class="mb-1"><input type="checkbox" name="active" checked> Actif</label>
        <div class="mt-1">
            <button type="submit" class="btn btn-primary btn-sm">Ajouter</button>
        </div>
    </form>
</div>
