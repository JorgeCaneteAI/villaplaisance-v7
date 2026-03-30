<!-- Breadcrumb -->
<nav class="breadcrumb" aria-label="Fil d'Ariane">
    <div class="container">
        <ol>
            <li><a href="<?= LangService::url('accueil') ?>"><?= t('nav.home') ?></a></li>
            <li aria-current="page"><?= t('nav.exterieurs') ?></li>
        </ol>
    </div>
</nav>

<?php
// Render all CMS sections for this page
$sections = BlockService::getSections('espaces-exterieurs', $lang);
foreach ($sections as $section) {
    echo BlockService::renderBlock($section);
}
