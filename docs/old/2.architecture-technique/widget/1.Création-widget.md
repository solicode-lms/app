# 📚 Documentation - Ajout d'un Nouveau Widget dans Solicode-LMS

---

## 1. Introduction

Un **widget** est un composant dynamique affiché sur le tableau de bord des utilisateurs.  
Chaque widget est défini dans la table **`widgets`** et personnalisé par utilisateur via **`widget_utilisateurs`**.

L'objectif est de configurer un widget en insérant un enregistrement dans `widgets` accompagné d'une configuration JSON (`parameters`) pour en définir le comportement et l'affichage.

---

## 2. Composition d’un Widget

| Champ               | Description                                                             | Exemple |
|---------------------|-------------------------------------------------------------------------|---------|
| `ordre`             | Position d’affichage sur le tableau de bord.                           | `2` |
| `name`              | Nom technique unique.                                                  | `TachesEnCours` |
| `label`             | Libellé visible par l'utilisateur.                                     | `Nombre de tâches en cours` |
| `type_id`           | Type de widget (`1` = valeur simple, `3` = tableau, etc.).              | `1` |
| `model_id`          | Modèle concerné (ex: `Apprenant`, `RealisationTache`, etc.).            | `96` |
| `operation_id`      | Type d’opération (`1 = count`, `3 = parameters`, etc.).                 | `1` |
| `color`             | Couleur Bootstrap (`success`, `info`, etc.).                            | `success` |
| `icon`              | Icône FontAwesome facultative.                                          | `fa-user` |
| `sys_color_id`      | Couleur personnalisée (`sys_colors`).                                   | `3` |
| `reference`         | UUID unique.                                                            | `5e5225ca-8a32-4316-949a-7aede93818cc` |
| `section_widget_id` | ID de la section (groupe) d'affichage.                                  | `1` |
| `parameters`        | JSON décrivant les comportements et filtres dynamiques du widget.       | Voir ci-dessous |

---

## 3. Structure du champ `parameters`

Le champ `parameters` est un JSON structurant les comportements suivants :

| Clé            | Description |
|----------------|-------------|
| `link`         | Définir la route d'accès lors du clic sur le widget. |
| `roles`        | Définir des filtres spécifiques selon le rôle utilisateur (`admin`, `formateur`, `apprenant`). |
| `dataSource`   | Définir la méthode de service à appeler pour charger les données. |
| `conditions`   | Ajouter des filtres statiques sur les modèles (requête DSL). |
| `tableUI`      | Structurer les colonnes si `type_id = 3` (affichage en tableau). |
| `order_by`     | Définir l’ordre d’affichage (`column`, `direction`). |
| `limit`        | Limiter le nombre de résultats affichés. |

---

### 🔥 Rappel important - Utilisation de `roles`

- **`roles`** permet de définir des **conditions de filtrage spécifiques par rôle** (admin, formateur, apprenant).
- Tous les critères de filtrage (`user_id`, `etat`, etc.) peuvent être définis ici pour adapter dynamiquement les résultats selon le rôle connecté.

```json
"roles": {
  "admin": {
    "etatRealisationTache.workflowTache.code": "EN_COURS"
  },
  "apprenant": {
    "realisationProjet.apprenant.user_id": "#user_id",
    "etatRealisationTache.workflowTache.code": "EN_COURS"
  }
}
```

---

### ⚙️ Deux manières de récupérer les données

| Méthode      | Description |
|--------------|-------------|
| `dataSource` | Appel d’une méthode spécifique d’un Service métier (ex : `getTachesEnCours()`) |
| `conditions` | Requête directe sur le modèle via des filtres (`where`) |

---

## 4. Exemple Complet d’un Widget JSON

```json
{
  "link": {
    "route_name": "realisationTaches.index",
    "route_params": {
      "filter.realisationTache.etatRealisationTache.WorkflowTache.Code": "EN_COURS"
    }
  },
  "roles": {
    "admin": {
      "etatRealisationTache.workflowTache.code": "EN_COURS"
    },
    "apprenant": {
      "realisationProjet.apprenant.user_id": "#user_id",
      "etatRealisationTache.workflowTache.code": "EN_COURS"
    }
  },
  "dataSource": null,
  "conditions": {},
  "tableUI": [
    {
      "key": "tache.titre",
      "label": "Tâche",
      "order": 1
    },
    {
      "key": "realisationProjet.apprenant",
      "label": "Apprenant",
      "order": 2
    }
  ],
  "order_by": {
    "column": "updated_at",
    "direction": "desc"
  },
  "limit": 5
}
```

---

## 5. Étapes pour créer un Nouveau Widget

### 1️⃣ Définir le besoin
- Quel modèle ?
- Quelle opération (`count`, `sum`, `parameters`) ?
- Quel affichage (`simple`, `tableau`) ?
- Quelle source (`dataSource` ou `conditions`) ?

### 2️⃣ Construire le JSON `parameters`
- Définir `link`, `roles`, `dataSource` ou `conditions`.
- Ajouter `tableUI` si besoin (`type_id = 3`).

### 3️⃣ Insérer dans la Base de Données

```sql
INSERT INTO widgets 
(ordre, name, label, type_id, model_id, operation_id, color, icon, sys_color_id, reference, section_widget_id, parameters)
VALUES 
(3, 'ApprenantsSansTache', 'Apprenants sans tâche à faire', 3, 11, 3, 'info', 'fa-user', 5, 'UUID-GÉNÉRÉ', 1, '{...JSON...}');
```

⚠️ `reference` doit être **unique** (UUID).

### 4️⃣ Synchroniser pour les Utilisateurs
- Utiliser `WidgetUtilisateurService::syncWidgetsFromRoles()` pour régénérer la liste des widgets selon les rôles.

---

## 6. Bonnes Pratiques

- Utiliser les **placeholders dynamiques** `#user_id`, `#apprenant_id`, `#formateur_id`.
- Toujours limiter le nombre de lignes avec `limit` pour éviter les lenteurs.
- Bien vérifier la cohérence entre `type_id`, `operation_id`, `tableUI`.
- Préférer `dataSource` pour les traitements complexes et `conditions` pour les cas simples.

---

# 📦 Résumé - Modèle JSON Prêt à Remplir

```json
{
  "link": {
    "route_name": "",
    "route_params": {}
  },
  "roles": {
    "admin": {},
    "formateur": {},
    "apprenant": {}
  },
  "dataSource": "",
  "conditions": {},
  "tableUI": [],
  "order_by": {
    "column": "",
    "direction": "desc"
  },
  "limit": 5
}
```

## Table UI

nature : 
- deadline
- duree
- badge