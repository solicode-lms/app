---
name: expert-deploy-update
description: >
  Expert en déploiement des mises à jour de données Solicode LMS.
  Permet de mettre à jour les fichiers CSV dans les dossiers data/ des modules
  et de lancer les seeders correspondants, à partir d'un dossier update_N/
  dans docs/8.déploiement/. Fonctionne en local (Windows) et sur le serveur (Linux/sudo).
triggers:
  - deploy update
  - déployer mise à jour
  - seeder update
  - update_N
  - mettre à jour les données
---

# Expert Deploy Update — Solicode LMS

## Rôle

Tu es l'expert en déploiement des mises à jour de données de **Solicode LMS**.
Tu aides à préparer et appliquer les mises à jour de données via les fichiers CSV et les seeders Laravel.

---

## Concept Clé

### Structure des Updates

Chaque mise à jour de données est versionnée sous la forme `update_N` :

```
docs/
└── 8.déploiement/
    ├── update_7/              ← dossier contenant les CSV de la v7
    │   ├── chapitres.csv
    │   ├── competences.csv
    │   ├── microCompetences.csv
    │   ├── uniteApprentissages.csv
    │   ├── sessionFormation.csv
    │   └── alignementUa.csv
    ├── update_7.md            ← commandes seeder pour la v7
    ├── update_8/
    └── update_8.md
```

### Mapping CSV → Module

| Fichier CSV               | Module cible                          | Seeder                                  |
|---------------------------|---------------------------------------|-----------------------------------------|
| `competences.csv`         | PkgCompetences/Database/data/         | CompetenceSeeder                        |
| `microCompetences.csv`    | PkgCompetences/Database/data/         | MicroCompetenceSeeder                   |
| `uniteApprentissages.csv` | PkgCompetences/Database/data/         | UniteApprentissageSeeder                |
| `chapitres.csv`           | PkgCompetences/Database/data/         | ChapitreSeeder                          |
| `phaseEvaluations.csv`    | PkgCompetences/Database/data/         | PhaseEvaluationSeeder                   |
| `critereEvaluations.csv`  | PkgCompetences/Database/data/         | CritereEvaluationSeeder                 |
| `sessionFormation.csv`    | PkgSessions/Database/data/            | SessionFormationSeeder                  |
| `alignementUa.csv`        | PkgSessions/Database/data/            | AlignementUaSeeder                      |
| `livrableSessions.csv`    | PkgSessions/Database/data/            | LivrableSessionSeeder                   |
| `filieres.csv`            | PkgFormation/Database/data/           | FiliereSeeder                           |
| `formateurs.csv`          | PkgFormation/Database/data/           | FormateurSeeder                         |
| `specialites.csv`         | PkgFormation/Database/data/           | SpecialiteSeeder                        |
| `modules.csv`             | PkgFormation/Database/data/           | ModuleSeeder                            |
| `anneeFormations.csv`     | PkgFormation/Database/data/           | AnneeFormationSeeder                    |

> **Note sur les noms de fichiers** : Les fichiers dans `update_N/` peuvent avoir des noms légèrement différents
> du CSV cible (ex: `sessionFormation.csv` vs `sessionFormations.csv`). Le script `deploy-update.cjs` gère
> les deux variantes.

---

## Workflow Standard

### Étape 1 — Préparer le dossier update_N

Créer le dossier `docs/8.déploiement/update_N/` et y placer les fichiers CSV contenant les nouvelles données.

**Format CSV requis** : header en première ligne, séparateur `,`, valeurs entre guillemets `"`.

Exemple de structure `chapitres.csv` :
```csv
"ordre","code","nom","unite_apprentissage_reference","duree_en_heure","isOfficiel","lien","description","formateur_reference","reference","is_imitation_ua"
"1","T.162.111","Mon tuto","DWB-UA.162.11","1","1","https://...","","","DWB-T.162.111","0"
```

### Étape 2 — Lancer le script deploy-update.cjs

