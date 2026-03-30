# Mise en production — Villa Plaisance V7

## Etape 1 — cPanel : Creer la BDD

1. Aller sur **cpanel.o2switch.net** > **Bases de donnees MySQL**
2. Creer la base : `efkz3012_VPV7`
3. Associer l'utilisateur `efkz3012_vpuser` avec **Tous les privileges**

## Etape 2 — cPanel : Cloner le repo Git

1. **cPanel** > **Git Version Control** > **Create**
2. **Clone URL :** `https://github.com/JorgeCaneteAI/villaplaisance-v7.git`
3. **Repository Path :** `/home/efkz3012/repositories/villaplaisance-v7`
4. Cliquer **Create**

## Etape 3 — Transferer les fichiers

Dans **cPanel** > **Terminal** :

```bash
# Copier le code vers le web root
cp -R /home/efkz3012/repositories/villaplaisance-v7/* /home/efkz3012/v1.villaplaisance.fr/
chmod -R 755 /home/efkz3012/v1.villaplaisance.fr/
```

## Etape 4 — Creer le .env de production

Dans **cPanel** > **Terminal** :

```bash
cat > /home/efkz3012/v1.villaplaisance.fr/.env << 'EOF'
DB_HOST=localhost
DB_NAME=efkz3012_VPV7
DB_USER=efkz3012_vpuser
DB_PASS=TON_MOT_DE_PASSE_ICI
APP_ENV=production
APP_URL=https://v1.villaplaisance.fr
ADMIN_EMAIL=contact@villaplaisance.fr
EOF
```

> Remplace `TON_MOT_DE_PASSE_ICI` par le vrai mot de passe de `efkz3012_vpuser`.

## Etape 5 — Creer les tables et injecter les donnees

Dans **cPanel** > **Terminal** :

```bash
cd /home/efkz3012/v1.villaplaisance.fr

# 1. Migration (cree les tables)
php seeds/001_migration.php

# 2. Donnees de base (admin, pages, settings)
php seeds/002_seed_data.php

# 3. Sections CMS (blocs des pages)
php seeds/003_seed_sections.php

# 4. Tables reglages (booking, social, amenities)
php seeds/004_reglages_tables.php

# 5. Table medias
php seeds/005_media_table.php

# 6. Import photos
php seeds/006_import_photos.php

# 7. Cover images articles
php seeds/008_articles_cover_images.php

# 8. Images des pieces
php seeds/010_add_image_to_pieces.php
php seeds/011_add_images_to_pieces.php

# 9. Sections EN/ES
php seeds/012_seed_sections_en_es.php

# 10. Pieces, FAQ, Stats multilangue
php seeds/013_seed_pieces_faq_stats_multilang.php

# 11. Proximites multilangue
php seeds/014_proximites_multilang.php

# 12. Amenities multilangue
php seeds/015_amenities_multilang.php

# 13. Remplir traductions
php seeds/016_fill_translations.php

# 14. Champs partages
php seeds/017_copy_shared_fields_to_translations.php

# 15. Articles EN/ES
php seeds/018_articles_en_es_empty.php

# 16. Redirections et SEO files
php seeds/019_redirects_and_seo_files.php

# 17. Avis clients
php seeds/020_real_reviews.php

# 18. Livret multilangue
php seeds/021_livret_multilang.php

# 19. Livret complet
php seeds/022_livret_complet.php
```

## Etape 6 — Verifier

1. Ouvrir `https://v1.villaplaisance.fr/` — la page d'accueil doit s'afficher
2. Ouvrir `https://v1.villaplaisance.fr/admin/login` — se connecter avec :
   - Email : `contact@villaplaisance.fr`
   - Mot de passe : `VillaP@2026!!` (defini dans seed 002)
   - **CHANGER CE MOT DE PASSE IMMEDIATEMENT** dans Reglages

## Etape 7 — Uploads

Les photos ne sont pas dans le repo Git. Transferer le dossier `public/uploads/` via **cPanel** > **Gestionnaire de fichiers** :
- Destination : `/home/efkz3012/v1.villaplaisance.fr/public/uploads/`

## Rappels

- Le `.env` n'est PAS dans Git (protege par `.gitignore`)
- Le dossier `uploads/` n'est PAS dans Git
- Apres chaque `git push`, le `.cpanel.yml` deploie automatiquement
- Le mot de passe admin par defaut est dans le seed — **le changer en production**
