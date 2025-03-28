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