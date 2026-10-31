---
name: session-importer
description: Automatise l'importation, la synchronisation et le nettoyage des sessions, compétences, micro-compétences, UAs, chapitres et alignements depuis les CSV d'autoformation Web et Mobile, assiste proactivement au bootstrapping des répertoires et au dépôt des CSV, et gère les feedbacks d'erreur vers les agents sources.
version: 1.1.0
inspiration:
  source: "anthropic/claude-plugins-official"
  plugin: "feature-dev"
  pattern: "data synchronization pipeline & multi-agent feedback loop"
---

# 1. Mission
Ce skill standardise et automatise l'importation complète des données pédagogiques exportées depuis les sites **autoformation-web** et **autoformation-mobile** vers Solicode LMS :
1. **Assistance Proactive au Bootstrapping & Cadrage :** Diagnostic de l'arborescence `deploy/imports/sessions/`, proposition et création des dossiers manquants (`web/`, `mobile/`, `reports/`), et guidage pas-à-pas du concepteur pour déposer les 6 fichiers CSV au bon endroit.
2. **Détection différentielle des éléments orphelins :** Identification des tutoriels/chapitres et unités d'apprentissage supprimés dans l'autoformation et suppression sécurisée en cascade en BDD.
3. **Alimentation ciblée des fichiers CSV DataSeeder :** Mise à jour propre de `modules/PkgCompetences/Database/data/` et `modules/PkgSessions/Database/data/`.
4. **Génération automatique de la fiche de déploiement :** Production de `deploy/updates/update_X.md` (commandes locales Windows et production Linux).
5. **Exécution séquentielle et contrôlée des 6 seeders :** Déroulement ordonné avec arrêt immédiat et formulation d'un prompt d'incident ciblé pour l'agent source (`autoformation-web` ou `autoformation-mobile`) en cas d'erreur.

---

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - Réception d'une nouvelle livraison de sessions exportées depuis l'autoformation Web ou Mobile.
  - Demande d'insertion de sessions (ex : "Ajouter les sessions Web de S3 à S7", "Importer les sessions Mobile").
  - Premier lancement sans arborescence préparée ou fichiers manquants : guidage et assistance de l'agent.
  - Nettoyage et synchronisation des tutoriels ou UAs orphelines en base de données.
  - Préparation d'une release de contenu et rédaction d'une mise à jour `update_X.md`.
- **Quand NE PAS l'activer :**
  - Développement de nouvelles entités ou de contrôleurs CRUD -> Déclencher `feature-dev`.
  - Modification de schéma SQL ou création de migrations -> Déclencher `domain-scaffolder`.
  - Conception ou raffinement de compétences ou de règles IA -> Déclencher `plugin-dev`.

---

