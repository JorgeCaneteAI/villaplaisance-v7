<?php
// Render all CMS sections for this page
$sections = BlockService::getSections('espaces-exterieurs', $lang);
foreach ($sections as $section) {
    echo BlockService::renderBlock($section);
}
