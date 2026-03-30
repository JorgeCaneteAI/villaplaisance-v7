<?php
$byLang = [];
foreach ($profiles as $p) {
    $byLang[$p['lang']] = $p;
}
$fr = $byLang['fr'] ?? [];
$en = $byLang['en'] ?? [];
$es = $byLang['es'] ?? [];
?>
<div class="admin-page-header">
    <h1>Votre hôte — Profil</h1>
    <p class="text-muted">Page "Qui suis-je" visible sur le site. Contenu en 3 langues.</p>
</div>

<!-- ═══ Profil principal ═══ -->
<form method="POST" action="/admin/host/save" class="admin-form">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

    <div class="host-admin-section">
        <h2>Profil principal</h2>

        <div class="form-group">
            <label>Photo portrait</label>
            <div style="display:flex; gap:0.5rem; align-items:center">
                <input type="text" id="photo" name="photo" value="<?= htmlspecialchars($fr['photo'] ?? '') ?>" placeholder="jorge-portrait.webp" style="flex:1" readonly>
                <button type="button" class="btn-sm btn-pick-image" data-target="photo">Choisir</button>
            </div>
            <div class="img-preview" id="preview-photo">
                <?php if (!empty($fr['photo'])): ?>
                <img src="/uploads/<?= htmlspecialchars($fr['photo']) ?>" alt="Photo hôte">
                <?php endif; ?>
            </div>
        </div>

        <div class="trilang-grid">
            <?php foreach (['fr' => $fr, 'en' => $en, 'es' => $es] as $lang => $data): ?>
            <div class="trilang-col">
                <div class="trilang-label"><?= strtoupper($lang) ?></div>
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="name_<?= $lang ?>" value="<?= htmlspecialchars($data['name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Sous-titre</label>
                    <input type="text" name="subtitle_<?= $lang ?>" value="<?= htmlspecialchars($data['subtitle'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Introduction</label>
                    <textarea name="intro_<?= $lang ?>" rows="4"><?= htmlspecialchars($data['intro'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Citation</label>
                    <textarea name="quote_<?= $lang ?>" rows="2"><?= htmlspecialchars($data['quote'] ?? '') ?></textarea>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Enregistrer le profil</button>
        </div>
    </div>
</form>

<!-- ═══ Blocs CV ═══ -->
<div class="host-admin-section">
    <h2>Blocs du CV <span class="text-muted" style="font-weight:400;font-size:0.85rem">(<?= count($blocks) ?> blocs)</span></h2>

    <?php foreach ($blocks as $i => $block): ?>
    <div class="host-block-card">
        <div class="host-block-header">
            <strong><?= htmlspecialchars($block['title_fr']) ?></strong>
            <div class="host-block-actions">
                <?php if ($i > 0): ?>
                <form method="POST" action="/admin/host/block/<?= $block['id'] ?>/move" style="display:inline">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                    <input type="hidden" name="direction" value="up">
                    <button type="submit" class="btn-sm" title="Monter">↑</button>
                </form>
                <?php endif; ?>
                <?php if ($i < count($blocks) - 1): ?>
                <form method="POST" action="/admin/host/block/<?= $block['id'] ?>/move" style="display:inline">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                    <input type="hidden" name="direction" value="down">
                    <button type="submit" class="btn-sm" title="Descendre">↓</button>
                </form>
                <?php endif; ?>
                <form method="POST" action="/admin/host/block/<?= $block['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer ce bloc ?')">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                    <button type="submit" class="btn-sm btn-danger" title="Supprimer">✕</button>
                </form>
            </div>
        </div>

        <form method="POST" action="/admin/host/block/<?= $block['id'] ?>/update">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

            <div class="form-group">
                <label>Image du bloc</label>
                <div style="display:flex; gap:0.5rem; align-items:center">
                    <input type="text" name="block_image" id="block-img-<?= $block['id'] ?>" value="<?= htmlspecialchars($block['image'] ?? '') ?>" placeholder="image.webp" style="flex:1" readonly>
                    <button type="button" class="btn-sm btn-pick-image" data-target="block-img-<?= $block['id'] ?>">Choisir</button>
                    <?php if (!empty($block['image'])): ?>
                    <button type="button" class="btn-sm" onclick="document.getElementById('block-img-<?= $block['id'] ?>').value=''; document.getElementById('preview-block-img-<?= $block['id'] ?>').innerHTML=''">Retirer</button>
                    <?php endif; ?>
                </div>
                <div class="img-preview" id="preview-block-img-<?= $block['id'] ?>">
                    <?php if (!empty($block['image'])): ?>
                    <img src="/uploads/<?= htmlspecialchars($block['image']) ?>" alt="Image bloc">
                    <?php endif; ?>
                </div>
            </div>

            <div class="trilang-grid">
                <?php foreach (['fr', 'en', 'es'] as $lang): ?>
                <div class="trilang-col">
                    <div class="trilang-label"><?= strtoupper($lang) ?></div>
                    <div class="form-group">
                        <label>Titre</label>
                        <input type="text" name="title_<?= $lang ?>" value="<?= htmlspecialchars($block["title_{$lang}"] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Contenu</label>
                        <textarea name="content_<?= $lang ?>" rows="4"><?= htmlspecialchars($block["content_{$lang}"] ?? '') ?></textarea>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary btn-sm">Enregistrer ce bloc</button>
            </div>
        </form>
    </div>
    <?php endforeach; ?>

    <!-- Ajouter un bloc -->
    <div class="host-block-card host-block-new">
        <h3>+ Ajouter un bloc</h3>
        <form method="POST" action="/admin/host/block/create">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

            <div class="form-group">
                <label>Image</label>
                <div style="display:flex; gap:0.5rem; align-items:center">
                    <input type="text" name="block_image" id="new-block-img" value="" placeholder="image.webp" style="flex:1" readonly>
                    <button type="button" class="btn-sm btn-pick-image" data-target="new-block-img">Choisir</button>
                </div>
                <div class="img-preview" id="preview-new-block-img"></div>
            </div>

            <div class="trilang-grid">
                <?php foreach (['fr', 'en', 'es'] as $lang): ?>
                <div class="trilang-col">
                    <div class="trilang-label"><?= strtoupper($lang) ?></div>
                    <div class="form-group">
                        <label>Titre</label>
                        <input type="text" name="title_<?= $lang ?>" value="">
                    </div>
                    <div class="form-group">
                        <label>Contenu</label>
                        <textarea name="content_<?= $lang ?>" rows="3"></textarea>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Créer le bloc</button>
            </div>
        </form>
    </div>
</div>

<style>
.host-admin-section { margin-bottom: 2.5rem; padding: 1.5rem; background: #fff; border: 1px solid #e8e6e1; border-radius: 8px; }
.host-admin-section h2 { font-size: 1.1rem; font-weight: 600; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e8e6e1; }
.trilang-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-top: 0.75rem; }
.trilang-col { background: #f8f8f6; border: 1px solid #e8e6e1; border-radius: 8px; padding: 1rem; }
.trilang-label { font-weight: 600; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: #88A398; margin-bottom: 0.75rem; padding-bottom: 0.4rem; border-bottom: 2px solid #88A398; }
.host-block-card { margin-bottom: 1.25rem; padding: 1.25rem; background: #fafaf8; border: 1px solid #e8e6e1; border-radius: 8px; }
.host-block-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e8e6e1; }
.host-block-actions { display: flex; gap: 0.35rem; }
.host-block-new { border-style: dashed; background: #fff; }
.host-block-new h3 { font-size: 1rem; font-weight: 500; margin-bottom: 1rem; color: #88A398; }
.btn-sm { padding: 0.3em 0.7em; font-size: 0.75rem; border: 1px solid #88A398; background: #88A398; color: #fff; border-radius: 4px; cursor: pointer; }
.btn-sm:hover { background: #6d8a7b; border-color: #6d8a7b; }
.btn-pick-image { background: #2c6fbb; border-color: #2c6fbb; color: #fff; }
.btn-pick-image:hover { background: #1e5294; border-color: #1e5294; }
.btn-danger { color: #c0392b; border-color: #c0392b; }
.btn-danger:hover { background: #c0392b; color: #fff; }
.form-actions { margin-top: 1rem; }
.img-preview { margin-top: 0.5rem; max-width: 200px; }
.img-preview img { width: 100%; border-radius: 6px; }
@media (max-width: 900px) { .trilang-grid { grid-template-columns: 1fr; } }
</style>

<!-- Media Picker Modal -->
<div id="media-picker-modal" class="media-modal" style="display:none">
    <div class="media-modal-backdrop"></div>
    <div class="media-modal-content">
        <div class="media-modal-header">
            <h3>Choisir une image</h3>
            <input type="text" id="media-picker-search" placeholder="Rechercher..." class="media-modal-search">
            <button type="button" class="media-modal-close">&times;</button>
        </div>
        <div class="media-modal-body" id="media-picker-grid"></div>
    </div>
</div>

<script>
(function() {
    let targetInputId = null;
    const modal = document.getElementById('media-picker-modal');
    if (!modal) return;
    const grid = document.getElementById('media-picker-grid');
    const search = document.getElementById('media-picker-search');
    const backdrop = modal.querySelector('.media-modal-backdrop');
    const closeBtn = modal.querySelector('.media-modal-close');
    let allFiles = [];

    // Open picker on button click
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-pick-image');
        if (btn) {
            targetInputId = btn.dataset.target;
            openModal();
        }
    });

    backdrop.addEventListener('click', closeModal);
    closeBtn.addEventListener('click', closeModal);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
    search.addEventListener('input', () => renderGrid(search.value.toLowerCase()));

    function openModal() {
        modal.style.display = 'flex';
        search.value = '';
        if (allFiles.length === 0) {
            grid.innerHTML = '<p style="padding:1rem;color:#888">Chargement...</p>';
            fetch('/admin/api/media-list')
                .then(r => r.json())
                .then(files => { allFiles = files; renderGrid(''); })
                .catch(() => { grid.innerHTML = '<p style="padding:1rem;color:#c00">Erreur de chargement</p>'; });
        } else {
            renderGrid('');
        }
        search.focus();
    }

    function closeModal() { modal.style.display = 'none'; targetInputId = null; }

    function renderGrid(filter) {
        const filtered = filter ? allFiles.filter(f => f.toLowerCase().includes(filter)) : allFiles;
        grid.innerHTML = filtered.map(f => `
            <div class="media-thumb" data-file="${f}">
                <img src="/uploads/${f}" alt="${f}" loading="lazy">
                <span class="media-thumb-name">${f.replace('villa-plaisance-','').replace('.webp','')}</span>
            </div>
        `).join('');

        grid.querySelectorAll('.media-thumb').forEach(thumb => {
            thumb.addEventListener('click', () => {
                const file = thumb.dataset.file;
                if (targetInputId) {
                    const input = document.getElementById(targetInputId);
                    if (input) input.value = file;
                    // Update preview
                    const preview = document.getElementById('preview-' + targetInputId);
                    if (preview) preview.innerHTML = '<img src="/uploads/' + file + '" alt="">';
                }
                closeModal();
            });
        });
    }
})();
</script>
