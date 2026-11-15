# Dossier d'Importation des Sessions de Formation

Ce dossier centralise les fichiers CSV exportés depuis les dépôts **autoformation-web** et **autoformation-mobile**, ainsi que les scripts d'automatisation et d'analyse différentielle permettant de synchroniser Solicode LMS.

---

## 📁 Arborescence

```text
deploy/imports/sessions/
├── web/                           # Fichiers CSV exportés pour la filière Web (DWB - Niveau N1)
│   ├── competences.csv
│   ├── microCompetences.csv
│   ├── uniteApprentissages.csv
│   ├── chapitres.csv
│   ├── sessionFormations.csv
│   └── alignementUas.csv
├── mobile/                        # Fichiers CSV exportés pour la filière Mobile (DMB - Niveaux N2 / N3)
│   ├── competences.csv
│   ├── microCompetences.csv
│   ├── uniteApprentissages.csv
│   ├── chapitres.csv
│   ├── sessionFormations.csv
│   └── alignementUas.csv
├── reports/                       # Rapports d'analyse et CSV des éléments orphelins détectés
│   ├── DWB_N1_elements_a_supprimer.csv
│   └── DMB_N3_elements_a_supprimer.csv
├── detect_obsolete_data.php       # Analyse différentielle BDD vs CSV par filière et par niveau
├── copy_to_dataseeders.php        # Copie vers modules/.../Database/data/
├── generate_update_doc.php        # Générateur de la fiche deploy/updates/update_X.md
├── fix_tuto_codes.php             # Normalisation des codes/références de chapitres
└── README.md
```

---

## 🎯 Spécificité Multi-Niveaux & Règle des Codes

Dans Solicode LMS, le cycle d'apprentissage est structuré par filière et par niveau :
- **Filière Web (`DWB`) :** Mono-niveau **N1** (tous les codes débutent par `1`).
- **Filière Mobile (`DMB`) :** Multi-niveaux comportant le niveau **N2** et le niveau **N3**.

### Comment le niveau est identifié dans les codes :
Le chiffre suivant immédiatement le préfixe identifie le palier de compétences :
- Compétences : `C.311` ➔ Niveau **N3** | `C.211` ➔ Niveau **N2** | `C.111` ➔ Niveau **N1**
- Micro-compétences : `D.311.1` ➔ Niveau **N3**
- Unités d'apprentissage : `UA.351.21` ➔ Niveau **N3** | `UA.201.11` ➔ Niveau **N2**
- Chapitres (Tutoriels) : `T.311.211` ➔ Niveau **N3** | `T.201.111` ➔ Niveau **N2**

### Deux modes d'exécution :
1. **Détection Automatique (Recommandé) :** Les scripts analysent automatiquement les codes des CSV sources et déduisent le niveau sans saisie manuelle.
2. **Paramètre Explicite (`--niveau=X`) :** Vous pouvez forcer le niveau ciblé avec `--niveau=3`, `--niveau=N3`, `--niveau=2`, `--niveau=1` ou `--niveau=all` pour un audit global.

> [!IMPORTANT]
> Le filtrage par niveau est **essentiel** pour la filière Mobile : il garantit que l'importation de sessions N3 ne supprime pas les chapitres, UAs et alignements N2 existants en base de données.

---

## 🚀 Workflow d'Exécution

### 1. Dépôt des fichiers CSV
Déposer les 6 fichiers exportés dans le sous-dossier correspondant :
- Filière Web (N1) : `deploy/imports/sessions/web/`
- Filière Mobile (N2 ou N3) : `deploy/imports/sessions/mobile/`

> **Note :** Les noms de fichiers peuvent être au singulier (`sessionFormation.csv`, `alignementUa.csv`) ou au pluriel (`sessionFormations.csv`, `alignementUas.csv`), les scripts les normalisent automatiquement.

---

### 2. Détection des Données Orphelines (et Nettoyage Sécurisé)

Exécuter le script pour comparer la BDD actuelle avec les nouveaux CSV d'importation :

```bash
# Détection automatique du niveau depuis les fichiers CSV (ex: N1 pour Web, N3 pour Mobile)
php deploy/imports/sessions/detect_obsolete_data.php --filiere=DWB
php deploy/imports/sessions/detect_obsolete_data.php --filiere=DMB

# Ou en forçant explicitement le niveau ciblé
php deploy/imports/sessions/detect_obsolete_data.php --filiere=DMB --niveau=3
php deploy/imports/sessions/detect_obsolete_data.php --filiere=DMB --niveau=2

# Après validation formelle du concepteur : suppression effective en cascade en BDD
php deploy/imports/sessions/detect_obsolete_data.php --filiere=DMB --niveau=3 --delete
```

Le script génère un rapport CSV cloisonné par niveau dans `reports/{filiere}_N{niveau}_elements_a_supprimer.csv` (ex. `reports/DMB_N3_elements_a_supprimer.csv`).

---

### 3. Copie vers les DataSeeders du Projet

Remplace le contenu des CSV officiels du LMS par les seules données de la filière et du niveau cibles :

```bash
# Détection automatique du niveau
php deploy/imports/sessions/copy_to_dataseeders.php --filiere=DWB
php deploy/imports/sessions/copy_to_dataseeders.php --filiere=DMB

# Ou avec niveau forcé
php deploy/imports/sessions/copy_to_dataseeders.php --filiere=DMB --niveau=3
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

Génère la fiche de déploiement prête pour le serveur avec la commande Linux sécurisée (incluant le flag `--niveau`) :

```bash
# Exemple pour le Web (N1)
php deploy/imports/sessions/generate_update_doc.php --filiere=DWB --num=17 --title="Mise à jour sessions Web S3 à S7"

# Exemple pour le Mobile (N3)
php deploy/imports/sessions/generate_update_doc.php --filiere=DMB --niveau=3 --title="Ajout sessions Mobile N3"
```

---

### 5. Exécution des Seeders en Local

Lancer séquentiellement les 6 seeders dans l'ordre strict des dépendances :

```bash
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\CompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\ChapitreSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\SessionFormationSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\AlignementUaSeeder"
```

---

### 6. Script Complémentaire : Normalisation des Codes de Tutoriels

En cas de codes malformés à 4 parties (`T.152.21.1` au lieu de `T.152.211`), utiliser le script dédié :

```bash
# Simulation en lecture seule
php deploy/imports/sessions/fix_tuto_codes.php --dry-run --filiere=DMB --niveau=3

# Application réelle en base de données
php deploy/imports/sessions/fix_tuto_codes.php --apply --filiere=DMB --niveau=3
```
