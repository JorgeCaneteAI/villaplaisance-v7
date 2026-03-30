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
<section class="section section-alt" id="chiffres">
    <div class="container">
        <div class="stats-grid" data-animate="counters">
            <?php foreach ($items as $stat): ?>
            <div class="stat-item">
                <?php if (!empty($stat['icon'])): ?>
                <span class="stat-icon"><?= ImageService::icon($stat['icon'], 32) ?></span>
                <?php endif; ?>
                <span class="stat-value" data-count="<?= htmlspecialchars($stat['value']) ?>"><?= htmlspecialchars($stat['value']) ?></span>
                <span class="stat-label"><?= htmlspecialchars($stat['label']) ?></span>
                <?php if (!empty($stat['sublabel'])): ?>
                <span class="stat-sublabel"><?= htmlspecialchars($stat['sublabel']) ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
