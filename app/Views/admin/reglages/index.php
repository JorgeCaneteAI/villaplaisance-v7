<div class="page-header">
    <h1>Réglages</h1>
</div>

<!-- ═══════════════════════════════════════════
     1. INFORMATIONS GÉNÉRALES
     ═══════════════════════════════════════════ -->
<form method="POST" action="/admin/reglages/save" class="admin-card">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
    <h2>Informations générales</h2>
    <div class="form-row">
        <div class="form-group">
            <label for="site_name">Nom du site</label>
            <input type="text" id="site_name" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? 'Villa Plaisance') ?>">
        </div>
        <div class="form-group">
            <label for="email">Email de contact</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($settings['email'] ?? '') ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($settings['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="address">Adresse</label>
            <input type="text" id="address" name="address" value="<?= htmlspecialchars($settings['address'] ?? '') ?>">
        </div>
    </div>
    <div class="mt-1">
        <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
    </div>
</form>

<!-- ═══════════════════════════════════════════
     2. LIENS DE RÉSERVATION
     ═══════════════════════════════════════════ -->
<div class="admin-card">
    <h2>Liens de réservation</h2>

    <!-- Offre Chambres d'hôtes -->
    <h3 class="reglage-subtitle">Offre Chambres d'hôtes</h3>
    <?php if (!empty($bookingBB)): ?>
    <div class="reglage-items">
        <?php foreach ($bookingBB as $link): ?>
        <form method="POST" action="/admin/reglages/booking/<?= $link['id'] ?>/update" class="reglage-item">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <div class="reglage-item-fields">
                <input type="text" name="platform_name" value="<?= htmlspecialchars($link['platform_name']) ?>" placeholder="Nom (Booking, Airbnb…)">
                <input type="url" name="url" value="<?= htmlspecialchars($link['url']) ?>" placeholder="https://...">
            </div>
            <div class="reglage-item-actions">
                <button type="submit" class="btn btn-sm btn-primary">OK</button>
            </div>
        </form>
        <form method="POST" action="/admin/reglages/booking/<?= $link['id'] ?>/delete" class="reglage-item-delete" onsubmit="return confirm('Supprimer ce lien ?')">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <button type="submit" class="btn btn-sm btn-danger">✕</button>
        </form>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p class="text-muted text-sm">Aucun lien pour cette offre.</p>
    <?php endif; ?>

    <form method="POST" action="/admin/reglages/booking/add" class="reglage-add">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input type="hidden" name="offer" value="bb">
        <input type="text" name="platform_name" placeholder="Nom de la plateforme" required>
        <input type="url" name="url" placeholder="https://..." required>
        <button type="submit" class="btn btn-sm">+ Ajouter</button>
    </form>

    <hr class="reglage-sep">

    <!-- Offre Villa entière -->
    <h3 class="reglage-subtitle">Offre Villa entière</h3>
    <?php if (!empty($bookingVilla)): ?>
    <div class="reglage-items">
        <?php foreach ($bookingVilla as $link): ?>
        <form method="POST" action="/admin/reglages/booking/<?= $link['id'] ?>/update" class="reglage-item">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <div class="reglage-item-fields">
                <input type="text" name="platform_name" value="<?= htmlspecialchars($link['platform_name']) ?>" placeholder="Nom">
                <input type="url" name="url" value="<?= htmlspecialchars($link['url']) ?>" placeholder="https://...">
            </div>
            <div class="reglage-item-actions">
                <button type="submit" class="btn btn-sm btn-primary">OK</button>
            </div>
        </form>
        <form method="POST" action="/admin/reglages/booking/<?= $link['id'] ?>/delete" class="reglage-item-delete" onsubmit="return confirm('Supprimer ce lien ?')">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <button type="submit" class="btn btn-sm btn-danger">✕</button>
        </form>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p class="text-muted text-sm">Aucun lien pour cette offre.</p>
    <?php endif; ?>

    <form method="POST" action="/admin/reglages/booking/add" class="reglage-add">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input type="hidden" name="offer" value="villa">
        <input type="text" name="platform_name" placeholder="Nom de la plateforme" required>
        <input type="url" name="url" placeholder="https://..." required>
        <button type="submit" class="btn btn-sm">+ Ajouter</button>
    </form>
</div>

<!-- ═══════════════════════════════════════════
     3. RÉSEAUX SOCIAUX
     ═══════════════════════════════════════════ -->
<div class="admin-card">
    <h2>Réseaux sociaux</h2>

    <?php if (!empty($socials)): ?>
    <div class="reglage-items">
        <?php foreach ($socials as $social): ?>
        <form method="POST" action="/admin/reglages/social/<?= $social['id'] ?>/update" class="reglage-item">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <div class="reglage-item-fields">
                <input type="text" name="name" value="<?= htmlspecialchars($social['name']) ?>" placeholder="Nom (Instagram, Facebook…)">
                <input type="url" name="url" value="<?= htmlspecialchars($social['url']) ?>" placeholder="https://...">
            </div>
            <div class="reglage-item-actions">
                <button type="submit" class="btn btn-sm btn-primary">OK</button>
            </div>
        </form>
        <form method="POST" action="/admin/reglages/social/<?= $social['id'] ?>/delete" class="reglage-item-delete" onsubmit="return confirm('Supprimer ce réseau ?')">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <button type="submit" class="btn btn-sm btn-danger">✕</button>
        </form>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="/admin/reglages/social/add" class="reglage-add">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input type="text" name="name" placeholder="Nom du réseau" required>
        <input type="url" name="url" placeholder="https://..." required>
        <button type="submit" class="btn btn-sm">+ Ajouter</button>
    </form>
</div>

<!-- ═══════════════════════════════════════════
     4. POINTS FORTS & ÉQUIPEMENTS
     ═══════════════════════════════════════════ -->
<div class="admin-card">
    <h2>Points forts &amp; équipements</h2>
    <p class="text-sm text-muted mb-2">Traduisez le nom et la description de chaque équipement. Les offres BB/Villa se gèrent depuis la colonne FR.</p>

    <?php if (!empty($amenities)): ?>
    <?php foreach ($amenities as $category => $items): ?>
    <div class="amenity-category">
        <h3 class="reglage-subtitle"><?= htmlspecialchars($category) ?> <span class="badge badge-info"><?= count($items) ?></span></h3>

        <?php foreach ($items as $item):
            $key = $item['category'] . ':' . $item['position'];
        ?>
        <div class="block-row" style="margin-bottom:0.75rem">
            <div class="lang-columns">
                <?php foreach ($langs as $l):
                    $a = $amenityIndex[$l][$key] ?? null;
                ?>
                <div class="lang-column">
                    <?php if ($l === 'fr'): ?>
                    <div class="lang-column-header" style="padding:0.25rem 0.5rem;font-size:0.7rem">
                        <span><?= $langLabels[$l] ?></span>
                        <div style="display:flex;gap:0.3rem;align-items:center">
                            <form method="POST" action="/admin/reglages/amenity/<?= $item['id'] ?>/toggle" style="display:inline">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                <input type="hidden" name="field" value="offer_bb">
                                <button type="submit" class="amenity-check <?= $item['offer_bb'] ? 'is-active' : '' ?>" style="font-size:0.6rem;padding:0.1rem 0.3rem" title="Chambres">BB <?= $item['offer_bb'] ? '✓' : '—' ?></button>
                            </form>
                            <form method="POST" action="/admin/reglages/amenity/<?= $item['id'] ?>/toggle" style="display:inline">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                <input type="hidden" name="field" value="offer_villa">
                                <button type="submit" class="amenity-check <?= $item['offer_villa'] ? 'is-active' : '' ?>" style="font-size:0.6rem;padding:0.1rem 0.3rem" title="Villa">Villa <?= $item['offer_villa'] ? '✓' : '—' ?></button>
                            </form>
                            <form method="POST" action="/admin/reglages/amenity/<?= $item['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer cet équipement ?')">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                <button type="submit" class="btn btn-sm btn-danger" style="padding:0 0.3rem;font-size:0.6rem">✕</button>
                            </form>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="lang-column-header" style="padding:0.25rem 0.5rem;font-size:0.7rem">
                        <span><?= $langLabels[$l] ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($a): ?>
                    <form method="POST" action="/admin/reglages/amenity/<?= $a['id'] ?>/update" class="lang-column-body" style="padding:0.4rem">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                        <input type="hidden" name="category" value="<?= htmlspecialchars($a['category']) ?>">
                        <div class="form-group" style="margin-bottom:0.2rem">
                            <input type="text" name="name" value="<?= htmlspecialchars($a['name'] ?? '') ?>" placeholder="Nom" style="font-size:0.75rem;padding:0.2rem 0.4rem">
                        </div>
                        <div class="form-group" style="margin-bottom:0.2rem">
                            <input type="text" name="description" value="<?= htmlspecialchars($a['description'] ?? '') ?>" placeholder="Description" style="font-size:0.7rem;padding:0.2rem 0.4rem;color:#666">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" style="font-size:0.6rem;padding:0.1rem 0.4rem">Sauver</button>
                    </form>
                    <?php else: ?>
                    <div class="lang-column-body" style="padding:0.4rem">
                        <p class="lang-missing" style="font-size:0.7rem">—</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <p class="text-muted">Aucun équipement enregistré.</p>
    <?php endif; ?>

    <hr class="reglage-sep">

    <h3 class="reglage-subtitle">Ajouter un équipement</h3>
    <form method="POST" action="/admin/reglages/amenity/add" class="reglage-add reglage-add-amenity">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input type="text" name="category" placeholder="Catégorie" list="amenity-categories" required>
        <input type="text" name="name" placeholder="Nom de l'équipement" required>
        <input type="text" name="description" placeholder="Description (optionnel)">
        <label class="amenity-add-check"><input type="checkbox" name="offer_bb" value="1" checked> BB</label>
        <label class="amenity-add-check"><input type="checkbox" name="offer_villa" value="1" checked> Villa</label>
        <button type="submit" class="btn btn-sm">+ Ajouter (FR/EN/ES)</button>
    </form>

    <?php if (!empty($amenities)): ?>
    <datalist id="amenity-categories">
        <?php foreach (array_keys($amenities) as $cat): ?>
        <option value="<?= htmlspecialchars($cat) ?>">
        <?php endforeach; ?>
    </datalist>
    <?php endif; ?>
</div>
