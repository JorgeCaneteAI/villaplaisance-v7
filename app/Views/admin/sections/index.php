<div class="page-header">
    <h1>Sections<?= $page_slug ? ' — ' . htmlspecialchars($page_slug) : '' ?></h1>
    <a href="/admin/pages" class="btn">Retour aux pages</a>
</div>

<?php if (!$page_slug): ?>
<p class="text-muted">Sélectionnez une page depuis la liste des pages CMS.</p>
<?php else: ?>

<?php if (empty($sections)): ?>
<p class="text-muted mb-2">Aucune section pour cette page.</p>
<?php else: ?>

<?php
// Field definitions per block type
$fieldDefs = [
    'hero' => [
        ['name' => 'title', 'label' => 'Titre (H1)', 'type' => 'text'],
        ['name' => 'subtitle', 'label' => 'Sous-titre', 'type' => 'text'],
        ['name' => 'cta_text', 'label' => 'Texte du bouton', 'type' => 'text'],
        ['name' => 'cta_url', 'label' => 'URL du bouton', 'type' => 'text'],
        ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
        ['name' => 'image_alt', 'label' => 'Alt image', 'type' => 'text'],
        ['name' => 'compact', 'label' => 'Mode compact (sans image)', 'type' => 'checkbox'],
    ],
    'prose' => [
        ['name' => 'heading', 'label' => 'Titre (H2)', 'type' => 'text'],
        ['name' => 'text', 'label' => 'Texte', 'type' => 'textarea'],
        ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
        ['name' => 'image_alt', 'label' => 'Alt image', 'type' => 'text'],
        ['name' => 'lead', 'label' => 'Style lead (texte plus grand)', 'type' => 'checkbox'],
    ],
    'cta' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'text', 'label' => 'Texte', 'type' => 'textarea'],
        ['name' => 'button_text', 'label' => 'Texte du bouton', 'type' => 'text'],
        ['name' => 'button_url', 'label' => 'URL du bouton', 'type' => 'text'],
        ['name' => 'dark', 'label' => 'Fond sombre', 'type' => 'checkbox'],
    ],
    'cartes' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'offer', 'label' => 'Offre', 'type' => 'select', 'options' => ['bb' => 'Chambres d\'hôtes', 'villa' => 'Villa entière']],
    ],
    'faq' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'page_slug', 'label' => 'Page source des FAQ', 'type' => 'text'],
    ],
    'avis' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'limit', 'label' => 'Nombre d\'avis', 'type' => 'number'],
        ['name' => 'offer_filter', 'label' => 'Filtrer par offre (bb/villa, vide = tous)', 'type' => 'text'],
    ],
    'stats' => [
        ['name' => '_info', 'label' => 'Les chiffres sont gérés dans la table vp_stats (pas de contenu JSON ici).', 'type' => 'info'],
    ],
    'territoire' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
    ],
    'galerie' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'images', 'label' => 'Images (JSON)', 'type' => 'json'],
    ],
    'articles' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => ['journal' => 'Journal', 'surplace' => 'Sur place']],
        ['name' => 'limit', 'label' => 'Nombre d\'articles', 'type' => 'number'],
    ],
    'liste' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'style', 'label' => 'Style', 'type' => 'select', 'options' => ['check' => 'Check ✓', 'bullet' => 'Puces', 'none' => 'Sans']],
        ['name' => 'items', 'label' => 'Items (JSON)', 'type' => 'json'],
    ],
    'tableau' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'rows', 'label' => 'Lignes (JSON)', 'type' => 'json'],
    ],
    'petit-dejeuner' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'text', 'label' => 'Texte', 'type' => 'textarea'],
        ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
        ['name' => 'image_alt', 'label' => 'Alt image', 'type' => 'text'],
    ],
    'piscine' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'text', 'label' => 'Texte', 'type' => 'textarea'],
        ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
        ['name' => 'image_alt', 'label' => 'Alt image', 'type' => 'text'],
    ],
];
?>

<?php foreach ($sections as $i => $s):
    $content = json_decode($s['content'] ?? '{}', true) ?: [];
    $type = $s['block_type'];
    $fields = $fieldDefs[$type] ?? [];
    $isInactive = !$s['active'];
