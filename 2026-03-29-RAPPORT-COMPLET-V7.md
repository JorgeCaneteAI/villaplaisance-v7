# RAPPORT COMPLET — Villa Plaisance V6
*Généré le 2026-03-29 — Sert de base pour la reprise du projet*

---

## 1. IDENTITÉ DU PROJET

**Villa Plaisance** — B&B de charme à Bédarrides, Vaucluse 84370
**Propriétaire** : Jorge (tutoyer toujours — jamais vouvoyer)
**Site live** : `https://vp.villaplaisance.fr`
**Admin live** : `https://vp.villaplaisance.fr/admin`
**GitHub** : `git@github.com:JorgeCaneteAI/villaplaisance-v6.git`

### Double offre saisonnière
- **Sept → Juin** : Chambres d'hôtes (B&B) — 2 chambres + petit-déjeuner
- **Juil → Août** : Villa entière en exclusivité — 4 chambres, 10 personnes max

### Chambres
| Chambre | Offre | Détail |
|---------|-------|--------|
| Chambre Verte | BB + Villa | Lit 160×200, vue jardin, clim, TV |
| Chambre Bleue | BB + Villa | 2 lits 90×200 jumelables + clic-clac, bibliothèque 300 livres, clim |
| Chambre Arche | Villa uniquement | Lit 140×180, arche bleue nuit, bibliothèques sol-plafond, RDC |
| Chambre 70 | Villa uniquement | Grand lit double, mobilier vintage années 70, accès direct jardin |

### Équipements villa
- Piscine privée 12m×6m clôturée
- Cuisine équipée
- Jardin provençal
- Salon, salle à manger, salle de bain

### Triangle d'Or — distances depuis Bédarrides
- 8 min : Châteauneuf-du-Pape
- 15 min : Avignon
- 18 min : Orange
- 25 min : L'Isle-sur-la-Sorgue
- 30 min : Pont du Gard
- 42 min : Gordes
- 45 min : Les Baux-de-Provence

---

## 2. PRINCIPE FONDAMENTAL — L'ADMIN EST UN CMS À BLOCS

**"CMS d'abord."** C'est le principe central de V6.

Chaque page du site est composée de **blocs ordonnés et éditables depuis l'admin**. L'architecture des données précède l'architecture des vues.

Jorge doit pouvoir :
- Ajouter un bloc à une page
- Supprimer un bloc
- Réordonner les blocs (↑ ↓)
- Modifier le contenu de chaque bloc via un formulaire dédié
- Activer / désactiver un bloc
- **Sans jamais toucher au code**

Les **14 types de blocs sont définitivement validés** : `hero`, `prose`, `cartes`, `liste`, `tableau`, `cta`, `avis`, `faq`, `stats`, `territoire`, `galerie`, `articles`, `petit-dejeuner`, `piscine`.

Chaque bloc a son propre formulaire dans l'admin et son propre template PHP dans `app/Views/blocks/`.

L'admin n'est pas un simple back-office — c'est **le centre de contrôle du site**.

---

## 5. RÈGLES ABSOLUES — NE JAMAIS ENFREINDRE

- Le mot **"luxe"** est interdit dans tous les contenus
- **Pas de tarifs** sur le site — jamais, sous aucune forme
- `declare(strict_types=1)` en tête de chaque fichier PHP
- SQL : requêtes **préparées PDO uniquement** — jamais d'interpolation de chaîne
- Images : **WebP uniquement**, < 200 Ko, attribut ALT obligatoire
- Ton éditorial : jamais "hôtelier générique", jamais de superlatifs vides
- Tests : manuels via navigateur

---

## 3. DESIGN — ÉTAT AU 2026-03-29

**Aucune maquette définie.** Le design est à construire entièrement.

Seuls principes actés :
- **Mobile first** obligatoire
- Palette "Ciel" validée (voir ci-dessous)
- Typographies validées (Cormorant Garamond + Inter)
- Concept "Épure & Respiration"

Tout le reste (layouts, composants, espacement, navigation, animations) est à définir et valider avec Jorge.

---

## 4. DIRECTION ARTISTIQUE — ÉLÉMENTS VALIDÉS

### Concept
**"Épure & Respiration"** — très blanc, très aéré, couleur lumineuse comme accent

### Palette "Ciel" — extraite des photos de la villa
```css
--bg: #FAFCFE          /* fond blanc froid très léger */
--bg-soft: #EBF2FA     /* fond alternatif bleu pâle */
--bg-line: #C4D8EE     /* bordures */
--accent: #1A6EB8      /* bleu ciel Provence — couleur signature */
--dark: #081426        /* nuit bleue profonde */
--text: #0C1A2E
--text-body: #2C4C6A
--text-muted: #5282AA
```

