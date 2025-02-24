# Procédure de mise à jour de SoliLMS sur le serveur


1. Backup de la base de donnée 


````bash
mysqldump -u root -p nom_base_de_donnees > sauvegarde.sql
````


2. Migration 
- execution de migration 
  - insertion de module : code 
- Ajouter le droit d'afficher, lecture Projet pour afficher les projet qui lui sont affectés


````bash
sudo git pull
sudo npm install


sudo chmod -R 755 /var/www/app/
sudo chown -R www-data:www-data /var/www/app/
sudo php artisan config:clear
sudo php artisan cache:clear
sudo php artisan optimize:clear
````

## Validation des noms des classes

````bash
sudo composer dump-autoload
````


## Initialisation de la base de données

````bash
sudo php artisan migrate
````