<?php
$frArticle = $articlesByLang['fr'] ?? [];
$isEdit = !empty($frArticle['id']);
$action = $isEdit ? "/admin/articles/{$frArticle['id']}/update" : "/admin/articles/store";
$pageTitle = $isEdit ? 'Éditer l\'article' : 'Nouvel article';
$articleType = $type ?? $frArticle['type'] ?? 'journal';

// Decode content for each lang
$contentRawByLang = [];
foreach ($langs as $l) {
    $raw = '';
    $a = $articlesByLang[$l] ?? [];
    if (!empty($a['content'])) {
        $blocks = json_decode($a['content'], true);
        if (is_array($blocks)) {
            foreach ($blocks as $block) {
                if (!is_array($block)) continue;
                if ($block['type'] === 'heading') $raw .= '## ' . ($block['text'] ?? '') . "\n\n";
                elseif ($block['type'] === 'quote') $raw .= '> ' . ($block['text'] ?? '') . "\n\n";
                elseif ($block['type'] === 'image') $raw .= '![' . ($block['alt'] ?? '') . '](' . ($block['src'] ?? '') . ')' . (!empty($block['caption']) ? ' ' . $block['caption'] : '') . "\n\n";
                elseif ($block['type'] === 'list') $raw .= implode("\n", array_map(fn($i) => "- {$i}", $block['items'] ?? [])) . "\n\n";
                elseif ($block['type'] === 'paragraph') $raw .= ($block['text'] ?? '') . "\n\n";
            }
        }
    }
    $contentRawByLang[$l] = trim($raw);
}

// Content quality check for FR
$frQuality = [];
$frQuality['title'] = !empty($frArticle['title']);
$frQuality['excerpt'] = !empty($frArticle['excerpt']) && mb_strlen($frArticle['excerpt']) >= 50;
$frQuality['content'] = !empty($frArticle['content']) && $frArticle['content'] !== '[]';
$frQuality['cover'] = !empty($frArticle['cover_image']);
$frQuality['meta_title'] = !empty($frArticle['meta_title']) && mb_strlen($frArticle['meta_title']) <= 60;
$frQuality['meta_desc'] = !empty($frArticle['meta_desc']) && mb_strlen($frArticle['meta_desc']) >= 120 && mb_strlen($frArticle['meta_desc']) <= 160;
$frQuality['category'] = !empty($frArticle['category']);
$frQualityScore = count(array_filter($frQuality));
$frQualityTotal = count($frQuality);
?>

<div class="page-header">
    <h1><?= $pageTitle ?></h1>
    <div class="btn-group">
        <a href="/admin/articles?type=<?= htmlspecialchars($articleType) ?>" class="btn">← Retour</a>
        <?php if ($isEdit && !empty($frArticle['slug'])): ?>
        <a href="/<?= $articleType === 'journal' ? 'journal' : 'sur-place' ?>/<?= htmlspecialchars($frArticle['slug']) ?>" class="btn" target="_blank">Voir ↗</a>
        <?php endif; ?>
    </div>
</div>

