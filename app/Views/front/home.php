<?php
// Render all CMS sections for this page
$sections = BlockService::getSections('accueil', $lang);
foreach ($sections as $section) {
    echo BlockService::renderBlock($section);
}
