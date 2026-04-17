<?php
/**
 * Vue : formulaire de saisie/édition d'une réservation.
 * @var ?array $resa
 * @var ?int $id
 * @var array $proprietes
 * @var array $sources
 * @var array $statuts
 */
$csrf = $_SESSION['csrf_token'] ?? ($_SESSION['csrf_token'] = bin2hex(random_bytes(32)));
?>
<div class="saisie-wrap">
    <h1><?= $id ? 'Modifier' : 'Nouvelle' ?> réservation</h1>

    <form method="post" action="/admin/calendrier/saisie<?= $id ? "/$id" : '' ?>" class="saisie">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

        <div class="row">
            <label>Nom du client
                <input name="nom_client" value="<?= htmlspecialchars($resa['nom_client'] ?? '') ?>" required autofocus>
            </label>
        </div>

        <div class="row cols-2">
            <label>Propriété
                <select name="propriete" required id="propriete">
                    <?php foreach ($proprietes as $code => $nom): ?>
                        <option value="<?= htmlspecialchars($code) ?>" <?= ($resa['propriete'] ?? '') === $code ? 'selected' : '' ?>>
                            <?= htmlspecialchars($nom) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Source
                <select name="source" required>
                    <?php foreach (array_keys($sources) as $s): ?>
                        <option value="<?= htmlspecialchars($s) ?>" <?= ($resa['source'] ?? 'Direct') === $s ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>

        <div class="row cols-2">
            <label>Arrivée
                <input type="date" name="arrivee" value="<?= htmlspecialchars($resa['arrivee'] ?? '') ?>" required>
            </label>

            <label>Départ
                <input type="date" name="depart" value="<?= htmlspecialchars($resa['depart'] ?? '') ?>" required>
            </label>
        </div>

        <fieldset class="occupants">
            <legend>Occupants</legend>
            <label>Adultes <input type="number" name="adultes" min="0" max="35" value="<?= (int) ($resa['adultes'] ?? 2) ?>" id="adultes"></label>
            <label>Enfants <input type="number" name="enfants" min="0" max="35" value="<?= (int) ($resa['enfants'] ?? 0) ?>" id="enfants"></label>
            <label>Bébés <input type="number" name="bebes" min="0" max="35" value="<?= (int) ($resa['bebes'] ?? 0) ?>" id="bebes"></label>
            <label>Animaux <input type="number" name="animaux" min="0" max="35" value="<?= (int) ($resa['animaux'] ?? 0) ?>" id="animaux"></label>
        </fieldset>

        <div class="row">
            <label>Détails animaux
                <input name="animaux_details" value="<?= htmlspecialchars($resa['animaux_details'] ?? '') ?>" placeholder="ex: 2 chiens moyens">
            </label>
        </div>

        <div class="row cols-2">
            <label>Code généré (lecture seule)
                <output id="code-preview" class="code-preview"><?= htmlspecialchars($resa['code'] ?? '—') ?></output>
            </label>

            <label>Provenance (ville · pays)
                <input name="provenance" value="<?= htmlspecialchars($resa['provenance'] ?? '') ?>" placeholder="ex: Paris · France">
            </label>
        </div>

        <div class="row">
            <label>Commentaire
                <textarea name="commentaire" rows="3"><?= htmlspecialchars($resa['commentaire'] ?? '') ?></textarea>
            </label>
        </div>

        <div class="row cols-3">
            <label>Statut
                <select name="statut">
                    <?php foreach ($statuts as $s): ?>
                        <option value="<?= htmlspecialchars($s) ?>" <?= ($resa['statut'] ?? 'Confirmée') === $s ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>N° de résa (Airbnb/Booking)
                <input name="numero_resa" value="<?= htmlspecialchars($resa['numero_resa'] ?? '') ?>">
            </label>

            <label>Montant (€)
                <input type="number" name="montant" step="0.01" min="0" value="<?= htmlspecialchars((string) ($resa['montant'] ?? '')) ?>">
            </label>
        </div>

        <div class="row">
            <label class="checkbox">
                <input type="checkbox" name="prive" <?= !empty($resa['prive']) ? 'checked' : '' ?>>
                Réservation privée (masquée des exports publics)
            </label>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary"><?= $id ? 'Enregistrer' : 'Créer' ?></button>
            <a href="/admin/calendrier" class="btn">Annuler</a>
        </div>
    </form>
</div>

<script>
// Aperçu live du code via l'API (Task 14 l'implémentera côté serveur).
// Pour l'instant on l'actualise depuis le client via un rechargement simple.
const inputIds = ['adultes', 'enfants', 'bebes', 'animaux', 'propriete'];
const preview = document.getElementById('code-preview');
async function refreshCode() {
    const params = new URLSearchParams({
        adultes: document.getElementById('adultes').value || 0,
        enfants: document.getElementById('enfants').value || 0,
        bebes:   document.getElementById('bebes').value   || 0,
        animaux: document.getElementById('animaux').value || 0,
        propriete: document.getElementById('propriete').value,
    });
    try {
        const r = await fetch('/admin/calendrier/api/code?' + params);
        if (r.ok) {
            const j = await r.json();
            preview.textContent = j.code || '—';
        }
    } catch (e) { /* silent — l'API arrive en Task 14 */ }
}
inputIds.forEach(id => document.getElementById(id)?.addEventListener('input', refreshCode));
refreshCode();
</script>

<style>
.saisie { display: flex; flex-direction: column; gap: 14px; max-width: 800px; }
.saisie .row { display: block; }
.saisie .cols-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.saisie .cols-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
.saisie label { display: flex; flex-direction: column; gap: 4px; font-size: 13px; color: #555; }
.saisie label.checkbox { flex-direction: row; align-items: center; gap: 8px; }
.saisie input, .saisie select, .saisie textarea { padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; font-family: inherit; }
.saisie fieldset.occupants { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; padding: 12px; border: 1px solid #e0e0e0; border-radius: 4px; }
.saisie fieldset.occupants label { font-size: 12px; }
.saisie .code-preview { padding: 8px; background: #f5f5f5; border-radius: 4px; font-family: monospace; font-weight: 700; font-size: 16px; }
.saisie .actions { display: flex; gap: 8px; margin-top: 8px; }
@media (max-width: 640px) {
    .saisie .cols-2, .saisie .cols-3 { grid-template-columns: 1fr; }
    .saisie fieldset.occupants { grid-template-columns: repeat(2, 1fr); }
}
</style>
