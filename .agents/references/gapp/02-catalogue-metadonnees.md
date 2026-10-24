# Référence Gapp : Catalogue des Métadonnées & Commandes

Gapp utilise des métadonnées déclaratives (gérées via son interface d'administration ou stockées en JSON) pour piloter la génération du code 3-tiers (Vues Blade, FormRequests, Contrôleurs, Services).

---

## 1. `dynamicDropdown`
Permet de configurer des listes déroulantes dépendantes en cascade :
- **Clé :** `dynamicDropdown`
- **Exemple de configuration :**
```json
{
  "target": "filiere_id",
  "endpoint": "/api/filieres-by-etablissement",
  "param": "etablissement_id"
}
```

---

## 2. `linkAction` (Boutons d'Action TableUI)
Ajoute un bouton ou une icône personnalisée dans chaque ligne d'un tableau `TableUI` pour naviguer vers une vue liée avec paramètres dynamiques.
- **Scope :** `model` / Groupe : `table`
- **Exemple de configuration JSON :**
```json
[
  {
    "actionName": "livrablesRealisations",
    "ordre": 1,
    "icon": "fas fa-file-alt",
    "tooltip": "Livrables",
    "route": "livrablesRealisations.index",
    "params": {
      "showIndex": true,
      "contextKey": "'livrablesRealisation-index'",
      "scope.livrablesRealisation.realisation_projet_id": "$realisationTache->realisation_projet_id",
      "scope.livrable.projet_id": "$realisationTache->realisationProjet->affectationProjet->projet_id"
    },
    "class": "btn btn-default btn-sm context-state actionEntity showIndex",
    "permission": "index-livrablesRealisation"
  }
]
```
> [!CRITICAL]
> **Règle des Guillemets sur `contextKey` :**
> Pour transmettre une chaîne de caractères via `params` dans Gapp, vous **DEVEZ impérativement l'entourer de guillemets simples** à l'intérieur de la valeur JSON (ex: `"contextKey": "'ModelName-index'"`). Sinon, le compilateur Blade l'écrira sans guillemets, provoquant une erreur de syntaxe PHP.

---

## 3. `ownedByUser` (Restriction d'Accès par Propriétaire)
Filtre l'accès complet à un modèle ou préremplit l'ID dans un formulaire selon l'utilisateur connecté :
```json
[
  {
    "role": "formateur",
    "dataScope": "filter",
    "userModelName": "User",
    "ownerRelationPath": "Projet.formateur.user"
  }
]
```
> [!IMPORTANT]
> **Règles de Parsing Eloquent Gapp :**
> 1. `userModelName` DOIT toujours être `"User"` (l'ID en session étant `user_id`).
> 2. `ownerRelationPath` DOIT obligatoirement se terminer par `.user` (ex: `Projet.formateur.user`). Sinon, Gapp génère `where('formateur', $id)` provoquant une erreur SQL de colonne inexistante.

---

## 4. `scopeDataByRole` & `scopeDataInEditContext`
- **`scopeDataByRole` :** Restreint les options d'un menu déroulant selon le rôle actif (formateur, apprenant) :
```json
[
  {
    "key": "scope.groupes.formateurs.id",
    "role": "formateur",
    "value": "formateur_id"
  }
]
```
- **`scopeDataInEditContext` :** Filtre dynamiquement les options d'une liste déroulante (select/combo) dans un formulaire d'édition pour qu'elle n'affiche que les éléments liés au contexte parent ou courant :
```json
[
  {
    "key": "scope.livrable.projet_id",
    "value": "projet_id",
    "modelName": "Tache"
  },
  {
    "key": "scope.mobilisationUa.projet_id",
    "value": "projet_id",
    "modelName": "Tache"
  }
]
```
> [!IMPORTANT]
> - `key` : Scope appliqué sur le modèle cible (`scope.{RelationCible}.{ChampCible}`). Le nom de relation doit correspondre exactement à celui du modèle cible.
> - `value` : Nom du champ dans le modèle source en cours d'édition dont la valeur sert de filtre (ex: `projet_id`).
> - `modelName` : Nom de la classe du modèle source en PascalCase, **STRICTEMENT SANS namespace** (ex: `"Tache"` et JAMAIS `"Modules\PkgCreationTache\Models\Tache"`).

---

## 5. `calculable` (Champs Calculables TableUI)
Permet d'ajouter une colonne calculée dynamique dans les DataGrids TableUI tout en conservant la capacité de tri et de recherche SQL :
- Déclarer `calculable = true` sur l'attribut de métadonnée.
- Définir la sous-requête SQL optimisée correspondante :
```sql
(SELECT COUNT(*) FROM livrables_realisations WHERE livrables_realisations.realisation_tache_id = realisations_taches.id)
```
Gapp intègre automatiquement cette sous-requête dans les clauses `selectRaw`, `orderBy` et les filtres textuels sans déclencher de requêtes N+1.

---

## 6. `viewFormGroups` & `viewFormFieldGroup`
Organise les champs de formulaire en groupes logiques (onglets, cartes, accordéons) :
- **Au niveau de l'Entité (`viewFormGroups`) :**
```json
{
  "informations_generales": {
    "icon": "fas fa-info-circle",
    "label": "Informations Générales",
    "order": 1
  },
  "pedagogie": {
    "icon": "fas fa-graduation-cap",
    "label": "Alignement Pédagogique",
    "order": 2
  }
}
```
- **Au niveau du Champ (`viewFormFieldGroup`) :**
```json
"informations_generales"
```

---

## 7. Commandes d'Exécution Gapp
Les commandes Gapp s'exécutent via l'exécutable natif `gapp` :
- **`gapp meta:sync`** : Synchronise les modifications de base de données avec les métadonnées de Gapp.
- **`gapp meta:seed .`** : Synchronise les métadonnées vers la base de données.
- **`gapp make:crud NomModel .`** : Régénère les fichiers CRUD standard (`BaseModel`, `BaseController`, `BaseService`, `FormRequests`) pour le modèle spécifié.
