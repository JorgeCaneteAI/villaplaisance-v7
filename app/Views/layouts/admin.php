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
    <link rel="stylesheet" href="/assets/css/admin.css?v=<?= filemtime(ROOT . '/public/assets/css/admin.css') ?>">
</head>
<body class="admin-body <?= htmlspecialchars($body_class ?? '') ?>">
    <header class="admin-topbar">
        <a href="/admin" class="topbar-logo">VP Admin</a>
        <nav class="topbar-nav">
            <a href="/admin/dashboard" class="topbar-link">Dashboard</a>
            <a href="/admin/calendrier" class="topbar-link <?= str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/admin/calendrier') ? 'active' : '' ?>">Calendrier</a>
            <a href="/admin/analytics" class="topbar-link">Statistiques</a>

            <div class="topbar-group">
                <button class="topbar-group-btn">Contenu ▾</button>
                <div class="topbar-dropdown">
                    <a href="/admin/pages" class="topbar-dd-link">Pages CMS</a>
                    <a href="/admin/articles" class="topbar-dd-link">Articles</a>
                    <a href="/admin/pieces" class="topbar-dd-link">Chambres</a>
                    <a href="/admin/host" class="topbar-dd-link">Votre hôte</a>
                    <a href="/admin/livret" class="topbar-dd-link">Livret</a>
                    <a href="/admin/itineraires" class="topbar-dd-link">Itinéraires</a>
                </div>
            </div>

            <div class="topbar-group">
                <button class="topbar-group-btn">Interactions ▾</button>
                <div class="topbar-dropdown">
                    <a href="/admin/messages" class="topbar-dd-link">Messages</a>
                    <a href="/admin/avis" class="topbar-dd-link">Avis</a>
                </div>
            </div>

            <div class="topbar-group">
                <button class="topbar-group-btn">Médias & SEO ▾</button>
                <div class="topbar-dropdown">
                    <a href="/admin/media" class="topbar-dd-link">Médias</a>
                    <a href="/admin/seo-files" class="topbar-dd-link">SEO</a>
                    <a href="/admin/redirects" class="topbar-dd-link">Redirections</a>
                </div>
            </div>

            <a href="/admin/reglages" class="topbar-link">Réglages</a>
        </nav>
        <div class="topbar-right">
            <a href="/" class="topbar-link" target="_blank">Voir le site</a>
            <span class="topbar-user"><?= htmlspecialchars($_SESSION['admin_user_name'] ?? 'Admin') ?></span>
            <a href="/admin/logout" class="topbar-link topbar-link-danger">Déconnexion</a>
        </div>
    </header>

    <main class="admin-main">
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

</body>
</html>
