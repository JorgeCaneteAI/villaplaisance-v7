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
    <p class="text-sm text-muted mb-2">Cochez les offres pour lesquelles chaque équipement est disponible.</p>

    <?php if (!empty($amenities)): ?>
    <?php foreach ($amenities as $category => $items): ?>
    <div class="amenity-category">
        <h3 class="reglage-subtitle"><?= htmlspecialchars($category) ?> <span class="badge badge-info"><?= count($items) ?></span></h3>
        <table class="amenity-table">
            <thead>
                <tr>
                    <th>Équipement</th>
                    <th class="amenity-col-offer">Chambres</th>
                    <th class="amenity-col-offer">Villa</th>
                    <th class="amenity-col-action"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <span class="amenity-name"><?= htmlspecialchars($item['name']) ?></span>
                        <?php if (!empty($item['description'])): ?>
                        <span class="amenity-desc"><?= htmlspecialchars($item['description']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="amenity-col-offer">
                        <form method="POST" action="/admin/reglages/amenity/<?= $item['id'] ?>/toggle" class="amenity-toggle-form">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                            <input type="hidden" name="field" value="offer_bb">
                            <button type="submit" class="amenity-check <?= $item['offer_bb'] ? 'is-active' : '' ?>" title="<?= $item['offer_bb'] ? 'Disponible en Chambres d\'hôtes' : 'Non disponible en Chambres d\'hôtes' ?>">
                                <?= $item['offer_bb'] ? '✓' : '—' ?>
                            </button>
                        </form>
                    </td>
                    <td class="amenity-col-offer">
                        <form method="POST" action="/admin/reglages/amenity/<?= $item['id'] ?>/toggle" class="amenity-toggle-form">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                            <input type="hidden" name="field" value="offer_villa">
                            <button type="submit" class="amenity-check <?= $item['offer_villa'] ? 'is-active' : '' ?>" title="<?= $item['offer_villa'] ? 'Disponible en Villa' : 'Non disponible en Villa' ?>">
                                <?= $item['offer_villa'] ? '✓' : '—' ?>
                            </button>
                        </form>
                    </td>
                    <td class="amenity-col-action">
                        <form method="POST" action="/admin/reglages/amenity/<?= $item['id'] ?>/delete" onsubmit="return confirm('Supprimer cet équipement ?')" class="amenity-delete">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                            <button type="submit" class="btn-icon" title="Supprimer">✕</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
        <button type="submit" class="btn btn-sm">+ Ajouter</button>
    </form>

    <?php if (!empty($amenities)): ?>
    <datalist id="amenity-categories">
        <?php foreach (array_keys($amenities) as $cat): ?>
        <option value="<?= htmlspecialchars($cat) ?>">
        <?php endforeach; ?>
    </datalist>
    <?php endif; ?>
</div>
