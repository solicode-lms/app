# Création de package Tableau de bord



## Création des Tables 


````bash
php artisan migrate
````

````bash
sudo php artisan migrate
````


```bash
php artisan dashboard:recalculer-amorcage
php artisan dashboard:recalculer-gating
php artisan dashboard:recalculer-revisions
php artisan dashboard:simuler-activite-formateurs
php artisan dashboard:simuler-file-attente
php artisan dashboard:simuler-remediations
php artisan dashboard:simuler-integrite-ia
php artisan dashboard:simuler-couverture-pedagogique
php artisan dashboard:simuler-docimologie
```



## 2. Exécution des Seeders

### En local (Windows)
```bash
php artisan db:seed --class="Modules\PkgRealisationTache\Database\Seeders\LiveCodingSeeder"
```

### En production (Serveur Linux)
```bash
sudo php artisan db:seed --class="Modules\PkgRealisationTache\Database\Seeders\LiveCodingSeeder"
```

## Droit d'accès : 
ajouter au foramteur doit ad'accès au Livecoding : Views et Delete,

