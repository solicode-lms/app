---
name: sys-gapp
description: Détermine la configuration JSON des métadonnées Gapp (scope, filter) et fournit les commandes associées.
---

# Skill : Expert Gapp Metadata

## 🎯 Périmètre Global
**Mission** : Fournir au développeur des blocs JSON de métadonnées Gapp compacts et valides, accompagnés des commandes de regénération CRUD.

### 🚫 Règles d'Or
1. **Validation** : Interdiction d'inventer des chemins de relation Eloquent. Utilisez toujours le skill [db-savoir](/skills/db-savoir/SKILL.md).
2. **Workflow Gapp (CRITIQUE)** : 
   - L'ajout des métadonnées se fait **principalement via l'application Web Gapp**. L'application sauvegarde ces informations directement dans la **base de données**.
   - Gapp lit les métadonnées **depuis la base de données**, et non depuis les fichiers JSON.
   - Les fichiers JSON (`db_schemas/`) servent uniquement à initialiser/restaurer la base de données (ex: nouveau projet, ou base provenant d'un serveur de déploiement où les tables Gapp sont vides).
   - L'application Web Gapp exécute **automatiquement** les commandes (ex: `gapp make:crud NomModel`) après l'ajout en base.
3. **Read-Only (INTERDICTION STRICTE)** : Il est **strictement interdit** pour l'agent de modifier quoi que ce soit dans le dossier `db_schemas/`. Ce dossier est exclusivement géré par Gapp via son interface web.
4. **Français** : La documentation et les instructions générées doivent être en français.
5. **Généralisation des modifications (Issues)** : En cas de modification exceptionnelle d'un fichier maintenu par Gapp (après autorisation stricte du développeur), vous devez OBLIGATOIREMENT demander sa généralisation sur tous les CRUDs. Pour cela, créez un fichier de ticket (issue en Markdown) dans le dossier `cahiers-charges/PkgGapp/issues/` détaillant précisément la modification à apporter au générateur Gapp. **RÈGLE STRICTE** : Le ticket doit impérativement lister les fichiers impactés (mentionnant leur ligne de lock Gapp) et inclure les blocs de code exacts qui ont été ajoutés ou modifiés manuellement.

---

## ⚡ Actions (Orchestration)

### Action Unique : Fournir Métadonnée au Développeur
> **Description** : Lorsque l'utilisateur demande la création ou la modification d'une métadonnée Gapp, **ne jamais modifier les fichiers dans `db_schemas/`**. Au lieu de cela, fournir uniquement les informations nécessaires au développeur pour qu'il le fasse lui-même dans l'interface Web Gapp.
- **Format de Sortie Obligatoire** :
  - **Nom de la Métadonnée** : (ex: `dynamicDropdown`)
  - **Contexte d'Application** : (Model ou Champ concerné)
  - **Valeur de Configuration** : (Bloc JSON complet et formaté à copier-coller dans Gapp)
- **📝 Instructions d'Orchestration** :
  1. Identifier le type de métadonnée et son format exact via les capacités.
  2. Valider formellement la structure des tables et relations avec `db-savoir`.
  3. Afficher STRICTEMENT le nom, le contexte et la valeur (le JSON).
  4. Ne proposer aucune commande artisan `gapp meta:sync` puisque la synchro et les commandes CRUD sont gérées par l'interface Web après l'insertion.

### Action 2 : Ajouter une Métadonnée comme Capacité
> **Description** : Mettre à jour le référentiel de connaissances du skill `sys-gapp` en documentant un nouveau type de métadonnée.
- **Entrées** : La documentation et la description de la métadonnée (ex: `linkAction`).
- **Sorties** : La création d'un nouveau fichier markdown dans le dossier `capacités/` (ex: `capacite-linkAction.md`) et la mise à jour des références si nécessaire.
- **📝 Instructions d'Orchestration** :
  1. Lire attentivement la description fournie par l'utilisateur.
  2. Créer un fichier `capacite-[nomDeLaMetadata].md` dans le sous-dossier `capacités/`.
  3. Mettre à jour les références dans `SKILL.md` ou `capacite-metadonnees-gapp.md` si pertinent.
