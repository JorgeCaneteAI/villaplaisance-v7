<div class="page-header">
    <h1>Livret d'accueil</h1>
</div>

<div class="tab-nav">
    <a href="/admin/livret?type=bb" class="tab-link <?= $type === 'bb' ? 'active' : '' ?>">Chambres d'hôtes</a>
    <a href="/admin/livret?type=villa" class="tab-link <?= $type === 'villa' ? 'active' : '' ?>">Villa entière</a>
</div>

<?php if (empty($sections)): ?>
<p class="text-muted">Aucune section pour ce livret.</p>
<?php else: ?>
<?php foreach ($sections as $section): ?>
<form method="POST" action="/admin/livret/save" class="admin-card">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
    <input type="hidden" name="id" value="<?= $section['id'] ?>">
    <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
    <input type="hidden" name="position" value="<?= $section['position'] ?>">

    <div class="form-row">
        <div class="form-group">
            <label>Titre de section</label>
            <input type="text" name="section_title" value="<?= htmlspecialchars($section['section_title']) ?>" required>
        </div>
        <div class="form-group" style="display:flex;align-items:end;gap:0.5rem">
            <label><input type="checkbox" name="active" <?= $section['active'] ? 'checked' : '' ?>> Actif</label>
        </div>
    </div>

    <div class="form-group">
        <label>Contenu</label>
        <textarea name="content" rows="4"><?= htmlspecialchars($section['content']) ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
</form>
<?php endforeach; ?>
<?php endif; ?>

<div class="mt-2">
    <form method="POST" action="/admin/livret/save" class="admin-card">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input type="hidden" name="id" value="0">
        <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
        <input type="hidden" name="position" value="<?= count($sections) + 1 ?>">
        <h2>Ajouter une section</h2>
        <div class="form-group">
            <label>Titre</label>
            <input type="text" name="section_title" required>
        </div>
        <div class="form-group">
            <label>Contenu</label>
            <textarea name="content" rows="3"></textarea>
        </div>
        <label class="mb-1"><input type="checkbox" name="active" checked> Actif</label>
        <div class="mt-1">
            <button type="submit" class="btn btn-primary btn-sm">Ajouter</button>
        </div>
    </form>
</div>
