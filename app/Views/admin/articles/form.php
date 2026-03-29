<?php
$isEdit = !empty($article['id']);
$action = $isEdit ? "/admin/articles/{$article['id']}/update" : "/admin/articles/store";
$pageTitle = $isEdit ? 'Modifier l\'article' : 'Nouvel article';

// Decode content for editing
$contentRaw = '';
if (!empty($article['content'])) {
    $blocks = json_decode($article['content'], true);
    if (is_array($blocks)) {
        foreach ($blocks as $block) {
            if (!is_array($block)) continue;
            if ($block['type'] === 'heading') $contentRaw .= '## ' . ($block['text'] ?? '') . "\n\n";
            elseif ($block['type'] === 'quote') $contentRaw .= '> ' . ($block['text'] ?? '') . "\n\n";
            elseif ($block['type'] === 'paragraph') $contentRaw .= ($block['text'] ?? '') . "\n\n";
        }
    }
}
?>

<div class="page-header">
    <h1><?= $pageTitle ?></h1>
    <a href="/admin/articles" class="btn">Retour</a>
</div>

<form method="POST" action="<?= $action ?>" class="admin-card">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

    <div class="form-row">
        <div class="form-group">
            <label for="title">Titre *</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="slug">Slug (URL)</label>
            <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($article['slug'] ?? '') ?>" placeholder="auto-généré si vide">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="type">Type</label>
            <select id="type" name="type">
                <option value="journal" <?= ($article['type'] ?? '') === 'journal' ? 'selected' : '' ?>>Journal</option>
                <option value="sur-place" <?= ($article['type'] ?? '') === 'sur-place' ? 'selected' : '' ?>>Sur place</option>
            </select>
        </div>
        <div class="form-group">
            <label for="category">Catégorie</label>
            <input type="text" id="category" name="category" value="<?= htmlspecialchars($article['category'] ?? '') ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="excerpt">Extrait / chapeau</label>
        <textarea id="excerpt" name="excerpt" rows="2"><?= htmlspecialchars($article['excerpt'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label for="content_raw">Contenu (Markdown simplifié : ## pour H2, > pour citation, paragraphes séparés par ligne vide)</label>
        <textarea id="content_raw" name="content_raw" rows="15"><?= htmlspecialchars(trim($contentRaw)) ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="meta_title">Meta Title (max 60 car.)</label>
            <input type="text" id="meta_title" name="meta_title" value="<?= htmlspecialchars($article['meta_title'] ?? '') ?>" maxlength="70">
        </div>
        <div class="form-group">
            <label for="meta_desc">Meta Description (max 160 car.)</label>
            <input type="text" id="meta_desc" name="meta_desc" value="<?= htmlspecialchars($article['meta_desc'] ?? '') ?>" maxlength="170">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="cover_image">Image de couverture (nom fichier)</label>
            <input type="text" id="cover_image" name="cover_image" value="<?= htmlspecialchars($article['cover_image'] ?? '') ?>" placeholder="ex: mon-article.webp">
        </div>
        <div class="form-group">
            <label for="published_at">Date de publication</label>
            <input type="date" id="published_at" name="published_at" value="<?= htmlspecialchars(substr($article['published_at'] ?? date('Y-m-d'), 0, 10)) ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="status">Statut</label>
            <select id="status" name="status">
                <option value="draft" <?= ($article['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                <option value="published" <?= ($article['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publié</option>
            </select>
        </div>
        <div class="form-group">
            <label for="lang">Langue</label>
            <select id="lang" name="lang">
                <option value="fr" <?= ($article['lang'] ?? 'fr') === 'fr' ? 'selected' : '' ?>>Français</option>
                <option value="en" <?= ($article['lang'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                <option value="es" <?= ($article['lang'] ?? '') === 'es' ? 'selected' : '' ?>>Español</option>
                <option value="de" <?= ($article['lang'] ?? '') === 'de' ? 'selected' : '' ?>>Deutsch</option>
            </select>
        </div>
    </div>

    <div class="mt-2">
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
    </div>
</form>
