<div class="page-header">
    <h1>Éditer l'avis de <?= htmlspecialchars($review['author']) ?></h1>
</div>

<div class="admin-card" style="max-width:800px;">
    <form method="POST" action="/admin/avis/<?= $review['id'] ?>/update">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Auteur *</label>
                <input type="text" name="author" value="<?= htmlspecialchars($review['author']) ?>" required>
            </div>
            <div class="form-group">
                <label>Ville / Pays d'origine</label>
                <input type="text" name="origin" value="<?= htmlspecialchars($review['origin'] ?? '') ?>" placeholder="Paris, France">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Plateforme</label>
                <select name="platform">
                    <option value="airbnb" <?= $review['platform'] === 'airbnb' ? 'selected' : '' ?>>Airbnb</option>
                    <option value="booking" <?= $review['platform'] === 'booking' ? 'selected' : '' ?>>Booking</option>
                    <option value="google" <?= $review['platform'] === 'google' ? 'selected' : '' ?>>Google</option>
                </select>
            </div>
            <div class="form-group">
                <label>Offre</label>
                <select name="offer">
                    <option value="bb" <?= $review['offer'] === 'bb' ? 'selected' : '' ?>>Chambres d'hôtes</option>
                    <option value="villa" <?= $review['offer'] === 'villa' ? 'selected' : '' ?>>Villa entière</option>
                    <option value="both" <?= $review['offer'] === 'both' ? 'selected' : '' ?>>Les deux</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Commentaire</label>
            <textarea name="content" rows="5"><?= htmlspecialchars($review['content']) ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Note</label>
                <input type="number" name="rating" value="<?= $review['rating'] ?>" min="0" max="10" step="0.1">
                <span style="font-size:0.7rem;color:#888;">Sur 5 (Airbnb/Google) ou sur 10 (Booking)</span>
            </div>
            <div class="form-group">
                <label>Date de l'avis</label>
                <input type="date" name="review_date" value="<?= $review['review_date'] ?? '' ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Statut</label>
                <select name="status">
                    <option value="published" <?= $review['status'] === 'published' ? 'selected' : '' ?>>Publié</option>
                    <option value="hidden" <?= $review['status'] === 'hidden' ? 'selected' : '' ?>>Masqué</option>
                    <option value="draft" <?= $review['status'] === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                </select>
            </div>
            <div class="form-group">
                <label>Options d'affichage</label>
                <div style="display:flex;gap:1.5rem;margin-top:0.5rem;">
                    <label style="display:flex;align-items:center;gap:0.3rem;font-size:0.85rem;cursor:pointer;">
                        <input type="checkbox" name="featured" value="1" <?= $review['featured'] ? 'checked' : '' ?>> Featured (mis en avant)
                    </label>
                    <label style="display:flex;align-items:center;gap:0.3rem;font-size:0.85rem;cursor:pointer;">
                        <input type="checkbox" name="home_carousel" value="1" <?= $review['home_carousel'] ? 'checked' : '' ?>> Carousel page d'accueil
                    </label>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:0.5rem;margin-top:1rem;">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/admin/avis" class="btn">Annuler</a>
            <form method="POST" action="/admin/avis/<?= $review['id'] ?>/delete" onsubmit="return confirm('Supprimer définitivement cet avis ?')" style="margin-left:auto;">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </form>
</div>