### Typographies
- **Titres** : Cormorant Garamond 300/400 (Google Fonts)
- **Corps / UI** : Inter 300/400/500/600 (Google Fonts)

### Animations validées
- Curseur custom (dot + ring avec inertie)
- Scroll reveal (IntersectionObserver)
- Fill-from-left sur les boutons
- Clip-path reveal sections identité
- Compteurs animés (chiffres clés)
- Boutons magnétiques
- Marquee continu
- Parallaxe hero
- Barre de progression scroll
- H1 multi-lignes animées (clip reveal ligne par ligne)

### Photos validées
- Hero : `IMG_4610.jpeg` (piscine + cyprès + grand ciel bleu)
- Section identité : `IMG_4611.jpeg` (reflet villa dans piscine)
- Photos disponibles dans `vrac/photos villa plaisance/`

---

## 6. WORKFLOW — TRAVAIL 100% LOCAL

**Nouvelle organisation : tout se passe en local. Zéro aller-retour avec le serveur pendant le développement.**

- Développement, tests, debug : uniquement sur `localhost:8000`
- On ne touche au serveur o2switch qu'au moment d'un déploiement validé
- Le serveur n'est jamais utilisé pour tester ou débugger
- Aucune commande à lancer dans le terminal cPanel pendant le dev

### Lancer le serveur local
```bash
cd /Users/jorgecanete/Documents/C_L_A_U_D_E/Projet_02_VillaPlaisance_V6/villaplaisance-v6
php -S localhost:8000 -t public
```

### Base de données locale
Nécessite MySQL/MariaDB local. Créer une base `vp_local`, configurer le `.env` local, puis exécuter les seeds dans l'ordre.

### Déploiement (uniquement quand c'est prêt)
```bash
git add -A && git commit -m "..." && git push
# Puis dans le terminal cPanel : git pull + rsync + chmod
```

---

## 7. STACK TECHNIQUE

- **PHP 8+** vanilla — zéro framework, zéro Composer
- **MySQL / MariaDB** — PDO avec ERRMODE_EXCEPTION
- **Multilingue** FR/EN/ES/DE (URLs `/{lang}/slug` sauf FR par défaut)
- **CMS intégré** — blocs ordonnés éditables depuis l'admin
- **Déploiement** — git push → .cpanel.yml → rsync sur o2switch

### Structure dossiers locaux
```
/Users/jorgecanete/Documents/C_L_A_U_D_E/Projet_02_VillaPlaisance_V6/
├── villaplaisance-v6/          ← code source (git)
│   ├── public/                 ← web root (index.php, assets)
│   ├── app/
│   │   ├── Controllers/
│   │   │   ├── Front/
│   │   │   └── Admin/
│   │   ├── Views/
│   │   │   ├── front/
│   │   │   ├── admin/
│   │   │   └── blocks/
│   │   ├── Services/
│   │   └── Lang/
│   ├── seeds/
│   └── config.php
├── CONTEXTE.md
├── PROJECTION.md
├── REALISATION.md
├── STRUCTURE-CMS.md
├── TECHNIQUE.md
└── CLAUDE.md
```

---

## 5. SERVEUR O2SWITCH

| Paramètre | Valeur |
|-----------|--------|
| Hébergeur | o2switch |
| Utilisateur cPanel | `efkz3012` |
| Hostname | `efkz3012.odns.fr` |
| IP | `109.234.164.178` |
| Panel cPanel | `cpanel.o2switch.net` |
| PHP version | 8.x |

### ⛔ SSH depuis le Mac = IMPOSSIBLE
Ports 22 et 7822 bloqués. Ne JAMAIS proposer `ssh efkz3012@...`.

### ✅ Terminal disponible dans cPanel uniquement
`cpanel.o2switch.net` → Terminal

### Structure dossiers sur le serveur
```
/home/efkz3012/
├── repositories/villaplaisance-v6/   ← clone git (git pull ici)
├── villaplaisance-v6/                ← web root Apache
│   └── public/                       ← document root
└── .env                              ← jamais commité
```

### Fichier .env sur le serveur
```
DB_HOST=localhost
DB_NAME=efkz3012_vp
DB_USER=efkz3012_vp
DB_PASS=MdpBdd@2026!!
APP_ENV=production
APP_URL=https://vp.villaplaisance.fr
ADMIN_EMAIL=contact@villaplaisance.fr
```

### Workflow déploiement
```bash
# 1. En local — après modifications
git add -A && git commit -m "..." && git push

# 2. Dans le terminal cPanel
cd ~/villaplaisance-v6 && git pull
rsync -a --exclude='.git' --exclude='.env' ~/villaplaisance-v6/ ~/vp.villaplaisance.fr/
chmod -R 755 ~/vp.villaplaisance.fr

# 3. Si nouveau seed
php seeds/nom_du_script.php
```

---

## 6. BASE DE DONNÉES

