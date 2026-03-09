---
trigger: always_on
---

# 🏗️ Architecture Backend & Services

## 1. Philosophie "Fat Models, Skinny Controllers" (Alternative)
Nous utilisons une couche **Service** intermédiaire pour soulager les contrôleurs.
**Contrôleur** -> **Service** -> **Model**

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
| `beforeUpdateRules` | `$item, array $data` | Règles de transition d'état, check permissions métier.                           |
| `afterUpdateRules`  | `$item, array $data` | Logs, cascades.                                                                  |

## 4. Conventions de Nommage
- **Classes/Services** : Français (Langue Client) -> `ProjetService`, `ApprenantService`.
- **Méthodes Techniques** : Anglais -> `get...`, `set...`.
- **Méthodes Métier** : Français -> `validerCandidature`, `calculerMoyenne`.

## 5. Responsabilité Unique
- Un Service ne doit PAS faire le travail d'un autre.
- Ex: `ProjetService` appelle `ApprenantService` pour créer un apprenant, il ne fait pas `new Apprenant()`.
