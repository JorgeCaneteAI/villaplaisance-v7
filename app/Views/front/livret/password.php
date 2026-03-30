<section class="section livret-gate">
    <div class="livret-gate-card">
        <div class="livret-gate-icon" aria-hidden="true">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        </div>
        <h1 class="livret-gate-title"><?= t('livret.title') ?></h1>
        <p class="livret-gate-subtitle">Villa Plaisance — Bédarrides</p>
        <p class="livret-gate-hint">Ce livret est réservé à nos hôtes.<br>Saisissez le code communiqué à votre arrivée.</p>

        <?php if (!empty($flash['error'])): ?>
        <div class="alert alert-error" role="alert"><?= htmlspecialchars($flash['error']) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= \LangService::url('livret') ?>?type=<?= htmlspecialchars($type) ?>" class="livret-gate-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <label for="livret_password" class="sr-only"><?= t('livret.password_prompt') ?></label>
            <input type="password" id="livret_password" name="livret_password"
                   placeholder="••••••••"
                   class="livret-gate-input" autocomplete="off" autofocus required>
            <button type="submit" class="btn-primary">Accéder au livret</button>
        </form>

        <div class="livret-gate-type">
            <a href="<?= \LangService::url('livret') ?>?type=bb" class="<?= $type === 'bb' ? 'active' : '' ?>"><?= t('livret.type_bb') ?></a>
            <span>&middot;</span>
            <a href="<?= \LangService::url('livret') ?>?type=villa" class="<?= $type === 'villa' ? 'active' : '' ?>"><?= t('livret.type_villa') ?></a>
        </div>
    </div>
</section>
