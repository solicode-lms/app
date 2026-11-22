# Procédure de Sauvegarde & Restauration de Base de Données


## 2. Commandes Manuelles (Windows / PowerShell)

### Étape 1 : Supprimer toutes les tables (Initialisation)
```powershell
php artisan db:wipe
```

### Étape 2 : Importer le dump SQL en UTF-8 (sans BOM)
```powershell
[Console]::OutputEncoding = New-Object System.Text.UTF8Encoding($false)
$OutputEncoding = New-Object System.Text.UTF8Encoding($false)
Get-Content .\solicode-2026_20_11_26.sql -Raw -Encoding UTF8 | mysql -u root -p solicode_2026
```

> **Alternative recommandée (plus rapide et sans surcharge mémoire) :**
> ```powershell
> cmd /c "mysql -u root -p solicode_2026 < .\solicode-2026_20_11_26.sql"
> ```

---



## 1. Méthode Recommandée : Assistant Automatique PowerShell

Un script interactif permet d'initialiser la base et d'importer automatiquement la dernière sauvegarde `.sql` présente dans le dossier `backup_db/` (triée selon sa date la plus récente) :

```powershell
.\backup_db\import-database.ps1
```

### Options disponibles :
- **Spécifier une base cible spécifique (par défaut `solicode_2026`) :**
  ```powershell
  .\backup_db\import-database.ps1 -Database solicode_2026
  ```
- **Spécifier manuellement un fichier particulier (sans détection auto) :**
  ```powershell
  .\backup_db\import-database.ps1 -File .\solicode-2026_29_10_26.sql
  ```
- **Importer sans réinitialiser la base (`db:wipe`) :**
  ```powershell
  .\backup_db\import-database.ps1 -SkipWipe
  ```
- **Mode automatique (sans invite interactive) :**
  ```powershell
  .\backup_db\import-database.ps1 -Force
  ```

---


## 3. Commandes Manuelles (Ubuntu / Linux)

```bash
mysql -u root -p --default-character-set=utf8 solicode_lms < sauvegarde_30_06_25.sql
```
