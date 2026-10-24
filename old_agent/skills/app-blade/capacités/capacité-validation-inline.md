# Capacité : Validation des Champs en Édition en Ligne (Inline Edit)

Cette capacité décrit comment implémenter des contraintes de validation sur des champs modifiés en ligne (Inline Edit).

---

## 🏗️ Le Problème de l'Instanciation Manuelle par Gapp

Dans Solicode LMS, le générateur Gapp instancie parfois les FormRequests manuellement dans la couche service ou les contrôleurs de base de la manière suivante :
```php
$rules = (new \Modules\PkgEvaluateurs\App\Requests\EvaluationRealisationTacheRequest())->rules();
```
Puisque le constructeur `new` est appelé sans injection de dépendances par le routeur de Laravel, l'instance du FormRequest ne contient pas les données ou les paramètres de la route courante. Ainsi :
- `$this->route('id')` retournera `null`.
- `$this->input('realisation_tache_id')` retournera `null`.

Pour contourner ce problème, il est **obligatoire** d'utiliser un mécanisme de repli (fallback) sur le helper global `request()`.

---

## ⚡ Méthode d'Implémentation dans le FormRequest

Pour définir des contraintes dynamiques (par exemple, un barème de note maximum issu de la tâche liée), suivez cette structure dans la méthode `rules()` de votre classe `[Model]Request.php` :

```php
public function rules(): array
{
    $rules = parent::rules();

    // 1. Récupération robuste de l'ID du modèle édité (via route ou request globale)
    $id = $this->route('nomRoute')
        ?? $this->route('id')
        ?? request()->route('nomRoute')
        ?? request()->route('id');

    $model = \Modules\MonModule\Models\MonModel::find($id);

    // 2. Récupération robuste des relations ou clés étrangères utiles
    $realisationTacheId = $this->input('realisation_tache_id')
        ?? request()->input('realisation_tache_id')
        ?? $model?->realisation_tache_id;

    $realisationTache = \Modules\PkgRealisationTache\Models\RealisationTache::find($realisationTacheId);
    
    // 3. Détermination de la contrainte dynamique
    $maxNote = $realisationTache?->tache?->note;

    // 4. Définition de la règle de validation
    $rules['note'] = [
        'nullable',
        'numeric',
        'min:0',
        $maxNote !== null ? 'max:' . $maxNote : '',
    ];

    return $rules;
}
```

---

## 🛡️ Sécurité additionnelle dans le Service Métier (`beforeUpdateRules`)

Bien que la validation dans le `FormRequest` bloque les requêtes HTTP invalides, il est recommandé de doubler cette sécurité dans la couche service pour bloquer les écritures programmatiques invalides via `beforeUpdateRules` dans `[Model]Service.php` :

```php
use Illuminate\Validation\ValidationException;

public function beforeUpdateRules(array &$data, $id)
{
    if (isset($data['note']) && $data['note'] !== null) {
        $model = $this->find($id);
        if ($model) {
            $model->loadMissing('realisationTache.tache');
            $maxNote = $model->getMaxNote();
            
            if ($maxNote !== null && $data['note'] > $maxNote) {
                throw ValidationException::withMessages([
                    'note' => __("La note ne doit pas dépasser le barème de la tâche (max : :max).", ['max' => $maxNote])
                ]);
            }
        }
    }
}
```
