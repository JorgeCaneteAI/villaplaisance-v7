<?php declare(strict_types=1);
$isEdit = ($itinerary['id'] ?? 0) > 0;
$action = $isEdit ? '/admin/itineraires/' . $itinerary['id'] . '/update' : '/admin/itineraires/store';
$title  = $isEdit ? 'Modifier l\'itinéraire' : 'Nouvel itinéraire';
?>

<div class="page-header" style="display:flex;justify-content:space-between;align-items:center">
    <h1><?= $title ?></h1>
    <a href="/admin/itineraires" class="btn-secondary">&larr; Retour</a>
</div>

<?php if (!empty($flash['error'])): ?>
<div class="alert alert-error"><?= htmlspecialchars($flash['error']) ?></div>
<?php endif; ?>

<form method="post" action="<?= $action ?>" id="itinerary-form" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

    <!-- Infos générales -->
    <div class="admin-card" style="margin-bottom:1rem">
        <h2>Informations</h2>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem">
            <div>
                <label for="guest_name" style="font-weight:600;font-size:0.85rem">Nom de l'hôte *</label>
                <input type="text" id="guest_name" name="guest_name" value="<?= htmlspecialchars($itinerary['guest_name'] ?? '') ?>" required
                       class="form-input" placeholder="Elisabeth-J">
            </div>
            <div>
                <label for="slug" style="font-weight:600;font-size:0.85rem">Slug (URL) *</label>
                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($itinerary['slug'] ?? '') ?>" required
                       class="form-input" placeholder="elisabeth-j" pattern="[a-z0-9-]+"
                       title="Lettres minuscules, chiffres et tirets uniquement">
                <div style="font-size:0.72rem;color:#888;margin-top:0.2rem">→ villaplaisance.fr/itineraire/<span id="slug-preview"><?= htmlspecialchars($itinerary['slug'] ?? '...') ?></span></div>
            </div>
            <div>
                <label for="itinerary_date" style="font-weight:600;font-size:0.85rem">Date de l'itinéraire</label>
                <input type="date" id="itinerary_date" name="itinerary_date"
                       value="<?= htmlspecialchars($itinerary['itinerary_date'] ?? '') ?>" class="form-input">
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr auto;gap:1rem;margin-bottom:1rem">
            <div>
                <label for="intro_text" style="font-weight:600;font-size:0.85rem">Message personnalisé</label>
                <textarea id="intro_text" name="intro_text" rows="3" class="form-input"
                          placeholder="Voici l'itinéraire que nous avons préparé pour votre dernière journée..."><?= htmlspecialchars($itinerary['intro_text'] ?? '') ?></textarea>
            </div>
            <div>
                <label style="font-weight:600;font-size:0.85rem">Langue</label>
                <select name="lang" class="form-input" style="width:auto">
                    <option value="fr" <?= ($itinerary['lang'] ?? 'fr') === 'fr' ? 'selected' : '' ?>>Français</option>
                    <option value="en" <?= ($itinerary['lang'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                    <option value="es" <?= ($itinerary['lang'] ?? '') === 'es' ? 'selected' : '' ?>>Español</option>
                </select>
                <div style="margin-top:0.5rem">
                    <label style="font-weight:600;font-size:0.85rem">Statut</label>
                    <select name="status" class="form-input" style="width:auto">
                        <option value="active" <?= ($itinerary['status'] ?? '') === 'active' ? 'selected' : '' ?>>Actif</option>
                        <option value="archived" <?= ($itinerary['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archivé</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Étapes -->
    <div class="admin-card" style="margin-bottom:1rem">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
            <h2>Étapes de l'itinéraire</h2>
            <button type="button" onclick="addStep()" class="btn-primary" style="font-size:0.8rem;padding:0.35rem 0.75rem">+ Ajouter une étape</button>
        </div>

        <div id="steps-container">
            <?php if (!empty($steps)): ?>
                <?php foreach ($steps as $i => $step): ?>
                <div class="step-row" style="border:1px solid #e8e0d8;border-radius:8px;padding:1rem;margin-bottom:0.75rem;background:#fafaf8">
                    <div style="display:grid;grid-template-columns:100px 1fr 100px auto;gap:0.75rem;align-items:start">
                        <div>
                            <label style="font-size:0.72rem;color:#888">Heure</label>
                            <input type="text" name="step_time[]" value="<?= htmlspecialchars($step['time_label'] ?? '') ?>" class="form-input" placeholder="10h30" style="font-size:0.85rem">
                        </div>
                        <div>
                            <label style="font-size:0.72rem;color:#888">Titre *</label>
                            <input type="text" name="step_title[]" value="<?= htmlspecialchars($step['title'] ?? '') ?>" class="form-input" placeholder="Château La Gardine" required style="font-size:0.85rem">
                        </div>
                        <div>
                            <label style="font-size:0.72rem;color:#888">Durée</label>
                            <input type="text" name="step_duration[]" value="<?= htmlspecialchars($step['duration'] ?? '') ?>" class="form-input" placeholder="~1h" style="font-size:0.85rem">
                        </div>
                        <div style="padding-top:1.2rem">
                            <button type="button" onclick="this.closest('.step-row').remove()" class="btn-danger" style="font-size:0.75rem;padding:0.25rem 0.5rem">✕</button>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 200px;gap:0.75rem;margin-top:0.5rem">
                        <div>
                            <label style="font-size:0.72rem;color:#888">Description</label>
                            <textarea name="step_description[]" rows="2" class="form-input" placeholder="Détails, horaires, conseils..." style="font-size:0.85rem"><?= htmlspecialchars($step['description'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label style="font-size:0.72rem;color:#888">Image</label>
                            <div style="display:flex;gap:0.5rem;align-items:start">
                                <input type="text" name="step_image[]" value="<?= htmlspecialchars($step['image'] ?? '') ?>" class="form-input step-image-input" placeholder="photo.webp" style="font-size:0.8rem">
                                <button type="button" onclick="openMediaPicker(this)" class="btn-secondary" style="font-size:0.75rem;padding:0.35rem 0.5rem;white-space:nowrap">Choisir</button>
                            </div>
                            <?php if (!empty($step['image'])): ?>
                            <img src="/uploads/<?= htmlspecialchars($step['image']) ?>" alt="" style="max-width:100%;max-height:80px;margin-top:0.3rem;border-radius:4px;object-fit:cover">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div style="margin-top:0.5rem">
                        <label style="font-size:0.72rem;color:#888">Lien (Google Maps, site web...)</label>
                        <input type="url" name="step_link[]" value="<?= htmlspecialchars($step['link'] ?? '') ?>" class="form-input" placeholder="https://maps.app.goo.gl/..." style="font-size:0.8rem">
                    </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Commentaires existants -->
    <?php if ($isEdit && !empty($comments)): ?>
    <div class="admin-card" style="margin-bottom:1rem">
        <h2>Commentaires reçus (<?= count($comments) ?>)</h2>
        <?php foreach ($comments as $c): ?>
        <div style="border:1px solid #e8e0d8;border-radius:8px;padding:0.75rem;margin-bottom:0.5rem;background:#fafaf8">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.25rem">
                <strong style="font-size:0.85rem"><?= htmlspecialchars($c['guest_name']) ?></strong>
                <div style="display:flex;align-items:center;gap:0.5rem">
                    <span style="font-size:0.72rem;color:#aaa"><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></span>
                    <form method="post" action="/admin/itineraires/comment/<?= $c['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer ce commentaire ?')">
                        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                        <button type="submit" class="btn-danger" style="font-size:0.65rem;padding:0.15rem 0.4rem">Suppr.</button>
                    </form>
                </div>
            </div>
            <p style="font-size:0.85rem;color:#555;margin:0"><?= nl2br(htmlspecialchars($c['message'])) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div style="display:flex;gap:1rem;justify-content:flex-end">
        <a href="/admin/itineraires" class="btn-secondary">Annuler</a>
        <button type="submit" class="btn-primary"><?= $isEdit ? 'Mettre à jour' : 'Créer l\'itinéraire' ?></button>
    </div>
</form>

<template id="step-template">
    <div class="step-row" style="border:1px solid #e8e0d8;border-radius:8px;padding:1rem;margin-bottom:0.75rem;background:#fafaf8">
        <div style="display:grid;grid-template-columns:100px 1fr 100px auto;gap:0.75rem;align-items:start">
            <div>
                <label style="font-size:0.72rem;color:#888">Heure</label>
                <input type="text" name="step_time[]" class="form-input" placeholder="10h30" style="font-size:0.85rem">
            </div>
            <div>
                <label style="font-size:0.72rem;color:#888">Titre *</label>
                <input type="text" name="step_title[]" class="form-input" placeholder="Lieu ou étape" required style="font-size:0.85rem">
            </div>
            <div>
                <label style="font-size:0.72rem;color:#888">Durée</label>
                <input type="text" name="step_duration[]" class="form-input" placeholder="~1h" style="font-size:0.85rem">
            </div>
            <div style="padding-top:1.2rem">
                <button type="button" onclick="this.closest('.step-row').remove()" class="btn-danger" style="font-size:0.75rem;padding:0.25rem 0.5rem">✕</button>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 200px;gap:0.75rem;margin-top:0.5rem">
            <div>
                <label style="font-size:0.72rem;color:#888">Description</label>
                <textarea name="step_description[]" rows="2" class="form-input" placeholder="Détails, horaires, conseils..." style="font-size:0.85rem"></textarea>
            </div>
            <div>
                <label style="font-size:0.72rem;color:#888">Image</label>
                <div style="display:flex;gap:0.5rem;align-items:start">
                    <input type="text" name="step_image[]" value="" class="form-input step-image-input" placeholder="photo.webp" style="font-size:0.8rem">
                    <button type="button" onclick="openMediaPicker(this)" class="btn-secondary" style="font-size:0.75rem;padding:0.35rem 0.5rem;white-space:nowrap">Choisir</button>
                </div>
            </div>
        </div>
        <div style="margin-top:0.5rem">
            <label style="font-size:0.72rem;color:#888">Lien (Google Maps, site web...)</label>
            <input type="url" name="step_link[]" value="" class="form-input" placeholder="https://maps.app.goo.gl/..." style="font-size:0.8rem">
        </div>
    </div>
</template>

<!-- Media picker modal -->
<div id="media-picker-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;max-width:700px;width:95%;max-height:80vh;overflow:auto;padding:1.5rem">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
            <h3 style="margin:0">Choisir une image</h3>
            <button type="button" onclick="closeMediaPicker()" style="border:none;background:none;font-size:1.5rem;cursor:pointer">&times;</button>
        </div>
        <div id="media-picker-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:0.5rem"></div>
    </div>
</div>

<script>
function addStep() {
    const tpl = document.getElementById('step-template');
    const clone = tpl.content.cloneNode(true);
    document.getElementById('steps-container').appendChild(clone);
}

// Auto-generate slug from guest name
document.getElementById('guest_name').addEventListener('input', function() {
    const slugInput = document.getElementById('slug');
    if (slugInput.dataset.manual) return;
    const slug = this.value.toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
    slugInput.value = slug;
    document.getElementById('slug-preview').textContent = slug || '...';
});

document.getElementById('slug').addEventListener('input', function() {
    this.dataset.manual = '1';
    document.getElementById('slug-preview').textContent = this.value || '...';
});

// Media picker
let activeImageInput = null;

function openMediaPicker(btn) {
    activeImageInput = btn.closest('.step-row').querySelector('.step-image-input');
    const modal = document.getElementById('media-picker-modal');
    const grid = document.getElementById('media-picker-grid');
    modal.style.display = 'flex';
    grid.innerHTML = '<p style="color:#888;grid-column:1/-1;text-align:center">Chargement...</p>';

    fetch('/admin/api/media-list')
        .then(r => r.json())
        .then(files => {
            grid.innerHTML = '';
            if (!files.length) {
                grid.innerHTML = '<p style="color:#888;grid-column:1/-1;text-align:center">Aucune image dans /uploads</p>';
                return;
            }
            files.forEach(f => {
                const div = document.createElement('div');
                div.style.cssText = 'cursor:pointer;border:2px solid transparent;border-radius:6px;overflow:hidden;transition:border-color 0.15s';
                div.innerHTML = '<img src="/uploads/' + f + '" alt="" style="width:100%;height:90px;object-fit:cover;display:block">'
                    + '<div style="font-size:0.7rem;padding:0.2rem 0.3rem;color:#888;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">' + f + '</div>';
                div.onmouseover = () => div.style.borderColor = '#8B7355';
                div.onmouseout  = () => div.style.borderColor = 'transparent';
                div.onclick = () => {
                    activeImageInput.value = f;
                    closeMediaPicker();
                };
                grid.appendChild(div);
            });
        });
}

function closeMediaPicker() {
    document.getElementById('media-picker-modal').style.display = 'none';
}

document.getElementById('media-picker-modal').addEventListener('click', function(e) {
    if (e.target === this) closeMediaPicker();
});
</script>
