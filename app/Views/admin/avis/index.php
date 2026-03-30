<div class="page-header">
    <h1>Avis clients</h1>
</div>

<!-- Stats -->
<div class="stats-cards" style="grid-template-columns: repeat(5, 1fr);margin-bottom:1.5rem;">
    <div class="stat-card">
        <div class="stat-number"><?= $stats['total'] ?></div>
        <div class="stat-label">Avis total</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['airbnb_bb'] ?></div>
        <div class="stat-label">Airbnb chambres</div>
        <div style="font-size:0.7rem;color:#aaa;margin-top:0.2rem;"><?= $stats['avg_airbnb'] ?>/5</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['airbnb_villa'] ?></div>
        <div class="stat-label">Airbnb villa</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['booking'] ?></div>
        <div class="stat-label">Booking</div>
        <div style="font-size:0.7rem;color:#aaa;margin-top:0.2rem;"><?= $stats['avg_booking'] ?>/10</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['google'] ?></div>
        <div class="stat-label">Google</div>
    </div>
</div>

<!-- Add new review -->
<div class="admin-card" id="form-add">
    <h2>Ajouter un avis</h2>
    <form method="POST" action="/admin/avis/create">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:0.75rem;">
            <div class="form-group" style="margin:0;">
                <label>Auteur *</label>
                <input type="text" name="author" required placeholder="Nom du voyageur">
            </div>
            <div class="form-group" style="margin:0;">
                <label>Ville / Pays</label>
                <input type="text" name="origin" placeholder="Paris, France">
            </div>
            <div class="form-group" style="margin:0;">
                <label>Plateforme</label>
                <select name="platform">
                    <option value="airbnb">Airbnb</option>
                    <option value="booking">Booking</option>
                    <option value="google">Google</option>
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Offre</label>
                <select name="offer">
                    <option value="bb">Chambres d'hôtes</option>
                    <option value="villa">Villa entière</option>
                </select>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 120px 140px;gap:0.75rem;margin-top:0.75rem;">
            <div class="form-group" style="margin:0;">
                <label>Commentaire</label>
                <textarea name="content" rows="2" placeholder="Texte de l'avis..."></textarea>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Note</label>
                <input type="number" name="rating" value="5.0" min="0" max="10" step="0.1">
            </div>
            <div class="form-group" style="margin:0;">
                <label>Date</label>
                <input type="date" name="review_date" value="<?= date('Y-m-d') ?>">
            </div>
        </div>
        <div style="display:flex;gap:1.5rem;align-items:center;margin-top:0.75rem;">
            <label style="display:flex;align-items:center;gap:0.3rem;font-size:0.8rem;cursor:pointer;">
                <input type="checkbox" name="featured" value="1"> Featured
            </label>
            <label style="display:flex;align-items:center;gap:0.3rem;font-size:0.8rem;cursor:pointer;">
                <input type="checkbox" name="home_carousel" value="1"> Carousel accueil
            </label>
            <select name="status" style="width:auto;padding:0.3rem 0.5rem;font-size:0.8rem;border:1px solid var(--admin-border);border-radius:var(--admin-radius);">
                <option value="published">Publié</option>
                <option value="hidden">Masqué</option>
            </select>
            <button type="submit" class="btn btn-primary" style="margin-left:auto;">Ajouter l'avis</button>
        </div>
    </form>
</div>

<!-- List -->
<div class="admin-card">
    <h2 style="display:flex;align-items:center;gap:0.5rem;">
        Liste des avis
        <span style="font-size:0.75rem;color:#888;font-weight:400;margin-left:auto;"><?= count($reviews) ?> avis</span>
    </h2>

    <?php if (empty($reviews)): ?>
        <p class="text-muted">Aucun avis.</p>
    <?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Auteur</th>
                <th>Plateforme</th>
                <th>Offre</th>
                <th>Note</th>
                <th>Commentaire</th>
                <th>Date</th>
                <th>Flags</th>
                <th>Statut</th>
                <th style="width:160px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review): ?>
            <tr style="<?= $review['status'] !== 'published' ? 'opacity:0.5;' : '' ?>">
                <td style="min-width:120px;">
                    <strong style="font-size:0.85rem;"><?= htmlspecialchars($review['author']) ?></strong>
                    <?php if ($review['origin']): ?>
                    <br><span style="font-size:0.7rem;color:#888;"><?= htmlspecialchars($review['origin']) ?></span>
                    <?php endif; ?>
                </td>
                <td style="font-size:0.78rem;"><?= htmlspecialchars($review['platform']) ?></td>
                <td><span class="badge badge-info"><?= $review['offer'] ?></span></td>
                <td style="font-weight:600;font-size:0.85rem;">
                    <?= $review['rating'] ?>/<?= $review['platform'] === 'booking' ? '10' : '5' ?>
                </td>
                <td style="max-width:300px;font-size:0.78rem;color:#555;">
                    <?php if ($review['content']): ?>
                        <?= htmlspecialchars(mb_strimwidth($review['content'], 0, 120, '...')) ?>
                    <?php else: ?>
                        <span style="color:#ccc;font-style:italic;">Sans commentaire</span>
                    <?php endif; ?>
                </td>
                <td style="font-size:0.78rem;color:#888;white-space:nowrap;"><?= $review['review_date'] ? date('m/Y', strtotime($review['review_date'])) : '—' ?></td>
                <td style="font-size:0.7rem;">
                    <?php if ($review['featured']): ?><span class="badge" style="background:#fff3cd;color:#856404;">Star</span> <?php endif; ?>
                    <?php if ($review['home_carousel']): ?><span class="badge" style="background:#d4edda;color:#155724;">Home</span><?php endif; ?>
                </td>
                <td>
                    <?php if ($review['status'] === 'published'): ?>
                    <span class="badge badge-success">Visible</span>
                    <?php else: ?>
                    <span class="badge badge-warning">Masqué</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="display:flex;gap:0.25rem;flex-wrap:wrap;">
                        <a href="/admin/avis/<?= $review['id'] ?>/edit" class="btn btn-sm btn-primary">Éditer</a>
                        <form method="POST" action="/admin/avis/<?= $review['id'] ?>/toggle" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                            <button type="submit" class="btn btn-sm"><?= $review['status'] === 'published' ? 'Masquer' : 'Publier' ?></button>
                        </form>
                        <form method="POST" action="/admin/avis/<?= $review['id'] ?>/delete" onsubmit="return confirm('Supprimer cet avis ?')" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Suppr.</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
