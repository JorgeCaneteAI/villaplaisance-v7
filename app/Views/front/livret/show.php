<section class="section livret-header">
    <div class="container" style="text-align:center">
        <p class="livret-welcome">Bienvenue chez vous</p>
        <h1><?= t('livret.title') ?></h1>
        <div class="livret-type-nav">
            <a href="<?= \LangService::url('livret') ?>?type=bb" class="<?= $type === 'bb' ? 'active' : '' ?>"><?= t('livret.type_bb') ?></a>
            <span>&middot;</span>
            <a href="<?= \LangService::url('livret') ?>?type=villa" class="<?= $type === 'villa' ? 'active' : '' ?>"><?= t('livret.type_villa') ?></a>
        </div>
    </div>
</section>

<?php if (!empty($flash['success'])): ?>
<div class="container container-narrow"><div class="alert alert-success" role="alert"><?= htmlspecialchars($flash['success']) ?></div></div>
<?php endif; ?>
<?php if (!empty($flash['error'])): ?>
<div class="container container-narrow"><div class="alert alert-error" role="alert"><?= htmlspecialchars($flash['error']) ?></div></div>
<?php endif; ?>

<?php if (!empty($sections)): ?>
<div class="container container-narrow">
    <div class="livret-sections">
        <?php foreach ($sections as $i => $section): ?>
        <article class="livret-section">
            <div class="livret-section-number" aria-hidden="true"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></div>
            <h2><?= htmlspecialchars($section['section_title']) ?></h2>
            <div class="livret-content"><?= nl2br(htmlspecialchars($section['content'])) ?></div>
        </article>
        <?php endforeach; ?>
    </div>
</div>
<?php else: ?>
<div class="container container-narrow">
    <p class="text-muted" style="text-align:center;padding:3rem 0"><?= $lang === 'fr' ? 'Contenu à venir.' : ($lang === 'es' ? 'Contenido próximamente.' : 'Content coming soon.') ?></p>
</div>
<?php endif; ?>

<!-- Message form -->
<section class="section livret-message-section">
    <div class="container container-narrow">
        <h2 style="text-align:center"><?= t('livret.message_title') ?></h2>
        <p class="livret-message-intro">Une question, une remarque, un besoin ? On vous répond rapidement.</p>
        <form method="POST" action="<?= \LangService::url('livret') ?>?type=<?= htmlspecialchars($type) ?>" class="contact-form" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <!-- Honeypot -->
            <div style="position:absolute;left:-9999px" aria-hidden="true">
                <label for="website">Ne pas remplir</label>
                <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="name"><?= t('livret.message_name') ?> *</label>
                <input type="text" id="name" name="name" required autocomplete="name">
            </div>
            <div class="form-group">
                <label for="email"><?= t('livret.message_email') ?> *</label>
                <input type="email" id="email" name="email" required autocomplete="email">
            </div>
            <div class="form-group">
                <label for="message"><?= t('livret.message_body') ?> *</label>
                <textarea id="message" name="message" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn-primary"><?= t('livret.message_send') ?></button>
        </form>
    </div>
</section>
