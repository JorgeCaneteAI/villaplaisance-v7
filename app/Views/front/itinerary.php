<?php
declare(strict_types=1);
$date = $itinerary['itinerary_date'] ? date('d/m/Y', strtotime($itinerary['itinerary_date'])) : '';
$dayNames = ['Sunday'=>'Dimanche','Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi','Thursday'=>'Jeudi','Friday'=>'Vendredi','Saturday'=>'Samedi'];
$dayName = $itinerary['itinerary_date'] ? ($dayNames[date('l', strtotime($itinerary['itinerary_date']))] ?? '') : '';
?>

<style>
.itinerary-page {
    max-width: 640px;
    margin: 0 auto;
    padding: 2rem 1.25rem 3rem;
}
.itinerary-header {
    text-align: center;
    margin-bottom: 2.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e8e0d8;
}
.itinerary-header h1 {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.8rem;
    font-weight: 500;
    color: #2c3e50;
    margin: 0 0 0.5rem;
    line-height: 1.3;
}
.itinerary-date {
    font-size: 0.9rem;
    color: #8B7355;
    font-weight: 500;
    margin-bottom: 1rem;
}
.itinerary-intro {
    font-size: 0.95rem;
    color: #666;
    line-height: 1.6;
    font-style: italic;
}

/* Timeline */
.itinerary-timeline {
    position: relative;
    padding-left: 2rem;
}
.itinerary-timeline::before {
    content: '';
    position: absolute;
    left: 6px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: linear-gradient(to bottom, #C5B9A8, #e8e0d8);
}
.itinerary-step {
    position: relative;
    margin-bottom: 2rem;
    padding-bottom: 0.5rem;
}
.itinerary-step:last-child {
    margin-bottom: 0;
}
.itinerary-step::before {
    content: '';
    position: absolute;
    left: -2rem;
    top: 6px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #8B7355;
    z-index: 1;
}
.itinerary-step:first-child::before {
    background: #8B7355;
    border-color: #8B7355;
}
.itinerary-step:last-child::before {
    background: #C5B9A8;
    border-color: #C5B9A8;
}
.step-time {
    font-size: 0.8rem;
    font-weight: 700;
    color: #8B7355;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    margin-bottom: 0.2rem;
}
.step-title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.15rem;
    line-height: 1.3;
}
.step-duration {
    font-size: 0.78rem;
    color: #aaa;
    margin-bottom: 0.4rem;
}
.step-desc {
    font-size: 0.9rem;
    color: #555;
    line-height: 1.6;
}

/* Footer */
.itinerary-footer {
    text-align: center;
    margin-top: 3rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e8e0d8;
    color: #aaa;
    font-size: 0.8rem;
}
.itinerary-footer a {
    color: #8B7355;
    text-decoration: none;
}

/* Mobile */
@media (max-width: 480px) {
    .itinerary-page { padding: 1.5rem 1rem 2rem; }
    .itinerary-header h1 { font-size: 1.5rem; }
    .step-title { font-size: 1.1rem; }
}
</style>

<div class="itinerary-page">

    <header class="itinerary-header">
        <h1>Votre itinéraire, <?= htmlspecialchars($itinerary['guest_name']) ?></h1>
        <?php if ($date): ?>
        <div class="itinerary-date"><?= $dayName ?> <?= $date ?></div>
        <?php endif; ?>
        <?php if (!empty($itinerary['intro_text'])): ?>
        <p class="itinerary-intro"><?= nl2br(htmlspecialchars($itinerary['intro_text'])) ?></p>
        <?php endif; ?>
    </header>

    <div class="itinerary-timeline">
        <?php foreach ($steps as $step): ?>
        <div class="itinerary-step">
            <?php if (!empty($step['time_label'])): ?>
            <div class="step-time"><?= htmlspecialchars($step['time_label']) ?></div>
            <?php endif; ?>
            <div class="step-title"><?= htmlspecialchars($step['title']) ?></div>
            <?php if (!empty($step['duration'])): ?>
            <div class="step-duration"><?= htmlspecialchars($step['duration']) ?></div>
            <?php endif; ?>
            <?php if (!empty($step['description'])): ?>
            <p class="step-desc"><?= nl2br(htmlspecialchars($step['description'])) ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <footer class="itinerary-footer">
        Préparé avec soin par <a href="<?= APP_URL ?>">Villa Plaisance</a><br>
        Chambres d'hôtes &amp; villa — Bédarrides, Provence
    </footer>

</div>
