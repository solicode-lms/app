---
name: règle-gestion-equipes
description: Règles de gestion et structure de la base de données des équipes de projet.
---

# 🛡️ Règles de Gestion : Équipes de Projet

## 1. Structure de la Base de Données

### Table `equipe_projets`
- `id` : Clé primaire
- `nom` : string (Nom de l'équipe)
- `description` : text (Description optionnelle)
- `reference` : string (Référence unique)
- `projet_id` : Clé étrangère vers la table `projets`
- `sys_color_id` : Clé étrangère vers la table `sys_colors` (Couleur d'identification système)

### Table Pivot `apprenant_equipe_projet`
- `apprenant_id` : Clé étrangère vers la table `apprenants`
- `equipe_projet_id` : Clé étrangère vers la table `equipe_projets`

---

## 2. Règles de Gestion Fonctionnelles

### A. Représentation Visuelle (SysColor)
- Chaque équipe de projet (`EquipeProjet`) est obligatoirement associée à une couleur système.
- **Performance & Eager Loading** : Le modèle PHP `EquipeProjet` DOIT charger cette relation de manière automatique via `protected $with = ['sysColor'];`. Cela évite les requêtes N+1 lors du rendu de listes ou de tableaux Kanban.
- **Rendu UI** : Cette couleur sert à l'identification visuelle rapide (badges, bordures, icônes) dans toutes les interfaces manipulant l'équipe ou ses tâches.

### B. Affectation des Apprenants
- Le rattachement d'un apprenant à une équipe ne peut se faire que parmi les apprenants appartenant au groupe affecté au projet (`$projet->groupe_id`).

### C. Filtrage des Tâches (RealisationTache)
- Dans les interfaces de filtre, la sélection d'une équipe (`tache.equipe_projet_id`) doit déclencher une mise à jour dynamique (cascading dropdown) du champ `tache_id` pour n'afficher que les tâches associées à cette équipe précise.
