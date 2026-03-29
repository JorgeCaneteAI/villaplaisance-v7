<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mot de passe oublié — Villa Plaisance Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="login-page">
    <div class="login-card">
        <h1>Villa Plaisance</h1>
        <p class="login-subtitle">Réinitialisation du mot de passe</p>

        <?php if (!empty($flash['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($flash['error']) ?></div>
        <?php endif; ?>

        <?php if (!empty($flash['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flash['success']) ?></div>
        <?php endif; ?>

        <form method="POST" action="/admin/forgot-password">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

            <div class="form-group">
                <label for="email">Adresse email du compte</label>
                <input type="email" id="email" name="email" required autocomplete="email" autofocus>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%">Envoyer le lien</button>

            <p class="forgot-link"><a href="/admin/login">&larr; Retour à la connexion</a></p>
        </form>
    </div>
</body>
</html>
