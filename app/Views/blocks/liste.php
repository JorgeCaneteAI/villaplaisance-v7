<?php
$heading = $heading ?? '';
$items = $items ?? [];
$style = $style ?? 'check'; // check, bullet, none
if (empty($items)) return;
?>
<section class="section">
    <div class="container">
        <?php if ($heading): ?>
        <h2><?= htmlspecialchars($heading) ?></h2>
        <?php endif; ?>
        <ul class="block-list block-list-<?= htmlspecialchars($style) ?>">
            <?php foreach ($items as $item): ?>
            <li><?= htmlspecialchars(is_array($item) ? ($item['text'] ?? '') : $item) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
