<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $ga4Id = \AnalyticsService::getGA4Id(); ?>
    <?php if ($ga4Id): ?>
    <script>
    window.vpGA4Id='<?= htmlspecialchars($ga4Id) ?>';
    function vpLoadGA(){
        if(document.cookie.indexOf('vp_consent=accept')!==-1&&window.vpGA4Id&&!window.vpGALoaded){
            var s=document.createElement('script');s.async=true;
            s.src='https://www.googletagmanager.com/gtag/js?id='+window.vpGA4Id;
            document.head.appendChild(s);
            window.dataLayer=window.dataLayer||[];
            function gtag(){dataLayer.push(arguments);}
            gtag('js',new Date());gtag('config',window.vpGA4Id);
            window.vpGALoaded=true;
        }
    }
    vpLoadGA();
    </script>
    <?php endif; ?>

    <!-- Favicon -->
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <title><?= htmlspecialchars($seo['title'] ?? 'Villa Plaisance') ?></title>
    <meta name="description" content="<?= htmlspecialchars($seo['description'] ?? '') ?>">

    <!-- Canonical -->
    <link rel="canonical" href="<?= htmlspecialchars($seo['canonical'] ?? '') ?>">


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
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Caveat:wght@400;500;600&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= filemtime(ROOT . '/public/assets/css/style.css') ?>">

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
            </a>

            <nav class="main-nav" role="navigation" aria-label="Navigation principale">
                <button class="nav-toggle" aria-expanded="false" aria-controls="nav-menu" aria-label="Menu">
                    <span class="nav-toggle-bar"></span>
                    <span class="nav-toggle-bar"></span>
                    <span class="nav-toggle-bar"></span>
                </button>
                <ul id="nav-menu" class="nav-list">
                    <li class="nav-close-wrap"><button class="nav-close" aria-label="Fermer le menu"></button></li>
                    <li><a href="<?= LangService::url('/') ?>"><?= t('nav.home') ?></a></li>
                    <li><a href="<?= LangService::url('chambres-d-hotes') ?>"><?= t('nav.chambres') ?></a></li>
                    <li><a href="<?= LangService::url('location-villa-provence') ?>"><?= t('nav.villa') ?></a></li>
                    <li><a href="<?= LangService::url('espaces-exterieurs') ?>"><?= t('nav.exterieurs') ?></a></li>
                    <li><a href="<?= LangService::url('journal') ?>"><?= t('nav.journal') ?></a></li>
                    <li><a href="<?= LangService::url('sur-place') ?>"><?= t('nav.surplace') ?></a></li>
                    <li><a href="<?= LangService::url('contact') ?>"><?= t('nav.contact') ?></a></li>
                </ul>
            </nav>

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
        <div class="footer-giant" aria-hidden="true">Villa Plaisance</div>
    </footer>

    <!-- Cookie consent RGPD -->
    <?php if (!isset($_COOKIE['vp_consent'])): ?>
    <div id="cookie-banner" class="cookie-banner" role="dialog" aria-label="Gestion des cookies">
        <div class="cookie-inner">
            <p class="cookie-text">Ce site utilise des cookies pour mesurer l'audience et améliorer votre expérience. Vous pouvez accepter ou refuser leur utilisation.</p>
            <div class="cookie-actions">
                <button id="cookie-refuse" class="cookie-btn cookie-btn-refuse">Refuser</button>
                <button id="cookie-accept" class="cookie-btn cookie-btn-accept">Accepter</button>
            </div>
        </div>
    </div>
    <style>
    .cookie-banner{position:fixed;bottom:0;left:0;right:0;z-index:10000;background:var(--dark);color:#fff;padding:1rem var(--gutter);transform:translateY(0);transition:transform 0.4s ease}
    .cookie-banner.hidden{transform:translateY(100%);pointer-events:none}
    .cookie-inner{max-width:var(--container);margin:0 auto;display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap}
    .cookie-text{flex:1;font-size:0.8rem;line-height:1.5;min-width:250px;color:rgba(255,255,255,0.85)}
    .cookie-actions{display:flex;gap:0.75rem;flex-shrink:0}
    .cookie-btn{padding:0.5rem 1.25rem;border-radius:4px;font-size:0.8rem;font-family:inherit;cursor:pointer;border:none;transition:background 0.2s}
    .cookie-btn-refuse{background:transparent;color:rgba(255,255,255,0.7);border:1px solid rgba(255,255,255,0.3)}
    .cookie-btn-refuse:hover{background:rgba(255,255,255,0.1);color:#fff}
    .cookie-btn-accept{background:var(--accent);color:#fff}
    .cookie-btn-accept:hover{background:#6d8a7b}
    @media(max-width:600px){.cookie-inner{flex-direction:column;text-align:center}.cookie-actions{width:100%;justify-content:center}}
    </style>
    <script>
    (function(){
        var banner=document.getElementById('cookie-banner');
        if(!banner)return;
        function setCookie(v){
            var d=new Date();d.setTime(d.getTime()+180*24*60*60*1000);
            document.cookie='vp_consent='+v+';expires='+d.toUTCString()+';path=/;SameSite=Lax';
        }
        document.getElementById('cookie-accept').addEventListener('click',function(){
            setCookie('accept');banner.classList.add('hidden');
            if(typeof vpLoadGA==='function')vpLoadGA();
        });
        document.getElementById('cookie-refuse').addEventListener('click',function(){
            setCookie('refuse');banner.classList.add('hidden');
        });
    })();
    </script>
    <?php endif; ?>

    <script src="/assets/js/main.js?v=<?= filemtime(ROOT . '/public/assets/js/main.js') ?>" defer></script>
</body>
</html>
