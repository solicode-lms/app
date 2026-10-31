# Dossier d'Importation des Sessions de Formation

Ce dossier centralise les fichiers CSV exportés depuis les dépôts **autoformation-web** et **autoformation-mobile**, ainsi que les scripts d'automatisation et d'analyse différentielle permettant de synchroniser Solicode LMS.

---

## 📁 Arborescence

```text
deploy/imports/sessions/
├── web/                           # Fichiers CSV exportés pour la filière Web (DWB)
│   ├── competences.csv
│   ├── microCompetences.csv
│   ├── uniteApprentissages.csv
│   ├── chapitres.csv
│   ├── sessionFormations.csv
│   └── alignementUas.csv
├── mobile/                        # Fichiers CSV exportés pour la filière Mobile (DMB)
│   ├── competences.csv
│   ├── microCompetences.csv
│   ├── uniteApprentissages.csv
│   ├── chapitres.csv
│   ├── sessionFormations.csv
│   └── alignementUas.csv
├── reports/                       # Rapports d'analyse et CSV des éléments orphelins détectés
│   └── {filiere}_elements_a_supprimer.csv
├── detect_obsolete_data.php       # Script d'analyse différentielle BDD vs CSV et suppression en cascade
├── copy_to_dataseeders.php        # Script de copie vers modules/.../Database/data/
├── generate_update_doc.php        # Générateur de la fiche deploy/updates/update_X.md
└── README.md
```

---

## 🚀 Workflow d'Exécution

### 1. Dépôt des fichiers CSV
Déposer les 6 fichiers exportés dans le sous-dossier correspondant :
- Filière Web : `deploy/imports/sessions/web/`
- Filière Mobile : `deploy/imports/sessions/mobile/`

> **Note :** Les noms de fichiers peuvent être au singulier (`sessionFormation.csv`, `alignementUa.csv`) ou au pluriel (`sessionFormations.csv`, `alignementUas.csv`), le script les normalise automatiquement.

---

### 2. Détection des Données Orphelines (et Nettoyage)

Exécuter le script pour comparer la BDD actuelle avec le nouveau fichier d'importation :

```bash
# Analyse en lecture seule (génère le rapport dans reports/DWB_elements_a_supprimer.csv)
php deploy/imports/sessions/detect_obsolete_data.php --filiere=DWB

# Après validation du concepteur : suppression effective en cascade en BDD
php deploy/imports/sessions/detect_obsolete_data.php --filiere=DWB --delete
```

Le script produit un rapport CSV détaillé dans `reports/{filiere}_elements_a_supprimer.csv` et affiche le résumé console des tutoriels (chapitres) et unités d'apprentissage supprimés.

---

### 3. Copie vers les DataSeeders du Projet

Remplace le contenu des CSV officiels du LMS par les seules données de la filière cible en traitement :

```bash
php deploy/imports/sessions/copy_to_dataseeders.php --filiere=DWB
```

Les fichiers cibles mis à jour sont :
- `modules/PkgCompetences/Database/data/competences.csv`
- `modules/PkgCompetences/Database/data/microCompetences.csv`
- `modules/PkgCompetences/Database/data/uniteApprentissages.csv`
- `modules/PkgCompetences/Database/data/chapitres.csv`
- `modules/PkgSessions/Database/data/sessionFormations.csv`
- `modules/PkgSessions/Database/data/alignementUas.csv`

---

### 4. Génération de la Fiche de Déploiement `deploy/updates/update_X.md`

Génère la fiche de déploiement prête pour le serveur :

```bash
php deploy/imports/sessions/generate_update_doc.php --filiere=DWB --num=17 --title="Ajout des sessions Web S3 à S7"
```

---

### 5. Exécution des Seeders en Local

Lancer séquentiellement les 6 seeders :

```bash
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\CompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\ChapitreSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\SessionFormationSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\AlignementUaSeeder"
```
