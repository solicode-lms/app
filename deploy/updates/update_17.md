# Synchronisation des sessions Web et normalisation des tutoriels

## 1. Données orphelines à supprimer en BDD

Les éléments ci-dessous existent en base de données pour la filière **Développement Web** mais n'apparaissent plus dans les fichiers d'importation CSV.
La suppression en cascade de ces enregistrements doit être effectuée via le script dédié ou les commandes ci-après.

### Liste des éléments orphelins détectés (17) :
- **[Chapitre (Tutoriel)]** ID: `147` | Réf: `DWB-TUTO-UA.121.12` | Tutoriel UA.121.12 : Contrôler l'exécution avec les conditions et les boucles
- **[Chapitre (Tutoriel)]** ID: `146` | Réf: `DWB-TUTO-UA.112.12` | Tutoriel UA.112.12 : Construire le dictionnaire de données
- **[Chapitre (Tutoriel)]** ID: `145` | Réf: `DWB-TUTO-UA.111.12` | Tutoriel UA.111.12 : Définir les fonctionnalités de la partie publique
- **[Chapitre (Tutoriel)]** ID: `47` | Réf: `DWB-T.151.142` | Les règles de sécurité avant de valider un commit
- **[Chapitre (Tutoriel)]** ID: `148` | Réf: `DWB-TUTO-UA.151.11` | Tutoriel UA.151.11 : Préparer l'environnement et déposer son premier travail avec VS Code
- **[Chapitre (Tutoriel)]** ID: `43` | Réf: `DWB-T.151.132` | Mettre à jour son ordinateur avec git pull
- **[Chapitre (Tutoriel)]** ID: `149` | Réf: `DWB-TUTO-UA.122.11` | Tutoriel UA.122.11 : Construire la structure de base d’une page HTML
- **[Chapitre (Tutoriel)]** ID: `150` | Réf: `DWB-TUTO-UA.122.21` | Tutoriel UA.122.21 : Comprendre et appliquer les bases de CSS
- **[Chapitre (Tutoriel)]** ID: `191` | Réf: `DWB-T.122.244` | Comprendre l'héritage
- **[Chapitre (Tutoriel)]** ID: `193` | Réf: `DWB-T.122.245` | Gérer les états d'un élément
- **[Chapitre (Tutoriel)]** ID: `198` | Réf: `DWB-T.122.246` | Centraliser les valeurs visuelles
- **[Chapitre (Tutoriel)]** ID: `202` | Réf: `DWB-T.122.247` | Construire des styles communs aux pages
- **[Chapitre (Tutoriel)]** ID: `206` | Réf: `DWB-T.122.248` | Projet de synthèse — Harmonisation du Blog
- **[Chapitre (Tutoriel)]** ID: `4` | Réf: `DWB-T.161.11` | Découvrir Google Slides et le partage
- **[AlignementUa]** ID: `2` | Réf: `DWB-S1-DWB-UA.161.12` | Alignement DWB-S1-DWB-UA.161.12
- **[AlignementUa]** ID: `3` | Réf: `DWB-S1-DWB-UA.161.13` | Alignement DWB-S1-DWB-UA.161.13
- **[AlignementUa]** ID: `4` | Réf: `DWB-S1-DWB-UA.161.14` | Alignement DWB-S1-DWB-UA.161.14

### 1.1 Normalisation préalable des codes de tutoriels en production (Linux)
Avant de lancer la suppression ou les seeders, normaliser les codes et références des chapitres en BDD :
```bash
sudo php deploy/imports/sessions/fix_tuto_codes.php --apply
```

### 1.2 Suppression des éléments orphelins en production (Linux) :
```bash
php deploy/imports/sessions/detect_obsolete_data.php --filiere=DWB --delete
```

## 2. Exécution des Seeders

### En local (Windows)
```bash
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\CompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\ChapitreSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\SessionFormationSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\AlignementUaSeeder"
```

### En production (Serveur Linux)
```bash
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\CompetenceSeeder"
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder"
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder"
sudo php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\ChapitreSeeder"
sudo php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\SessionFormationSeeder"
sudo php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\AlignementUaSeeder"
```
