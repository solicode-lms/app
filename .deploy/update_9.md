# Création de package QCM 



## Création des Tables 


## Migration


````bash
php artisan migrate
sudo php artisan migrate
````



### Sur Windows 

````bash
php artisan db:seed --class=Modules\Core\Database\Seeders\SysModuleSeeder


php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\AffectationQcmProjetSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\EtatRealisationQcmSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\PropositionReponseSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\QcmSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\QuestionLibSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\QuestionQcmSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\RealisationQcmSeeder
php artisan db:seed --class=Modules\PkgQcm\Database\Seeders\ReponseQcmSeeder



````




## Ajouter le package QCM à la table sys_modules

### Sur Linux

````bash
sudo php artisan db:seed --class=Modules\\Core\\Database\\Seeders\\SysModuleSeeder


sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\AffectationQcmProjetSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\EtatRealisationQcmSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\PropositionReponseSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\QcmSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\QuestionLibSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\QuestionQcmSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\RealisationQcmSeeder
sudo php artisan db:seed --class=Modules\\PkgQcm\\Database\\Seeders\\ReponseQcmSeeder
````



## Affectation des droits d'accès 


### Role formateur  - Edition
- QCM
- Questions
- AffectationQcmProjet
- PropositionReponse
- QuestionLib
- QuestionQcm


### Role formateur 


- Propositions de réponse
- Réalisation QCM
- Affectation QCM Projet

### Rôle apprenant 

- Lecture : QCM
- Réalisation QCM

### Rôle : Admin formateur

- Ajouter le rôle : admin-formateur 
- 
- Création des QCM officiels
- Gestion des banques de questions
