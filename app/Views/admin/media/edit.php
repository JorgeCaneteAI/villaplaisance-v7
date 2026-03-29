<?php
use App\Controllers\Admin\MediaController;
$src = file_exists(ROOT . '/public/uploads/' . $media['filename'])
    ? '/uploads/' . htmlspecialchars($media['filename'])
    : ImageService::placeholder((int)$media['width'] ?: 600, (int)$media['height'] ?: 400, $media['filename']);
?>
<div class="page-header">
    <h1>Modifier le média</h1>
    <a href="/admin/media" class="btn">Retour à la médiathèque</a>
</div>

<div class="media-edit-layout">
    <!-- Preview -->
    <div class="media-edit-preview">
        <img src="<?= $src ?>" alt="<?= htmlspecialchars($media['alt_fr'] ?? '') ?>">
        <div class="media-edit-file-info">
            <table class="media-info-table">
                <tr><th>Fichier</th><td><?= htmlspecialchars($media['filename']) ?></td></tr>
                <tr><th>Original</th><td><?= htmlspecialchars($media['original_name']) ?></td></tr>
                <tr><th>Type</th><td><?= htmlspecialchars($media['mime_type']) ?></td></tr>
                <tr><th>Dimensions</th><td><?= $media['width'] ?> × <?= $media['height'] ?> px</td></tr>
                <tr><th>Taille</th><td><?= MediaController::formatSize((int)$media['file_size']) ?></td></tr>
                <tr><th>URL</th><td><code class="media-url">/uploads/<?= htmlspecialchars($media['filename']) ?></code></td></tr>
                <tr><th>Importé le</th><td><?= date('d/m/Y H:i', strtotime($media['created_at'])) ?></td></tr>
            </table>
        </div>
    </div>

    <!-- Edit form -->
    <form method="POST" action="/admin/media/<?= $media['id'] ?>/update" class="admin-card media-edit-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

        <h2>Texte alternatif (SEO)</h2>
        <p class="text-sm text-muted mb-1">Le texte alt est essentiel pour le SEO et l'accessibilité. Décrivez l'image en une phrase concise.</p>

        <div class="form-group">
            <label for="alt_fr">Alt FR (principal)</label>
            <input type="text" id="alt_fr" name="alt_fr" value="<?= htmlspecialchars($media['alt_fr'] ?? '') ?>" placeholder="Ex: Piscine privée de Villa Plaisance à Bédarrides">
            <span class="field-hint" id="alt-fr-count"><?= mb_strlen($media['alt_fr'] ?? '') ?>/125 caractères</span>
        </div>
        <div class="form-group">
            <label for="alt_en">Alt EN</label>
            <input type="text" id="alt_en" name="alt_en" value="<?= htmlspecialchars($media['alt_en'] ?? '') ?>" placeholder="Ex: Private pool at Villa Plaisance in Bédarrides">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="alt_es">Alt ES</label>
                <input type="text" id="alt_es" name="alt_es" value="<?= htmlspecialchars($media['alt_es'] ?? '') ?>" placeholder="Alt en espagnol">
            </div>
            <div class="form-group">
                <label for="alt_de">Alt DE</label>
                <input type="text" id="alt_de" name="alt_de" value="<?= htmlspecialchars($media['alt_de'] ?? '') ?>" placeholder="Alt en allemand">
            </div>
        </div>

        <h2 class="mt-2">Métadonnées</h2>

        <div class="form-group">
            <label for="title">Titre (attribut title)</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($media['title'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="caption">Légende</label>
            <textarea id="caption" name="caption" rows="2"><?= htmlspecialchars($media['caption'] ?? '') ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="credit">Crédit photo</label>
                <input type="text" id="credit" name="credit" value="<?= htmlspecialchars($media['credit'] ?? '') ?>" placeholder="© Photographe">
            </div>
            <div class="form-group">
                <label for="folder">Dossier</label>
                <select id="folder" name="folder">
                    <?php foreach ($folders as $f): ?>
                    <option value="<?= htmlspecialchars($f) ?>" <?= $media['folder'] === $f ? 'selected' : '' ?>><?= ucfirst(htmlspecialchars($f)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="tags">Tags (séparés par des virgules)</label>
            <input type="text" id="tags" name="tags" value="<?= htmlspecialchars($media['tags'] ?? '') ?>" placeholder="piscine, été, extérieur">
        </div>

        <div class="mt-2">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>
    </form>
</div>

<script>
// Alt text character counter
const altFr = document.getElementById('alt_fr');
const altCount = document.getElementById('alt-fr-count');
if (altFr && altCount) {
    altFr.addEventListener('input', () => {
        const len = altFr.value.length;
        altCount.textContent = len + '/125 caractères';
        altCount.style.color = len > 125 ? '#C0392B' : '#888';
    });
}
</script>
