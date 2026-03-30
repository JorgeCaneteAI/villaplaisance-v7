<?php
// Render all CMS sections for this page
$sections = BlockService::getSections('accueil', $lang);
$count = count($sections);
foreach ($sections as $i => $section) {
    echo BlockService::renderBlock($section);

    // After first section (hero): typographic interlude
    if ($i === 0) {
        echo '<div class="typo-interlude" aria-hidden="true">';
        echo '<span class="typo-interlude-text">Bédarrides, Provence</span>';
        echo '</div>';
    }
}
