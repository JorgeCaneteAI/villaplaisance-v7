<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — Villa Plaisance</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body <?= htmlspecialchars($body_class ?? '') ?>">
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <a href="/admin" class="sidebar-logo">VP Admin</a>
        </div>
        <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="sidebar-link">Dashboard</a>
            <a href="/admin/articles" class="sidebar-link">Articles</a>
            <a href="/admin/messages" class="sidebar-link">Messages</a>
            <a href="/admin/avis" class="sidebar-link">Avis clients</a>
            <a href="/admin/livret" class="sidebar-link">Livret d'accueil</a>
            <a href="/admin/media" class="sidebar-link">Médiathèque</a>
            <a href="/admin/pages" class="sidebar-link">Pages CMS</a>
            <a href="/admin/pieces" class="sidebar-link">Chambres &amp; Espaces</a>
            <a href="/admin/reglages" class="sidebar-link">Réglages</a>
            <hr class="sidebar-sep">
            <a href="/" class="sidebar-link" target="_blank">Voir le site</a>
            <a href="/admin/logout" class="sidebar-link sidebar-link-danger">Déconnexion</a>
        </nav>
    </aside>

    <main class="admin-main">
        <header class="admin-header">
            <span>Connecté : <?= htmlspecialchars($_SESSION['admin_user_name'] ?? 'Admin') ?></span>
        </header>

        <?php if (!empty($flash['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flash['success']) ?></div>
        <?php endif; ?>
        <?php if (!empty($flash['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($flash['error']) ?></div>
        <?php endif; ?>

        <div class="admin-content">
            <?= $content ?>
        </div>
    </main>

    <?php if (!empty($preview_url)): ?>
    <div class="preview-panel" id="preview-panel">
        <div class="preview-bar">
            <div class="preview-bar-left">
                <span class="preview-label">Prévisualisation</span>
                <span class="preview-page"><?= htmlspecialchars($preview_url) ?></span>
            </div>
            <div class="preview-bar-actions">
                <button type="button" class="preview-btn" id="preview-refresh" title="Rafraîchir">↺</button>
                <button type="button" class="preview-btn" id="preview-scale-toggle" title="Basculer desktop/mobile">⊞</button>
                <a href="<?= htmlspecialchars($preview_url) ?>" target="_blank" class="preview-btn" title="Ouvrir dans un nouvel onglet">↗</a>
            </div>
        </div>
        <div class="preview-viewport" id="preview-viewport">
            <iframe
                src="<?= htmlspecialchars($preview_url) ?>"
                id="preview-iframe"
                title="Prévisualisation de la page"
            ></iframe>
        </div>
    </div>
    <script>
    (function() {
        const iframe = document.getElementById('preview-iframe');
        const viewport = document.getElementById('preview-viewport');
        const refreshBtn = document.getElementById('preview-refresh');
        const scaleBtn = document.getElementById('preview-scale-toggle');
        let desktopMode = false;

        // Auto-refresh after save (flash success présent = on vient de sauvegarder)
        const hasSuccess = document.querySelector('.alert-success');
        if (hasSuccess) {
            iframe.addEventListener('load', function onLoad() {
                iframe.removeEventListener('load', onLoad);
            });
            iframe.src = iframe.src;
        }

        refreshBtn.addEventListener('click', () => {
            iframe.src = iframe.src;
        });

        scaleBtn.addEventListener('click', () => {
            desktopMode = !desktopMode;
            applyScale();
            scaleBtn.title = desktopMode ? 'Mode mobile' : 'Mode desktop';
            scaleBtn.classList.toggle('active', desktopMode);
        });

        function applyScale() {
            if (desktopMode) {
                const panelW = viewport.offsetWidth;
                const scale = panelW / 1280;
                iframe.style.width = '1280px';
                iframe.style.height = (viewport.offsetHeight / scale) + 'px';
                iframe.style.transform = `scale(${scale})`;
                iframe.style.transformOrigin = 'top left';
            } else {
                iframe.style.width = '';
                iframe.style.height = '';
                iframe.style.transform = '';
                iframe.style.transformOrigin = '';
            }
        }

        // Refresh iframe after form submit (via postMessage from save forms)
        document.querySelectorAll('form[action*="/save"], form[action*="/toggle"], form[action*="/move"]').forEach(form => {
            form.addEventListener('submit', () => {
                sessionStorage.setItem('vp_refresh_preview', '1');
            });
        });

        if (sessionStorage.getItem('vp_refresh_preview')) {
            sessionStorage.removeItem('vp_refresh_preview');
            iframe.src = iframe.src;
        }
    })();
    </script>
    <?php endif; ?>

</body>
</html>
