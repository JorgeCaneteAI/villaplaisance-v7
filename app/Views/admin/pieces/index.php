<div class="page-header">
    <h1>Chambres &amp; Espaces</h1>
</div>

<?php if (empty($pieces)): ?>
<p class="text-muted mb-2">Aucune chambre ou espace défini.</p>
<?php else: ?>

<?php
$currentOffer = '';
foreach ($pieces as $p):
    $key = $p['offer'] . ':' . $p['position'];
    $frImgList = json_decode($p['images'] ?? '[]', true) ?: [];
    if (empty($frImgList) && !empty($p['image'])) $frImgList = [$p['image']];

    if ($p['offer'] !== $currentOffer):
        $currentOffer = $p['offer'];
        $offerLabels = ['bb' => 'Chambres d\'hôtes (B&B)', 'villa' => 'Villa entière', 'both' => 'Commun'];
?>
<h2 class="section-group-title"><?= $offerLabels[$currentOffer] ?? $currentOffer ?></h2>
<?php endif; ?>

<div class="block-row">
    <!-- Header with shared controls -->
    <div class="block-row-header">
        <div class="block-row-info">
            <span class="section-position"><?= $p['position'] ?></span>
            <span class="badge badge-info"><?= htmlspecialchars($p['type']) ?></span>
            <strong><?= htmlspecialchars($p['name']) ?></strong>
        </div>
        <div class="block-row-actions">
            <form method="POST" action="/admin/pieces/<?= $p['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer cette fiche (toutes langues) ?')">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-sm btn-danger">✕</button>
            </form>
        </div>
    </div>

    <!-- 3-column language grid -->
    <div class="lang-columns">
        <?php foreach ($langs as $l):
            $lp = $pieceIndex[$l][$key] ?? null;
        ?>
        <div class="lang-column">
            <div class="lang-column-header">
                <span><?= $langLabels[$l] ?? strtoupper($l) ?></span>
                <?php if (!$lp): ?><span class="badge badge-warning">manquant</span><?php endif; ?>
            </div>
            <?php if ($lp): ?>
            <form method="POST" action="/admin/pieces/<?= $lp['id'] ?>/save" class="lang-column-body">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <?php // Non-FR langs get shared fields from FR as hidden inputs
                if ($l !== 'fr'):
                    $frP = $pieceIndex['fr'][$key] ?? $p;
                ?>
                    <input type="hidden" name="offer" value="<?= htmlspecialchars($frP['offer']) ?>">
                    <input type="hidden" name="type" value="<?= htmlspecialchars($frP['type']) ?>">
                    <input type="hidden" name="image" value="<?= htmlspecialchars($frP['image'] ?? '') ?>">
                    <input type="hidden" name="images" value="<?= htmlspecialchars($frP['images'] ?? '[]') ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($lp['name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Sous-titre</label>
                    <input type="text" name="sous_titre" value="<?= htmlspecialchars($lp['sous_titre'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"><?= htmlspecialchars($lp['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Equipements</label>
                    <input type="text" name="equip" value="<?= htmlspecialchars($lp['equip'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Note</label>
                    <input type="text" name="note" value="<?= htmlspecialchars($lp['note'] ?? '') ?>">
                </div>

                <?php // Shared fields only in FR column
                if ($l === 'fr'): ?>
                <hr style="margin:0.5rem 0;border:none;border-top:1px solid #e2e8f0">
                <p style="font-size:0.65rem;color:#999;margin-bottom:0.25rem">Paramètres partagés</p>
                <div class="form-group">
                    <label>Offre</label>
                    <select name="offer">
                        <option value="bb" <?= $lp['offer'] === 'bb' ? 'selected' : '' ?>>Chambres d'hotes</option>
                        <option value="villa" <?= $lp['offer'] === 'villa' ? 'selected' : '' ?>>Villa entiere</option>
                        <option value="both" <?= $lp['offer'] === 'both' ? 'selected' : '' ?>>Les deux</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type">
                        <option value="chambre" <?= $lp['type'] === 'chambre' ? 'selected' : '' ?>>Chambre</option>
                        <option value="espace" <?= $lp['type'] === 'espace' ? 'selected' : '' ?>>Espace</option>
                    </select>
                </div>
                <div class="form-group form-group-image">
                    <label>Photos</label>
                    <?php
                    $imgList = json_decode($lp['images'] ?? '[]', true) ?: [];
                    if (empty($imgList) && !empty($lp['image'])) $imgList = [$lp['image']];
                    ?>
                    <div class="piece-images-grid" id="pimgs-grid-<?= $lp['id'] ?>">
                        <?php foreach ($imgList as $img): ?>
                        <div class="piece-img-thumb" data-file="<?= htmlspecialchars($img) ?>">
                            <img src="/uploads/<?= htmlspecialchars($img) ?>" alt="" loading="lazy">
                            <button type="button" class="piece-img-remove" title="Retirer">&times;</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-add-piece-image" data-piece-id="<?= $lp['id'] ?>">+ Photo</button>
                    <input type="hidden" name="images" id="pimgs-<?= $lp['id'] ?>" value="<?= htmlspecialchars(json_encode($imgList)) ?>">
                    <input type="hidden" name="image" value="<?= htmlspecialchars($imgList[0] ?? $lp['image'] ?? '') ?>">
                </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary btn-sm mt-1">Enregistrer</button>
            </form>
            <?php else: ?>
            <div class="lang-column-body">
                <p class="lang-missing">Pas encore de version <?= strtoupper($l) ?></p>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endforeach; ?>
<?php endif; ?>

<!-- Ajouter une fiche -->
<form method="POST" action="/admin/pieces/create" class="section-card section-card-new">
    <div class="section-card-header">
        <div class="section-card-info">
            <span class="section-position">+</span>
            <strong>Ajouter une chambre / espace</strong>
        </div>
    </div>
    <div class="section-card-body">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <div class="section-fields">
            <div class="form-group form-group-inline">
                <label for="new-offer">Offre</label>
                <select id="new-offer" name="offer">
                    <option value="bb">Chambres d'hotes</option>
                    <option value="villa">Villa entiere</option>
                </select>
            </div>
            <div class="form-group form-group-inline">
                <label for="new-type">Type</label>
                <select id="new-type" name="type">
                    <option value="chambre">Chambre</option>
                    <option value="espace">Espace</option>
                </select>
            </div>
        </div>
        <div class="section-card-footer">
            <button type="submit" class="btn btn-primary btn-sm">Ajouter (FR/EN/ES)</button>
        </div>
    </div>
</form>

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
    let pieceCallback = null;
    const modal = document.getElementById('media-picker-modal');
    if (!modal) return;
    const grid = document.getElementById('media-picker-grid');
    const search = document.getElementById('media-picker-search');
    const backdrop = modal.querySelector('.media-modal-backdrop');
    const closeBtn = modal.querySelector('.media-modal-close');
    let allFiles = [];

    document.addEventListener('click', (e) => {
        const addBtn = e.target.closest('.btn-add-piece-image');
        if (addBtn) {
            const pid = addBtn.dataset.pieceId;
            pieceCallback = function(file) { addPieceImage(pid, file); };
            openModal();
            return;
        }
        const rmBtn = e.target.closest('.piece-img-remove');
        if (rmBtn) {
            const thumb = rmBtn.closest('.piece-img-thumb');
            const imgGrid = thumb.closest('.piece-images-grid');
            const pid = imgGrid.id.replace('pimgs-grid-', '');
            thumb.remove();
            syncPieceImages(pid);
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

    function closeModal() { modal.style.display = 'none'; pieceCallback = null; }

    function addPieceImage(pid, file) {
        const g = document.getElementById('pimgs-grid-' + pid);
        if (!g || g.querySelector('[data-file="' + file + '"]')) return;
        const div = document.createElement('div');
        div.className = 'piece-img-thumb';
        div.dataset.file = file;
        div.innerHTML = '<img src="/uploads/' + file + '" alt="" loading="lazy">'
            + '<button type="button" class="piece-img-remove" title="Retirer">&times;</button>';
        g.appendChild(div);
        syncPieceImages(pid);
    }

    function syncPieceImages(pid) {
        const g = document.getElementById('pimgs-grid-' + pid);
        const hidden = document.getElementById('pimgs-' + pid);
        if (!g || !hidden) return;
        const files = Array.from(g.querySelectorAll('.piece-img-thumb')).map(t => t.dataset.file);
        hidden.value = JSON.stringify(files);
        const form = g.closest('form');
        if (form) {
            const imgInput = form.querySelector('input[name="image"]');
            if (imgInput) imgInput.value = files[0] || '';
        }
    }

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
                if (pieceCallback) pieceCallback(file);
                closeModal();
            });
        });
    }
})();
</script>
