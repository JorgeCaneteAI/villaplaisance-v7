# Déploiement Calendrier Réservations — Production o2switch

**Date :** 2026-04-17
**Cible :** `villaplaisance.fr/admin/calendrier` (module de VP V5)
**Remplace :** `cal.villaplaisance.fr` (Python Flask — à démonter)
**Branche :** `feat/calendrier-integration` (25+ commits depuis `main`)

---

## Pré-requis

- Accès SSH au compte o2switch `efkz3012`
- Accès cPanel pour configurer le cron et le sous-domaine
- Backup MySQL `vp_v7` effectué avant migration
- Extensions PHP requises : `pdo_mysql`, `pdo_sqlite`, `curl`, `gd`, `mbstring`, `iconv`, `zlib`

---

## Étape 1 — Backup préventif

Sur le serveur o2switch :

```bash
# Dump de la BDD VP V5
mysqldump --default-character-set=utf8mb4 vp_v7 > ~/backups/vp_v7_$(date +%Y%m%d_%H%M).sql

# Sauvegarde de l'ancienne SQLite Flask (pour l'import)
cp ~/cal.villaplaisance.fr/reservations.db ~/backups/reservations_flask_$(date +%Y%m%d).db
```

---

## Étape 2 — Push du code

Depuis le Mac local :

```bash
cd ~/Documents/AGENCE/CLIENTS/villa-plaisance/02_SITE/actuel/
git checkout feat/calendrier-integration
git push origin feat/calendrier-integration

# Merge dans main si validation OK
git checkout main
git merge feat/calendrier-integration
git push origin main
```

Sur le serveur, pull du code (via cPanel Git Version Control ou FTP).

---

## Étape 3 — Configuration `.env` prod

Sur le serveur, éditer `.env` (gitignored) :

```bash
cd ~/villaplaisance.fr
nano .env
```

