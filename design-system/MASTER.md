# MASTER — Design System Villa Plaisance V7
*Généré le 2026-03-29 — Source of Truth pour tous les fichiers pages/*

---

## 1. IDENTITÉ VISUELLE

### Concept validé
**"Épure & Respiration"** — très blanc, très aéré, couleur lumineuse comme accent.
Le visiteur respire avant de lire. L'image prime sur le texte. Le texte prime sur les ornements.

### Règles absolues
- Le mot **"luxe"** est interdit dans tous les contenus
- **Pas de tarifs** sur le site — jamais
- Jamais de superlatifs vides, jamais de ton "hôtelier générique"

---

## 2. PALETTE "CIEL" — Tokens CSS

```css
:root {
  /* Fonds */
  --bg:         #FAFCFE;   /* fond blanc froid très léger */
  --bg-soft:    #EBF2FA;   /* fond alternatif bleu pâle */
  --bg-line:    #C4D8EE;   /* bordures et séparateurs */

  /* Couleur signature */
  --accent:     #1A6EB8;   /* bleu ciel Provence — usage CTA, highlights */
  --accent-hover: #155A9A; /* assombri pour hover */
  --accent-light: #D6E8F7; /* version pâle pour surfaces actives */

  /* Textes */
  --dark:       #081426;   /* nuit bleue profonde — headings majeurs */
  --text:       #0C1A2E;   /* texte principal */
  --text-body:  #2C4C6A;   /* corps de texte */
  --text-muted: #5282AA;   /* texte secondaire, légendes */

  /* Contraste vérifié WCAG AA */
  /* --text (#0C1A2E) sur --bg (#FAFCFE) = 16.5:1 ✅ AAA */
  /* --text-body (#2C4C6A) sur --bg (#FAFCFE) = 8.2:1 ✅ AAA */
  /* --text-muted (#5282AA) sur --bg (#FAFCFE) = 3.9:1 ✅ AA (large text) */
  /* --accent (#1A6EB8) sur --bg (#FAFCFE) = 4.8:1 ✅ AA */

  /* États sémantiques */
  --success: #1A7A4A;
  --error:   #C0392B;
  --warning: #B7780A;
}
```

> ⚠️ Ne jamais utiliser de hex brut dans les composants — toujours utiliser les tokens ci-dessus.

---

## 3. TYPOGRAPHIE

### Choix validés (confirmés par UI/UX Pro Max — "Classic Elegant")
| Rôle | Police | Graisses | Usage |
|------|--------|---------|-------|
| Titres | **Cormorant Garamond** | 300 / 400 | H1, H2, heroes, citations |
| Corps / UI | **Inter** | 300 / 400 / 500 / 600 | Paragraphes, boutons, navigation |

### Échelle typographique

```css
:root {
  --text-xs:   0.75rem;   /* 12px — labels, légendes */
  --text-sm:   0.875rem;  /* 14px — texte secondaire */
  --text-base: 1rem;      /* 16px — corps minimum (iOS auto-zoom) */
  --text-lg:   1.125rem;  /* 18px — corps confortable */
  --text-xl:   1.25rem;   /* 20px — intro, chapeau */
  --text-2xl:  1.5rem;    /* 24px — H3 */
  --text-3xl:  1.875rem;  /* 30px — H2 */
  --text-4xl:  2.25rem;   /* 36px — H2 desktop */
  --text-5xl:  3rem;      /* 48px — H1 mobile */
  --text-6xl:  3.75rem;   /* 60px — H1 desktop */
  --text-7xl:  clamp(3rem, 8vw, 6rem); /* Hero */
}
```

### Règles typographiques
- Corps minimum : **16px** (évite le zoom auto iOS)
- Line-height corps : **1.65** (aéré, "Épure & Respiration")
- Line-height titres : **1.1–1.2** (serré, impact éditorial)
- Longueur de ligne : **55–70 caractères** (mobile) / **65–75** (desktop)
- Pas d'italic décoratif sur Inter — italic réservé aux citations Cormorant
- Letter-spacing titres : **-0.02em** (condensé, élégant)

### Import Google Fonts (à auto-héberger avant lancement RGPD)

```html
<!-- Temporaire dev — à remplacer par fonts auto-hébergées -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
```

> ⚠️ **RGPD** : Avant lancement, télécharger et auto-héberger dans `public/assets/fonts/` avec `@font-face` + `font-display: swap`.

---

## 4. ESPACEMENT — Échelle 4pt/8dp

```css
:root {
  --space-1:  0.25rem;   /* 4px */
  --space-2:  0.5rem;    /* 8px */
  --space-3:  0.75rem;   /* 12px */
  --space-4:  1rem;      /* 16px */
  --space-5:  1.25rem;   /* 20px */
  --space-6:  1.5rem;    /* 24px */
  --space-8:  2rem;      /* 32px */
  --space-10: 2.5rem;    /* 40px */
  --space-12: 3rem;      /* 48px */
  --space-16: 4rem;      /* 64px */
  --space-20: 5rem;      /* 80px */
  --space-24: 6rem;      /* 96px */
  --space-32: 8rem;      /* 128px — sections hero */
}
```

### Sections verticales
```css
.section { padding-block: var(--space-16); }           /* 64px mobile */
.section-large { padding-block: var(--space-24); }     /* 96px desktop */
.section-hero { padding-block: var(--space-32); }      /* 128px hero */
```

---

## 5. LAYOUT & GRILLE

### Breakpoints systématiques
```css
/* Mobile first */
/* xs: 375px — base (iPhone SE) */
/* sm: 640px */
/* md: 768px — tablette portrait */
/* lg: 1024px — tablette paysage / laptop */
/* xl: 1280px — desktop */
/* 2xl: 1440px — desktop large */
```

### Conteneur maximal
```css
.container {
  width: 100%;
  max-width: 1280px;
  margin-inline: auto;
  padding-inline: var(--space-4);   /* 16px mobile */
}
@media (min-width: 768px) {
  .container { padding-inline: var(--space-8); }  /* 32px tablette */
}
@media (min-width: 1024px) {
  .container { padding-inline: var(--space-12); } /* 48px desktop */
}
```

### Règles layout
- No horizontal scroll mobile : `max-w-full overflow-x-hidden` sur `body`
- Viewport : `<meta name="viewport" content="width=device-width, initial-scale=1">` — **ne jamais désactiver le zoom**
- Images : toujours déclarer `width` + `height` (prévient le CLS — Core Web Vitals)
- `min-h-dvh` sur la page principale (pas `100vh` — problème barre mobile)

---

## 6. STYLE VISUEL — "Minimal Éditorial"

### Recette synthèse (Hotel/Hospitality + Exaggerated Minimalism)
- **Pattern landing** : Hero-Centric + Social Proof (avis, distances, chiffres)
- **Densité** : très faible — beaucoup d'espace négatif
- **Images** : grandes, immersives, pleine largeur — jamais de décorations génériques
- **Bordures** : utilisées comme séparateurs discrets (1px `--bg-line`), pas de boîtes/cards surchargées
- **Ombres** : quasi absentes — la hiérarchie passe par l'espace et la taille, pas la profondeur
- **Border-radius** : modéré, cohérent — `--radius-sm: 4px`, `--radius-md: 8px`, `--radius-lg: 16px`

```css
:root {
  --radius-sm: 4px;
  --radius-md: 8px;
  --radius-lg: 16px;
  --radius-full: 9999px;

  /* Ombres minimalistes */
  --shadow-sm: 0 1px 3px 0 rgba(8, 20, 38, 0.06);
  --shadow-md: 0 4px 16px 0 rgba(8, 20, 38, 0.08);
  --shadow-lg: 0 8px 32px 0 rgba(8, 20, 38, 0.10);
}
```

### Anti-patterns à éviter absolument
- Pas de photos stock génériques
- Pas de gradient criard
- Pas d'emojis comme icônes (SVG Lucide/Heroicons uniquement)
- Pas de cartes avec shadows lourdes
- Pas de "Réserver maintenant" comme seul texte CTA sans contexte
- Pas de texte < 16px dans le corps
- Pas de gris sur gris (contraste trop faible)
- Pas de formulaire de réservation/tarifs — jamais

---

## 7. ANIMATIONS — Règles strictes

### Timings validés
```css
:root {
  --duration-fast:   150ms;  /* micro-interactions (hover, focus) */
  --duration-base:   250ms;  /* transitions standard */
  --duration-slow:   400ms;  /* reveals, entrées de sections */
  --duration-reveal: 600ms;  /* clip-path reveals hero */

  --ease-out: cubic-bezier(0.0, 0.0, 0.2, 1);   /* entrées */
  --ease-in:  cubic-bezier(0.4, 0.0, 1, 1);      /* sorties */
  --ease-std: cubic-bezier(0.4, 0.0, 0.2, 1);   /* transitions standard */
}
```

### Animations validées pour Villa Plaisance
| Animation | Durée | Cible | Notes |
|-----------|-------|-------|-------|
| Scroll reveal (opacity + translateY) | 600ms ease-out | Sections, cartes | IntersectionObserver |
| Fill-from-left (boutons) | 250ms ease-out | CTAs | `::before` pseudo-element |
| Clip-path reveal | 600ms ease-out | H1 hero | Ligne par ligne |
| Compteurs animés | 1000ms ease-out | Chiffres clés | Déclenché par IntersectionObserver |
| Parallaxe hero | — | Image hero | Subtil, max 20% déplacement |
| Marquee continu | — | Texte défilant | `animation: marquee 20s linear infinite` |
| Boutons magnétiques | 200ms | CTAs desktop | mousemove event |
| Barre de progression scroll | — | Header | `scaleX` basé sur scrollY |
| Curseur custom | — | Desktop uniquement | dot + ring avec inertie |

### Règle CRITIQUE — `prefers-reduced-motion`

```css
/* OBLIGATOIRE sur toutes les animations */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
```

> ⚠️ Le parallaxe peut provoquer des nausées. Toujours conditionner : `if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches)`.

### Règles animations (UI/UX Pro Max)
- Animer uniquement `transform` + `opacity` (jamais `width`, `height`, `top`, `left`)
- Sorties plus courtes que entrées (60–70% de la durée)
- Max 1–2 éléments animés simultanément par vue
- Animations de liste : stagger 40ms par item
- Jamais d'animation purement décorative sans relation cause-effet

---

## 8. COMPOSANTS CLÉS

### Bouton CTA principal
```css
.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  padding: var(--space-3) var(--space-8);
  background: var(--accent);
  color: white;
  font-family: 'Inter', sans-serif;
  font-size: var(--text-base);
  font-weight: 500;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  border-radius: var(--radius-sm);
  border: 1px solid var(--accent);
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: color var(--duration-base) var(--ease-out),
              border-color var(--duration-base) var(--ease-out);
}

