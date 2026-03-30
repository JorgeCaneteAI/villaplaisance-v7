<section class="section livret-gate">
    <div class="container container-narrow" style="text-align:center;padding-top:10vh">
        <h1 class="livret-gate-title"><?= t('livret.title') ?></h1>
        <p class="livret-gate-subtitle">Villa Plaisance — Bédarrides</p>

        <?php if (!empty($flash['error'])): ?>
        <div class="alert alert-error" role="alert"><?= htmlspecialchars($flash['error']) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= \LangService::url('livret') ?>?type=<?= htmlspecialchars($type) ?>" class="livret-gate-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <label for="livret_password" class="sr-only"><?= t('livret.password_prompt') ?></label>
            <input type="password" id="livret_password" name="livret_password"
                   placeholder="<?= t('livret.password_prompt') ?>"
                   class="livret-gate-input" autocomplete="off" autofocus required>
            <button type="submit" class="btn-primary"><?= t('livret.password_submit') ?></button>
        </form>

        <div class="livret-gate-type">
            <a href="<?= \LangService::url('livret') ?>?type=bb" class="<?= $type === 'bb' ? 'active' : '' ?>"><?= t('livret.type_bb') ?></a>
            <span>&middot;</span>
            <a href="<?= \LangService::url('livret') ?>?type=villa" class="<?= $type === 'villa' ? 'active' : '' ?>"><?= t('livret.type_villa') ?></a>
        </div>
    </div>
</section>
