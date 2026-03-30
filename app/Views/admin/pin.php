<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Code PIN — Villa Plaisance Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
    .pin-inputs { display: flex; gap: 0.75rem; justify-content: center; margin: 1.5rem 0; }
    .pin-inputs input {
        width: 52px; height: 62px; text-align: center;
        font-size: 1.5rem; font-weight: 600; letter-spacing: 0;
        border: 2px solid var(--admin-border); border-radius: 8px;
        background: #f8f9fb; transition: border-color 0.2s;
    }
    .pin-inputs input:focus { border-color: var(--admin-accent); outline: none; background: #fff; }
    .pin-subtitle { text-align: center; color: #888; font-size: 0.85rem; margin-bottom: 0.5rem; }
    .pin-lock { text-align: center; margin-bottom: 1rem; }
    .pin-lock svg { width: 40px; height: 40px; color: var(--admin-accent); }
    </style>
</head>
<body class="login-page">
    <div class="login-card">
        <div class="pin-lock">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><circle cx="12" cy="16" r="1"/></svg>
        </div>
        <h1 style="font-size:1.1rem;text-align:center">Vérification de sécurité</h1>
        <p class="pin-subtitle">Saisissez votre code PIN à 6 chiffres</p>

        <?php if (!empty($flash['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($flash['error']) ?></div>
        <?php endif; ?>

        <form method="POST" action="/admin/pin">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <input type="hidden" name="pin" id="pin-hidden" value="">

            <div class="pin-inputs">
                <input type="password" inputmode="numeric" maxlength="1" pattern="[0-9]" class="pin-digit" data-index="0" autofocus>
                <input type="password" inputmode="numeric" maxlength="1" pattern="[0-9]" class="pin-digit" data-index="1">
                <input type="password" inputmode="numeric" maxlength="1" pattern="[0-9]" class="pin-digit" data-index="2">
                <input type="password" inputmode="numeric" maxlength="1" pattern="[0-9]" class="pin-digit" data-index="3">
                <input type="password" inputmode="numeric" maxlength="1" pattern="[0-9]" class="pin-digit" data-index="4">
                <input type="password" inputmode="numeric" maxlength="1" pattern="[0-9]" class="pin-digit" data-index="5">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%">Valider</button>
        </form>

        <p style="text-align:center;margin-top:1rem"><a href="/admin/logout" style="color:#888;font-size:0.8rem">Annuler</a></p>
    </div>

    <script>
    const digits = document.querySelectorAll('.pin-digit');
    const hidden = document.getElementById('pin-hidden');

    digits.forEach((input, i) => {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value && i < 5) digits[i + 1].focus();
            updateHidden();
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && i > 0) {
                digits[i - 1].focus();
                digits[i - 1].value = '';
                updateHidden();
            }
        });
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const paste = (e.clipboardData.getData('text') || '').replace(/[^0-9]/g, '').slice(0, 6);
            paste.split('').forEach((c, j) => { if (digits[j]) digits[j].value = c; });
            if (paste.length > 0) digits[Math.min(paste.length, 5)].focus();
            updateHidden();
        });
    });

    function updateHidden() {
        hidden.value = Array.from(digits).map(d => d.value).join('');
        if (hidden.value.length === 6) {
            document.querySelector('form').submit();
        }
    }
    </script>
</body>
</html>
