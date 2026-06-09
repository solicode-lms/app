# Capacité : Configurer scopeDataByConnectedUser (ownedByUser)

## 📖 Description
La métadonnée `ownedByUser` (faisant référence au scoping par utilisateur connecté) permet de restreindre dynamiquement l'accès ou la portée des données d'un modèle entier en fonction de l'utilisateur connecté. Elle applique soit des filtres de requête automatiques, soit des scopes sur les formulaires d'édition/création.

---

## 🔄 Processus de Configuration en 2 Étapes

### Étape 1 : Déterminer le chemin des données et la structure des tables
L'agent doit identifier la chaîne de relations reliant le modèle de données à l'utilisateur connecté.

> [!IMPORTANT]
> L'agent **DOIT** impérativement utiliser le skill [db-savoir](file:///c:/AppServer/solicode-lms/.agent/skills/db-savoir/SKILL.md) pour explorer la base de données (`db.sql`, `db_structure.yaml`) afin d'identifier les tables concernées et construire les chemins de relations exacts.


1. **Identifier le modèle cible à sécuriser/filtrer (Model)** :
   * *Exemple* : `RealisationTache`.
2. **Identifier le(s) rôle(s) concerné(s) (Role)** :
   * Qui a accès à ce modèle et doit voir ses données filtrées ?
   * *Exemple* : `formateur` et `apprenant`.
3. **Déterminer le type de portée (dataScope)** :
   * `"filter"` : Utilisé pour filtrer les requêtes de base de données de manière globale (ex: liste index).
   * `"scope"` : Utilisé pour injecter le scope dans les formulaires (ex: création/édition).
4. **Tracer le chemin de relation vers l'utilisateur connecté (ownerRelationPath)** :
   * **Important** : Utiliser le skill [db-savoir](.agent/skills/db-savoir/SKILL.md) pour explorer la base de données afin de tracer le chemin exact reliant le modèle à la relation `user`.
   * Partir du modèle cible et lister toutes les relations successives séparées par des points (`.`) jusqu'à atteindre la relation finale pointant vers `user`.
   * *Règle critique* : Les noms de relations dans le chemin doivent être en **PascalCase**.
   * *Exemple pour Formateur* : `RealisationTache` -> `RealisationProjet` -> `AffectationProjet` -> `Projet` -> `Formateur` -> `user`.
     Chemin de relation : `RealisationProjet.AffectationProjet.Projet.Formateur.user`.
   * *Exemple pour Apprenant* : `RealisationTache` -> `RealisationProjet` -> `Apprenant` -> `user`.
     Chemin de relation : `RealisationProjet.Apprenant.user`.

---

### Étape 2 : Détermination finale de la configuration
Déterminer la structure JSON à insérer dans le modèle au niveau de la métadonnée `ownedByUser` dans Gapp.

#### Structure JSON Standard
```json
[
  {
    "role": "[roleName]",
    "dataScope": "[filter|scope]",
    "userModelName": "[UserModelName, ex: Formateur / Apprenant / User]",
    "ownerRelationPath": "[ownerRelationPath]"
  }
]
```

#### Exemples de Référence

##### Exemple A : Filtrage des réalisations de tâches
Restreindre les réalisations de tâches pour que les formateurs ne voient que celles liées à leurs projets, et que les apprenants ne voient que les leurs :
* **Modèle** : `RealisationTache`
* **JSON final** :
```json
[
  {
    "role": "formateur",
    "dataScope": "filter",
    "userModelName": "Formateur",
    "ownerRelationPath": "RealisationProjet.AffectationProjet.Projet.Formateur.user"
  },
  {
    "role": "apprenant",
    "dataScope": "filter",
    "userModelName": "Apprenant",
    "ownerRelationPath": "RealisationProjet.Apprenant.user"
  }
]
```

##### Exemple B : Scoping des widgets utilisateurs
Scoper le formulaire de création de widget utilisateur pour forcer l'attribut `user_id` à correspondre à l'utilisateur actuellement connecté :
* **Modèle** : `WidgetUtilisateur`
* **JSON final** :
```json
[
  {
    "role": "formateur",
    "dataScope": "scope",
    "userModelName": "User",
    "ownerRelationPath": "user"
  }
]
```

---

## 💻 Code Généré Attendu
Après application dans Gapp, le contrôleur associé contiendra le code d'initialisation suivant :

### Cas `"dataScope": "filter"` :
```php
// ownedByUser
if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.realisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id') == null){
   $this->viewState->init('filter.realisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
}
```

### Cas `"dataScope": "scope"` :
```php
// ownedByUser
if(Auth::user()->hasRole('formateur')){
   $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
}
```
 Gapp intègre ensuite cette clé pour filtrer automatiquement les enregistrements en base de données ou pré-remplir les formulaires de création/édition.
