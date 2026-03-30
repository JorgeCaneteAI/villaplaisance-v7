<div class="page-header">
    <h1>Sections<?= $page_slug ? ' — ' . htmlspecialchars($page_slug) : '' ?></h1>
    <a href="/admin/pages" class="btn">Retour aux pages</a>
</div>

<?php if (!$page_slug): ?>
<p class="text-muted">Sélectionnez une page depuis la liste des pages CMS.</p>
<?php else: ?>

<?php
$langLabels = ['fr' => '🇫🇷 Français', 'en' => '🇬🇧 English', 'es' => '🇪🇸 Español'];

// Field definitions per block type
$fieldDefs = [
    'hero' => [
        ['name' => 'title', 'label' => 'Titre (H1)', 'type' => 'text'],
        ['name' => 'subtitle', 'label' => 'Sous-titre', 'type' => 'text'],
        ['name' => 'buttons', 'label' => 'Boutons (JSON)', 'type' => 'buttons'],
    ],
    'prose' => [
        ['name' => 'heading', 'label' => 'Titre (H2)', 'type' => 'text'],
        ['name' => 'text', 'label' => 'Texte', 'type' => 'textarea'],
    ],
    'cta' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'text', 'label' => 'Texte', 'type' => 'textarea'],
        ['name' => 'buttons', 'label' => 'Boutons (JSON)', 'type' => 'buttons'],
    ],
    'cartes' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
    ],
    'faq' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
    ],
    'avis' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
    ],
    'stats' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
    ],
    'territoire' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
    ],
    'galerie' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
    ],
    'articles' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
    ],
    'liste' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'items', 'label' => 'Items (JSON)', 'type' => 'json'],
    ],
    'tableau' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'rows', 'label' => 'Lignes (JSON)', 'type' => 'json'],
    ],
    'petit-dejeuner' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'text', 'label' => 'Texte', 'type' => 'textarea'],
    ],
    'piscine' => [
        ['name' => 'heading', 'label' => 'Titre', 'type' => 'text'],
        ['name' => 'text', 'label' => 'Texte', 'type' => 'textarea'],
    ],
];

// Shared fields (not translatable) per block type
$sharedFieldDefs = [
    'hero' => [
        ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
        ['name' => 'image_alt', 'label' => 'Alt image', 'type' => 'text'],
        ['name' => 'compact', 'label' => 'Mode compact', 'type' => 'checkbox'],
    ],
    'prose' => [
        ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
        ['name' => 'image_alt', 'label' => 'Alt image', 'type' => 'text'],
        ['name' => 'lead', 'label' => 'Style lead', 'type' => 'checkbox'],
    ],
    'cta' => [
        ['name' => 'dark', 'label' => 'Fond sombre', 'type' => 'checkbox'],
    ],
    'cartes' => [
        ['name' => 'offer', 'label' => 'Offre', 'type' => 'select', 'options' => ['bb' => 'B&B', 'villa' => 'Villa']],
    ],
    'faq' => [
        ['name' => 'page_slug', 'label' => 'Page source FAQ', 'type' => 'text'],
    ],
    'avis' => [
        ['name' => 'limit', 'label' => 'Nombre', 'type' => 'number'],
        ['name' => 'offer_filter', 'label' => 'Filtre offre', 'type' => 'text'],
    ],
    'articles' => [
        ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => ['journal' => 'Journal', 'surplace' => 'Sur place']],
        ['name' => 'limit', 'label' => 'Nombre', 'type' => 'number'],
    ],
    'galerie' => [
        ['name' => 'images', 'label' => 'Images (JSON)', 'type' => 'json'],
    ],
    'liste' => [
        ['name' => 'style', 'label' => 'Style', 'type' => 'select', 'options' => ['check' => 'Check', 'bullet' => 'Puces', 'none' => 'Sans']],
    ],
    'petit-dejeuner' => [
        ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
        ['name' => 'image_alt', 'label' => 'Alt', 'type' => 'text'],
    ],
    'piscine' => [
        ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
        ['name' => 'image_alt', 'label' => 'Alt', 'type' => 'text'],
    ],
];

// Index EN/ES sections by position to match FR sections
$sectionIndex = [];
foreach ($langs as $l) {
    foreach ($sectionsByLang[$l] ?? [] as $s) {
        $sectionIndex[$l][$s['position']] = $s;
    }
}
?>

