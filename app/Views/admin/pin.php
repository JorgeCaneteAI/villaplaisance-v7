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
    .pin-display { display: flex; gap: 0.75rem; justify-content: center; margin: 1.5rem 0; }
    .pin-dot {
        width: 18px; height: 18px; border-radius: 50%;
        border: 2px solid var(--admin-border); background: #f8f9fb;
        transition: background 0.15s, border-color 0.15s;
    }
    .pin-dot.filled { background: var(--admin-accent); border-color: var(--admin-accent); }
    .pin-subtitle { text-align: center; color: #888; font-size: 0.85rem; margin-bottom: 0.5rem; }
    .pin-lock { text-align: center; margin-bottom: 1rem; }
    .pin-lock svg { width: 40px; height: 40px; color: var(--admin-accent); }
    .pin-keypad { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; max-width: 280px; margin: 1.5rem auto; }
    .pin-key {
        width: 100%; aspect-ratio: 1.4; border: 1px solid var(--admin-border);
        border-radius: 10px; background: #fff; font-size: 1.5rem; font-weight: 600;
        cursor: pointer; transition: background 0.1s, transform 0.1s;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Inter', sans-serif; color: #333;
    }
    .pin-key:hover { background: #f0f0f0; }
    .pin-key:active { background: #e0e0e0; transform: scale(0.95); }
    .pin-key.backspace { font-size: 1.2rem; color: #888; }
    .pin-key.empty { visibility: hidden; border: none; }
    .pin-error-shake { animation: shake 0.4s; }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        75% { transform: translateX(8px); }
    }
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

        <form method="POST" action="/admin/pin" id="pin-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <input type="hidden" name="pin" id="pin-hidden" value="">

            <div class="pin-display" id="pin-dots">
                <div class="pin-dot"></div>
                <div class="pin-dot"></div>
                <div class="pin-dot"></div>
                <div class="pin-dot"></div>
                <div class="pin-dot"></div>
                <div class="pin-dot"></div>
            </div>

            <div class="pin-keypad">
                <button type="button" class="pin-key" data-val="1">1</button>
                <button type="button" class="pin-key" data-val="2">2</button>
                <button type="button" class="pin-key" data-val="3">3</button>
                <button type="button" class="pin-key" data-val="4">4</button>
                <button type="button" class="pin-key" data-val="5">5</button>
                <button type="button" class="pin-key" data-val="6">6</button>
                <button type="button" class="pin-key" data-val="7">7</button>
                <button type="button" class="pin-key" data-val="8">8</button>
                <button type="button" class="pin-key" data-val="9">9</button>
                <button type="button" class="pin-key empty"></button>
                <button type="button" class="pin-key" data-val="0">0</button>
                <button type="button" class="pin-key backspace" data-val="back">&#9003;</button>
            </div>
        </form>

        <p style="text-align:center;margin-top:1rem"><a href="/admin/logout" style="color:#888;font-size:0.8rem">Annuler</a></p>
    </div>

    <script>
    const dots = document.querySelectorAll('.pin-dot');
    const hidden = document.getElementById('pin-hidden');
    const form = document.getElementById('pin-form');
    let pin = '';

    document.querySelectorAll('.pin-key').forEach(btn => {
        btn.addEventListener('click', () => {
            const val = btn.dataset.val;
            if (!val) return;

            if (val === 'back') {
                pin = pin.slice(0, -1);
            } else if (pin.length < 6) {
                pin += val;
            }

            updateDots();

            if (pin.length === 6) {
                hidden.value = pin;
                setTimeout(() => form.submit(), 150);
            }
        });
    });

    // Support clavier physique aussi
    document.addEventListener('keydown', (e) => {
        if (e.key >= '0' && e.key <= '9' && pin.length < 6) {
            pin += e.key;
            updateDots();
            if (pin.length === 6) {
                hidden.value = pin;
                setTimeout(() => form.submit(), 150);
            }
        } else if (e.key === 'Backspace') {
            pin = pin.slice(0, -1);
            updateDots();
        }
    });

    function updateDots() {
        dots.forEach((dot, i) => {
            dot.classList.toggle('filled', i < pin.length);
        });
    }
    </script>
</body>
</html>
