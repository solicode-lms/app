# Capacité : Configurer scopeDataInEditContext

## 📖 Description
La métadonnée `scopeDataInEditContext` permet de filtrer (scoper) dynamiquement la liste de choix (les options d'un select de relation ManyToOne ou ManyToMany) proposée au développeur dans le formulaire d'édition ou de création. 

Ce scope Eloquent est ajouté dynamiquement par l'application Laravel sur la base d'une condition entre :
1. Le chemin de relations de la liste de choix (spécifié dans la **`key`** par le modèle d'application et son chemin de relation Laravel).
2. La valeur de condition extraite en temps réel (spécifiée par la **`value`**) à partir du modèle du formulaire principal en cours d'édition (spécifié dans **`modelName`**).

Elle est principalement mise en œuvre dans les formulaires imbriqués (sous-formulaires liés par une relation `hasMany` ou `hasOne`).

---

## 🔄 Processus de Configuration en 2 Étapes

### Étape 1 : Déterminer le chemin des données et la structure des tables
L'agent doit demander ou analyser le contexte du formulaire d'édition/création fourni par l'utilisateur.

> [!IMPORTANT]
> L'agent **DOIT** impérativement utiliser le skill [db-savoir](.agent/skills/db-savoir/SKILL.md) pour explorer la base de données (`db.sql`) afin de comprendre la structure des tables, identifier les clés étrangères et en déduire les relations Eloquent correspondantes.


1. **Identifier le modèle du formulaire (FormModel)** :
   * Quel est l'objet principal en cours de création ou d'édition ?
   * *Exemple* : `MobilisationUa`.
2. **Identifier le champ de sélection à scoper (TargetField) selon le type de relation** :
   * **Cas ManyToOne (BelongsTo)** : .
   * **Cas ManyToMany (BelongsToMany)** :

1. **Tracer le chemin de restriction depuis le modèle cible (Key Path)** :
   * **Important** : Déterminer la structure des tables et relations en utilisant le skill [db-savoir](.agent/skills/db-savoir/SKILL.md) pour explorer la base de données.
   * Partir du modèle de l'option (ex: `UniteApprentissage` pour ManyToOne, ou `LabelProjet` pour ManyToMany) et remonter les relations jusqu'à l'attribut de filtrage.
   * *Exemple ManyToOne* : `UniteApprentissage` -> `microCompetence` -> `competence` -> `module` -> `filiere_id`. Clé de portée : `scope.uniteApprentissage.microCompetence.competence.module.filiere_id`.
   * *Exemple ManyToMany* : `LabelProjet` -> `projet_id`. Clé de portée : `scope.labelProjet.projet_id`.
2. **Tracer le chemin de la valeur source depuis le modèle du formulaire (Value Path)** :
   * **Important** : Utiliser à nouveau le skill [db-savoir](.agent/skills/db-savoir/SKILL.md) pour identifier comment le modèle du formulaire est relié à l'attribut contenant la valeur de filtre.
   * Partir du modèle du formulaire (ex: `MobilisationUa` ou `RealisationTache`) et remonter la relation jusqu'à l'attribut qui contient la valeur à filtrer.
   * *Exemple ManyToOne (sur MobilisationUa)* : `projet` -> `filiere_id` (soit `projet.filiere_id`).
   * *Exemple ManyToMany (sur RealisationTache)* : `tache` -> `projet_id` (soit `tache.projet_id`).

---

### Étape 2 : Détermination finale de la configuration
Déterminer la structure JSON à insérer dans le générateur Gapp.

#### Structure JSON Standard
```json
[
  {
    "key": "scope.[ModelApplication].[RelationPath.Attribute]",
    "value": "[RelationPathFromFormModel].[attribute]",
    "modelName": "[FormModelName]"
  }
]
```

##### Détail des Propriétés :
* **`key`** : Clé d'identification du scope, composée impérativement de trois parties séparées par des points :
  1. **`scope`** : Préfixe obligatoire indiquant que la variable du ViewState est une variable de portée.
  2. **`[ModelApplication]`** (ex: `competence`, `uniteApprentissage`) : Le modèle Eloquent auquel s'applique le scope de filtrage pour les options de la liste de choix.
  3. **`[RelationPath.Attribute]`** (ex: `module.filiere_id`, `microCompetence.competence.module.filiere_id`) : Le chemin de relations complet se terminant par l'attribut de condition. Ce chemin relationnel doit exister au niveau des relations définies sur le modèle d'application.
* **`value`** : Le chemin relationnel (depuis le modèle du formulaire) menant à l'attribut qui fournit la valeur dynamique servant de filtre (ex: `filiere_id` ou `projet.filiere_id`).
* **`modelName`** : Le nom du modèle du formulaire en cours d'édition (FormModel), qui sert de point d'entrée pour la récupération de la valeur source (ex: `Projet` ou `MobilisationUa`).


#### Exemples de Référence

##### Exemple A : Relation simple sur le modèle Projet
Dans le formulaire d'édition de `Projet`, restreindre le select des compétences par la filière du projet en cours d'édition :
* **Modèle** : `Projet`
* **Champ à scoper** : `competence_id` (Modèle `Competence`)
* **Chemin depuis Competence** : `Competence` -> `module` -> `filiere_id`
* **Valeur source sur Projet** : `filiere_id`
* **JSON final** :
```json
[
  {
    "key": "scope.competence.module.filiere_id",
    "value": "filiere_id",
    "modelName": "Projet"
  }
]
```

##### Exemple B : Relation imbriquée sur MobilisationUa
Dans le formulaire d'édition de `MobilisationUa`, restreindre le select des unités d'apprentissage par la filière du projet associé :
* **Modèle** : `MobilisationUa`
* **Champ à scoper** : `unite_apprentissage_id` (Modèle `UniteApprentissage`)
* **Chemin depuis UniteApprentissage** : `UniteApprentissage` -> `microCompetence` -> `competence` -> `module` -> `filiere_id`
* **Valeur source sur MobilisationUa** : `projet` -> `filiere_id`
* **JSON final** :
```json
[
  {
    "key": "scope.uniteApprentissage.microCompetence.competence.module.filiere_id",
    "value": "projet.filiere_id",
    "modelName": "MobilisationUa"
  }
]
```

##### Exemple C : Relation ManyToMany (M2M) sur RealisationTache
Dans le formulaire d'édition de `RealisationTache`, restreindre les labels de projet sélectionnables (dropdown multiselect) au projet de la tâche en cours :
* **Modèle** : `RealisationTache`
* **Champ à scoper** : `realisation_taches_label_projets_label_realisation_tache_m2m_m2m` (Modèle associé : `LabelProjet`)
* **Chemin depuis LabelProjet** : `LabelProjet` -> `projet_id`
* **Valeur source sur RealisationTache** : `tache` -> `projet_id`
* **JSON final** :
```json
[
  {
    "key": "scope.labelProjet.projet_id",
    "value": "tache.projet_id",
    "modelName": "RealisationTache"
  }
]
```

---

## 💻 Code Généré Attendu
Après application dans Gapp, le contrôleur Laravel (`BaseMobilisationUaController` par exemple) contiendra le code suivant dans ses méthodes `create`, `edit`, `fieldMeta`, et `patchInline` :
```php
// scopeDataInEditContext
$value = $itemMobilisationUa->getNestedValue('projet.filiere_id');
$key = 'scope.uniteApprentissage.microCompetence.competence.module.filiere_id';
$this->viewState->set($key, $value);
```
Puis, lors de la récupération des options du select de manière asynchrone, le système appliquera automatiquement le scope présent dans le ViewState pour filtrer les résultats renvoyés.