<?php if (empty($sections)): ?>
<p class="text-muted mb-2">Aucune section pour cette page.</p>
<?php else: ?>

<?php foreach ($sections as $frSection):
    $type = $frSection['block_type'];
    $pos = $frSection['position'];
    $transFields = $fieldDefs[$type] ?? [];
    $shared = $sharedFieldDefs[$type] ?? [];
    $frContent = json_decode($frSection['content'] ?? '{}', true) ?: [];
    $isInactive = !$frSection['active'];
?>
<div class="block-row">
    <!-- Block header (shared controls) -->
    <div class="block-row-header">
        <div class="block-row-info">
            <span class="section-position"><?= $pos ?></span>
            <span class="badge badge-info"><?= htmlspecialchars($type) ?></span>
            <strong><?= htmlspecialchars($frSection['title'] ?? '(sans titre)') ?></strong>
            <?php if ($isInactive): ?><span class="badge badge-warning">Masqué</span><?php endif; ?>
        </div>
        <div class="block-row-actions">
            <form method="POST" action="/admin/sections/<?= $frSection['id'] ?>/toggle" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-sm"><?= $frSection['active'] ? '👁 Masquer' : '👁‍🗨 Activer' ?></button>
            </form>
            <form method="POST" action="/admin/sections/<?= $frSection['id'] ?>/move/up" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-sm">↑</button>
            </form>
            <form method="POST" action="/admin/sections/<?= $frSection['id'] ?>/move/down" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-sm">↓</button>
            </form>
            <form method="POST" action="/admin/sections/<?= $frSection['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer cette section (toutes langues) ?')">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-sm btn-danger">✕</button>
            </form>
        </div>
    </div>

    <!-- 3-column language grid -->
    <?php if (!empty($transFields)): ?>
    <div class="lang-columns">
        <?php foreach ($langs as $l):
            $s = $sectionIndex[$l][$pos] ?? null;
            $content = $s ? (json_decode($s['content'] ?? '{}', true) ?: []) : [];
        ?>
        <div class="lang-column">
            <div class="lang-column-header">
                <span><?= $langLabels[$l] ?? strtoupper($l) ?></span>
                <?php if (!$s): ?><span class="badge badge-warning">manquant</span><?php endif; ?>
            </div>
            <?php if ($s): ?>
            <form method="POST" action="/admin/sections/<?= $s['id'] ?>/save" class="lang-column-body">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <input type="hidden" name="block_type" value="<?= htmlspecialchars($type) ?>">
                <input type="hidden" name="title" value="<?= htmlspecialchars($s['title'] ?? '') ?>">
                <?php // Copy shared fields from FR content into hidden inputs for non-FR langs
                if ($l !== 'fr') {
                    foreach ($shared as $sf) {
                        $sfVal = $frContent[$sf['name']] ?? '';
                        if (is_array($sfVal)) $sfVal = json_encode($sfVal);
                        echo '<input type="hidden" name="fields[' . htmlspecialchars($sf['name']) . ']" value="' . htmlspecialchars((string)$sfVal) . '">';
                    }
                }
                ?>
                <?php foreach ($transFields as $field):
                    $val = $content[$field['name']] ?? '';
                ?>
                    <?php if ($field['type'] === 'text'): ?>
                    <div class="form-group">
                        <label><?= $field['label'] ?></label>
                        <input type="text" name="fields[<?= $field['name'] ?>]" value="<?= htmlspecialchars((string)$val) ?>">
                    </div>
                    <?php elseif ($field['type'] === 'textarea'): ?>
                    <div class="form-group">
                        <label><?= $field['label'] ?></label>
                        <textarea name="fields[<?= $field['name'] ?>]" rows="3"><?= htmlspecialchars((string)$val) ?></textarea>
                    </div>
                    <?php elseif ($field['type'] === 'json'): ?>
                    <div class="form-group">
                        <label><?= $field['label'] ?></label>
                        <textarea name="fields[<?= $field['name'] ?>]" rows="3" class="code-textarea"><?= htmlspecialchars(is_array($val) ? json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : (string)$val) ?></textarea>
                    </div>
                    <?php elseif ($field['type'] === 'buttons'): ?>
                    <?php
                    // Parse buttons — support legacy single cta_text/cta_url or button_text/button_url
                    $btns = [];
                    if (is_array($val)) {
                        $btns = $val;
                    } elseif (is_string($val) && str_starts_with(trim($val), '[')) {
                        $btns = json_decode($val, true) ?: [];
                    }
                    // Legacy fallback: migrate from old single-button fields
                    if (empty($btns)) {
                        if (!empty($content['cta_text'])) {
                            $btns[] = ['text' => $content['cta_text'], 'url' => $content['cta_url'] ?? '', 'style' => 'primary'];
                        }
                        if (!empty($content['button_text'])) {
                            $btns[] = ['text' => $content['button_text'], 'url' => $content['button_url'] ?? $frContent['button_url'] ?? '', 'style' => 'primary'];
                        }
                    }
                    if (empty($btns)) $btns[] = ['text' => '', 'url' => '', 'style' => 'primary'];
                    $sectionId = $s['id'];
                    ?>
                    <div class="form-group buttons-editor" data-section="<?= $sectionId ?>">
                        <label>Boutons</label>
                        <div class="buttons-list">
                        <?php foreach ($btns as $bi => $btn): ?>
                            <div class="button-row" data-index="<?= $bi ?>">
                                <input type="text" placeholder="Texte" value="<?= htmlspecialchars($btn['text'] ?? '') ?>" class="btn-text" style="flex:1">
                                <input type="text" placeholder="URL" value="<?= htmlspecialchars($btn['url'] ?? '') ?>" class="btn-url" style="flex:1">
                                <select class="btn-style" style="width:auto">
                                    <option value="primary" <?= ($btn['style'] ?? '') === 'primary' ? 'selected' : '' ?>>Principal</option>
                                    <option value="outline" <?= ($btn['style'] ?? '') === 'outline' ? 'selected' : '' ?>>Outline</option>
                                    <option value="external" <?= ($btn['style'] ?? '') === 'external' ? 'selected' : '' ?>>Externe ↗</option>
                                </select>
                                <button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding:0 0.4rem">&times;</button>
                            </div>
                        <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-add-button" style="margin-top:0.25rem;font-size:0.7rem">+ Bouton</button>
                        <input type="hidden" name="fields[<?= $field['name'] ?>]" class="buttons-json" value="<?= htmlspecialchars(json_encode($btns, JSON_UNESCAPED_UNICODE)) ?>">
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php // Show shared fields only in FR column
                if ($l === 'fr' && !empty($shared)): ?>
                <hr style="margin:0.5rem 0;border:none;border-top:1px solid #e2e8f0">
                <p style="font-size:0.65rem;color:#999;margin-bottom:0.25rem">Paramètres partagés</p>
                <?php foreach ($shared as $sf):
                    $sfVal = $frContent[$sf['name']] ?? '';
                ?>
                    <?php if ($sf['type'] === 'text'): ?>
                    <div class="form-group">
                        <label><?= $sf['label'] ?></label>
                        <input type="text" name="fields[<?= $sf['name'] ?>]" value="<?= htmlspecialchars((string)$sfVal) ?>">
                    </div>
                    <?php elseif ($sf['type'] === 'number'): ?>
                    <div class="form-group">
                        <label><?= $sf['label'] ?></label>
                        <input type="number" name="fields[<?= $sf['name'] ?>]" value="<?= htmlspecialchars((string)$sfVal) ?>" min="1" max="50">
                    </div>
                    <?php elseif ($sf['type'] === 'select'): ?>
                    <div class="form-group">
                        <label><?= $sf['label'] ?></label>
                        <select name="fields[<?= $sf['name'] ?>]">
                            <?php foreach ($sf['options'] as $optVal => $optLabel): ?>
                            <option value="<?= htmlspecialchars($optVal) ?>" <?= (string)$sfVal === (string)$optVal ? 'selected' : '' ?>><?= htmlspecialchars($optLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php elseif ($sf['type'] === 'checkbox'): ?>
                    <div class="form-group">
                        <label><input type="hidden" name="fields[<?= $sf['name'] ?>]" value="0"><input type="checkbox" name="fields[<?= $sf['name'] ?>]" value="1" <?= !empty($sfVal) ? 'checked' : '' ?>> <?= $sf['label'] ?></label>
                    </div>
                    <?php elseif ($sf['type'] === 'image'): ?>
                    <div class="form-group section-image-picker" data-section="<?= $s['id'] ?>">
                        <label><?= $sf['label'] ?></label>
                        <div style="display:flex;gap:0.5rem;align-items:center">
                            <input type="text" name="fields[<?= $sf['name'] ?>]" value="<?= htmlspecialchars((string)$sfVal) ?>" class="section-image-input" style="flex:1;font-size:0.75rem" placeholder="fichier.webp">
                            <button type="button" class="btn btn-sm btn-pick-image">Choisir</button>
                        </div>
                        <?php if (!empty($sfVal)): ?>
                        <div style="margin-top:0.3rem">
                            <img src="/uploads/<?= htmlspecialchars((string)$sfVal) ?>" alt="" style="max-width:120px;max-height:80px;border-radius:4px;border:1px solid #e2e8f0" loading="lazy">
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php elseif ($sf['type'] === 'json'): ?>
                    <div class="form-group">
                        <label><?= $sf['label'] ?></label>
                        <textarea name="fields[<?= $sf['name'] ?>]" rows="3" class="code-textarea"><?= htmlspecialchars(is_array($sfVal) ? json_encode($sfVal, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : (string)$sfVal) ?></textarea>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>

                <input type="hidden" name="active" value="<?= $s['active'] ? '1' : '' ?>">
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

    <?php // Inline FAQ items for faq blocks
    if ($type === 'faq'):
        $faqPageSlug = $frContent['page_slug'] ?? $page_slug;
    ?>
    <div class="block-inline-detail">
        <div class="block-inline-detail-header">
            <strong>Questions / Réponses</strong>
            <form method="POST" action="/admin/faq/create" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <input type="hidden" name="page_slug" value="<?= htmlspecialchars($faqPageSlug) ?>">
                <button type="submit" class="btn btn-sm btn-primary">+ Ajouter FAQ</button>
            </form>
        </div>
        <?php
        // Get max positions across all langs for this page_slug
        $faqPositions = [];
        foreach ($langs as $fl) {
            foreach ($faqBySlugLang[$faqPageSlug][$fl] ?? [] as $fItem) {
                $faqPositions[$fItem['position']] = true;
            }
        }
        ksort($faqPositions);
        // Index FAQ by lang+position
        $faqIndex = [];
        foreach ($langs as $fl) {
            foreach ($faqBySlugLang[$faqPageSlug][$fl] ?? [] as $fItem) {
                $faqIndex[$fl][$fItem['position']] = $fItem;
            }
        }
        ?>
        <?php foreach (array_keys($faqPositions) as $fPos): ?>
        <div class="lang-columns faq-row">
            <?php foreach ($langs as $fl):
                $fItem = $faqIndex[$fl][$fPos] ?? null;
            ?>
            <div class="lang-column">
                <?php if ($fl === 'fr'): ?>
                <div class="lang-column-header" style="padding:0.25rem 0.5rem">
                    <span>Q<?= $fPos ?></span>
                    <form method="POST" action="/admin/faq/<?= $fItem['id'] ?? 0 ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer cette FAQ (toutes langues) ?')">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                        <button type="submit" class="btn btn-sm btn-danger" style="padding:0 0.4rem;font-size:0.65rem">✕</button>
                    </form>
                </div>
                <?php endif; ?>
                <?php if ($fItem): ?>
                <form method="POST" action="/admin/faq/<?= $fItem['id'] ?>/save" class="lang-column-body" style="padding:0.5rem">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                    <div class="form-group">
                        <label style="font-size:0.65rem">Question</label>
                        <input type="text" name="question" value="<?= htmlspecialchars($fItem['question'] ?? '') ?>" style="font-size:0.75rem">
                    </div>
                    <div class="form-group">
                        <label style="font-size:0.65rem">Réponse</label>
                        <textarea name="answer" rows="2" style="font-size:0.75rem"><?= htmlspecialchars($fItem['answer'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" style="font-size:0.65rem;padding:0.15rem 0.5rem">Sauver</button>
                </form>
                <?php else: ?>
                <div class="lang-column-body" style="padding:0.5rem">
                    <p class="lang-missing" style="font-size:0.7rem">—</p>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php // Inline Stats items for stats blocks
    if ($type === 'stats'):
    ?>
    <div class="block-inline-detail">
        <div class="block-inline-detail-header">
            <strong>Chiffres</strong>
            <form method="POST" action="/admin/stats/create" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-sm btn-primary">+ Ajouter stat</button>
            </form>
        </div>
        <?php
        $statPositions = [];
        foreach ($langs as $sl) {
            foreach ($statsByLang[$sl] ?? [] as $sItem) {
                $statPositions[$sItem['position']] = true;
            }
        }
        ksort($statPositions);
        $statIndex = [];
        foreach ($langs as $sl) {
            foreach ($statsByLang[$sl] ?? [] as $sItem) {
                $statIndex[$sl][$sItem['position']] = $sItem;
            }
        }
        ?>
        <?php foreach (array_keys($statPositions) as $sPos): ?>
        <div class="lang-columns stat-row">
            <?php foreach ($langs as $sl):
                $sItem = $statIndex[$sl][$sPos] ?? null;
            ?>
            <div class="lang-column">
                <?php if ($sl === 'fr'): ?>
                <div class="lang-column-header" style="padding:0.25rem 0.5rem">
                    <span>#<?= $sPos ?></span>
                    <form method="POST" action="/admin/stats/<?= $sItem['id'] ?? 0 ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer cette stat (toutes langues) ?')">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                        <button type="submit" class="btn btn-sm btn-danger" style="padding:0 0.4rem;font-size:0.65rem">✕</button>
                    </form>
                </div>
                <?php endif; ?>
                <?php if ($sItem): ?>
                <form method="POST" action="/admin/stats/<?= $sItem['id'] ?>/save" class="lang-column-body" style="padding:0.5rem">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                    <div class="form-group">
                        <label style="font-size:0.65rem">Valeur</label>
                        <input type="text" name="value" value="<?= htmlspecialchars($sItem['value'] ?? '') ?>" style="font-size:0.75rem">
                    </div>
                    <div class="form-group">
                        <label style="font-size:0.65rem">Label</label>
                        <input type="text" name="label" value="<?= htmlspecialchars($sItem['label'] ?? '') ?>" style="font-size:0.75rem">
                    </div>
                    <div class="form-group">
                        <label style="font-size:0.65rem">Sous-label</label>
                        <input type="text" name="sublabel" value="<?= htmlspecialchars($sItem['sublabel'] ?? '') ?>" style="font-size:0.75rem">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" style="font-size:0.65rem;padding:0.15rem 0.5rem">Sauver</button>
                </form>
                <?php else: ?>
                <div class="lang-column-body" style="padding:0.5rem">
                    <p class="lang-missing" style="font-size:0.7rem">—</p>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php // Inline Proximites for territoire blocks
    if ($type === 'territoire'):
    ?>
    <div class="block-inline-detail">
        <div class="block-inline-detail-header">
            <strong>Proximités</strong>
            <form method="POST" action="/admin/proximites/create" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <button type="submit" class="btn btn-sm btn-primary">+ Ajouter</button>
            </form>
        </div>
        <?php
        $proxPositions = [];
        foreach ($langs as $pl) {
            foreach ($proxByLang[$pl] ?? [] as $pxItem) {
                $proxPositions[$pxItem['position']] = true;
            }
        }
        ksort($proxPositions);
        $proxIndex = [];
        foreach ($langs as $pl) {
            foreach ($proxByLang[$pl] ?? [] as $pxItem) {
                $proxIndex[$pl][$pxItem['position']] = $pxItem;
            }
        }
        ?>
        <?php foreach (array_keys($proxPositions) as $pxPos): ?>
        <div class="lang-columns prox-row">
            <?php foreach ($langs as $pl):
                $pxItem = $proxIndex[$pl][$pxPos] ?? null;
            ?>
            <div class="lang-column">
                <?php if ($pl === 'fr'): ?>
                <div class="lang-column-header" style="padding:0.25rem 0.5rem">
                    <span>#<?= $pxPos ?></span>
                    <form method="POST" action="/admin/proximites/<?= $pxItem['id'] ?? 0 ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer cette proximité ?')">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                        <button type="submit" class="btn btn-sm btn-danger" style="padding:0 0.4rem;font-size:0.65rem">✕</button>
                    </form>
                </div>
                <?php endif; ?>
                <?php if ($pxItem): ?>
                <form method="POST" action="/admin/proximites/<?= $pxItem['id'] ?>/save" class="lang-column-body" style="padding:0.5rem">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                    <div class="form-group">
                        <label style="font-size:0.65rem">Nom</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($pxItem['name'] ?? '') ?>" style="font-size:0.75rem">
                    </div>
                    <div class="form-group">
                        <label style="font-size:0.65rem">Distance</label>
                        <input type="text" name="distance" value="<?= htmlspecialchars($pxItem['distance'] ?? '') ?>" style="font-size:0.75rem">
                    </div>
                    <div class="form-group">
                        <label style="font-size:0.65rem">Description</label>
                        <input type="text" name="description" value="<?= htmlspecialchars($pxItem['description'] ?? '') ?>" style="font-size:0.75rem">
                    </div>
                    <?php if ($pl === 'fr'): ?>
                    <div class="form-group">
                        <label style="font-size:0.65rem">Catégorie</label>
                        <select name="category" style="font-size:0.75rem">
                            <?php foreach (['ville', 'monument', 'vignoble', 'theatre', 'marche', 'nature', 'plage', 'sport'] as $cat): ?>
                            <option value="<?= $cat ?>" <?= ($pxItem['category'] ?? '') === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-size:0.65rem">Distance (min, tri)</label>
                        <input type="number" name="distance_min" value="<?= (int)($pxItem['distance_min'] ?? 0) ?>" style="font-size:0.75rem" min="0">
                    </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary btn-sm" style="font-size:0.65rem;padding:0.15rem 0.5rem">Sauver</button>
                </form>
                <?php else: ?>
                <div class="lang-column-body" style="padding:0.5rem">
                    <p class="lang-missing" style="font-size:0.7rem">—</p>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php elseif (!empty($shared)): ?>
    <!-- Shared fields only (no translatable content) -->
    <div class="lang-columns" style="grid-template-columns: 1fr">
        <?php $s = $sectionIndex['fr'][$pos] ?? null; ?>
        <?php if ($s): ?>
        <form method="POST" action="/admin/sections/<?= $s['id'] ?>/save" class="lang-column-body" style="padding:0.75rem">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <input type="hidden" name="block_type" value="<?= htmlspecialchars($type) ?>">
            <input type="hidden" name="title" value="<?= htmlspecialchars($s['title'] ?? '') ?>">
            <p style="font-size:0.65rem;color:#999;margin-bottom:0.25rem">Paramètres partagés</p>
            <?php foreach ($shared as $sf):
                $sfVal = $frContent[$sf['name']] ?? '';
            ?>
                <?php if ($sf['type'] === 'text'): ?>
                <div class="form-group">
                    <label><?= $sf['label'] ?></label>
                    <input type="text" name="fields[<?= $sf['name'] ?>]" value="<?= htmlspecialchars((string)$sfVal) ?>">
                </div>
                <?php elseif ($sf['type'] === 'number'): ?>
                <div class="form-group">
                    <label><?= $sf['label'] ?></label>
                    <input type="number" name="fields[<?= $sf['name'] ?>]" value="<?= htmlspecialchars((string)$sfVal) ?>" min="1" max="50">
                </div>
                <?php elseif ($sf['type'] === 'select'): ?>
                <div class="form-group">
                    <label><?= $sf['label'] ?></label>
                    <select name="fields[<?= $sf['name'] ?>]">
                        <?php foreach ($sf['options'] as $optVal => $optLabel): ?>
                        <option value="<?= htmlspecialchars($optVal) ?>" <?= (string)$sfVal === (string)$optVal ? 'selected' : '' ?>><?= htmlspecialchars($optLabel) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php elseif ($sf['type'] === 'checkbox'): ?>
                <div class="form-group">
                    <label><input type="hidden" name="fields[<?= $sf['name'] ?>]" value="0"><input type="checkbox" name="fields[<?= $sf['name'] ?>]" value="1" <?= !empty($sfVal) ? 'checked' : '' ?>> <?= $sf['label'] ?></label>
                </div>
                <?php elseif ($sf['type'] === 'json'): ?>
                <div class="form-group">
                    <label><?= $sf['label'] ?></label>
                    <textarea name="fields[<?= $sf['name'] ?>]" rows="3" class="code-textarea"><?= htmlspecialchars(is_array($sfVal) ? json_encode($sfVal, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : (string)$sfVal) ?></textarea>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <input type="hidden" name="active" value="<?= $s['active'] ? '1' : '' ?>">
            <button type="submit" class="btn btn-primary btn-sm mt-1">Enregistrer</button>
        </form>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <p class="text-muted text-sm" style="padding:0.5rem 0">Bloc géré automatiquement.</p>
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
            <button type="submit" class="btn btn-primary btn-sm">Ajouter (FR/EN/ES)</button>
        </div>
    </div>
</form>

<?php endif; ?>

<script>
(function() {
    // Buttons editor
    function syncButtons(editor) {
        const rows = editor.querySelectorAll('.button-row');
        const btns = [];
        rows.forEach(row => {
            btns.push({
                text: row.querySelector('.btn-text').value,
                url: row.querySelector('.btn-url').value,
                style: row.querySelector('.btn-style').value
            });
        });
        editor.querySelector('.buttons-json').value = JSON.stringify(btns);
    }

    document.addEventListener('click', (e) => {
        if (e.target.closest('.btn-add-button')) {
            const editor = e.target.closest('.buttons-editor');
            const list = editor.querySelector('.buttons-list');
            const idx = list.querySelectorAll('.button-row').length;
            const row = document.createElement('div');
            row.className = 'button-row';
            row.dataset.index = idx;
            row.innerHTML = '<input type="text" placeholder="Texte" class="btn-text" style="flex:1">'
                + '<input type="text" placeholder="URL" class="btn-url" style="flex:1">'
                + '<select class="btn-style" style="width:auto"><option value="primary">Principal</option><option value="outline">Outline</option><option value="external">Externe ↗</option></select>'
                + '<button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding:0 0.4rem">&times;</button>';
            list.appendChild(row);
            syncButtons(editor);
        }
        if (e.target.closest('.btn-remove-row')) {
            const row = e.target.closest('.button-row');
            const editor = row.closest('.buttons-editor');
            if (editor.querySelectorAll('.button-row').length > 1) {
                row.remove();
            } else {
                row.querySelector('.btn-text').value = '';
                row.querySelector('.btn-url').value = '';
            }
            syncButtons(editor);
        }
    });

    document.addEventListener('input', (e) => {
        if (e.target.closest('.button-row')) {
            syncButtons(e.target.closest('.buttons-editor'));
        }
    });
    document.addEventListener('change', (e) => {
        if (e.target.closest('.button-row')) {
            syncButtons(e.target.closest('.buttons-editor'));
        }
    });
})();
</script>

<!-- Media Picker Modal for Sections -->
<div id="section-media-modal" class="media-modal" style="display:none">
    <div class="media-modal-backdrop"></div>
    <div class="media-modal-content">
        <div class="media-modal-header">
            <h3>Choisir une image</h3>
            <input type="text" id="section-media-search" placeholder="Rechercher..." class="media-modal-search">
            <button type="button" class="media-modal-close">&times;</button>
        </div>
        <div class="media-modal-body" id="section-media-grid"></div>
    </div>
</div>

<script>
(function() {
    let imgCallback = null;
    const modal = document.getElementById('section-media-modal');
    if (!modal) return;
    const grid = document.getElementById('section-media-grid');
    const search = document.getElementById('section-media-search');
    const backdrop = modal.querySelector('.media-modal-backdrop');
    const closeBtn = modal.querySelector('.media-modal-close');
    let allFiles = [];

    document.addEventListener('click', (e) => {
        const pickBtn = e.target.closest('.btn-pick-image');
        if (pickBtn) {
            const picker = pickBtn.closest('.section-image-picker');
            const input = picker.querySelector('.section-image-input');
            imgCallback = function(file) {
                input.value = file;
                // Update preview
                let preview = picker.querySelector('img');
                if (!preview) {
                    const div = document.createElement('div');
                    div.style.marginTop = '0.3rem';
                    div.innerHTML = '<img src="/uploads/' + file + '" alt="" style="max-width:120px;max-height:80px;border-radius:4px;border:1px solid #e2e8f0" loading="lazy">';
                    picker.appendChild(div);
                } else {
                    preview.src = '/uploads/' + file;
                }
            };
            openModal();
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

    function closeModal() { modal.style.display = 'none'; imgCallback = null; }

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
                if (imgCallback) imgCallback(thumb.dataset.file);
                closeModal();
            });
        });
    }
})();
</script>
