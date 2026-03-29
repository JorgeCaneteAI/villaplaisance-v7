<?php
$heading = $heading ?? 'Questions fréquentes';
$page_slug = $page_slug ?? ($section['page_slug'] ?? 'accueil');
$lang = LangService::get();

$faqs = [];
try {
    $faqs = Database::fetchAll(
        "SELECT question, answer FROM vp_faq WHERE page_slug = ? AND lang = ? AND active = 1 ORDER BY position ASC",
        [$page_slug, $lang]
    );
} catch (\Throwable) {}
if (empty($faqs)) return;
?>
<section class="section section-alt" id="faq">
    <div class="container">
        <h2><?= htmlspecialchars($heading) ?></h2>
        <div class="faq-list">
            <?php foreach ($faqs as $faq): ?>
            <details class="faq-item">
                <summary class="faq-question"><?= htmlspecialchars($faq['question']) ?></summary>
                <div class="faq-answer"><p><?= htmlspecialchars($faq['answer']) ?></p></div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