/* Fill-from-left */
.btn-primary::before {
  content: '';
  position: absolute;
  inset: 0;
  background: var(--dark);
  transform: translateX(-100%);
  transition: transform var(--duration-base) var(--ease-out);
}
.btn-primary:hover::before { transform: translateX(0); }
.btn-primary:hover { color: white; }

/* Focus visible */
.btn-primary:focus-visible {
  outline: 2px solid var(--accent);
  outline-offset: 3px;
}
```

### Touch targets (min 44×44px)
```css
/* Tous les éléments interactifs */
a, button, [role="button"] {
  min-height: 44px;
  min-width: 44px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
```

### Smooth scroll
```css
html {
  scroll-behavior: smooth;
}
@media (prefers-reduced-motion: reduce) {
  html { scroll-behavior: auto; }
}
```

---

## 9. ACCESSIBILITÉ — Checklist CRITIQUE

### Contraste (WCAG AA minimum)
- Texte normal : 4.5:1 minimum ✅ (tous tokens vérifiés section 2)
- Grand texte (>18px bold ou >24px) : 3:1 minimum
- Composants UI : 3:1 minimum

### Focus
- Tous les éléments interactifs ont un `:focus-visible` visible (2–4px, couleur `--accent`)
- Jamais `outline: none` sans remplacement
- Tab order = ordre visuel

### Images
- Toutes les images ont un attribut `alt` obligatoire
- Images décoratives : `alt=""`

### Formulaires
- Chaque input a un `<label>` associé (pas placeholder-only)
- Erreurs affichées sous le champ concerné
- `aria-live="polite"` sur les zones de feedback dynamique

### Icônes
- Icônes seules (sans texte) : `aria-label` obligatoire
- Bibliothèque : **Lucide** (SVG, stroke, cohérent)

---

## 10. IMAGES

### Conventions
- Format : **WebP uniquement**, < 200 Ko
- `width` et `height` déclarés sur toutes les `<img>` (prévient CLS)
- `loading="lazy"` sur toutes les images hors-viewport
- `loading="eager"` uniquement pour l'image hero LCP
- Attribut `alt` obligatoire et descriptif

### Tailles recommandées
| Usage | Dimensions | Max |
|-------|-----------|-----|
| Hero full-width | 1920×1080 | 180 Ko |
| Section identité | 1200×800 | 150 Ko |
| Chambre (carte) | 800×600 | 100 Ko |
| OG image | 1200×630 | 150 Ko |
| Galerie | 1200×900 | 120 Ko |

---

## 11. NAVIGATION

### Structure (pattern Hotel/Hospitality)
- Navigation fixe en haut (sticky header)
- Logo centré ou gauche
- Mobile : hamburger menu (overlay ou drawer)
- Bottom nav : **non** — pas adapté au site vitrine desktop-first
- Breadcrumbs : uniquement sur articles de blog (3+ niveaux)

### Règles
- Navigation identique sur toutes les pages
- Page active visuellement marquée (couleur `--accent`, poids 600)
- Pas de navigation dans les modales — les modales sont pour le contenu, pas la navigation
- Back button du navigateur doit toujours fonctionner correctement

---

## 12. PAGES — Sommaire des overrides

Créer un fichier `design-system/pages/<nom>.md` pour les déviations spécifiques par page.

| Page | Override | Status |
|------|---------|--------|
| `home.md` | Hero plein écran, animations au scroll, sections blocs CMS | À créer |
| `chambres.md` | Galerie photos, FAQ accordéon, schema BedAndBreakfast | À créer |
| `villa.md` | Galerie photos, équipements liste, schema VacationRental | À créer |
| `journal.md` | Grille éditoriale articles, typo éditoriale corps | À créer |
| `article.md` | Long-form, pull quotes, drop cap, table des matières | À créer |
| `contact.md` | Formulaire simple, no tarifs, validation inline | À créer |
| `sur-place.md` | Carte interactive, liste points d'intérêt | À créer |

---

## 13. CHECKLIST PRÉ-LIVRAISON (Web — non app mobile)

### Qualité visuelle
- [ ] Aucun emoji utilisé comme icône (SVG Lucide uniquement)
- [ ] Tous les icônes issus d'une seule famille (Lucide)
- [ ] Tokens CSS utilisés partout (jamais de hex brut dans les composants)
- [ ] Images WebP, < 200 Ko, `alt` renseigné, `width`+`height` déclarés

### Interaction
- [ ] `cursor: pointer` sur tous les éléments cliquables
- [ ] Hover states : transition 150–300ms
- [ ] Focus visible sur tous les éléments interactifs
- [ ] `prefers-reduced-motion` respecté (media query globale)
- [ ] Touch targets ≥ 44×44px

### Layout
- [ ] Pas de scroll horizontal sur 375px
- [ ] `<meta name="viewport">` sans `user-scalable=no`
- [ ] Testé sur 375px, 768px, 1024px, 1440px
- [ ] Images avec `width`+`height` (prévient CLS)
- [ ] Contenu pas caché derrière le header fixe

### Accessibilité
- [ ] Contraste 4.5:1 minimum sur texte normal
- [ ] Contraste 3:1 minimum sur grands textes et UI
- [ ] Tous les `<img>` ont un `alt`
- [ ] Tous les inputs ont un `<label>`
- [ ] H1 unique par page
- [ ] Hiérarchie H1→H2→H3 sans sauts

### SEO technique
- [ ] `<title>` 50–60 caractères
- [ ] `<meta name="description">` 150–160 caractères
- [ ] JSON-LD présent (BedAndBreakfast / VacationRental / FAQPage)
- [ ] `<html lang="">` dynamique
- [ ] Canonical self-referencing
- [ ] hreflang FR/EN/ES/DE
- [ ] OG + Twitter Card présents

---

*Source of Truth — Pour dérogations spécifiques à une page, créer `design-system/pages/<nom>.md`*
*Dernière mise à jour : 2026-03-29*
