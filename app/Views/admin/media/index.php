<?php
use App\Controllers\Admin\MediaController;
?>
<div class="page-header">
    <h1>Médiathèque</h1>
    <div class="media-stats-bar">
        <span class="badge badge-info"><?= $stats['total'] ?> fichier<?= $stats['total'] > 1 ? 's' : '' ?></span>
        <span class="badge badge-info"><?= MediaController::formatSize($stats['size']) ?> total</span>
        <span class="badge badge-success"><?= $stats['webp'] ?> WebP</span>
    </div>
</div>

<!-- Upload zone -->
<div class="admin-card media-upload-card">
    <h2>Importer des images</h2>
    <form method="POST" action="/admin/media/upload" enctype="multipart/form-data" class="media-upload-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <div class="media-upload-zone" id="dropzone">
            <div class="media-upload-zone-inner">
                <span class="media-upload-icon">+</span>
                <p>Glisser-déposer ou <label for="file-input" class="media-upload-browse">parcourir</label></p>
                <p class="text-sm text-muted">JPG, PNG, WebP, AVIF — max 5 Mo — conversion auto WebP</p>
                <input type="file" id="file-input" name="images[]" multiple accept="image/jpeg,image/png,image/webp,image/gif,image/avif" class="media-file-input">
            </div>
            <div id="file-preview" class="media-file-preview"></div>
        </div>
        <div class="media-upload-options">
            <div class="form-group form-group-inline">
                <label for="upload-folder">Dossier</label>
                <select id="upload-folder" name="folder">
                    <?php foreach ($folders as $f): ?>
                    <option value="<?= htmlspecialchars($f) ?>"><?= ucfirst(htmlspecialchars($f)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm" id="upload-btn" disabled>Importer</button>
        </div>
    </form>
</div>

<!-- Filters -->
<div class="media-filters">
    <div class="media-filter-folders">
        <a href="/admin/media" class="btn btn-sm <?= $folder === '' ? 'btn-primary' : '' ?>">Tous</a>
        <?php foreach ($folders as $f): ?>
        <a href="/admin/media?folder=<?= urlencode($f) ?>" class="btn btn-sm <?= $folder === $f ? 'btn-primary' : '' ?>"><?= ucfirst(htmlspecialchars($f)) ?></a>
        <?php endforeach; ?>
    </div>
    <form method="GET" action="/admin/media" class="media-search">
        <?php if ($folder): ?>
        <input type="hidden" name="folder" value="<?= htmlspecialchars($folder) ?>">
        <?php endif; ?>
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher (nom, alt, tags)…">
        <button type="submit" class="btn btn-sm">Chercher</button>
    </form>
</div>

<!-- Media grid -->
<?php if (empty($media)): ?>
<div class="admin-card">
    <p class="text-muted text-center">Aucun média<?= $search ? " pour « {$search} »" : '' ?>.</p>
</div>
<?php else: ?>
<div class="media-grid">
    <?php foreach ($media as $m): ?>
    <div class="media-card">
        <div class="media-thumb">
            <?php
            $src = file_exists(ROOT . '/public/uploads/' . $m['filename'])
                ? '/uploads/' . htmlspecialchars($m['filename'])
                : ImageService::placeholder((int)$m['width'] ?: 300, (int)$m['height'] ?: 200, $m['filename']);
            ?>
            <img src="<?= $src ?>" alt="<?= htmlspecialchars($m['alt_fr'] ?? $m['filename']) ?>" loading="lazy">
        </div>
        <div class="media-card-info">
            <span class="media-card-name" title="<?= htmlspecialchars($m['filename']) ?>"><?= htmlspecialchars($m['filename']) ?></span>
            <div class="media-card-meta">
                <span><?= $m['width'] ?>×<?= $m['height'] ?></span>
                <span><?= MediaController::formatSize((int)$m['file_size']) ?></span>
                <span class="badge badge-info"><?= htmlspecialchars($m['folder']) ?></span>
            </div>
            <?php if (empty($m['alt_fr'])): ?>
            <span class="media-alert">Alt manquant</span>
            <?php else: ?>
            <span class="media-alt-preview" title="<?= htmlspecialchars($m['alt_fr']) ?>">alt: <?= htmlspecialchars(mb_strimwidth($m['alt_fr'], 0, 40, '…')) ?></span>
            <?php endif; ?>
        </div>
        <div class="media-card-actions">
            <a href="/admin/media/<?= $m['id'] ?>/edit" class="btn btn-sm">Modifier</a>
            <form method="POST" action="/admin/media/<?= $m['id'] ?>/delete" onsubmit="return confirm('Supprimer ce média ?')" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-sm btn-danger">✕</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
// Drag & drop + preview
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('file-input');
const uploadBtn = document.getElementById('upload-btn');
const preview = document.getElementById('file-preview');

function showPreview(files) {
    preview.innerHTML = '';
    if (files.length === 0) { uploadBtn.disabled = true; return; }
    uploadBtn.disabled = false;
    Array.from(files).forEach(f => {
        const div = document.createElement('div');
        div.className = 'media-preview-item';
        if (f.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(f);
            div.appendChild(img);
        }
        const span = document.createElement('span');
        span.textContent = f.name + ' (' + (f.size / 1024).toFixed(0) + ' Ko)';
        div.appendChild(span);
        preview.appendChild(div);
    });
}

fileInput.addEventListener('change', () => showPreview(fileInput.files));

dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('drag-over'); });
dropzone.addEventListener('dragleave', () => dropzone.classList.remove('drag-over'));
dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.classList.remove('drag-over');
    fileInput.files = e.dataTransfer.files;
    showPreview(e.dataTransfer.files);
});
</script>
