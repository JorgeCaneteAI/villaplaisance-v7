<?php
$heading = $heading ?? '';
$rows = $rows ?? [];
if (empty($rows)) return;
?>
<section class="section">
    <div class="container">
        <?php if ($heading): ?>
        <h2><?= htmlspecialchars($heading) ?></h2>
        <?php endif; ?>
        <table class="block-table">
            <?php foreach ($rows as $row): ?>
            <tr>
                <th><?= htmlspecialchars($row['label'] ?? '') ?></th>
                <td><?= htmlspecialchars($row['value'] ?? '') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>
