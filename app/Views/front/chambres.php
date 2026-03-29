<?php
// Render all CMS sections for this page
$sections = BlockService::getSections('chambres-d-hotes', $lang);
foreach ($sections as $section) {
    echo BlockService::renderBlock($section);
}
