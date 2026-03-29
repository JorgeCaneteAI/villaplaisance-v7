<?php
$heading = $heading ?? t('home.avis.title');
$limit = $limit ?? 4;
$offer_filter = $offer_filter ?? '';

$reviews = [];
try {
    $sql = "SELECT * FROM vp_reviews WHERE status = 'published' AND featured = 1";
    $params = [];
    if ($offer_filter) {
        $sql .= " AND offer = ?";
        $params[] = $offer_filter;
    }
    $sql .= " ORDER BY review_date DESC LIMIT " . (int)$limit;
    $reviews = Database::fetchAll($sql, $params);
} catch (\Throwable) {}
if (empty($reviews)) return;
?>
<section class="section" id="avis">
    <div class="container">
        <h2><?= htmlspecialchars($heading) ?></h2>
        <div class="reviews-grid">
            <?php foreach ($reviews as $review): ?>
            <blockquote class="review-card">
                <p class="review-content"><?= htmlspecialchars($review['content']) ?></p>
                <footer class="review-footer">
                    <cite class="review-author"><?= htmlspecialchars($review['author']) ?></cite>
                    <?php if (!empty($review['origin'])): ?>
                    <span class="review-origin"><?= htmlspecialchars($review['origin']) ?></span>
                    <?php endif; ?>
                    <?php
                    $platformIconMap = [
                        'airbnb' => 'icon-airbnb', 'booking' => 'icon-booking',
                        'booking.com' => 'icon-booking', 'google' => 'icon-google',
                    ];
                    $pf = mb_strtolower(trim($review['platform'] ?? ''));
                    $pfIcon = $platformIconMap[$pf] ?? null;
                    ?>
                    <span class="review-platform"><?php if ($pfIcon): ?><?= ImageService::icon($pfIcon, 18, 'platform-icon') ?><?php endif; ?><?= htmlspecialchars($review['platform'] ?? '') ?></span>
                    <span class="review-rating" aria-label="Note : <?= $review['rating'] ?>/5">
                        <?php for ($i = 0; $i < min((int)$review['rating'], 5); $i++): ?><?= ImageService::icon('icon-etoile-pleine', 14, 'star-icon') ?><?php endfor; ?>
                    </span>
                </footer>
            </blockquote>
            <?php endforeach; ?>
        </div>
    </div>
</section>
