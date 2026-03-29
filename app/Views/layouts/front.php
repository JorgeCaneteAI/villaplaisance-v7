<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= htmlspecialchars($seo['title'] ?? 'Villa Plaisance') ?></title>
    <meta name="description" content="<?= htmlspecialchars($seo['description'] ?? '') ?>">

    <!-- Canonical -->
    <link rel="canonical" href="<?= htmlspecialchars($seo['canonical'] ?? '') ?>">

    <!-- Hreflang -->
    <?php foreach (($seo['hreflang'] ?? []) as $hl): ?>
    <link rel="alternate" hreflang="<?= $hl['lang'] ?>" href="<?= htmlspecialchars($hl['url']) ?>">
    <?php endforeach; ?>

    <!-- Open Graph -->
    <?php if (!empty($seo['og'])): ?>
    <meta property="og:title" content="<?= htmlspecialchars($seo['og']['title'] ?? '') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($seo['og']['description'] ?? '') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($seo['og']['image'] ?? '') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($seo['og']['url'] ?? '') ?>">
    <meta property="og:type" content="<?= htmlspecialchars($seo['og']['type'] ?? 'website') ?>">
    <meta property="og:locale" content="<?= htmlspecialchars($seo['og']['locale'] ?? 'fr_FR') ?>">
    <meta property="og:site_name" content="Villa Plaisance">
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($seo['og']['title'] ?? $seo['title'] ?? '') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($seo['og']['description'] ?? $seo['description'] ?? '') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($seo['og']['image'] ?? '') ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- JSON-LD -->
    <?php foreach (($jsonLd ?? []) as $ld): ?>
    <script type="application/ld+json"><?= json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?></script>
    <?php endforeach; ?>
</head>
<body>
    <!-- SVG Sprite (hidden) -->
    <?php
    $spriteFile = ROOT . '/public/assets/img/icons.svg';
    if (file_exists($spriteFile)) {
        echo '<div style="display:none" aria-hidden="true">';
        readfile($spriteFile);
        echo '</div>';
    }
    ?>

    <!-- Skip to content -->
    <a href="#main-content" class="skip-link">Aller au contenu</a>

    <!-- Header -->
    <header class="site-header" role="banner">
        <div class="container header-inner">
            <a href="<?= LangService::url('accueil') ?>" class="logo" aria-label="Villa Plaisance — Accueil">
                <img src="/assets/img/logo.svg" alt="Villa Plaisance" class="logo-img" width="44" height="44">
                <span class="logo-text">Villa Plaisance</span>
            </a>

            <nav class="main-nav" role="navigation" aria-label="Navigation principale">
                <button class="nav-toggle" aria-expanded="false" aria-controls="nav-menu" aria-label="Menu">
                    <span class="nav-toggle-bar"></span>
                    <span class="nav-toggle-bar"></span>
                    <span class="nav-toggle-bar"></span>
                </button>
                <ul id="nav-menu" class="nav-list">
                    <li class="nav-close-wrap"><button class="nav-close" aria-label="Fermer le menu"></button></li>
                    <li><a href="<?= LangService::url('chambres-d-hotes') ?>"><?= t('nav.chambres') ?></a></li>
                    <li><a href="<?= LangService::url('location-villa-provence') ?>"><?= t('nav.villa') ?></a></li>
                    <li><a href="<?= LangService::url('espaces-exterieurs') ?>"><?= t('nav.exterieurs') ?></a></li>
                    <li><a href="<?= LangService::url('journal') ?>"><?= t('nav.journal') ?></a></li>
                    <li><a href="<?= LangService::url('sur-place') ?>"><?= t('nav.surplace') ?></a></li>
                    <li><a href="<?= LangService::url('contact') ?>"><?= t('nav.contact') ?></a></li>
                </ul>
            </nav>

            <!-- Language switcher -->
            <div class="lang-switcher" aria-label="Changer de langue">
                <?php
                $currentPage = 'accueil'; // Default, should be passed from controller
                foreach (LangService::getAllLangs() as $l):
                    $active = ($l === $lang) ? ' class="active" aria-current="true"' : '';
                ?>
                <a href="<?= $l === 'fr' ? '/' : '/' . $l . '/' ?>"<?= $active ?>><?= strtoupper($l) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main id="main-content">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="site-footer" role="contentinfo">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <p class="footer-brand">Villa Plaisance</p>
                    <p class="footer-location">
                        <?= ImageService::icon('icon-localisation', 16, 'footer-icon') ?>
                        Bédarrides, Vaucluse 84370<br>Provence, France
                    </p>
                    <?php
                    $socialLinks = [];
                    try { $socialLinks = Database::fetchAll("SELECT * FROM vp_social_links ORDER BY position ASC"); } catch (\Throwable) {}
                    if ($socialLinks): ?>
                    <div class="footer-social">
                        <?php foreach ($socialLinks as $sl):
                            $slIcon = 'icon-' . htmlspecialchars($sl['icon'] ?? 'lien-externe');
                        ?>
                        <a href="<?= htmlspecialchars($sl['url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= htmlspecialchars($sl['name']) ?>">
                            <?= ImageService::icon($slIcon, 20) ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="footer-col">
                    <nav aria-label="Navigation du pied de page">
                        <ul>
                            <li><a href="<?= LangService::url('chambres-d-hotes') ?>"><?= t('nav.chambres') ?></a></li>
                            <li><a href="<?= LangService::url('location-villa-provence') ?>"><?= t('nav.villa') ?></a></li>
                            <li><a href="<?= LangService::url('journal') ?>"><?= t('nav.journal') ?></a></li>
                            <li><a href="<?= LangService::url('contact') ?>"><?= t('nav.contact') ?></a></li>
                        </ul>
                    </nav>
                </div>
                <div class="footer-col">
                    <nav aria-label="Informations légales">
                        <ul>
                            <li><a href="<?= LangService::url('mentions-legales') ?>"><?= t('footer.mentions') ?></a></li>
                            <li><a href="<?= LangService::url('politique-confidentialite') ?>"><?= t('footer.confidentialite') ?></a></li>
                            <li><a href="<?= LangService::url('plan-du-site') ?>"><?= t('footer.plan') ?></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <p class="footer-copy"><?= t('footer.rights', ['year' => date('Y')]) ?></p>
        </div>
    </footer>

    <script src="/assets/js/main.js" defer></script>
</body>
</html>
