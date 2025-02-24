# Restauration de la base de données 


````bash
mysql -u root -p nom_base_de_donnees < sauvegarde.sql
mysqldump -u root -p solicode_lms > sauvegarde.sql
````



````
mysql -u root -p solicode_lms < sauvegarde.sql

````