**DB** : `efkz3012_vp`
**User** : `efkz3012_vp`
**Password** : `MdpBdd@2026!!`

### Tables et état
| Table | Contenu |
|-------|---------|
| `vp_pages` | 8 pages FR avec SEO complet |
| `vp_sections` | Toutes pages alimentées |
| `vp_pieces` | 6 pièces : Verte+Bleue (BB), Verte+Bleue+Arche+70 (villa) |
| `vp_reviews` | 7 avis (4 BB, 3 villa), featured=1 |
| `vp_faq` | 14 questions (accueil×3, chambres×6, villa×6) |
| `vp_stats` | 6 chiffres clés |
| `vp_proximites` | 9 lieux Triangle d'Or |
| `vp_articles` | 19 articles (10 journal + 9 sur-place) |
| `vp_media` | Vide — photos à uploader |
| `vp_users` | Compte admin |

### Schéma `vp_articles`
```sql
id, type (journal|sur-place), category, slug, lang, title, excerpt, content (JSON),
meta_title, meta_desc, meta_keywords, gso_desc, og_image, cover_image,
status (published|draft), published_at, created_at, updated_at
```

---

## 7. ARTICLES EN BASE (19 articles, tous published, lang=fr)

### Journal (10 articles)

| id | slug | title | category |
|----|------|-------|----------|
| 1 | le-tourisme-de-masse-est-une-arnaque | Le tourisme de masse est une arnaque. Voilà pourquoi on y retourne quand même. | Voyager autrement |
| 2 | louer-maison-plutot-hotel-voyage | Louer une maison plutôt qu'un hôtel : pourquoi ça change tout au voyage | Voyager autrement |
| 3 | vie-proprietaire-chambre-hotes | Ce que personne ne dit sur la vie d'un propriétaire de chambre d'hôtes | Hôtes & hôteliers |
| 4 | recevoir-des-inconnus-chez-soi | Recevoir des inconnus chez soi : ce que ça apprend sur les gens | Hôtes & hôteliers |
| 5 | chateauneuf-du-pape-2026 | Châteauneuf-du-Pape en 2026 : entre sécheresse et renaissance | Territoire & transition |
| 6 | provence-vignerons-autrement | La Provence qui résiste : portraits de vignerons qui font autrement | Territoire & transition |
| 7 | duree-ideale-sejour-provence | Deux nuits ou deux semaines : comment trouver la durée idéale pour un séjour en Provence | L'art de séjourner |
| 8 | deconnecter-provence | Déconnecter vraiment : ce que la Provence impose à ceux qui s'y posent | L'art de séjourner |
| 9 | bedarrides-provence-authentique | Bédarrides n'est pas sur les brochures. C'est pour ça qu'on y vit. | Provence contemporaine |
| 10 | touriste-2026-nouvelles-attentes | Le touriste de 2026 : ce qu'il veut vraiment, et ce que l'industrie n'a pas compris | Provence contemporaine |

### Sur place (9 articles)

