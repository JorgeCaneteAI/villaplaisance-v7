<?php
// Render all CMS sections for this page
$sections = BlockService::getSections('location-villa-provence', $lang);
foreach ($sections as $section) {
    echo BlockService::renderBlock($section);
}
