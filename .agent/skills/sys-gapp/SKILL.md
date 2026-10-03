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
   - Les fichiers JSON (`app_meta_data/`) servent uniquement à initialiser/restaurer la base de données (ex: nouveau projet, ou base provenant d'un serveur de déploiement où les tables Gapp sont vides).
   - L'application Web Gapp exécute **automatiquement** les commandes (ex: `gapp make:crud NomModel`) après l'ajout en base.
3. **Read-Only** : Ce skill fournit la structure JSON correcte. Le développeur peut soit l'utiliser dans l'interface Web, soit la mettre dans le JSON (dans ce cas, il devra synchroniser la BDD avec `gapp meta:sync`).
4. **Français** : La documentation et les instructions générées doivent être en français.

---

## ⚡ Actions (Orchestration)

### Action Unique : Générer Métadonnée & Explications
> **Description** : Produire un bloc JSON strict pour Gapp (`scopeDataInEditContext`, `scopeDataByRole`, ou `ownedByUser`) et expliquer au développeur comment l'intégrer.
- **Capacités Utilisées** :
  - `capacités/capacite-metadonnees-gapp.md` (Structures JSON)
  - `capacités/capacite-scope-edit-context.md` (Explication détaillée des scopes d'édition)
  - `capacités/capacite-commandes-gapp.md` (Commandes)
- **Entrées** : Type de métadonnée, entité cible, relations, rôle.
- **Sorties** : Bloc JSON à fournir au développeur, suivi des instructions d'application via l'interface Web ou via les fichiers JSON.
- **📝 Instructions d'Orchestration** :
  1. Identifier le type de métadonnée et son format exact via `capacite-metadonnees-gapp.md`.
  2. Valider formellement la structure des tables et relations avec `db-savoir`.
  3. Afficher le JSON au développeur de manière concise.
  4. Expliquer clairement que la métadonnée doit être saisie dans l'Application Web Gapp (qui gérera la BDD et le `make:crud` automatiquement), OU s'il la met dans le fichier JSON, lui rappeler d'exécuter `gapp meta:sync` suivi de `gapp make:crud`.