Ajouter les 4 URLs iCal actualisées (les rotater d'abord si déjà commitées en dev) :

```
ICAL_AV_ANN_AIRBNB=https://www.airbnb.fr/calendar/ical/3520144.ics?t=<NEW_TOKEN>
ICAL_VP_BB_AIRBNB=https://www.airbnb.fr/calendar/ical/597660428689098985.ics?t=<NEW_TOKEN>
ICAL_VP_ETE_AIRBNB=https://www.airbnb.fr/calendar/ical/625764424244747021.ics?t=<NEW_TOKEN>
ICAL_VP_BB_BOOKING=https://ical.booking.com/v1/export?t=<NEW_TOKEN>
```

---

## Étape 4 — Création des tables et import des données

Sur le serveur, en SSH :

```bash
cd ~/villaplaisance.fr

# Copier la BDD SQLite Flask (source)
cp ~/cal.villaplaisance.fr/reservations.db /tmp/reservations.db

# Ajouter la variable d'env temporaire
echo "FLASK_DB_PATH=/tmp/reservations.db" >> .env

# Lancer les seeds
php seeds/036_reservations_tables.php
php seeds/037_import_reservations_flask.php

# Vérifier
php -r 'require "config.php"; $n = \Database::fetchOne("SELECT COUNT(*) c FROM vp_reservations"); echo "Total résas: " . $n["c"] . \PHP_EOL;'

# Nettoyer : retirer la var d'env + le fichier temporaire
sed -i '/FLASK_DB_PATH/d' .env
rm /tmp/reservations.db
```

Résultat attendu : ~26+ résas (26 du Flask + celles importées des feeds iCal à la première sync).

---

## Étape 5 — Configuration du cron iCal

Dans **cPanel → Tâches Cron** :

- **Fréquence :** toutes les 30 minutes
- **Commande :**

```
*/30 * * * * /usr/local/bin/php /home/efkz3012/villaplaisance.fr/bin/sync_ical.php >> /home/efkz3012/logs/ical_sync.log 2>&1
```

Créer le dossier logs s'il n'existe pas :

```bash
mkdir -p ~/logs
chmod 755 ~/logs
```

Premier run manuel pour valider :

```bash
cd ~/villaplaisance.fr
./bin/sync_ical.php
```

Attendu : `[YYYY-MM-DD HH:MM:SS] Sync OK — créées: N, MAJ: M, supprimées: K`, exit 0.

---

## Étape 6 — Démontage de l'app Flask `cal.villaplaisance.fr`

**C'est l'étape critique qui résout le problème de conflit runtime o2switch.**

Dans **cPanel → Setup Python App** :

1. Sélectionner l'app `cal.villaplaisance.fr`
2. Cliquer **Stop App** puis **Destroy**

Puis en SSH, vider le dossier :

```bash
cd ~/cal.villaplaisance.fr
# Backup avant suppression (sécurité)
tar -czf ~/backups/cal-flask-final-$(date +%Y%m%d).tar.gz .

# Suppression du contenu Flask
rm -rf app.py database.py auth.py sync_ical.py export_pdf.py passenger_wsgi.py setup_auth.py
rm -rf templates static exports __pycache__
rm -f auth.json reservations.db requirements.txt deploy.zip Archive.zip *.command *.indd *.pdf

# Vérifier qu'il ne reste rien de Python
ls -la
```

Créer le `.htaccess` de redirection 301 :

```bash
cat > .htaccess <<'EOF'
RewriteEngine On
RewriteRule ^(.*)$ https://villaplaisance.fr/admin/calendrier/$1 [R=301,L]
EOF
```

Et le fallback HTML :

```bash
cat > index.html <<'EOF'
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="0;url=https://villaplaisance.fr/admin/calendrier">
<title>Redirection…</title>
</head>
<body>
<p><a href="https://villaplaisance.fr/admin/calendrier">Accéder au calendrier</a></p>
</body>
</html>
EOF
```

Supprimer le venv Python :

```bash
rm -rf ~/virtualenv/cal.villaplaisance.fr
```

---

## Étape 7 — Tests de production

Checklist complète à valider après déploiement :

### Redirection sous-domaine
- [ ] `curl -I https://cal.villaplaisance.fr` → `301`, Location `https://villaplaisance.fr/admin/calendrier`
- [ ] `curl -I https://cal.villaplaisance.fr/annee/2026` → `301`, Location `https://villaplaisance.fr/admin/calendrier/annee/2026`

### Auth
- [ ] `https://villaplaisance.fr/admin/login` : login email + password → redirige vers `/admin/pin`
- [ ] `/admin/pin` : entrer PIN → accès à `/admin/dashboard`
- [ ] Cocher "Faire confiance à cet appareil pendant 6 mois" puis submit → `/admin/dashboard` OK
- [ ] Déconnexion + re-login : le password seul suffit, pas de demande de PIN (trust device)

### Calendrier
- [ ] `/admin/calendrier` : vue mois courant avec les 26+ résas
- [ ] Couleurs par source : Airbnb rouge, Booking bleu marine, Direct vert, Privée gris, Absence noir
- [ ] Navigation prev/next month : liens qui s'incrémentent correctement (y compris changements d'année)
- [ ] `/admin/calendrier/annee/2026` : 12 mini-calendriers, header `Lu Ma Me Je Ve Sa Di`
- [ ] `/admin/calendrier/liste` : toutes les résas listées, filtres fonctionnels (propriété, source, statut, mois, recherche)
- [ ] `/admin/calendrier/saisie` (nouvelle) : formulaire, aperçu live du code
- [ ] `/admin/calendrier/saisie/<id>` (édition) : formulaire pré-rempli + bouton Supprimer (confirm)
- [ ] `/admin/calendrier/print/2026/4` : vue impression A4 paysage, Cmd+P propre

### Sync iCal
- [ ] Bouton "🔄 Sync iCal" sur vue mois → flash `Sync iCal — N créée(s), M mise(s) à jour, K supprimée(s)`
- [ ] Badge en haut : vert "✓ Sync il y a X min" si < 1h
- [ ] `/admin/calendrier/logs` : table avec les runs cron + manuel
- [ ] Après 30 min : `tail -f ~/logs/ical_sync.log` montre le cron en action