?>
<div class="section-card<?= $isInactive ? ' section-card-inactive' : '' ?>">
    <!-- Header -->
    <div class="section-card-header">
        <div class="section-card-info">
            <span class="section-position"><?= $s['position'] ?></span>
            <span class="badge badge-info"><?= htmlspecialchars($type) ?></span>
            <strong class="section-title"><?= htmlspecialchars($s['title'] ?? '(sans titre)') ?></strong>
            <?php if ($isInactive): ?>
            <span class="badge badge-warning">Masqué</span>
            <?php endif; ?>
        </div>
        <div class="section-card-actions">
            <form method="POST" action="/admin/sections/<?= $s['id'] ?>/toggle" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-sm" title="<?= $s['active'] ? 'Masquer' : 'Activer' ?>">
                    <?= $s['active'] ? '👁 Masquer' : '👁‍🗨 Activer' ?>
                </button>
            </form>
            <form method="POST" action="/admin/sections/<?= $s['id'] ?>/move/up" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-sm" title="Monter">↑</button>
            </form>
            <form method="POST" action="/admin/sections/<?= $s['id'] ?>/move/down" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-sm" title="Descendre">↓</button>
            </form>
            <form method="POST" action="/admin/sections/<?= $s['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer cette section ?')">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">✕ Suppr.</button>
            </form>
        </div>
    </div>

    <!-- Content form -->
    <form method="POST" action="/admin/sections/<?= $s['id'] ?>/save" class="section-card-body">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input type="hidden" name="block_type" value="<?= htmlspecialchars($type) ?>">

        <div class="section-fields">
            <!-- Section title -->
            <div class="form-group form-group-inline">
                <label for="title-<?= $s['id'] ?>">Titre de la section</label>
                <input type="text" id="title-<?= $s['id'] ?>" name="title" value="<?= htmlspecialchars($s['title'] ?? '') ?>">
            </div>

            <?php
            // Separate checkbox fields from other fields
            $checkboxFields = [];
            $otherFields = [];
            if (!empty($fields)) {
                foreach ($fields as $field) {
                    if ($field['type'] === 'checkbox') {
                        $checkboxFields[] = $field;
                    } else {
                        $otherFields[] = $field;
                    }
                }
            }
            ?>

            <?php if (empty($fields)): ?>
            <!-- Fallback: raw JSON editor -->
            <div class="form-group">
                <label for="content-raw-<?= $s['id'] ?>">Contenu (JSON)</label>
                <textarea id="content-raw-<?= $s['id'] ?>" name="content" rows="6" class="code-textarea"><?= htmlspecialchars($s['content'] ?? '{}') ?></textarea>
            </div>
            <?php else: ?>
            <!-- Typed fields (non-checkbox) -->
            <?php foreach ($otherFields as $field):
                $val = $content[$field['name']] ?? '';
            ?>
                <?php if ($field['type'] === 'info'): ?>
                <div class="form-group">
                    <p class="field-info"><?= $field['label'] ?></p>
                </div>

                <?php elseif ($field['type'] === 'image'):
                    // Support multi-image: value can be a JSON array string or a single filename
                    $imgArr = [];
                    if (is_array($val)) {
                        $imgArr = $val;
                    } elseif (is_string($val) && str_starts_with(trim($val), '[')) {
                        $imgArr = json_decode($val, true) ?: [];
                    } elseif (!empty($val)) {
                        $imgArr = [(string)$val];
                    }
                    $fieldUid = 'simgs-' . $s['id'] . '-' . $field['name'];
                ?>
                <div class="form-group form-group-image">
                    <label><?= $field['label'] ?></label>
                    <div class="piece-images-grid" id="<?= $fieldUid ?>-grid">
                        <?php foreach ($imgArr as $img): ?>
                        <div class="piece-img-thumb" data-file="<?= htmlspecialchars($img) ?>">
                            <img src="/uploads/<?= htmlspecialchars($img) ?>" alt="" loading="lazy">
                            <button type="button" class="piece-img-remove" title="Retirer">&times;</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-add-section-image" data-grid-id="<?= $fieldUid ?>-grid" data-hidden-id="<?= $fieldUid ?>">+ Ajouter photo</button>
                    <input type="hidden" name="fields[<?= $field['name'] ?>]" id="<?= $fieldUid ?>" value="<?= htmlspecialchars(json_encode($imgArr)) ?>">
                </div>

                <?php elseif ($field['type'] === 'text'): ?>
                <div class="form-group form-group-inline">
                    <label for="f-<?= $s['id'] ?>-<?= $field['name'] ?>"><?= $field['label'] ?></label>
                    <input type="text" id="f-<?= $s['id'] ?>-<?= $field['name'] ?>" name="fields[<?= $field['name'] ?>]" value="<?= htmlspecialchars((string)$val) ?>">
                </div>

                <?php elseif ($field['type'] === 'number'): ?>
                <div class="form-group form-group-inline">
                    <label for="f-<?= $s['id'] ?>-<?= $field['name'] ?>"><?= $field['label'] ?></label>
                    <input type="number" id="f-<?= $s['id'] ?>-<?= $field['name'] ?>" name="fields[<?= $field['name'] ?>]" value="<?= htmlspecialchars((string)$val) ?>" min="1" max="50">
                </div>

                <?php elseif ($field['type'] === 'textarea'): ?>
                <div class="form-group">
                    <label for="f-<?= $s['id'] ?>-<?= $field['name'] ?>"><?= $field['label'] ?></label>
                    <textarea id="f-<?= $s['id'] ?>-<?= $field['name'] ?>" name="fields[<?= $field['name'] ?>]" rows="4"><?= htmlspecialchars((string)$val) ?></textarea>
                </div>

                <?php elseif ($field['type'] === 'select'): ?>
                <div class="form-group form-group-inline">
                    <label for="f-<?= $s['id'] ?>-<?= $field['name'] ?>"><?= $field['label'] ?></label>
                    <select id="f-<?= $s['id'] ?>-<?= $field['name'] ?>" name="fields[<?= $field['name'] ?>]">
                        <?php foreach ($field['options'] as $optVal => $optLabel): ?>
                        <option value="<?= htmlspecialchars($optVal) ?>" <?= (string)$val === (string)$optVal ? 'selected' : '' ?>><?= htmlspecialchars($optLabel) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php elseif ($field['type'] === 'json'): ?>
                <div class="form-group">
                    <label for="f-<?= $s['id'] ?>-<?= $field['name'] ?>"><?= $field['label'] ?></label>
                    <textarea id="f-<?= $s['id'] ?>-<?= $field['name'] ?>" name="fields[<?= $field['name'] ?>]" rows="4" class="code-textarea"><?= htmlspecialchars(is_array($val) ? json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : (string)$val) ?></textarea>
                </div>

                <?php endif; ?>
            <?php endforeach; ?>
            <?php endif; ?>

            <!-- Checkboxes grouped on one line -->
            <div class="section-checks">
                <?php foreach ($checkboxFields as $field):
                    $val = $content[$field['name']] ?? '';
                ?>
                <div class="form-group-check">
                    <label>
                        <input type="hidden" name="fields[<?= $field['name'] ?>]" value="0">
                        <input type="checkbox" name="fields[<?= $field['name'] ?>]" value="1" <?= !empty($val) ? 'checked' : '' ?>>
                        <?= $field['label'] ?>
                    </label>
                </div>
                <?php endforeach; ?>
                <div class="form-group-check">
                    <label>
                        <input type="checkbox" name="active" <?= $s['active'] ? 'checked' : '' ?>>
                        Section active
                    </label>
                </div>
            </div>
        </div>

        <div class="section-card-footer">
            <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
        </div>
    </form>

    <?php if ($type === 'cartes'):
        $offerFilter = $content['offer'] ?? 'bb';
        $filteredPieces = array_filter($pieces ?? [], fn($p) => $p['offer'] === $offerFilter);
    ?>
    <?php if (!empty($filteredPieces)): ?>
    <div class="pieces-inline">
        <div class="pieces-inline-header">
            <strong>Fiches <?= $offerFilter === 'bb' ? 'chambres d\'hôtes' : 'villa' ?></strong>
            <a href="/admin/pieces" class="btn btn-sm">Gérer les fiches →</a>
        </div>
        <?php foreach ($filteredPieces as $p):
            $imgList = json_decode($p['images'] ?? '[]', true) ?: [];
            // Fallback: if images empty but image set, use it
            if (empty($imgList) && !empty($p['image'])) $imgList = [$p['image']];
        ?>
        <form method="POST" action="/admin/pieces/<?= $p['id'] ?>/save" class="piece-inline-card">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <input type="hidden" name="offer" value="<?= htmlspecialchars($p['offer']) ?>">
            <input type="hidden" name="type" value="<?= htmlspecialchars($p['type']) ?>">
            <input type="hidden" name="image" value="<?= htmlspecialchars($imgList[0] ?? $p['image'] ?? '') ?>">
            <div class="piece-inline-row">
                <div class="piece-inline-images-col">
                    <label style="font-size:0.7rem;font-weight:500;margin-bottom:0.3rem;display:block">Photos</label>
                    <div class="piece-images-grid" id="pimgs-grid-<?= $p['id'] ?>">
                        <?php foreach ($imgList as $img): ?>
                        <div class="piece-img-thumb" data-file="<?= htmlspecialchars($img) ?>">
                            <img src="/uploads/<?= htmlspecialchars($img) ?>" alt="" loading="lazy">
                            <button type="button" class="piece-img-remove" title="Retirer">&times;</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-add-piece-image" data-piece-id="<?= $p['id'] ?>">+ Ajouter photo</button>
                    <input type="hidden" name="images" id="pimgs-<?= $p['id'] ?>" value="<?= htmlspecialchars(json_encode($imgList)) ?>">
                </div>
                <div class="piece-inline-fields">
                    <div class="piece-field-row">
                        <div class="form-group form-group-inline">
                            <label for="pname-<?= $p['id'] ?>">Nom</label>
                            <input type="text" id="pname-<?= $p['id'] ?>" name="name" value="<?= htmlspecialchars($p['name']) ?>">
                        </div>
                        <div class="form-group form-group-inline">
                            <label for="psub-<?= $p['id'] ?>">Sous-titre</label>
                            <input type="text" id="psub-<?= $p['id'] ?>" name="sous_titre" value="<?= htmlspecialchars($p['sous_titre'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pdesc-<?= $p['id'] ?>">Description</label>
                        <textarea id="pdesc-<?= $p['id'] ?>" name="description" rows="2"><?= htmlspecialchars($p['description'] ?? '') ?></textarea>
                    </div>
                    <div class="piece-field-row">
                        <div class="form-group form-group-inline">
                            <label for="pequip-<?= $p['id'] ?>">Équipements (virgules)</label>
                            <input type="text" id="pequip-<?= $p['id'] ?>" name="equip" value="<?= htmlspecialchars($p['equip'] ?? '') ?>">
                        </div>
                        <div class="form-group form-group-inline">
                            <label for="pnote-<?= $p['id'] ?>">Note</label>
                            <input type="text" id="pnote-<?= $p['id'] ?>" name="note" value="<?= htmlspecialchars($p['note'] ?? '') ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                </div>
            </div>
        </form>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>
