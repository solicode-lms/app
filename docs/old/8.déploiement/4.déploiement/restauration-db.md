# Restauration de la Base de Données

## Extraction de la Base de Données sur un Serveur Ubuntu

Pour extraire une base de données MySQL et générer un fichier de sauvegarde (`sauvegarde.sql`), utilisez la commande suivante :

```bash
mysqldump -u root -p solicode_lms > sauvegarde.sql
```

## Importation de la Base de Données sous Ubuntu

Pour restaurer la base de données depuis un fichier de sauvegarde, exécutez la commande suivante :

```bash
mysql -u root -p solicode_lms < sauvegarde.sql
```

## Importation de la Base de Données sous Windows (MySQL)

Si vous utilisez MySQL sous Windows, la commande d'importation est identique :

CMD

```bash
mysql -u root -p solicode_lms < sauvegarde.sql
```

Powershell
````

Get-Content .\sauvegarde_26_02_25.sql -Raw -Encoding UTF8 | mysql -u root -p solicode_lms


[System.Text.Encoding]::Default
$OutputEncoding = [System.Text.Encoding]::UTF8
Get-Content .\sauvegarde_26_02_25.sql | mysql -u root -p solicode_lms

````

💡 **Remarque** :
- Assurez-vous que le service MySQL est en cours d'exécution avant d'exécuter ces commandes.
- Remplacez `solicode_lms` par le nom de votre base de données si nécessaire.
- Si vous travaillez avec un serveur distant, ajoutez l'option `-h [adresse_du_serveur]`.