<form method="POST" action="<?= $action ?>" id="article-form">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

    <!-- ═══════════════════════════════════════════
         BARRE LATÉRALE : Paramètres partagés
         ═══════════════════════════════════════════ -->
    <div class="article-layout">
        <div class="article-sidebar">

            <!-- Score qualité -->
            <div class="admin-card article-quality-card">
                <h3 class="article-sidebar-title">Qualité de l'article</h3>
                <div class="quality-score">
                    <div class="quality-bar">
                        <div class="quality-fill" style="width:<?= round($frQualityScore / $frQualityTotal * 100) ?>%"></div>
                    </div>
                    <span class="quality-label"><?= $frQualityScore ?>/<?= $frQualityTotal ?></span>
                </div>
                <ul class="quality-checklist">
                    <li class="<?= $frQuality['title'] ? 'ok' : 'missing' ?>">Titre renseigné</li>
                    <li class="<?= $frQuality['excerpt'] ? 'ok' : 'missing' ?>">Extrait ≥ 50 car.</li>
                    <li class="<?= $frQuality['content'] ? 'ok' : 'missing' ?>">Contenu rédigé</li>
                    <li class="<?= $frQuality['cover'] ? 'ok' : 'missing' ?>">Image de couverture</li>
                    <li class="<?= $frQuality['meta_title'] ? 'ok' : 'missing' ?>">Meta title ≤ 60 car.</li>
                    <li class="<?= $frQuality['meta_desc'] ? 'ok' : 'missing' ?>">Meta desc 120–160 car.</li>
                    <li class="<?= $frQuality['category'] ? 'ok' : 'missing' ?>">Catégorie définie</li>
                </ul>
            </div>

            <!-- Paramètres partagés -->
            <div class="admin-card">
                <h3 class="article-sidebar-title">Paramètres</h3>

                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <option value="journal" <?= $articleType === 'journal' ? 'selected' : '' ?>>Journal</option>
                        <option value="sur-place" <?= $articleType === 'sur-place' ? 'selected' : '' ?>>Sur place</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="category">Catégorie</label>
                    <input type="text" id="category" name="category" value="<?= htmlspecialchars($frArticle['category'] ?? '') ?>" placeholder="Ex: Territoire, Restaurants…">
                </div>

                <div class="form-group">
                    <label for="slug">Slug (URL)</label>
                    <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($frArticle['slug'] ?? '') ?>" placeholder="auto-généré si vide">
                    <small class="text-muted">Identique pour FR/EN/ES</small>
                </div>

                <div class="form-group">
                    <label for="status">Statut</label>
                    <select id="status" name="status">
                        <option value="draft" <?= ($frArticle['status'] ?? '') === 'draft' ? 'selected' : '' ?>>🔶 Brouillon</option>
                        <option value="published" <?= ($frArticle['status'] ?? '') === 'published' ? 'selected' : '' ?>>🟢 Publié</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="published_at">Date de publication</label>
                    <input type="date" id="published_at" name="published_at" value="<?= htmlspecialchars(substr($frArticle['published_at'] ?? date('Y-m-d'), 0, 10)) ?>">
                </div>
            </div>

            <!-- Image de couverture -->
            <div class="admin-card">
                <h3 class="article-sidebar-title">Image de couverture</h3>
                <div class="form-group article-cover-picker" id="cover-picker">
                    <div class="article-cover-preview" id="cover-preview">
                        <?php if (!empty($frArticle['cover_image'])): ?>
                        <img src="/uploads/<?= htmlspecialchars($frArticle['cover_image']) ?>" alt="">
                        <?php else: ?>
                        <div class="cover-placeholder">Aucune image</div>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="cover_image" id="cover_image" value="<?= htmlspecialchars($frArticle['cover_image'] ?? '') ?>">
                    <button type="button" class="btn btn-sm btn-pick-cover" style="width:100%">Choisir l'image</button>
                </div>
            </div>

            <!-- Image OG -->
            <div class="admin-card">
                <h3 class="article-sidebar-title">Image réseaux sociaux (OG)</h3>
                <div class="form-group article-cover-picker" id="og-picker">
                    <div class="article-cover-preview" id="og-preview">
                        <?php if (!empty($frArticle['og_image'])): ?>
                        <img src="/uploads/<?= htmlspecialchars($frArticle['og_image']) ?>" alt="">
                        <?php else: ?>
                        <div class="cover-placeholder">= image couverture</div>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="og_image" id="og_image" value="<?= htmlspecialchars($frArticle['og_image'] ?? '') ?>">
                    <button type="button" class="btn btn-sm btn-pick-og" style="width:100%">Choisir</button>
                    <small class="text-muted">1200×630 recommandé. Si vide, utilise la couverture.</small>
                </div>
            </div>

            <!-- Aide rédaction -->
            <div class="admin-card">
                <h3 class="article-sidebar-title">Aide rédaction</h3>
                <div class="article-help">
                    <p><strong>Syntaxe contenu :</strong></p>
                    <code>## Titre H2</code><br>
                    <code>> Citation</code><br>
                    <code>![alt](image.webp) légende</code><br>
                    <code>- Item de liste</code><br>
                    <p style="margin-top:0.5rem"><strong>Recommandations SEO :</strong></p>
                    <ul>
                        <li>Title : 50–60 caractères</li>
                        <li>Description : 120–160 car.</li>
                        <li>1 seul H1 (= titre article)</li>
                        <li>2–4 H2 dans le contenu</li>
                        <li>Au moins 1 image avec alt</li>
                    </ul>
                    <p style="margin-top:0.5rem"><strong>Recommandations GSO :</strong></p>
                    <ul>
                        <li>Résumé clair et factuel</li>
                        <li>Répondre à une question</li>
                        <li>Structurer avec des listes</li>
                    </ul>
                </div>
            </div>

            <!-- Assistant IA -->
            <?php if (!empty($_ENV['ANTHROPIC_API_KEY'])): ?>
            <div class="admin-card" id="ai-panel">
                <h3 class="article-sidebar-title">Assistant IA</h3>
                <div class="form-group">
                    <label for="ai_subtitle">Sous-titre / angle</label>
                    <input type="text" id="ai_subtitle" placeholder="Ex: trucs et astuces pour…">
                </div>
                <button type="button" class="btn btn-ai" id="btn-generate-ai" style="width:100%">
                    Rédiger avec l'IA
                </button>
                <div id="ai-status" style="display:none;margin-top:0.5rem">
                    <div class="ai-loader"></div>
                    <small class="text-muted" id="ai-status-text">Génération en cours… (30-60s)</small>
                </div>
                <small class="text-muted" style="display:block;margin-top:0.4rem">Génère contenu FR + EN + ES, SEO et GSO. Relisez avant de publier.</small>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem"><?= $isEdit ? 'Enregistrer (FR/EN/ES)' : 'Créer (FR/EN/ES)' ?></button>
        </div>

        <!-- ═══════════════════════════════════════════
             ZONE PRINCIPALE : 3 colonnes FR / EN / ES
             ═══════════════════════════════════════════ -->
        <div class="article-main">

            <!-- CONTENU ÉDITORIAL -->
            <div class="admin-card">
                <h2 class="article-section-title">Contenu éditorial</h2>
                <div class="lang-columns">
                    <?php foreach ($langs as $l):
                        $a = $articlesByLang[$l] ?? [];
                    ?>
                    <div class="lang-column">
                        <div class="lang-column-header">
                            <span><?= $langLabels[$l] ?></span>
                            <?php
                            $titleLen = mb_strlen($a['title'] ?? '');
                            $hasContent = !empty($a['content']) && ($a['content'] ?? '[]') !== '[]';
                            ?>
                            <?php if ($titleLen > 0 && $hasContent): ?>
                            <span class="badge badge-success" style="font-size:0.6rem">OK</span>
                            <?php elseif ($titleLen > 0): ?>
                            <span class="badge badge-warning" style="font-size:0.6rem">Partiel</span>
                            <?php else: ?>
                            <span class="badge badge-danger" style="font-size:0.6rem">Vide</span>
                            <?php endif; ?>
                        </div>
                        <div class="lang-column-body">
                            <div class="form-group">
                                <label>Titre *</label>
                                <input type="text" name="title_<?= $l ?>" value="<?= htmlspecialchars($a['title'] ?? '') ?>" placeholder="Titre de l'article" <?= $l === 'fr' ? 'required' : '' ?>>
                            </div>
                            <div class="form-group">
                                <label>Extrait / chapeau</label>
                                <textarea name="excerpt_<?= $l ?>" rows="3" placeholder="Résumé court (2-3 phrases)"><?= htmlspecialchars($a['excerpt'] ?? '') ?></textarea>
                                <small class="char-count text-muted" data-target="excerpt_<?= $l ?>"><?= mb_strlen($a['excerpt'] ?? '') ?> car.</small>
                            </div>
                            <div class="form-group">
                                <label>Contenu</label>
                                <textarea name="content_raw_<?= $l ?>" rows="14" class="article-content-editor" placeholder="## Titre H2&#10;&#10;Paragraphe...&#10;&#10;> Citation&#10;&#10;![alt](image.webp) légende"><?= htmlspecialchars($contentRawByLang[$l] ?? '') ?></textarea>
                                <?php
                                $blockCount = 0;
                                if (!empty($a['content'])) {
                                    $blocks = json_decode($a['content'], true);
                                    $blockCount = is_array($blocks) ? count($blocks) : 0;
                                }
                                ?>
                                <small class="text-muted"><?= $blockCount ?> blocs</small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- SEO -->
            <div class="admin-card">
                <h2 class="article-section-title">SEO — Optimisation moteurs de recherche</h2>
                <div class="lang-columns">
                    <?php foreach ($langs as $l):
                        $a = $articlesByLang[$l] ?? [];
                        $mtLen = mb_strlen($a['meta_title'] ?? '');
                        $mdLen = mb_strlen($a['meta_desc'] ?? '');
                    ?>
                    <div class="lang-column">
                        <div class="lang-column-header">
                            <span><?= $langLabels[$l] ?></span>
                            <?php if ($mtLen > 0 && $mtLen <= 60 && $mdLen >= 120 && $mdLen <= 160): ?>
                            <span class="badge badge-success" style="font-size:0.6rem">OK</span>
                            <?php elseif ($mtLen > 0 || $mdLen > 0): ?>
                            <span class="badge badge-warning" style="font-size:0.6rem">À revoir</span>
                            <?php else: ?>
                            <span class="badge badge-danger" style="font-size:0.6rem">Vide</span>
                            <?php endif; ?>
                        </div>
                        <div class="lang-column-body">
                            <div class="form-group">
                                <label>Meta Title <small class="text-muted">(≤ 60)</small></label>
                                <input type="text" name="meta_title_<?= $l ?>" value="<?= htmlspecialchars($a['meta_title'] ?? '') ?>" maxlength="70" placeholder="Titre pour Google">
                                <small class="char-count <?= $mtLen > 60 ? 'text-danger' : 'text-muted' ?>" data-target="meta_title_<?= $l ?>"><?= $mtLen ?>/60</small>
                            </div>
                            <div class="form-group">
                                <label>Meta Description <small class="text-muted">(120–160)</small></label>
                                <textarea name="meta_desc_<?= $l ?>" rows="2" maxlength="170" placeholder="Description pour Google"><?= htmlspecialchars($a['meta_desc'] ?? '') ?></textarea>
                                <small class="char-count <?= ($mdLen < 120 || $mdLen > 160) && $mdLen > 0 ? 'text-danger' : 'text-muted' ?>" data-target="meta_desc_<?= $l ?>"><?= $mdLen ?>/160</small>
                            </div>
                            <div class="form-group">
                                <label>Keywords <small class="text-muted">(séparés par virgule)</small></label>
                                <input type="text" name="meta_keywords_<?= $l ?>" value="<?= htmlspecialchars($a['meta_keywords'] ?? '') ?>" placeholder="mot-clé 1, mot-clé 2">
                            </div>
                            <!-- Aperçu SERP -->
                            <div class="serp-preview">
                                <div class="serp-title"><?= htmlspecialchars($a['meta_title'] ?? $a['title'] ?? 'Titre de l\'article') ?></div>
                                <div class="serp-url">villaplaisance.fr/<?= $articleType === 'journal' ? 'journal' : 'sur-place' ?>/<?= htmlspecialchars($frArticle['slug'] ?? 'slug') ?></div>
                                <div class="serp-desc"><?= htmlspecialchars(mb_substr($a['meta_desc'] ?? $a['excerpt'] ?? 'Description de l\'article...', 0, 160)) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- GSO — Optimisation IA -->
            <div class="admin-card">
                <h2 class="article-section-title">GSO — Optimisation pour l'IA (ChatGPT, Perplexity…)</h2>
                <p class="text-sm text-muted mb-2">Résumé structuré et factuel pour les moteurs de recherche IA. Répondez aux questions que les voyageurs posent aux assistants IA.</p>
                <div class="lang-columns">
                    <?php foreach ($langs as $l):
                        $a = $articlesByLang[$l] ?? [];
                    ?>
                    <div class="lang-column">
                        <div class="lang-column-header">
                            <span><?= $langLabels[$l] ?></span>
                        </div>
                        <div class="lang-column-body">
                            <div class="form-group">
                                <label>Résumé GSO</label>
                                <textarea name="gso_desc_<?= $l ?>" rows="4" placeholder="Résumé clair et factuel pour les IA. Ex: Villa Plaisance propose 3 chambres d'hôtes à Bédarrides..."><?= htmlspecialchars($a['gso_desc'] ?? '') ?></textarea>
                                <small class="text-muted">Ton factuel, phrases courtes, données concrètes.</small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="padding:1rem 0">
                <button type="submit" class="btn btn-primary btn-lg"><?= $isEdit ? 'Enregistrer toutes les langues' : 'Créer l\'article (FR/EN/ES)' ?></button>
            </div>
        </div>
    </div>
