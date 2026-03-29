<section class="section">
    <div class="container container-narrow">
        <h1><?= t('footer.plan') ?></h1>
        <nav aria-label="Plan du site">
            <ul class="sitemap-list">
                <li><a href="<?= LangService::url('accueil') ?>"><?= t('nav.home') ?></a></li>
                <li>
                    <a href="<?= LangService::url('chambres-d-hotes') ?>"><?= t('nav.chambres') ?></a>
                </li>
                <li>
                    <a href="<?= LangService::url('location-villa-provence') ?>"><?= t('nav.villa') ?></a>
                </li>
                <li><a href="<?= LangService::url('espaces-exterieurs') ?>"><?= t('nav.exterieurs') ?></a></li>
                <li>
                    <a href="<?= LangService::url('journal') ?>"><?= t('nav.journal') ?></a>
                </li>
                <li>
                    <a href="<?= LangService::url('sur-place') ?>"><?= t('nav.surplace') ?></a>
                </li>
                <li><a href="<?= LangService::url('contact') ?>"><?= t('nav.contact') ?></a></li>
                <li><a href="<?= LangService::url('mentions-legales') ?>"><?= t('footer.mentions') ?></a></li>
                <li><a href="<?= LangService::url('politique-confidentialite') ?>"><?= t('footer.confidentialite') ?></a></li>
            </ul>
        </nav>
    </div>
</section>