<?php endforeach; ?>

<?php endif; ?>

<!-- Add new section -->
<form method="POST" action="/admin/sections/create" class="section-card section-card-new">
    <div class="section-card-header">
        <div class="section-card-info">
            <span class="section-position">+</span>
            <strong>Ajouter une section</strong>
        </div>
    </div>
    <div class="section-card-body">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input type="hidden" name="page_slug" value="<?= htmlspecialchars($page_slug) ?>">
        <div class="section-fields">
            <div class="form-group form-group-inline">
                <label for="new-block_type">Type de bloc</label>
                <select id="new-block_type" name="block_type">
                    <?php foreach ($blockTypes as $key => $label): ?>
                    <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group form-group-inline">
                <label for="new-title">Titre</label>
                <input type="text" id="new-title" name="title" value="Nouvelle section">
            </div>
        </div>
        <div class="section-card-footer">
            <button type="submit" class="btn btn-primary btn-sm">Ajouter</button>
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
    const modal = document.getElementById('media-picker-modal');
    if (!modal) return;
    const pickerGrid = document.getElementById('media-picker-grid');
    const search = document.getElementById('media-picker-search');
    const backdrop = modal.querySelector('.media-modal-backdrop');
    const closeBtn = modal.querySelector('.media-modal-close');
    let allFiles = [];
    let onSelect = null;

    // Unified click handler
    document.addEventListener('click', (e) => {
        // Multi-image: add photo to section field (hero, prose, etc.)
        const secBtn = e.target.closest('.btn-add-section-image');
        if (secBtn) {
            const gridId = secBtn.dataset.gridId;
            const hiddenId = secBtn.dataset.hiddenId;
            onSelect = (file) => addToGrid(gridId, hiddenId, file);
            openModal();
            return;
        }

        // Multi-image: add photo to piece (inline cards)
        const pieceBtn = e.target.closest('.btn-add-piece-image');
        if (pieceBtn) {
            const pid = pieceBtn.dataset.pieceId;
            onSelect = (file) => addToGrid('pimgs-grid-' + pid, 'pimgs-' + pid, file);
            openModal();
            return;
        }

        // Remove photo from any multi-image grid
        const rmBtn = e.target.closest('.piece-img-remove');
        if (rmBtn) {
            const thumb = rmBtn.closest('.piece-img-thumb');
            const g = thumb.closest('.piece-images-grid');
            thumb.remove();
            syncGrid(g);
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
            pickerGrid.innerHTML = '<p style="padding:1rem;color:#888">Chargement...</p>';
            fetch('/admin/api/media-list')
                .then(r => r.json())
                .then(files => { allFiles = files; renderGrid(''); })
                .catch(() => { pickerGrid.innerHTML = '<p style="padding:1rem;color:#c00">Erreur de chargement</p>'; });
        } else {
            renderGrid('');
        }
        search.focus();
    }

    function closeModal() { modal.style.display = 'none'; onSelect = null; }

    // Add image to a grid + sync hidden input
    function addToGrid(gridId, hiddenId, file) {
        const g = document.getElementById(gridId);
        if (!g || g.querySelector('[data-file="' + file + '"]')) return;
        const div = document.createElement('div');
        div.className = 'piece-img-thumb';
        div.dataset.file = file;
        div.innerHTML = '<img src="/uploads/' + file + '" alt="" loading="lazy">'
            + '<button type="button" class="piece-img-remove" title="Retirer">&times;</button>';
        g.appendChild(div);
        syncGrid(g);
    }

    // Sync grid state to hidden input(s)
    function syncGrid(g) {
        if (!g) return;
        const files = Array.from(g.querySelectorAll('.piece-img-thumb')).map(t => t.dataset.file);
        // Find associated hidden input (sibling or by ID convention)
        const id = g.id;
        let hidden;
        if (id.startsWith('pimgs-grid-')) {
            hidden = document.getElementById(id.replace('-grid', ''));
        } else if (id.startsWith('simgs-')) {
            hidden = document.getElementById(id.replace('-grid', ''));
        }
        if (hidden) hidden.value = JSON.stringify(files);
        // Also update single image field for pieces
        const form = g.closest('form');
        if (form) {
            const imgInput = form.querySelector('input[name="image"]');
            if (imgInput) imgInput.value = files[0] || '';
        }
    }

    function renderGrid(filter) {
        const filtered = filter ? allFiles.filter(f => f.toLowerCase().includes(filter)) : allFiles;
        pickerGrid.innerHTML = filtered.map(f => `
            <div class="media-thumb" data-file="${f}">
                <img src="/uploads/${f}" alt="${f}" loading="lazy">
                <span class="media-thumb-name">${f.replace('villa-plaisance-','').replace('.webp','')}</span>
            </div>
        `).join('');

        pickerGrid.querySelectorAll('.media-thumb').forEach(thumb => {
            thumb.addEventListener('click', () => {
                if (onSelect) onSelect(thumb.dataset.file);
                closeModal();
            });
        });
    }
})();
</script>

<?php endif; ?>
