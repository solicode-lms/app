
## Seeders 

- Supprimer les sys_module en doublons 

````bash
# Ajouter les seeders et droit d'accès
sudo php artisan db:seed --class=Modules\\Core\\Database\\Seeders\\Base\\BaseSysModuleSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\CommentaireRealisationTacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\DependanceTacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\EtatRealisationTacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\HistoriqueRealisationTacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\LabelRealisationTacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\PrioriteTacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\RealisationTacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\TacheSeeder
sudo php artisan db:seed --class=Modules\\PkgGestionTaches\\Database\\Seeders\\TypeDependanceTacheSeeder
````


## Ajouter les droit d'accès au formateur 

- Pkg : Gestion tâches

## Ajouter les droit d'accès au apprenant