**Simulation (dry-run) d'abord :**
```powershell
cd D:\solicode-lms\app
node .agent/skills/expert-deploy-update/deploy-update.cjs --version 7 --dry-run
```

**Appliquer pour de vrai :**
```powershell
node .agent/skills/expert-deploy-update/deploy-update.cjs --version 7
```

**Avec un chemin explicite :**
```powershell
node .agent/skills/expert-deploy-update/deploy-update.cjs --version 7 --app-root D:\solicode-lms\app
```

Le script va :
1. ✅ Copier les CSV de `update_7/` vers les dossiers `data/` des modules
2. ✅ Créer une sauvegarde automatique de l'ancien CSV (`nomfichier_backup_v6.csv`)
3. ✅ Afficher les commandes seeder pour Windows ET Linux/Serveur

### Étape 3 — Exécuter les seeders

**Windows (PowerShell) :**
```powershell
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\CompetenceSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder
php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\ChapitreSeeder
php artisan db:seed --class=Modules\PkgSessions\Database\Seeders\SessionFormationSeeder
php artisan db:seed --class=Modules\PkgSessions\Database\Seeders\AlignementUaSeeder
```

**Linux / Serveur (avec sudo) :**
```bash
sudo php artisan db:seed --class=Modules\\PkgCompetences\\Database\\Seeders\\CompetenceSeeder
sudo php artisan db:seed --class=Modules\\PkgCompetences\\Database\\Seeders\\MicroCompetenceSeeder
sudo php artisan db:seed --class=Modules\\PkgCompetences\\Database\\Seeders\\UniteApprentissageSeeder
sudo php artisan db:seed --class=Modules\\PkgCompetences\\Database\\Seeders\\ChapitreSeeder
sudo php artisan db:seed --class=Modules\\PkgSessions\\Database\\Seeders\\SessionFormationSeeder
sudo php artisan db:seed --class=Modules\\PkgSessions\\Database\\Seeders\\AlignementUaSeeder
```

---

## Guide de Création d'une Nouvelle Update

### Quand créer une update_N ?

Quand tu dois ajouter ou modifier des données en base de données (tutos, compétences, sessions, etc.).

### Procédure

**1. Créer le dossier :**
```
docs/8.déploiement/update_N/
```

**2. Placer les CSV avec les nouvelles données** (seulement les tables concernées)

**3. Créer le fichier `update_N.md`** avec les commandes seeder :
```markdown
## Update N — Description

php artisan db:seed --class=Modules\PkgCompetences\Database\Seeders\ChapitreSeeder

```
sudo php artisan db:seed --class=Modules\\PkgCompetences\\Database\\Seeders\\ChapitreSeeder
```
```

**4. Lancer le script** et vérifier avec `--dry-run` avant d'appliquer.

---

## Fonctionnement des Seeders

Les seeders utilisent `updateOrCreate` sur la colonne `reference` :
- Si un enregistrement avec la même `reference` existe → il est **mis à jour**
- Sinon → il est **créé**

Cela garantit l'**idempotence** : on peut relancer le seeder sans dupliquer les données.

---

## Ajouter un Nouveau Mapping CSV

Si un nouveau type de CSV n'est pas encore mappé dans `deploy-update.cjs`, il faut l'ajouter dans les deux objets :

```javascript
// Dans CSV_MAPPING
'nouveauFichier.csv': 'modules/PkgXxx/Database/data/nouveauxFichiers.csv',

// Dans SEEDER_MAPPING
'nouveauFichier.csv': 'Modules\\PkgXxx\\Database\\Seeders\\NouveauSeeder',
```

---

## Fichier Script

Le script Node.js se trouve dans :
```
.agent/skills/expert-deploy-update/deploy-update.cjs
```

**Arguments disponibles :**
| Argument            | Description                                      |
|---------------------|--------------------------------------------------|
| `--version N`       | Numéro de la mise à jour (obligatoire)           |
| `--dry-run`         | Simulation sans modification                     |
| `--app-root <path>` | Chemin racine de l'app (auto-détecté sinon)     |
| `--help`            | Afficher l'aide                                  |
