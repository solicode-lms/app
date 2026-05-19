---
name: db-savoir
description: Compétence pour explorer la structure de la base de données via des ressources locales (db.sql, yaml) sans scanner tout le projet.
---

# 🧠 DB-SAVOIR : Maître de la Structure de Données (Mode Offline)

Ce skill permet à l'agent de comprendre la structure de la base de données et les relations en utilisant **exclusivement** des fichiers de ressources centralisés, évitant ainsi de scanner les milliers de fichiers du projet.

## 📍 Ressources Locales
Les fichiers de référence sont situés dans le dossier `resources/` relatif à ce skill 

1.  **`db_structure.yaml`** (Priorité 1) : Cartographie haute vue. Contient la liste des tables, colonnes principales et relations clés. À lire en premier pour une vue d'ensemble.
2.  **`db.sql`** (Priorité 2) : Structure exacte. Contient les définitions `CREATE TABLE`. À lire avec `grep_search` pour trouver les détails d'une table spécifique ou des contraintes étrangères.

## 1. Action : Comprendre une Table
Si on demande "Quelle est la structure de la table `users` ?" :
1.  Utiliser `grep_search` sur `resources/db.sql` avec la query `CREATE TABLE .users.`.
2.  Lire le bloc de création pour identifier les colonnes et types.

## 2. Capability: "Lire le Chemin de Lecture" (Relation & Join)
Pour trouver le chemin entre la Table A (ex: `projets`) et la Table B (ex: `competences`) :

1.  **Recherche Topologique (`db_structure.yaml`)** :
    - Lire ce fichier pour voir si une relation directe est déclarée.
    - Chercher des tables pivots potentielles (ex: `projet_competence` ou via `taches`).
    
2.  **Recherche de Clés Étrangères (`db.sql`)** :
    - Chercher les FK dans `db.sql` : `grep_search` sur "CONSTRAINT ... FOREIGN KEY ... REFERENCES projets".
    - Cela révèle qui pointe vers `projets` (les enfants).

3.  **Synthèse du Chemin** :
    - Direct : `Projet` -> `hasMany` -> `Tache`
    - Indirect : `Projet` -> `hasMany` -> `Tache` -> `hasMany` -> `Competence` (Si applicable).

## 3. Contraintes
- **NE PAS scanner** les dossiers `modules/` ou `app/` pour trouver des relations, sauf si les fichiers ressources sont muets ou incohérents.
- Se fier à `db.sql` comme vérité terrain pour les noms de colonnes.