# 3. Entrées & Ressources à Charger
- **Ressource interne :** `.agents/skills/session-importer/resources/prompt-template-autoformation.md` (Template de prompt de rétroaction d'incident).
- **Scripts du dossier projet :**
  - `deploy/imports/sessions/detect_obsolete_data.php` (Analyse différentielle et purge en cascade).
  - `deploy/imports/sessions/copy_to_dataseeders.php` (Copie ciblée vers les DataSeeders).
  - `deploy/imports/sessions/generate_update_doc.php` (Générateur de fiche de déploiement).
- **Fichiers CSV sources :** `deploy/imports/sessions/web/*.csv` ou `deploy/imports/sessions/mobile/*.csv`.
- **Fichiers cibles DataSeeder :**
  - `modules/PkgCompetences/Database/data/` (`competences.csv`, `microCompetences.csv`, `uniteApprentissages.csv`, `chapitres.csv`).
  - `modules/PkgSessions/Database/data/` (`sessionFormations.csv`, `alignementUas.csv`).

---

# 4. Contraintes & Règles Inviolables
1. **Assistance Proactive au Démarrage (Dossiers Manquants ou CSV Absents) :** Si les répertoires sous `deploy/imports/sessions/` n'existent pas ou si les 6 fichiers CSV n'ont pas encore été déposés par le concepteur, **l'agent a l'interdiction formelle de tenter d'exécuter les scripts en aveugle**. L'agent doit obligatoirement proposer de créer les dossiers manquants, indiquer l'emplacement exact et la liste des 6 fichiers attendus, puis attendre la confirmation du dépôt par le concepteur.
2. **Règle du Remplacement des DataSeeders :** À chaque importation, les fichiers de `modules/PkgCompetences/Database/data/` et `modules/PkgSessions/Database/data/` sont vidés puis alimentés uniquement avec les données de la filière traitée (via `copy_to_dataseeders.php`).
3. **Respect des 4 Points de Validation Humaine (HITL) :** L'agent ne doit jamais lancer la suppression en BDD ou l'exécution en chaîne sans les arbitrages formels du concepteur.
4. **Ordre Séquentiel Inviolable des 6 Seeders :**
   1. `CompetenceSeeder`
   2. `MicroCompetenceSeeder`
   3. `UniteApprentissageSeeder`
   4. `ChapitreSeeder`
   5. `SessionFormationSeeder`
   6. `AlignementUaSeeder`
5. **Arrêt Immédiat en Cas d'Erreur :** Si un seeder échoue, interrompre immédiatement le workflow, isoler la ligne incriminée et générer le prompt de correction pour l'agent de l'autoformation concerné.
6. **Interdiction Absolue de `php artisan migrate` :** Ne jamais tenter d'altérer la structure de la BDD.
7. **Préservation des Fichiers `Base/` :** Ne modifier aucun seeder dans `Base/`.

---

# 5. Procédure Pas-à-Pas (Workflow d'Importation)

### Phase 1 : Diagnostic d'Arborescence, Cadrage & Assistance au Dépôt des CSV (Validation #1)
1. **Vérification et Création Proactive de l'Arborescence :**
   - Contrôler l'existence de l'arborescence :
     - `deploy/imports/sessions/`
     - `deploy/imports/sessions/web/`
     - `deploy/imports/sessions/mobile/`
     - `deploy/imports/sessions/reports/`
   - Si l'un de ces répertoires manque : proposer au concepteur de le créer immédiatement et l'initialiser (ex : `mkdir deploy/imports/sessions/{web,mobile,reports}`).
2. **Identification de la Filière Cible :**
   - Identifier la filière cible : `DWB` (Web) ou `DMB` (Mobile).
3. **Audit de Présence des 6 Fichiers CSV :**
   - Vérifier la présence effective des 6 fichiers CSV dans `deploy/imports/sessions/web/` ou `deploy/imports/sessions/mobile/` :
     1. `competences.csv`
     2. `microCompetences.csv`
     3. `uniteApprentissages.csv` (ou `uniteApprentissage.csv` / `uas.csv`)
     4. `chapitres.csv` (ou `chapitre.csv`)
     5. `sessionFormations.csv` (ou `sessionFormation.csv` / `sessions.csv`)
     6. `alignementUas.csv` (ou `alignementUa.csv`)
4. **Assistance au Dépôt si Fichiers Absents ou Incomplets :**
   - Si les fichiers CSV ne sont pas encore présents (dossier vide ou partiel) :
     - **Ne jamais lancer `detect_obsolete_data.php`**.
     - Assister chaleureusement le concepteur en lui indiquant :
       - Le chemin absolu ou relatif où déposer les fichiers : `deploy/imports/sessions/web/` (ou `mobile/`).
       - La liste des 6 fichiers requis exportés depuis le dépôt d'autoformation correspondant.
       - Inviter le concepteur à déposer ses exports et à notifier l'agent dès qu'ils sont en place.
5. **Point de Validation #1 :** Demander confirmation au concepteur de la filière et de la disponibilité effective des 6 fichiers CSV avant d'engager la Phase 2.

### Phase 2 : Détection des Données Orphelines & Validation #2
1. Lancer l'analyse différentielle en lecture seule :
   ```bash
   php deploy/imports/sessions/detect_obsolete_data.php --filiere=[DWB|DMB]
   ```
2. Examiner le rapport généré `deploy/imports/sessions/reports/[filiere]_elements_a_supprimer.csv`.
3. Présenter au concepteur le récapitulatif des tutoriels et UAs orphelins détectés.
4. **Point de Validation #2 :** Le concepteur valide la purge de ces éléments en base.

### Phase 3 : Suppression Directe en Cascade en BDD
1. Dès validation du concepteur, exécuter la suppression en BDD :
   ```bash
   php deploy/imports/sessions/detect_obsolete_data.php --filiere=[DWB|DMB] --delete
   ```
2. Vérifier que la suppression en cascade s'est déroulée sans erreur de contrainte.

### Phase 4 : Alimentation des DataSeeders
1. Remplacer les données des CSV officiels du LMS par celles de la filière traitée :
   ```bash
   php deploy/imports/sessions/copy_to_dataseeders.php --filiere=[DWB|DMB]
   ```
2. Contrôler que les 6 fichiers ont bien été copiés dans `modules/PkgCompetences/Database/data/` et `modules/PkgSessions/Database/data/`.

### Phase 5 : Génération de la Fiche `deploy/updates/update_X.md` & Validation #3
1. Générer la fiche de mise à jour prête pour le déploiement :
   ```bash
   php deploy/imports/sessions/generate_update_doc.php --filiere=[DWB|DMB] --num=[N] --title="[Titre]"
   ```
2. **Point de Validation #3 :** Le concepteur vérifie la fiche `deploy/updates/update_[N].md` générée.

### Phase 6 : Exécution Séquentielle des 6 Seeders & Gestion des Incidents
1. Exécuter un par un les seeders dans l'ordre strict :
   ```bash
   php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\CompetenceSeeder"
   php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder"
   php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder"
   php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\ChapitreSeeder"
   php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\SessionFormationSeeder"
   php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\AlignementUaSeeder"
   ```
2. **En cas de succès total :** Afficher le bilan final de synchronisation.
3. **En cas d'échec :**
   - Interrompre immédiatement la séquence.
   - Extraire le message d'erreur SQL/PHP, le fichier CSV et la référence incriminée.
   - Charger le template `resources/prompt-template-autoformation.md` et générer le prompt de demande de correction pour l'agent `autoformation-web` ou `autoformation-mobile`.
   - **Point de Validation #4 :** Soumettre le prompt généré au concepteur pour transmission.

---

# 6. Gestion des Ambiguïtés & Décisions
- **Dossiers d'import inexistants :** Si les dossiers `deploy/imports/sessions/{web,mobile,reports}` n'existent pas sur le disque, l'agent propose au concepteur de les créer automatiquement avec `mkdir` sans bloquer le processus.
- **Fichiers CSV non encore déposés :** L'agent ne tente jamais de lancer l'analyse différentielle sur un dossier vide (ce qui risquerait de considérer à tort toutes les entités de la BDD comme orphelines). Il liste les 6 fichiers attendus et attend le signalement du concepteur.
- **Noms singulier vs pluriel dans les CSV :** Les scripts acceptent automatiquement `sessionFormation.csv` ou `sessionFormations.csv`, `alignementUa.csv` ou `alignementUas.csv`.
- **Numéro d'update non fourni :** Le script `generate_update_doc.php` auto-incrémente automatiquement le numéro en analysant les fichiers existants dans `deploy/updates/`.
- **Présence d'accents ou caractères spéciaux :** Les CSV exportés d'autoformation sont lus en UTF-8 avec gestion propre des délimiteurs et guillemets.

---

# 7. Format de Sortie & Livrables
- Le guide d'assistance au dépôt des fichiers (en cas de premier lancement ou fichiers manquants).
- Le rapport CSV des orphelins : `deploy/imports/sessions/reports/{filiere}_elements_a_supprimer.csv`.
- La fiche de déploiement Markdown : `deploy/updates/update_{N}.md`.
- Les 6 fichiers DataSeeders mis à jour dans `modules/PkgCompetences/Database/data/` et `modules/PkgSessions/Database/data/`.
- En cas d'erreur de seed : le prompt formaté prêt pour l'agent `autoformation-web` ou `autoformation-mobile`.

---

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] L'arborescence des dossiers `deploy/imports/sessions/{web,mobile,reports}` a-t-elle été vérifiée et initialisée si nécessaire ?
- [ ] En cas de fichiers CSV absents, l'agent a-t-il assisté le concepteur avec les chemins exacts et la liste des 6 fichiers avant d'exécuter les scripts ?
- [ ] La filière cible (`DWB` ou `DMB`) a-t-elle été formellement validée ?
- [ ] Le rapport des orphelins a-t-il été audité avant de lancer `--delete` ?
- [ ] Les DataSeeders contiennent-ils uniquement les données de la filière traitée ?
- [ ] La fiche `deploy/updates/update_X.md` a-t-elle été générée avec les commandes local et prod ?
- [ ] L'ordre des 6 seeders a-t-il été scrupuleusement respecté ?
- [ ] En cas d'erreur, un prompt de rétroaction a-t-il été généré pour l'agent source ?
- [ ] Aucune migration (`php artisan migrate`) n'a-t-elle été exécutée ?
- [ ] Les classes `Base/` sont-elles demeurées intactes ?