### Export PDF (FPDF)
- [ ] `/admin/calendrier/export/pdf/2026/4` → download `reservations_2026_04.pdf`
- [ ] `/admin/calendrier/export/pdf/annee/2026` → download `reservations_2026.pdf` (12 pages)
- [ ] PDF ouvert dans Aperçu : accents OK (`RÉSERVATIONS — AVRIL 2026`, `ÉLISABETH NOËL`), couleurs des sources, légende en bas

### PWA
- [ ] Chrome desktop : DevTools → Application → Manifest : `name`, icônes, scope `/admin/` ✓
- [ ] Chrome barre d'adresse : icône "Installer l'app" visible → clic → app Mac installée
- [ ] iPhone Safari : "Partager" → "Sur l'écran d'accueil" → icône installée, ouverture plein écran
- [ ] Service Worker actif : DevTools → Application → Service Workers : `sw.js` activated

### Sécurité
- [ ] `/admin/securite` : liste les appareils de confiance (dont le mien après cochage de l'étape Auth)
- [ ] Bouton "Révoquer" → confirm → appareil retiré → nécessite PIN à la prochaine connexion

---

## Rollback

Si problème critique dans les 48h après déploiement :

```bash
# 1. Restaurer l'ancien venv + fichiers Flask
cd ~/cal.villaplaisance.fr
tar -xzf ~/backups/cal-flask-final-<date>.tar.gz

# 2. Recréer l'app Python dans cPanel → Setup Python App (pointer vers ce dossier)
# 3. Restaurer la BDD MySQL (pour annuler les modifs du calendrier)
mysql vp_v7 < ~/backups/vp_v7_<date>.sql

# 4. Retirer les routes /admin/calendrier du Router (git revert)
cd ~/villaplaisance.fr
git revert <SHA_merge_calendrier>
```

Temps estimé : **15 min**.

---

## Commits déployés

Environ 25+ commits sur `feat/calendrier-integration`, du premier commit (`c0b03b2 feat(calendrier): create reservation tables`) au dernier (Task 30).

Exemple de vérification après push :

```bash
cd ~/villaplaisance.fr
git log --oneline main..HEAD | head -30
```

---

## Fichiers ajoutés par la refonte

```
app/Controllers/Admin/ReservationController.php  (13 actions : mois, annee, saisie, liste, print, PDF, sync, logs, apiCode, apiQuickUpdate, …)
app/Controllers/Admin/SecuriteController.php     (2 actions : index, revoke)
app/Services/ReservationService.php              (10 méthodes : generateCode, calculerDuree, CRUD, getForMonth, buildCalendarData, buildPayload)
app/Services/IcalSyncService.php                 (syncAll + syncFeed + parser iCal maison)
app/Services/CalendarPdfService.php              (exportMonth + exportYear via FPDF)
app/Services/ReservationConstants.php            (PROPRIETES, SOURCES, STATUTS, MOIS_FR, JOURS_FR, JOURS_FR_COURT)
app/Services/lib/fpdf/                           (FPDF 1.86 vendoré + fonts DejaVu UTF-8)
app/Views/admin/reservations/                    (index, saisie, liste, annee, print, logs)
app/Views/admin/securite.php
bin/sync_ical.php                                (CLI pour le cron o2switch)
seeds/036_reservations_tables.php                (4 tables MySQL + seed iCal feeds)
seeds/037_import_reservations_flask.php          (import one-shot 26 résas depuis SQLite Flask)
tests/reservation_service_test.php               (10 assertions encoding + durée)
tests/ical_parser_test.php                       (10 assertions parser iCal)
tests/ical_dedupe_test.php                       (4 assertions UNIQUE KEY)
public/manifest.webmanifest
public/sw.js
public/assets/pwa/icon-{192,512}.png
```

Fichiers modifiés :

```
app/Router.php                              (+ ~20 routes /admin/calendrier/*, /admin/securite/*)
app/Controllers/Admin/AuthController.php    (+ logique trust device dans login + verifyPin)
app/Views/admin/pin.php                     (+ checkbox "Faire confiance")
app/Views/layouts/admin.php                 (+ PWA tags, + liens nav Calendrier et Sécurité)
public/.htaccess                            (+ Content-Type manifest + Service-Worker-Allowed)
.env.example                                (+ 4 clés ICAL_*)
```
