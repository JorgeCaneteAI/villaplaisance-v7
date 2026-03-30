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
    <header class="admin-topbar">
        <a href="/admin" class="topbar-logo">VP Admin</a>
        <nav class="topbar-nav">
            <a href="/admin/dashboard" class="topbar-link">Dashboard</a>
            <a href="/admin/articles" class="topbar-link">Articles</a>
            <a href="/admin/messages" class="topbar-link">Messages</a>
            <a href="/admin/avis" class="topbar-link">Avis</a>
            <a href="/admin/livret" class="topbar-link">Livret</a>
            <a href="/admin/media" class="topbar-link">Médias</a>
            <a href="/admin/pages" class="topbar-link">Pages CMS</a>
            <a href="/admin/pieces" class="topbar-link">Chambres</a>
            <a href="/admin/redirects" class="topbar-link">Redirections</a>
            <a href="/admin/seo-files" class="topbar-link">SEO</a>
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
