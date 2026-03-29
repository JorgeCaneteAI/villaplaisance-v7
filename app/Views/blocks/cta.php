<?php
$heading = $heading ?? t('home.cta.title');
$text = $text ?? '';
$button_text = $button_text ?? t('home.cta.button');
$button_url = $button_url ?? LangService::url('contact');
$dark = $dark ?? true;
?>
<section class="section <?= $dark ? 'section-cta' : '' ?>">
    <div class="container text-center">
        <h2><?= htmlspecialchars($heading) ?></h2>
        <?php if ($text): ?>
        <p><?= htmlspecialchars($text) ?></p>
        <?php endif; ?>
        <a href="<?= htmlspecialchars($button_url) ?>" class="btn-primary<?= $dark ? ' btn-invert' : '' ?>"><?= htmlspecialchars($button_text) ?></a>
    </div>
</section>
