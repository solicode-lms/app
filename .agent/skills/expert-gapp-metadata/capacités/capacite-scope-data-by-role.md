# Capacité : Configurer scopeDataByRole

## 📖 Description
La métadonnée `scopeDataByRole` permet de restreindre les options de sélection (dropdown) d'un formulaire ou les données d'un modèle d'après le rôle de l'utilisateur connecté (ex: un formateur ne voit que les filières qui lui sont assignées).

---

## 🔄 Processus de Configuration en 2 Étapes

### Étape 1 : Déterminer le chemin des données et la structure des tables
L'agent doit analyser le modèle et l'attribut à scoper, ainsi que les rôles cibles.

> [!IMPORTANT]
> L'agent **DOIT** impérativement utiliser le skill [db-savoir](file:///c:/AppServer/solicode-lms/.agent/skills/db-savoir/SKILL.md) pour explorer la base de données (`db.sql`, `db_structure.yaml`) afin d'analyser les tables concernées et construire les chemins de relations exacts.


1. **Identifier le modèle et le champ cible (TargetField)** :
   * Quel champ de quel modèle doit être restreint en fonction du rôle ?
   * *Exemple* : `filiere_id` sur le modèle `Projet`.
2. **Identifier le rôle utilisateur et sa clé de session (User Role & Key)** :
   <!-- * Quel est le rôle qui subit la restriction (ex: `formateur`) ? -->
   * Quelle variable de session contient son identifiant de rôle ?
   * *Exemple* : rôle `formateur` -> variable de session `formateur_id` ; rôle `apprenant` -> variable de session `apprenant_id`.
3. **Tracer le chemin de relation depuis le modèle de l'option (Key Path)** :
   * **Important** : Utiliser le skill [db-savoir](.agent/skills/db-savoir/SKILL.md) pour explorer la base de données afin de tracer le chemin exact reliant le modèle de l'option à l'identifiant du rôle.
   * Partir du modèle associé au select (ex: le select affiche des `Filiere`, donc le modèle est `Filiere`) et tracer le chemin jusqu'à l'identifiant du rôle (ex: `Formateur`).
   * *Exemple* : `Filiere` -> relation `groupes` -> relation `formateurs` -> attribut `id`.
   * Le chemin de clé sera : `scope.filiere.groupes.formateurs.id`.

---

### Étape 2 : Détermination finale de la configuration
Déterminer la structure JSON à insérer dans le générateur Gapp.

#### Structure JSON Standard
```json
[
  {
    "key": "scope.[relationPathFromScopedModel].[attribute]",
    "role": "[roleName]",
    "value": "[sessionStateKey]"
  }
]
```

#### Exemples de Référence

##### Exemple A : Filtrer les filières pour un formateur
Restreindre la liste des filières proposées lors de la création d'un projet pour que le formateur connecté ne voie que ses propres filières :
* **Modèle** : `Projet`
* **Champ à scoper** : `filiere_id` (Modèle `Filiere`)
* **Chemin de restriction** : `Filiere` -> `groupes` -> `formateurs` -> `id`
* **Rôle** : `formateur`
* **Session key** : `formateur_id`
* **JSON final** :
```json
[
  {
    "key": "scope.filiere.groupes.formateurs.id",
    "role": "formateur",
    "value": "formateur_id"
  }
]
```

##### Exemple B : Filtrer les sessions de formation pour un formateur
Restreindre la liste des sessions de formation proposées lors de la création d'un projet :
* **Modèle** : `Projet`
* **Champ à scoper** : `session_formation_id` (Modèle `SessionFormation`)
* **Chemin de restriction** : `SessionFormation` -> `filiere` -> `groupes` -> `formateurs` -> `id`
* **Rôle** : `formateur`
* **Session key** : `formateur_id`
* **JSON final** :
```json
[
  {
    "key": "scope.sessionFormation.filiere.groupes.formateurs.id",
    "role": "formateur",
    "value": "formateur_id"
  }
]
```

---

## 💻 Code Généré Attendu
Après application dans Gapp, le contrôleur Laravel associé (`BaseProjetController` par exemple) contiendra le code d'initialisation suivant dans son constructeur ou ses méthodes d'action (`index`, `create`, `edit`) :
```php
// scopeDataByRole
if(Auth::user()->hasRole('formateur')){
    $this->viewState->init('scope.filiere.groupes.formateurs.id'  , $this->sessionState->get('formateur_id'));
}
```
Cette variable dans le ViewState servira de critère de filtrage automatique sur la requête de récupération des options du champ `filiere_id` pour le rôle de formateur.
