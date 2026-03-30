<?php
// Fetch stats from DB for current language
$items = [];
try {
    $currentLang = LangService::get();
    $items = Database::fetchAll("SELECT * FROM vp_stats WHERE lang = ? ORDER BY position ASC", [$currentLang]);
} catch (\Throwable) {
    $items = $items ?? [];
}
if (empty($items)) return;
?>
<section class="section" id="chiffres">
    <div class="container container-wide">
        <div class="stats-grid" data-animate="counters">
            <?php foreach ($items as $i => $stat): ?>
            <div class="stat-item">
                <span class="stat-number"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                <span class="stat-value" data-count="<?= htmlspecialchars($stat['value']) ?>"><?= htmlspecialchars($stat['value']) ?></span>
                <span class="stat-label"><?= htmlspecialchars($stat['label']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
