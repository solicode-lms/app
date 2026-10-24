# Référence : Architecture 3-Tiers & Couche Service
 
## 1. Vue d'Ensemble
L'application repose sur un découpage strict à 3 niveaux :
1. **Couche Présentation (HTTP & Vues) :**
   - Contrôleurs dérivant de `Modules\Core\Controllers\Base\AdminController` (ou `PublicController`).
   - Requêtes de validation `FormRequest`.
   - Vues Blade dans `modules/Pkg.../resources/views/`.
2. **Couche Métier (Business Logic Layer - BLL) :**
   - `Modules\Core\Services\BaseService` (Niveau Core universel).
   - `Base[Model]Service` (Niveau généré par Gapp, **protégé et intouchable**).
   - `[Model]Service` (Niveau métier modifiable par le développeur).
   - Traits spécialisés dans `modules/Pkg.../Services/Traits/[Model]/`.
3. **Couche Persistance (Data Access Layer - DAL) :**
   - `Base[Model]` (Modèle Eloquent généré par Gapp, **protégé**).
   - `[Model]` (Modèle Eloquent enfant modifiable).

## 2. Règle d'Héritage et Template Method dans les Contrôleurs
Ne jamais copier-coller entièrement les méthodes de rendu du contrôleur de base.
Surcharger uniquement les méthodes de préparation de données :
```php
protected function dataForEditView(string $id): array {
    $viewData = parent::dataForEditView($id);
    $viewData['mes_donnees_custom'] = 'valeur';
    return $viewData;
}
```

## 3. Gestion des Erreurs Métier (BlException)
Dans la couche Service, les blocages métiers doivent obligatoirement lever une `BlException` :
```php
use Modules\Core\App\Exceptions\BlException;

if ($conditionEchec) {
    throw new BlException("L'opération est refusée : la date limite est dépassée.");
}
```
Cette exception est interceptée automatiquement par le Handler global et affichée dans l'interface utilisateur sans crash de page.