| id | slug | title | category |
|----|------|-------|----------|
| 11 | courses-bedarrides-sorgues | Faire ses courses à Bédarrides et Sorgues : les adresses qu'on donne à nos hôtes | Commerces |
| 12 | artisans-savonnerie-chocolaterie | Savonnerie et chocolaterie : deux adresses artisanales à ne pas rater | Commerces |
| 13 | fontaine-de-vaucluse-guide-pratique | Fontaine de Vaucluse : le guide pratique (ce qu'on ne vous dit pas toujours) | Sites à visiter |
| 14 | sentier-des-ocres-roussillon | Le Sentier des Ocres de Roussillon : guide pratique avant de partir | Sites à visiter |
| 15 | chateau-la-gardine-chateauneuf-du-pape | Château de la Gardine : dégustation à Châteauneuf-du-Pape à 8 minutes de Bédarrides | Sites à visiter |
| 16 | parc-spirou-provence-monteux | Parc Spirou Provence : tout savoir avant d'y aller avec des enfants | Que faire avec des enfants |
| 17 | ateliers-creatifs-enfants-provence | Ateliers créatifs pour enfants en Provence : notre sélection vérifiée | Que faire avec des enfants |
| 18 | imperial-bus-diner-bedarrides | Impérial Bus Diner : le burger de Bédarrides, 4,5/5 sur 340 avis | Restaurants & tables |
| 19 | le-numero-3-bedarrides | Le Numéro 3 : le bistrot de Bédarrides, en bord d'Ouvèze | Restaurants & tables |

---

## 8. ADMIN

| Paramètre | Valeur |
|-----------|--------|
| URL login | `https://vp.villaplaisance.fr/admin/login` |
| Email | `contact@villaplaisance.fr` |
| Mot de passe | `VillaP@2026!!` |
| Reset MDP envoyé à | `jorge@canete.fr` |

### Sections admin disponibles
- Dashboard (stats globales)
- Pages CMS (sections éditables par page)
- Pièces (chambres et espaces)
- Avis clients
- Articles (journal + sur-place)
- FAQ
- Médiathèque

---

## 9. ARCHITECTURE CMS

### Flux de requête
```
public/index.php
  → config.php : .env parsé, services core, autoloader PSR-4, session
  → new Router() → LangService::init()
  → Router::dispatch() → Controller::action()
  → AdminBaseController::render() → ob_start() → view → ob_get_clean() → layout
```

### Services core (`app/Services/`)
| Service | Rôle |
|---------|------|
| `Database.php` | Singleton PDO. `fetchAll`, `fetchOne`, `insert`, `update`, `delete` |
| `LangService.php` | `get()`, `t($key)`, `navUrl($page, $lang)` |
| `SeoService.php` | `forPage(slug, lang, fallbackTitle, fallbackDesc)` |
| `BlockService.php` | `getSections()`, `getAllSections()`, `getPage()`, `saveSection()`, `createSection()` |
| `IconService.php` | Icônes Lucide SVG inline |

### Types de blocs disponibles (14 types)
| Type | Usage | Source données |
|------|-------|----------------|
| `hero` | Header H1 + CTA | JSON direct |
| `prose` | H2 + texte + encadré | JSON direct |
| `cartes` | Grille pièces/espaces | `vp_pieces` filtré par offer |
| `liste` | Items inclus/exclus | JSON direct |
| `tableau` | Lignes libellé/valeur | JSON direct |
| `cta` | Accroche + bouton(s) | JSON direct |
| `avis` | Avis clients | `vp_reviews` filtré |
| `faq` | Questions/réponses | `vp_faq` filtré par page |
| `stats` | Chiffres clés | `vp_stats` |
| `territoire` | Lieux géographiques | `vp_proximites` |
| `galerie` | Photos | JSON direct |
| `articles` | Extraits d'articles | `vp_articles` filtré |
| `petit-dejeuner` | Bloc spécifique BB | JSON direct |
| `piscine` | Bloc spécifique Villa | JSON direct |

### Multilingue
- URL : `/{lang}/slug` si lang ≠ `fr` (défaut)
- Traductions : `app/Lang/fr.php`, `en.php`, `es.php`, `de.php`
- Appel : `t('clé')` ou `t('clé', ['var' => $val])`
- `vp_sections` et `vp_pages` ont une colonne `lang`

---

## 10. AVIS CLIENTS — TABLE `vp_reviews`

**Schéma** : `id, platform, offer (bb|villa|both), author, origin, content, rating, review_date, featured, home_carousel, status, created_at`

**Source complète** : `seeds/007_seed_reviews_corrected.php`

### Répartition
| Plateforme | Offre | Nb avis |
|------------|-------|---------|
| Airbnb | villa | 9 |
| Airbnb | bb | ~50 |
| Booking | bb | 21 |
| Google | bb | 7 |

### Avis Airbnb — Villa entière (9 avis)
| Auteur | Origine | Note | Date | Featured | Carousel |
|--------|---------|------|------|----------|---------|
| Marianne | Waterloo, Belgique | 5 | août 2025 | oui | oui |
| Jan | Anvers, Belgique | 4 | juillet 2025 | oui | non |
| Marie-Louise | — | 5 | août 2024 | non | non |
| Rachel | — | 5 | août 2023 | oui | oui |
| Emma | Puteaux, France | 5 | août 2022 | oui | non |
| Déborah | — | 5 | août 2022 | oui | non |
| Carina | Le Coteau, France | 5 | août 2022 | oui | oui |
| Yashi | Cambridge, Royaume-Uni | 5 | juillet 2022 | oui | non |
| Charlotte | Allemagne | 5 | juillet 2022 | oui | oui |

### Avis Airbnb — Chambres d'hôtes BB (sélection featured)
| Auteur | Origine | Note | Date | Carousel |
|--------|---------|------|------|---------|
| Mathieu | Riols, France | 5 | octobre 2025 | oui |
| Rosemarie | Northampton, Royaume-Uni | 5 | septembre 2025 | oui |
| Dirk | — | 5 | juillet 2025 | non |
| Pierre | Port Townsend, Washington | 5 | juin 2025 | non |
| Manon | Annecy, France | 5 | mai 2025 | oui |
| Jaime | Londres, Royaume-Uni | 5 | janvier 2025 | non |
| Timo | Vantaa, Finlande | 5 | octobre 2024 | oui |
| Louise | Waremme, Belgique | 5 | septembre 2024 | oui |
| Massimo | Rimini, Italie | 5 | août 2024 | oui |
| Mélanie | Clermont-Ferrand | 5 | juin 2024 | non |
| Lucas | France | 5 | mai 2024 | oui |
| Rob | Sydney, Australie | 5 | mai 2024 | non |
| John | Menheniot, Royaume-Uni | 5 | septembre 2023 | oui |
| Bei | Paris, France | 5 | juillet 2023 | oui |
| Monica | Croydon, Australie | 5 | juin 2023 | oui |
| Aurélie | Liège, Belgique | 5 | juin 2023 | oui |
| Mario | Québec, Canada | 5 | juin 2023 | non |
| Birgitta | Mannheim, Allemagne | 5 | juin 2023 | oui |
| Graham | Queensland, Australie | 5 | mai 2023 | oui |
| Catherine | Angleterre | 5 | mai 2023 | non |
| Elisabeth | Hamm, Allemagne | 5 | avril 2023 | oui |
| Laurène | Marseille, France | 5 | octobre 2022 | oui |
| Josephine | Monterey, Californie | 5 | septembre 2022 | non |
| Grace | Luxembourg | 5 | juillet 2022 | oui |
| Faustine | Paris, France | 5 | juin 2022 | oui |
| Nancy | Austin, Texas | 5 | juin 2022 | non |
| Quentin | Attignat, France | 5 | juin 2022 | oui |

### Avis Booking — BB (sélection featured)
| Auteur | Origine | Note | Date | Carousel |
|--------|---------|------|------|---------|
| Touria | France | 9/10 | octobre 2025 | non |
| Jeroen | Pays-Bas | 10/10 | juin 2025 | oui |
| FRED&MARINE | France | 10/10 | juin 2025 | oui |
| Giorgos | Grèce | 10/10 | juin 2025 | oui |
| Michael | Allemagne | 10/10 | avril 2025 | non |
| Krystelle | France | 10/10 | mars 2025 | oui |
| Katia | France | 9/10 | janvier 2025 | non |
| Matthieu | France | 10/10 | décembre 2024 | oui |

### Avis Google — BB (sélection featured)
| Auteur | Note | Date | Featured |
|--------|------|------|---------|
| RUGBY A 5 | 5 | avril 2025 | oui |
| Achim Donald | 5 | avril 2025 | oui |
| Enyrd Weis | 5 | mai 2022 | non |
| Raphaël Saunier | 5 | octobre 2022 | non |

---

## 11. SEEDS EXÉCUTÉS EN BASE

| Seed | Contenu |
|------|---------|
| `001_migration.php` | Création de toutes les tables |
| `002_seed_admin.php` | Compte admin contact@villaplaisance.fr / VillaP@2026!! |
| `003_seed_pages.php` | 8 pages FR avec SEO |
| `004_add_reset_token.php` | Colonnes reset_token dans vp_users |
| `005_seed_content.php` | Sections, pièces, stats, FAQ, avis, proximités |
| `006_seed_reviews.php` | Avis V1 |
| `007_seed_reviews_corrected.php` | 7 avis finaux (Airbnb, Booking, Google, direct) |
| `008_seed_content_validated.php` | Contenu pages validé |
| `009_seed_articles_journal.php` | 10 articles Journal |
| `010_seed_articles_surplace.php` | 9 articles Sur Place |

---

## 12. PIÈCES — TABLE `vp_pieces`

**Schéma** : `id, offer (bb|villa|both), type (chambre|espace), position, name, sous_titre, description, equip, note, css_class, lang`

### Chambres d'hôtes (offer=bb)

| Nom | Sous-titre | Description | Équipements | Note |
|-----|-----------|-------------|-------------|------|
| Chambre Verte | Grand lit, vue jardin | Chambre lumineuse avec un grand lit 160×200, donnant sur le jardin et les oliviers. Espace cocooning, sobriété et calme. Climatisation réversible, TV. | Lit 160×200, Vue jardin, Climatisation réversible, TV, Wifi | Rez-de-chaussée |
| Chambre Bleue | Bibliothèque, idéale famille | Deux lits 90×200 jumelables en grand lit 180. Un clic-clac pour une troisième personne. Une bibliothèque de 300 livres. La chambre des lecteurs et des familles. | 2 lits 90×200 jumelables, Clic-clac (1 pers.), Bibliothèque 300 livres, Climatisation réversible, Wifi | Idéale pour famille ou voyage entre amis |

### Villa entière (offer=villa)

| Nom | Sous-titre | Description | Équipements | Note |
|-----|-----------|-------------|-------------|------|
| Chambre Verte | Grand lit, vue jardin | Lit 160×200, vue sur le jardin et les oliviers. Climatisation réversible, TV. Au rez-de-chaussée. | Lit 160×200, Vue jardin, Climatisation, TV, Wifi | Rez-de-chaussée |
| Chambre Bleue | Bibliothèque 300 livres | Deux lits 90×200 jumelables, clic-clac, bibliothèque de 300 livres. Climatisation réversible. | 2 lits 90×200 jumelables, Clic-clac, Bibliothèque 300 livres, Climatisation, Wifi | — |
| Chambre Arche | Arche bleue nuit, bibliothèques sol-plafond | Lit 140×180 sous une grande arche peinte en bleu nuit. Bibliothèques sol-plafond des deux côtés. Au rez-de-chaussée, avec vue sur le jardin. | Lit 140×180, Arche bleue nuit, Bibliothèques sol-plafond, Vue jardin, Climatisation | Rez-de-chaussée · Accès direct jardin |
| Chambre 70 | Mobilier vintage années 70 | Grand lit double, mobilier chiné des années 70. Accès direct sur le jardin par une porte-fenêtre. La chambre la plus atypique de la villa. | Grand lit double, Mobilier vintage, Accès direct jardin, Climatisation | Accès direct jardin |

---

## 13. STATS — TABLE `vp_stats`

| Valeur | Label | Sous-label | Icône |
|--------|-------|-----------|-------|
| 4 | Chambres | en villa entière | 🛏 |
| 10 | Personnes max | en villa exclusive | 👥 |
| 8 min | Châteauneuf-du-Pape | Triangle d'Or | 📍 |
| 12×6 | Piscine privée | Clôturée, privatisée | 🏊 |
| 15 min | Avignon | Centre historique | 🏛 |
| 300 | Livres | Dans la bibliothèque | 📚 |

---

## 14. PROXIMITÉS — TABLE `vp_proximites`

| Nom | Distance | Durée | Catégorie | Description |
|-----|---------|-------|-----------|-------------|
| Marché de Bédarrides | 0 km | Sur place | Sur place | Marché du mercredi matin, place du village |
| Châteauneuf-du-Pape | 8 km | 8 min | Vins & gastronomie | Vignobles emblématiques de la vallée du Rhône |
| Avignon | 18 km | 15 min | Culture & patrimoine | Palais des Papes, remparts, Festival d'Avignon |
| Orange | 22 km | 18 min | Culture & patrimoine | Théâtre antique, Arc de triomphe romain |
| L'Isle-sur-la-Sorgue | 30 km | 25 min | Marchés & antiquités | Capitale des antiquaires, marché du dimanche |
| Pont du Gard | 40 km | 30 min | Culture & patrimoine | Aqueduc romain, site UNESCO |
| Gordes | 50 km | 42 min | Villages perchés | Village perché du Luberon, panorama exceptionnel |
| Les Baux-de-Provence | 60 km | 45 min | Villages perchés | Village médiéval, Carrières de Lumières |
| Vaison-la-Romaine | 35 km | 30 min | Culture & patrimoine | Cité romaine, marché provençal du mardi |

---

## 15. FAQ — TABLE `vp_faq`

### Page Accueil (3 questions)

**Q : Quelle est la différence entre les chambres d'hôtes et la villa entière ?**
R : De septembre à juin, nous proposons 2 chambres indépendantes avec petit-déjeuner inclus (formule B&B). En juillet et août, la villa entière est disponible en location exclusive : 4 chambres, piscine privatisée, cuisine équipée, jusqu'à 10 personnes.

**Q : Comment réserver ?**
R : Pour les chambres d'hôtes, vous pouvez réserver via Airbnb ou Booking, ou nous contacter directement par mail. Pour la villa estivale, nous sommes également sur Airbnb.

**Q : Villa Plaisance est-elle bien située pour visiter la Provence ?**
R : Idéalement. Bédarrides est au centre du Triangle d'Or : Châteauneuf-du-Pape à 8 minutes, Avignon à 15 minutes, Orange à 18 minutes, L'Isle-sur-la-Sorgue à 25 minutes. Gordes et les Baux-de-Provence à moins d'une heure.

### Page Chambres (6 questions)

**Q : Le petit-déjeuner est-il inclus ?**
R : Oui, le petit-déjeuner est compris dans le séjour. Il est servi à l'heure qui vous convient — viennoiseries du boulanger du village, confitures maison, jus de fruits frais, café ou thé.

**Q : Peut-on accéder à la piscine ?**
R : Oui. La piscine est accessible aux hôtes en chambres d'hôtes.

**Q : Quels sont les horaires d'arrivée et de départ ?**
R : Arrivée à partir de 17h, départ avant 11h. Des arrangements sont possibles selon disponibilités.

**Q : Les chambres sont-elles climatisées ?**
R : Oui, les deux chambres disposent de la climatisation réversible. Elle peut aussi chauffer en demi-saison.

**Q : Y a-t-il un parking ?**
R : Oui, le parking est gratuit et sécurisé dans la propriété.

**Q : Est-ce que Villa Plaisance accepte les enfants ?**
R : Oui. Les enfants sont les bienvenus. La piscine est clôturée mais la vigilance parentale reste de mise, comme partout.

### Page Villa (6 questions)

**Q : La location est-elle à la semaine ?**
R : Vous pouvez louer Villa Plaisance pour les mois de juillet et août à partir de 4 nuits. Les arrivées se font à partir de 17h et les départs avant 10h.

**Q : La piscine est-elle vraiment privatisée ?**
R : Oui, entièrement. Personne d'autre n'a accès à la piscine pendant votre séjour — ni d'autres hôtes, ni les propriétaires. Elle est pour votre groupe exclusivement.

**Q : La villa accueille combien de personnes ?**
R : Jusqu'à 10 personnes. La villa dispose de 4 chambres : Chambre Verte (lit 160×200), Chambre Bleue (2 lits 90×200 jumelables + clic-clac), Chambre Arche (lit 140×180), Chambre 70 (grand lit double).

**Q : La cuisine est-elle équipée pour cuisiner pour 10 ?**
R : Oui. Four, plaques vitrocéramique, grand réfrigérateur, lave-vaisselle, cafetières, équipement complet. La terrasse dispose d'un espace barbecue.

**Q : Est-ce que Villa Plaisance accepte les événements (anniversaires, enterrement de vie de garçon…) ?**
R : Contactez-nous en amont pour en discuter. Nous évaluons chaque demande. Les séjours familiaux et entre amis sont notre cœur de cible.

**Q : Y a-t-il le Wifi ?**
R : Oui, le Wifi haut débit est disponible dans toute la villa et le jardin.

---

## 16. SECTIONS CMS PAR PAGE (contenu validé seed 008)

### Accueil (9 sections)
| Pos | Type | Contenu clé |
|-----|------|------------|
| 10 | hero | "La Villa Plaisance" — Bédarrides, Triangle d'Or |
| 20 | prose | "Chambres d'hôtes — de septembre à juin" |
| 30 | prose | "Villa entière — juillet et août" |
| 40 | stats | Chiffres clés (depuis vp_stats) |
| 50 | avis | offer=both, max=6 |
| 60 | territoire | Lieux Triangle d'Or (depuis vp_proximites) |
| 70 | articles | type=journal, max=3 |
| 80 | cta | "Une question ? Une disponibilité ?" → /contact/ |
| 90 | faq | page_filter=accueil |

### Chambres d'hôtes (11 sections)
| Pos | Type | Contenu clé |
|-----|------|------------|
| 10 | hero | "Chambres d'hôtes" — De septembre à juin |
| 20 | prose | "Deux chambres et une salle de bain privée" — Séjour min 2 nuits |
| 30 | cartes | offer=bb (Chambre Verte + Chambre Bleue) |
| 40 | tableau | En un coup d'œil (2 chambres, 5 pers max, PDJ inclus…) |
| 50 | petit-dejeuner | Viennoiseries, confitures maison, jus frais |
| 60 | piscine | 12m×6m, clôturée, transats, parasols |
| 70 | liste | Ce qui est inclus (PDJ, piscine, parking, wifi…) |
| 80 | tableau | Infos pratiques (arrivée 17h, départ 11h, animaux OK…) |
| 90 | avis | offer=bb, max=6 |
| 100 | faq | page_filter=chambres |
| 110 | cta | "Envie de séjourner ?" → /contact/ |

### Villa entière (12 sections)
| Pos | Type | Contenu clé |
|-----|------|------------|
| 10 | hero | "Location villa entière" — Juillet et août |
| 20 | prose | "La villa pour vous seuls" — exclusivité absolue |
| 30 | cartes | offer=villa (4 chambres) |
| 40 | tableau | En un coup d'œil (4 chambres, 10 pers, piscine 12×6…) |
| 50 | piscine | 12m×6m, clôturée, cyprès et ciel provençal |
| 60 | liste | Ce qui est inclus (piscine, cuisine, linge, wifi…) |
| 70 | tableau | Infos pratiques (à la semaine, arrivées le samedi…) |
| 80 | avis | offer=villa, max=6 |
| 90 | faq | page_filter=villa |
| 100 | territoire | Triangle d'Or |
| 110 | cta | "Disponibilités juillet et août" → /contact/ |

### Contact (1 section)
| Pos | Type | Contenu clé |
|-----|------|------------|
| 10 | hero | "Contact" — Nous répondons sous 24 heures |

---

## 17. PAGES ET URLS

| Page | URL FR |
|------|--------|
| Accueil | `/` |
| Chambres d'hôtes | `/chambres-d-hotes/` |
| Villa entière | `/location-villa-provence/` |
| Journal | `/journal/` |
| Sur Place | `/sur-place/` |
| Contact | `/contact/` |
| Mentions légales | `/mentions-legales/` |
| Politique de confidentialité | `/politique-confidentialite/` |
| Article journal | `/journal/{slug}` |
| Article sur-place | `/sur-place/{slug}` |

---

## 19. ÉTAT AU 2026-03-29 — CE QUI FONCTIONNE

- ✅ Site front en ligne (`vp.villaplaisance.fr`)
- ✅ Toutes les pages front s'affichent avec contenu réel
- ✅ Admin opérationnel (login, dashboard, pages CMS, pièces, avis, FAQ, media)
- ✅ 19 articles en base (tous published, lang=fr)
- ✅ Multilingue FR en place
- ✅ Déploiement git push → o2switch fonctionnel
- ⚠️ Page `/admin/articles` → **PAGE BLANCHE** (bug non résolu)
- ⚠️ Photos WebP manquantes (placeholders en place)
- ⚠️ Langues EN/ES/DE : pages non créées
- ⚠️ `APP_ENV=development` sur le serveur (à remettre en `production` après débogage)

---

## 20. BUG ADMIN/ARTICLES — Ce qu'on sait

**Symptôme** : `/admin/articles` → page blanche. Toutes les autres pages admin fonctionnent.

**Ce qui a été vérifié** :
- 19 articles bien en base avec données complètes
- Syntaxe PHP correcte (php -l OK sur view et controller)
- Routeur correct (route définie lignes 186-188 de Router.php)
- Controller correct (SQL, render OK)
- Vue index.php correcte
- Layout admin correct

**Ce qui N'A PAS été identifié** : la cause exacte de la page blanche.

**Piste probable** : la fonction `aval()` définie dans `form.php` (template de formulaire article) est une fonction PHP globale. Si `form.php` est chargé ET que la page index est rechargée dans le même contexte (impossible en théorie avec PHP-FPM), cela provoquerait "Cannot redeclare function aval()". À surveiller en local.

**État du serveur après la session de debug** :
- `index.php` : ⚠️ peut être corrompu par les patches — à vérifier/restaurer via git pull
- `.env` : APP_ENV=development (à remettre en production)

**Commandes de restauration dans le terminal cPanel** :
```bash
cd ~/villaplaisance-v6 && git pull
rsync -a --exclude='.git' --exclude='.env' ~/villaplaisance-v6/ ~/vp.villaplaisance.fr/
chmod -R 755 ~/vp.villaplaisance.fr
# Puis remettre APP_ENV=production dans .env
sed -i 's/APP_ENV=development/APP_ENV=production/' ~/vp.villaplaisance.fr/.env
```

---

## 21. PROCHAINES ÉTAPES (avant session debug)

1. Résoudre bug `/admin/articles` (tester en local)
2. Uploader photos WebP dans `public/assets/img/`
3. Contenus légaux (mentions + politique de confidentialité)
4. Créer pages EN (slug + sections)
5. Finaliser `es.php` et `de.php` (traductions)
6. Hero avec vraie photo (IMG_4610)
7. CSS affinements

---

## 22. SEO/GSO — Stratégie

### Mots-clés cibles
- "chambre d'hôtes Bédarrides"
- "chambre d'hôtes Vaucluse"
- "chambre d'hôtes près d'Avignon"
- "location villa Provence"
- "location villa Châteauneuf-du-Pape"
- "location villa piscine privée Vaucluse"
- "villa 10 personnes Provence"

### Schemas JSON-LD par page
- Accueil : `LocalBusiness` + `AggregateRating`
- Chambres : `BedAndBreakfast` + `AggregateRating` + `FAQPage`
- Villa : `VacationRental` + `LodgingBusiness` + `AggregateRating` + `FAQPage`
- Articles : `Article` + `BreadcrumbList`

### GSO
- Chaque page a une description GSO (`speakable WebPage JSON-LD`)
- FAQ répondent à des questions réelles posées aux IA
- Textes structurés pour ChatGPT, Perplexity, Gemini

---

## 23. COMMANDES UTILES

```bash
# Local — lancer le serveur de dev
cd /Users/jorgecanete/Documents/C_L_A_U_D_E/Projet_02_VillaPlaisance_V6/villaplaisance-v6
php -S localhost:8000 -t public

# Local — déployer
git add -A && git commit -m "..." && git push

# Serveur cPanel — déploiement manuel
cd ~/villaplaisance-v6 && git pull
rsync -a --exclude='.git' --exclude='.env' ~/villaplaisance-v6/ ~/vp.villaplaisance.fr/
chmod -R 755 ~/vp.villaplaisance.fr

# Serveur — MySQL
mysql -u efkz3012_vp -p efkz3012_vp -e "REQUÊTE;"
# (utiliser /usr/bin/mariadb si warning sur mysql)

# Serveur — exécuter un seed
cd ~/villaplaisance-v6 && php seeds/nom_du_script.php
```
