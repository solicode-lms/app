
```bash
[System.Text.Encoding]::Default
$OutputEncoding = [System.Text.Encoding]::UTF8

Get-Content .\sauvegarde_29_05_25.sql -Raw -Encoding UTF8 | mysql -u root -p solicode_lms
```

Pour supprimer tous les tables 

````
php artisan db:wipe
````