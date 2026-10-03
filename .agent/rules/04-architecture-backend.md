---
trigger: always_on
---

# 🏗️ Architecture Backend & Services

## 1. Philosophie "Fat Models, Skinny Controllers" (Alternative)
Nous utilisons une couche **Service** intermédiaire pour soulager les contrôleurs. L'architecture respecte le flux : **Contrôleur** -> **Service** -> **Model**.

**Séparation stricte des responsabilités (CRITIQUE) :**
- **Le Contrôleur (`app-controller`)** : Gère uniquement la requête HTTP, l'autorisation, la validation formelle (FormRequest) et la sélection de données pour l'affichage. **Il ne doit faire aucune modification en base de données ni aucun calcul métier.**
- **Le Service (`app-service`)** : Gère l'intégralité de la logique métier, les calculs, et **toutes** les opérations de modification de données (création, mise à jour, suppression, changement d'état). Il est le seul garant de l'intégrité de ses données.
- **Le Modèle (`app-model`)** : Ne doit jamais être utilisé directement pour sauvegarder des données (`save()`, `update()`) depuis un contrôleur ou un autre service. Chaque modification passe obligatoirement par le Service associé à l'entité.
- **La Vue (Blade) (`app-blade`)** : Ne doit **JAMAIS** appeler la couche Service (ex: `app(MonService::class)`). Toutes les données complexes, y compris les variables d'état (comme `$viewState`), doivent être préparées par le Contrôleur/Service et injectées via `compact()`. La vue est uniquement responsable de l'affichage.

## 2. Structure des Services
- **Héritage** : Tous les Services étendent `BaseService` ou le modèle parent (ex: `BaseTacheService`).
- **Composition (Traits)** : Si un Service dépasse **500 lignes**, découper en Traits dans `Services/Traits/{NomEntite}/` :
    - `{Model}CrudTrait` : createInstance, before/after rules.
    - `{Model}ActionsTrait` : Logique métier complexe.
    - `{Model}GetterTrait` : Scopes et getters.
    - `{Model}CalculTrait` : Calculs statistiques/business.

## 3. Hooks CRUD (Cycle de Vie)
Les méthodes standard (`create`, `update`) du `BaseService` appellent des hooks que tu dois surcharger si nécessaire :

| Hook                | Arguments            | Usage                                                                            |
| :------------------ | :------------------- | :------------------------------------------------------------------------------- |
| `beforeCreateRules` | `array &$data`       | Validation métier, valeurs par défaut. `&$data` (référence) permet modification. |
| `afterCreateRules`  | `$item`              | Création enfants, notifications, jobs asynchrones.                               |
| `beforeUpdateRules` | `array &$data, $id`  | Règles de transition d'état, check permissions métier.                           |
| `afterUpdateRules`  | `$item, $id`         | Logs, cascades.                                                                  |

## 4. Conventions de Nommage
- **Classes/Services** : Français (Langue Client) -> `ProjetService`, `ApprenantService`.
- **Méthodes Techniques** : Anglais -> `get...`, `set...`.
- **Méthodes Métier** : Français -> `validerCandidature`, `calculerMoyenne`.

## 5. Responsabilité Unique
- Un Service ne doit PAS faire le travail d'un autre.
- Ex: `ProjetService` appelle `ApprenantService` pour créer un apprenant, il ne fait pas `new Apprenant()`.

## 6. ViewState & Filtres Sauvegardés (Règle Critique)

### Fonctionnement du ViewState
Le `ViewStateService` organise son état par **`contextKey`**. Les filtres persistés en base de données (via `UserModelFilterService`) sont également **indexés par `contextKey`**.

### ⚠️ Ordre d'Appel OBLIGATOIRE

**AVANT tout appel à `loadLastFilterIfEmpty()`, il est OBLIGATOIRE d'appeler `setContextKeyIfEmpty()` sur le `ViewState`.**

Sans cela, le système opère sous la clé `"default_context"` et ne retrouve **pas** les filtres sauvegardés en base de données.

#### ✅ Pattern Correct
```php
$this->viewState->setContextKeyIfEmpty('monModel.index'); // ← 1er : définir le contexte
$this->monService->loadLastFilterIfEmpty();               // ← 2ème : charger les filtres
$filterVariables = $this->viewState->getFilterVariables('monModel'); // ← 3ème : lire
```

#### ❌ Pattern Interdit
```php
// INTERDIT : loadLastFilterIfEmpty() avant setContextKeyIfEmpty()
$this->monService->loadLastFilterIfEmpty();  // cherche sous "default_context" → filtre vide !
$filterVariables = $this->viewState->getFilterVariables('monModel');
```

### Cas d'Application
Cette règle s'applique partout où les filtres sont nécessaires **hors du flux standard `index()`** :
- Export CSV / Excel
- Endpoints API filtrant par contexte utilisateur
- Traitements batch ou tâches planifiées

