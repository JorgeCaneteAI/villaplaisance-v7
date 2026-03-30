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
<section class="section section-reviews" id="avis">
    <div class="container">
        <div class="reviews-header">
            <h2><?= htmlspecialchars($heading) ?></h2>
        </div>
        <div class="postcard-carousel">
            <div class="postcard-track">
                <?php foreach ($reviews as $i => $review): ?>
                <blockquote class="review-card postcard<?= $i === 0 ? ' active' : '' ?>">
                    <div class="postcard-inner">
                        <div class="postcard-message">
                            <p class="review-content"><?= preg_replace('/\b(Jorge|Georges|George)\b/iu', '<a href="/votre-hote" class="host-link"><strong>$1</strong></a>', htmlspecialchars($review['content'])) ?></p>
                        </div>
                        <div class="postcard-divider"></div>
                        <div class="postcard-address">
                            <div class="postcard-stamp">
                                <?php
                                $platformIconMap = [
                                    'airbnb' => 'icon-airbnb', 'booking' => 'icon-booking',
                                    'booking.com' => 'icon-booking', 'google' => 'icon-google',
                                ];
                                $pf = mb_strtolower(trim($review['platform'] ?? ''));
                                $pfIcon = $platformIconMap[$pf] ?? null;
                                ?>
                                <span class="review-platform"><?php if ($pfIcon): ?><?= ImageService::icon($pfIcon, 16, 'platform-icon') ?><?php endif; ?><?= htmlspecialchars($review['platform'] ?? '') ?></span>
                                <span class="review-rating" aria-label="Note : <?= $review['rating'] ?>/5">
                                    <?php for ($j = 0; $j < min((int)$review['rating'], 5); $j++): ?><?= ImageService::icon('icon-etoile-pleine', 12, 'star-icon') ?><?php endfor; ?>
                                </span>
                            </div>
                            <div class="postcard-dest">
                                <cite class="review-author"><?= htmlspecialchars($review['author']) ?></cite>
                                <?php if (!empty($review['origin'])): ?>
                                <span class="review-origin"><?= htmlspecialchars($review['origin']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </blockquote>
                <?php endforeach; ?>
            </div>
            <div class="postcard-nav">
                <button class="postcard-prev" aria-label="Précédent">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <span class="postcard-counter"><span class="postcard-current">1</span> / <?= count($reviews) ?></span>
                <button class="postcard-next" aria-label="Suivant">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>
