---
trigger: on_demand
---

# Principes Généraux de Conception & Qualité Logicielle

## 1. Principes SOLID & DRY
- **Single Responsibility :** Chaque classe a une responsabilité unique. Séparer l'orchestration HTTP (Controller), la logique métier (Service) et la persistance (Model).
- **Open / Closed :** Étendre les capacités via les classes enfants et les Traits sans modifier les classes de base du Core (`BaseService`, `BaseModel`, `AdminController`).
- **DRY (Don't Repeat Yourself) :** Toute logique partagée par plusieurs entités doit être extraite dans un Trait dédié.

## 2. Découpage en Traits dans la Couche Service
Les services métier complexes dans `modules/Pkg[Nom]/Services/` sont découpés en Traits sous `modules/Pkg[Nom]/Services/Traits/[Model]/` :
- `[Model]CrudTrait.php` : Hooks de cycle de vie (`beforeCreateRules`, `afterCreateRules`, `beforeUpdateRules`).
- `[Model]ActionsTrait.php` : Actions spécifiques (workflows, imports/exports).
- `[Model]CalculTrait.php` : Calculs de statistiques, agrégations et enrichissements.
- `[Model]RelationsTrait.php` : Synchronisation et gestion fine des relations.

## 3. Sécurité & Autorisations
- Validation stricte de chaque champ via `FormRequest`.
- Échappement systématique des sorties dans Blade (`{{ $variable }}`).
- Vérification des permissions Spatie avant toute opération sensible (`$this->authorizeAction(...)` ou `Gate::authorize(...)`).
- Utiliser l'annotation PHPDoc `/** @DynamicPermissionIgnore */` sur toute nouvelle méthode ajoutée dans un contrôleur pour éviter les blocages du middleware dynamique.