---

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Dossier `deploy/imports/sessions/` ou sous-dossiers inexistants | Première utilisation ou répertoire non créé | Proposer de créer l'arborescence `deploy/imports/sessions/{web,mobile,reports}` |
| Fichiers CSV introuvables ou dossier vide | Le concepteur lance le skill avant d'avoir déposé les exports | Bloquer l'exécution, assister le concepteur en lui indiquant le chemin exact et la liste des 6 fichiers CSV attendus |
| `Cannot add or update a child row (foreign key constraint fails)` | Une référence parent (ex: `competence_reference`) n'existe pas | Isoler la ligne CSV, identifier la référence manquante et générer le prompt pour l'agent autoformation |
| Tutoriels orphelins subsistant après import | `--delete` n'a pas été exécuté | Lancer `php deploy/imports/sessions/detect_obsolete_data.php --filiere=... --delete` |
| Seeders exécutés dans le désordre | Dépendances non résolues | Réexécuter scrupuleusement selon l'ordre 1 à 6 |

---

# 10. Exemples Concrets (Few-Shot)

### Exemple A : Assistance proactive au premier lancement (dossiers manquants ou fichiers CSV absents)
**Situation :** Le concepteur lance `/session-importer` mais les fichiers CSV ne sont pas encore déposés.
**Comportement de l'agent :**
1. L'agent audite `deploy/imports/sessions/` et ses sous-dossiers (`web/`, `mobile/`, `reports/`). S'ils sont absents, il propose de les créer.
2. L'agent détecte qu'aucun fichier CSV n'est présent dans `deploy/imports/sessions/web/` ou `deploy/imports/sessions/mobile/`.
3. L'agent affiche un message d'orientation clair :
   ```markdown
   > 📂 **Arborescence vérifiée et prête.**
   > 
   > Pour importer les sessions de la filière **Web (DWB)** [ou Mobile (DMB)], veuillez déposer les 6 fichiers CSV exportés dans le dossier :
   > `deploy/imports/sessions/web/`
   > 
   > 📋 **Fichiers attendus :**
   > - `competences.csv`
   > - `microCompetences.csv`
   > - `uniteApprentissages.csv` (ou `uniteApprentissage.csv`)
   > - `chapitres.csv` (ou `chapitre.csv`)
   > - `sessionFormations.csv` (ou `sessionFormation.csv`)
   > - `alignementUas.csv` (ou `alignementUa.csv`)
   > 
   > ⚠️ *Note : Ces fichiers doivent être exportés depuis le dépôt `autoformation-web` ou `autoformation-mobile`.*
   > 
   > Merci de me confirmer dès que ces fichiers sont déposés pour que nous puissions lancer l'analyse différentielle !
   ```
