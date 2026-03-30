<?php
$heading = $heading ?? t('home.cta.title');
$text = $text ?? '';
$button_text = $button_text ?? t('home.cta.button');
$button_url = $button_url ?? LangService::url('contact');
$dark = $dark ?? true;
?>
<section class="section <?= $dark ? 'section-cta' : '' ?>">
    <div class="container">
        <div class="cta-layout">
            <h2><?= htmlspecialchars($heading) ?></h2>
            <div class="cta-action">
                <?php if ($text): ?>
                <p><?= htmlspecialchars($text) ?></p>
                <?php endif; ?>
                <a href="<?= htmlspecialchars($button_url) ?>" class="btn-primary<?= $dark ? ' btn-invert' : '' ?>"><?= htmlspecialchars($button_text) ?></a>
            </div>
        </div>
    </div>
</section>
