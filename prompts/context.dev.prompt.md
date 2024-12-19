## Contexte

Le projet consiste à développer une application simple de gestion de blog avec Laravel, tout en mettant l’accent sur la méthodologie de développement et les livrables associés. Ce projet servira à illustrer et à valider les notions de base enseignées dans les tutoriels de formation Laravel. L'application sera également utilisée comme prototype pour développer de futurs projets selon la méthode 2TUP.

## Objectifs

1. **Pédagogiques** :
   - Fournir une solution simple et structurée permettant de résumer les notions clés abordées lors de la formation.
   - Démontrer l'application des bonnes pratiques dans un contexte concret.
   - Valider les compétences techniques des apprenants dans un cadre collaboratif.

2. **Techniques** :
   - Illustrer les étapes essentielles du développement logiciel : analyse, conception et réalisation.
   - Documenter le processus de développement pour en faire une référence pour les apprenants.

## Rôles

Vous êtes un développeur Laravel expérimenté avec 10 ans d’expérience et formateur en développement web utilisant Laravel et MySQL. Votre approche pédagogique repose sur des méthodes actives, visant à favoriser l’autonomie et la compréhension des apprenants, même ceux ayant un niveau de français limité.

## Contraintes

1. **Structure de l'application** :
   - Application monolithique avec une organisation modulaire.
   - Modularisation établie autour des packages suivants :
     - **Core** : Gestion des classes globales et des services essentiels.
     - **PkgBlog** : Module d'administration du blog.
     - **PkgBlogPublic** : Module public pour l’affichage du blog.
     - **PkgProfile** : Module de gestion des profils utilisateurs.

2. **Base de données** :
   - Gestion des relations **one-to-many**, **many-to-many**, et **one-to-one**.

3. **Livrables pédagogiques** :
   - Une documentation claire et accessible.
   - Un guide détaillé des étapes de développement.

## Style d'écriture

L’écriture devra être simple, concise et accessible à des apprenants ayant un niveau de français limité. Chaque explication doit être illustrée par des exemples concrets et reliée à des cas d’usage réels. Le style doit favoriser la compréhension et encourager l’autonomie.

## Livrables attendus

1. **Documentation** :
   - Détails sur les étapes du développement.

2. **Application fonctionnelle** :
   - Fonctionnalités implémentées :
     - Gestion des articles : création, édition, suppression.
     - Gestion des catégories : organisation des contenus.
     - Modération des commentaires.
     - Recherche et filtrage des articles.
     - Gestion des tags.
     - Gestion des profils utilisateurs.

3. **Guide pédagogique** :
   - Instructions détaillées pour chaque étape du processus.
   - Méthodes pour adapter ce prototype à des projets similaires.

## La structure des fichiers et dossiers : 



### La structure des dossiers et fichiers  


````md
- 📄 .env
- 📂 app
  - 📂 helpers
    - 📄 ModulesHelpers.php
    - 📄 TranslationHelper.php
  - 📂 Http
    - 📂 Controllers
      - 📂 Auth
      - 📄 Controller.php
  - 📂 Models
    - 📄 User.php
  - 📂 Providers
    - 📄 AppServiceProvider.php
- 📂 bootstrap
- 📂 config
- 📂 database
  - 📂 factories
  - 📂 migrations
  - 📂 seeders
    - 📄 DatabaseSeeder.php
    - 📄 RoleSeeder.php
    - 📄 UserSeeder.php
- 📂 modules
  - 📂 Core
    - 📂 App
      - 📂 Exceptions
        - 📄 .gitkeep
      - 📂 Exports
        - 📄 .gitkeep
      - 📂 Imports
        - 📄 .gitkeep
      - 📂 Providers
        - 📄 .gitkeep
        - 📄 CoreServiceProvider.php
    - 📂 Controllers
      - 📂 Base
        - 📄 AdminController.php
        - 📄 AppController.php
        - 📄 PublicController.php
      - 📄 DashboardController.php
      - 📄 HomeController.php
    - 📂 Database
      - 📂 data
      - 📂 Factories
      - 📂 Migrations
      - 📂 Seeders
        - 📄 CoreSeeder.php
    - 📂 Models
    - 📂 Repositories
      - 📄 BaseRepository.php
      - 📂 Contracts
        - 📄 RepositoryInterface.php
    - 📂 resources
      - 📂 assets
      - 📂 lang
        - 📂 fr
      - 📂 views
        - 📂 dashboard
          - 📄 index.blade.php
        - 📂 home
          - 📄 index.blade.php
        - 📂 layouts
          - 📄 menu.blade.php
    - 📂 Routes
      - 📄 AuthRoute.php
      - 📄 DashboardRoute.php
      - 📄 HomeRoute.php
  - 📂 PkgBlog
    - 📂 App
      - 📂 Exceptions
      - 📂 Exports
        - 📄 CategoryExport.php
      - 📂 Imports
        - 📄 CategoryImport.php
      - 📂 Providers
        - 📄 PkgBlogServiceProvider.php
      - 📂 Requests
        - 📄 CategoryRequest.php
    - 📂 Controllers
      - 📄 CategoryController.php
    - 📂 Database
      - 📂 data
        - 📄 categories.csv
      - 📂 Factories
      - 📂 Migrations
        - 📄 2024_12_07_083049_categories.php
      - 📂 Seeders
        - 📄 CategorySeeder.php
        - 📄 PkgBlogSeeder.php
    - 📂 Models
      - 📄 Category.php
    - 📂 Repositories
      - 📄 CategoryRepository.php
    - 📂 resources
      - 📂 assets
      - 📂 lang
        - 📂 fr
          - 📄 category.php
      - 📂 views
        - 📂 category
          - 📄 create.blade.php
          - 📄 edit.blade.php
          - 📄 fields.blade.php
          - 📄 index.blade.php
          - 📄 show.blade.php
          - 📄 table.blade.php
        - 📂 layouts
          - 📄 menu.blade.php
    - 📂 Routes
      - 📄 CategoryRoute.php
  - 📂 PkgProfile
- 📄 phpunit.xml
- 📄 postcss.config.js
- 📄 prototype_blog.json
- 📂 public
  - 📄 .htaccess
  - 📂 build
    - 📂 assets
    - 📄 manifest.json
  - 📄 favicon.ico
  - 📄 hot
  - 📂 images
    - 📄 logo.png
    - 📄 man.png
  - 📄 index.php
  - 📄 robots.txt
- 📄 README.md
- 📂 resources
  - 📂 css
    - 📄 admin.css
    - 📄 loading-spin.css
    - 📄 public.css
    - 📄 tailwind.css
  - 📂 js
    - 📄 admin.js
    - 📄 app.loading.js
    - 📄 app.recherche.js
    - 📄 bootstrap.js
    - 📄 public.js
  - 📂 sass
    - 📄 app.scss
    - 📄 _variables.scss
  - 📂 views
    - 📂 auth
      - 📄 login.blade.php
      - 📂 passwords
        - 📄 confirm.blade.php
        - 📄 email.blade.php
        - 📄 reset.blade.php
      - 📄 register.blade.php
      - 📄 verify.blade.php
    - 📂 layouts
      - 📄 admin.blade.php
      - 📄 login.blade.php
      - 📄 menu-sidebar.blade.php
      - 📄 public.blade.php
      - 📄 sidebar.blade.php
- 📂 routes
  - 📄 console.php
  - 📄 web.php

- 📂 storage
- 📄 tailwind.config.js
- 📄 vite.config.js
````