4. L'agent attend la réponse du concepteur sans exécuter `detect_obsolete_data.php`.

### Exemple B : Importation standard réussie pour la filière Web
```bash
# 1. Audit des orphelins (après confirmation de présence des CSV)
php deploy/imports/sessions/detect_obsolete_data.php --filiere=DWB
# 2. Suppression validée
php deploy/imports/sessions/detect_obsolete_data.php --filiere=DWB --delete
# 3. Copie vers les DataSeeders
php deploy/imports/sessions/copy_to_dataseeders.php --filiere=DWB
# 4. Génération de update_17.md
php deploy/imports/sessions/generate_update_doc.php --filiere=DWB --num=17 --title="Ajouter les sessions Web de S3 à S7"
# 5. Exécution des seeders
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\CompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder"
php artisan db:seed --class="Modules\PkgCompetences\Database\Seeders\ChapitreSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\SessionFormationSeeder"
php artisan db:seed --class="Modules\PkgSessions\Database\Seeders\AlignementUaSeeder"
```

### Exemple C : Prompt généré lors d'une erreur de foreign key
```markdown
### 📢 Demande de Correction CSV pour l'Agent autoformation-web

**Contexte :** Lors de l'importation des sessions Web, le seeder ChapitreSeeder a échoué.
**Erreur :** `SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row`
**Fichier :** `chapitres.csv` - Ligne 42 - Réf: `DWB-T.125.10`
**Cause :** La clé `unite_apprentissage_reference` vaut `DWB-UA.125.99`, or cette UA n'existe pas dans `uniteApprentissages.csv`.
**Action requise :** Corriger la référence UA du tutoriel dans `autoformations-web` et régénérer les CSV.
```
