# Page : Accueil (home)
*Override du MASTER — déviations spécifiques à la homepage*

Lire d'abord `design-system/MASTER.md`. Ce fichier ne liste que les **déviations**.

---

## Pattern landing (Hotel/Hospitality + Hero-Centric)

Structure de sections dans l'ordre :

```
1. HERO              — Image plein écran, H1 animé ligne par ligne, CTA unique
2. IDENTITÉ          — Pitch 300 mots + photo identité (reflet piscine)
3. CHIFFRES CLÉS     — Compteurs animés (8 min Châteauneuf, 4 chambres, 12×6m piscine)
4. OFFRES (2 blocs)  — Chambre d'hôtes (sept-juin) | Villa entière (juil-août)
5. TERRITOIRE        — Triangle d'Or, carte/distances
6. AVIS              — Extrait 2–3 avis clients (AggregateRating)
7. JOURNAL           — 3 derniers articles (grille)
8. CTA FINAL         — Bloc contact simple
```

---

## Hero — Dérogations au MASTER

### Hauteur
```css
.hero {
  height: 100svh;     /* svh = small viewport height — gère la barre mobile */
  min-height: 600px;
  position: relative;
  overflow: hidden;
}
```

### Image hero
- Fichier : `IMG_4610.jpeg` → **convertir en WebP** : `hero.webp`
- `loading="eager"` + `fetchpriority="high"` (c'est le LCP)
- `object-fit: cover` + `object-position: center 30%` (garde le ciel visible)
- Pas de `loading="lazy"` sur cette image

### Overlay
```css
.hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom,
    rgba(8, 20, 38, 0.15) 0%,
    rgba(8, 20, 38, 0.40) 100%
  );
}
```

### H1 hero
- Police : Cormorant Garamond 300
- Taille : `clamp(2.5rem, 6vw, 5rem)`
- Couleur : white
- Animation : clip-path reveal ligne par ligne (délai 150ms entre chaque)
- Centré verticalement dans le hero

### CTA hero
- Un seul CTA : "Découvrir la maison" ou "Voir les chambres"
- Style : `btn-primary` du MASTER + version inversée (border blanc, fond transparent → fond blanc hover)
- Pas de formulaire de réservation — jamais

---

## Section Chiffres clés

```html
<!-- Données à afficher -->
<dl>
  <dt>8 min</dt>     <dd>de Châteauneuf-du-Pape</dd>
  <dt>15 min</dt>    <dd>d'Avignon</dd>
  <dt>18 min</dt>    <dd>d'Orange</dd>
  <dt>12 × 6 m</dt>  <dd>piscine privée</dd>
  <dt>4</dt>         <dd>chambres</dd>
  <dt>10</dt>        <dd>personnes max (villa)</dd>
</dl>
```

- Compteurs JS animés (0 → valeur finale, 1000ms ease-out)
- Déclenché par IntersectionObserver (threshold: 0.3)
- `prefers-reduced-motion` → afficher directement la valeur finale sans animation

---

## Section Offres — 2 blocs côte à côte

### Chambre d'hôtes (sept–juin)
- Fond : `--bg-soft` (#EBF2FA)
- Titre : "Chambre d'hôtes"
- Sous-titre : "De septembre à juin"
- 3 bullets max : piscine partagée, petit-déjeuner maison, 2 chambres (Verte + Bleue)

### Villa entière (juil–août)
- Fond : `--dark` (#081426) avec texte blanc
- Titre : "La Villa en exclusivité"
- Sous-titre : "Juillet et août"
- 3 bullets max : piscine privée 12×6m, 4 chambres, 10 personnes max

---

## Section Avis

- Afficher uniquement si `reviewCount > 0` (condition BDD — voir audit SEO)
- 2–3 avis maximum sur la homepage
- Schema `AggregateRating` JSON-LD conditionnel (idem)
- Style : citation en italique Cormorant Garamond, nom en Inter 500

---

## Animations spécifiques homepage

| Animation | Règle |
|-----------|-------|
| H1 clip-path reveal | Déclencher au chargement (pas au scroll) — délai 300ms après DOMContentLoaded |
| Sections scroll reveal | IntersectionObserver, threshold 0.15, translateY(40px) → (0), opacity 0→1, 600ms |
| Parallaxe hero | `transform: translateY(scrollY * 0.3)` — désactivé si `prefers-reduced-motion` |
| Marquee territoire | Défile noms des villages/sites — `animation: marquee 25s linear infinite` |
| Compteurs | IntersectionObserver + requestAnimationFrame |

---

## SEO — Données structurées homepage

Voir `SEO-AUDIT-CODE-2026-03-28.md` pour les corrections à faire.

**Rappel adresse à harmoniser :**
Vérifier la vraie adresse postale avant de fixer le JSON-LD `LodgingBusiness`.
Les deux adresses actuelles en conflit (`12 avenue de Rascassa` vs `205 Route de Courthézon`) sont invalides.

---

*Mise à jour : 2026-03-29*