</form>

<!-- Media Picker Modal -->
<div id="article-media-modal" class="media-modal" style="display:none">
    <div class="media-modal-backdrop"></div>
    <div class="media-modal-content">
        <div class="media-modal-header">
            <h3>Choisir une image</h3>
            <input type="text" id="article-media-search" placeholder="Rechercher..." class="media-modal-search">
            <button type="button" class="media-modal-close">&times;</button>
        </div>
        <div class="media-modal-body" id="article-media-grid"></div>
    </div>
</div>

<script>
(function() {
    // Character counters
    document.querySelectorAll('input[name], textarea[name]').forEach(field => {
        field.addEventListener('input', () => {
            const counter = field.parentElement.querySelector('.char-count');
            if (counter) {
                const len = field.value.length;
                const target = counter.dataset.target;
                if (target && target.startsWith('meta_title')) {
                    counter.textContent = len + '/60';
                    counter.className = 'char-count ' + (len > 60 ? 'text-danger' : 'text-muted');
                } else if (target && target.startsWith('meta_desc')) {
                    counter.textContent = len + '/160';
                    counter.className = 'char-count ' + ((len < 120 || len > 160) && len > 0 ? 'text-danger' : 'text-muted');
                } else if (target && target.startsWith('excerpt')) {
                    counter.textContent = len + ' car.';
                }
            }
        });
    });

    // Media picker
    let mediaCallback = null;
    const modal = document.getElementById('article-media-modal');
    const grid = document.getElementById('article-media-grid');
    const search = document.getElementById('article-media-search');
    const backdrop = modal.querySelector('.media-modal-backdrop');
    const closeBtn = modal.querySelector('.media-modal-close');
    let allFiles = [];

    function openModal(cb) {
        mediaCallback = cb;
        modal.style.display = 'flex';
        search.value = '';
        if (allFiles.length === 0) {
            grid.innerHTML = '<p style="padding:1rem;color:#888">Chargement...</p>';
            fetch('/admin/api/media-list')
                .then(r => r.json())
                .then(files => { allFiles = files; renderGrid(''); })
                .catch(() => { grid.innerHTML = '<p style="padding:1rem;color:#c00">Erreur</p>'; });
        } else {
            renderGrid('');
        }
        search.focus();
    }
    function closeModal() { modal.style.display = 'none'; mediaCallback = null; }
    backdrop.addEventListener('click', closeModal);
    closeBtn.addEventListener('click', closeModal);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    search.addEventListener('input', () => renderGrid(search.value.toLowerCase()));

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
                if (mediaCallback) mediaCallback(thumb.dataset.file);
                closeModal();
            });
        });
    }

    // Cover image picker
    document.querySelector('.btn-pick-cover')?.addEventListener('click', () => {
        openModal(file => {
            document.getElementById('cover_image').value = file;
            document.getElementById('cover-preview').innerHTML = `<img src="/uploads/${file}" alt="">`;
        });
    });

    // OG image picker
    document.querySelector('.btn-pick-og')?.addEventListener('click', () => {
        openModal(file => {
            document.getElementById('og_image').value = file;
            document.getElementById('og-preview').innerHTML = `<img src="/uploads/${file}" alt="">`;
        });
    });

    // Insert image into content textarea
    document.querySelectorAll('.article-content-editor').forEach(editor => {
        editor.addEventListener('keydown', e => {
            if (e.key === 'Tab') {
                e.preventDefault();
                const start = editor.selectionStart;
                editor.value = editor.value.substring(0, start) + '    ' + editor.value.substring(editor.selectionEnd);
                editor.selectionStart = editor.selectionEnd = start + 4;
            }
        });
    });

    // AI Generation
    const aiBtn = document.getElementById('btn-generate-ai');
    if (aiBtn) {
        aiBtn.addEventListener('click', async () => {
            const title = document.querySelector('input[name="title_fr"]')?.value?.trim();
            if (!title) {
                alert('Renseignez d\'abord le titre FR.');
                return;
            }
            if (!confirm('Générer le contenu avec l\'IA ?\nCela remplacera les champs vides (FR, EN, ES, SEO, GSO).')) return;

            const subtitle = document.getElementById('ai_subtitle')?.value?.trim() || '';
            const type = document.querySelector('select[name="type"]')?.value || 'journal';
            const category = document.querySelector('input[name="category"]')?.value?.trim() || '';

            const status = document.getElementById('ai-status');
            const statusText = document.getElementById('ai-status-text');
            status.style.display = 'block';
            aiBtn.disabled = true;
            aiBtn.textContent = 'Génération…';
            statusText.textContent = 'Génération en cours… (30-60s)';

            try {
                const res = await fetch('/admin/api/generate-article', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ type, title, subtitle, category })
                });
                const json = await res.json();

                if (json.error) {
                    alert('Erreur : ' + json.error);
                    return;
                }

                const data = json.data;
                if (!data) {
                    alert('Réponse invalide de l\'IA.');
                    return;
                }

                // Fill fields for each language
                ['fr', 'en', 'es'].forEach(lang => {
                    const d = data[lang];
                    if (!d) return;

                    const fill = (name, val) => {
                        const el = document.querySelector(`[name="${name}_${lang}"]`);
                        if (el && (!el.value.trim() || lang !== 'fr')) {
                            el.value = val || '';
                            // Trigger input event for char counters
                            el.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                    };

                    // For FR, only fill empty fields. For EN/ES, fill all.
                    if (lang === 'fr') {
                        const titleEl = document.querySelector('[name="title_fr"]');
                        if (titleEl && !titleEl.value.trim()) titleEl.value = d.title || '';
                    } else {
                        fill('title', d.title);
                    }
                    fill('excerpt', d.excerpt);
                    fill('content_raw', d.content);
                    fill('meta_title', d.meta_title);
                    fill('meta_desc', d.meta_desc);
                    fill('meta_keywords', d.meta_keywords);
                    fill('gso_desc', d.gso_desc);
                });

                statusText.textContent = 'Contenu généré ! Relisez avant de publier.';
                statusText.style.color = '#1A7A4A';

            } catch (err) {
                alert('Erreur réseau : ' + err.message);
            } finally {
                aiBtn.disabled = false;
                aiBtn.textContent = 'Rédiger avec l\'IA';
            }
        });
    }
})();
</script>
