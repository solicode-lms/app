---
name: sys-gapp
description: Détermine la configuration JSON des métadonnées Gapp (scope, filter) et fournit les commandes associées.
---

# Skill : Expert Gapp Metadata

## 🎯 Périmètre Global
**Mission** : Fournir au développeur des blocs JSON de métadonnées Gapp compacts et valides, accompagnés des commandes de regénération CRUD.

### 🚫 Règles d'Or
1. **Validation** : Interdiction d'inventer des chemins de relation Eloquent. Utilisez toujours le skill [db-savoir](/skills/db-savoir/SKILL.md).
2. **Read-Only (CRITIQUE)** : Ce skill ne modifie **JAMAIS** les fichiers JSON de Gapp. L'agent fournit le JSON et laisse le développeur le coller et lancer les commandes.
3. **Français** : La documentation et les instructions générées doivent être en français.

---

## ⚡ Actions (Orchestration)

### Action Unique : Générer Métadonnée & Commandes
> **Description** : Produire un bloc JSON strict pour Gapp (`scopeDataInEditContext`, `scopeDataByRole`, ou `ownedByUser`) et afficher les commandes `gapp` requises.
- **Capacités Utilisées** :
  - `capacités/capacite-metadonnees-gapp.md` (Structures JSON)
  - `capacités/capacite-commandes-gapp.md` (Commandes)
- **Entrées** : Type de métadonnée, entité cible, relations, rôle.
- **Sorties** : Bloc JSON à fournir au développeur, suivi des instructions et commandes d'application.
- **📝 Instructions d'Orchestration** :
  1. Identifier le type de métadonnée et son format exact via `capacite-metadonnees-gapp.md`.
  2. Valider formellement la structure des tables et relations avec `db-savoir`.
  3. Afficher le JSON au développeur de manière concise.
  4. Afficher les commandes de mise à jour avec `capacite-commandes-gapp.md`.
