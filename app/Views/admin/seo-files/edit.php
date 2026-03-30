<div class="page-header">
    <h1>Éditer : <?= htmlspecialchars($file['filename']) ?></h1>
</div>

<div class="admin-card">
    <form method="POST" action="/admin/seo-files/<?= $file['id'] ?>/update">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label>Contenu de <code><?= htmlspecialchars($file['filename']) ?></code></label>
            <textarea name="content" rows="25" style="font-family:'JetBrains Mono',monospace;font-size:0.85rem;line-height:1.6;tab-size:4;"><?= htmlspecialchars($file['content']) ?></textarea>
        </div>

        <div style="display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/admin/seo-files" class="btn">Annuler</a>
            <a href="/<?= htmlspecialchars($file['filename']) ?>" target="_blank" class="btn" style="margin-left:auto;">Aperçu en ligne</a>
        </div>
    </form>
</div>

<?php if ($file['filename'] === 'robots.txt'): ?>
<div class="admin-card" style="background:#f8f9fb;">
    <p style="font-size:0.8rem;color:#888;">
        <strong>Astuce :</strong> Utilisez <code>{{BASE_URL}}</code> pour insérer l'URL du site automatiquement.
        Par défaut : autorisez les bots IA (GPTBot, PerplexityBot, ClaudeBot) pour maximiser la visibilité GSO.
    </p>
</div>
<?php endif; ?>
