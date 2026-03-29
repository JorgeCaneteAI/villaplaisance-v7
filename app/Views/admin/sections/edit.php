<div class="page-header">
    <h1>Modifier la section</h1>
    <a href="/admin/sections/page/<?= htmlspecialchars($section['page_slug']) ?>" class="btn">Retour</a>
</div>

<form method="POST" action="/admin/sections/<?= $section['id'] ?>/save" class="admin-card">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

    <div class="form-row">
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($section['title'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="block_type">Type de bloc</label>
            <select id="block_type" name="block_type">
                <?php foreach ($blockTypes as $key => $label): ?>
                <option value="<?= htmlspecialchars($key) ?>" <?= $section['block_type'] === $key ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="content">Contenu (JSON)</label>
        <textarea id="content" name="content" rows="12" style="font-family:monospace;font-size:0.8rem"><?= htmlspecialchars($section['content'] ?? '{}') ?></textarea>
    </div>

    <label class="mb-1"><input type="checkbox" name="active" <?= $section['active'] ? 'checked' : '' ?>> Section active</label>

    <div class="mt-2">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </div>
</form>
