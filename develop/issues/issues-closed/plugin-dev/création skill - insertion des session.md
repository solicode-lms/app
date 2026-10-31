

# Création de skill : Insertion des session de puis les ficheirs CSV

Ce skill peremt à parrie des fichier csv exporter depuis le site autoformation Web ou autoformation Mobile de remplire les fichiers csv de DataSeeder de : 

php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\CompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\ChapitreSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\SessionFormationSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\AlignementUaSeeder"


on traite chaqe mise à jour soit le développement Web soit le développement Mobile.

puis exectuer chaque commende et voire si il ya des problème d'execution, si il existe une problème d'execution, l'agent doit générer un prompt pour l'agent : auformation-web ou l'agent autofmration-mobule pour fixer le problème pour générer le csv qui va permettre de mettre à jour la session, les compétences, les micro compétences, les unités d'apprentissage et les chapitres.

il faut aussi, Créer un script PHP qui doit petre executer sur le projet pour créer un fichier csv qui indique les ligne des donnés à supprimer, les line à suppriumer sont ceux qui exisiste dans le fichier d'importtation ders session mais qui n'exite pas dans la base de donnes pour la filiere en traitement.

Le script et les fichiers à importer doit être existe dans un dossier à ajoutr dnas le projet , tu doit spécifier le nom convenable pour ce dossier, 

Il faut donner ce workflow à valider et propoer des point de validation par le concepteur ( moi) dans notre workflow d'importation. 