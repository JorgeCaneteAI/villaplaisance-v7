<?php
// Render hero from CMS
$heroSections = BlockService::getSections('contact', $lang);
foreach ($heroSections as $section) {
    echo BlockService::renderBlock($section);
}
?>

<!-- Formulaire (non géré par bloc — logique spécifique) -->
<section class="section">
    <div class="container container-narrow">
        <?php if (!empty($flash['success'])): ?>
        <div class="alert alert-success" role="alert"><?= htmlspecialchars($flash['success']) ?></div>
        <?php endif; ?>
        <?php if (!empty($flash['error'])): ?>
        <div class="alert alert-error" role="alert"><?= htmlspecialchars($flash['error']) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= LangService::url('contact') ?>" class="contact-form" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

            <!-- Honeypot -->
            <div style="position:absolute;left:-9999px" aria-hidden="true">
                <label for="website">Ne pas remplir</label>
                <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="form-group">
                <label for="name"><?= t('contact.form.name') ?> *</label>
                <input type="text" id="name" name="name" required autocomplete="name">
            </div>

            <div class="form-group">
                <label for="email"><?= t('contact.form.email') ?> *</label>
                <input type="email" id="email" name="email" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="subject"><?= t('contact.form.subject') ?></label>
                <input type="text" id="subject" name="subject">
            </div>

            <div class="form-group">
                <label for="message"><?= t('contact.form.message') ?> *</label>
                <textarea id="message" name="message" rows="6" required></textarea>
            </div>

            <button type="submit" class="btn-primary"><?= t('contact.form.send') ?></button>
        </form>
    </div>
</section>
