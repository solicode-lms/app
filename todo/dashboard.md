### Solution Complète : Widgets avec Tables de Paramétrage

Voici une solution entièrement mise à jour, utilisant des **tables de paramétrage** sans recourir aux énumérations. La gestion des **types de widgets**, des **modèles Laravel** et des **opérations** est centralisée dans des tables de paramétrage, rendant le système extensible et maintenable.

---

### **1. Schéma de Base de Données**

#### **1.1. Table `widget_types`**
Stocke les types de widgets disponibles.

```php
Schema::create('widget_types', function (Blueprint $table) {
    $table->id();
    $table->string('type'); // Exemple : card, chart, table
    $table->string('description')->nullable();
    $table->timestamps();
});
```

**Exemple de données** :
| id | type   | description             |
|----|--------|-------------------------|
| 1  | card   | Affiche une carte       |
| 2  | chart  | Affiche un graphique    |
| 3  | table  | Affiche un tableau      |

---

#### **1.2. Table `sys_models`**
Liste les modèles Laravel disponibles.

```php
Schema::create('sys_models', function (Blueprint $table) {
    $table->id();
    $table->string('model'); // Exemple : App\Models\Article
    $table->string('description')->nullable();
    $table->timestamps();
});
```

**Exemple de données** :
| id | model                  | description          |
|----|------------------------|----------------------|
| 1  | App\Models\Article     | Articles du blog     |
| 2  | App\Models\User        | Utilisateurs         |
| 3  | App\Models\Order       | Commandes clients    |

---

#### **1.3. Table `operations`**
Liste des opérations supportées.

```php
Schema::create('widget_operations', function (Blueprint $table) {
    $table->id();
    $table->string('operation'); // Exemple : count, sum, group_by
    $table->string('description')->nullable();
    $table->timestamps();
});
```

**Exemple de données** :
| id | operation          | description                         |
|----|--------------------|-------------------------------------|
| 1  | count              | Compte les enregistrements          |
| 2  | sum                | Somme d'une colonne                |
| 3  | getGroupedByColumn | Groupe les résultats par une colonne|

---

#### **1.4. Table `widgets`**
Stocke les widgets configurés avec des relations vers les tables de paramétrage.

```php
Schema::create('widgets', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // Nom du widget
    $table->foreignId('type_id')->constrained('widget_types')->onDelete('cascade');
    $table->foreignId('model_id')->constrained('sys_models')->onDelete('cascade');
    $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');
    $table->string('color')->nullable();
    $table->string('icon')->nullable();
    $table->string('label')->nullable();
    $table->json('parameters')->nullable(); // Conditions et autres paramètres
    $table->timestamps();
});
```

---

### **2. Contrôleurs**

#### **2.1. Contrôleur pour Ajouter un Widget**

Gestion de la création d’un widget avec des listes déroulantes alimentées par les tables de paramétrage.

```php
use App\Models\WidgetType;
use App\Models\ModelClass;
use App\Models\Operation;
use App\Models\Widget;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    // Formulaire de création
    public function create()
    {
        $widgetTypes = WidgetType::all();
        $modelClasses = ModelClass::all();
        $operations = Operation::all();

        return view('widgets.create', compact('widgetTypes', 'modelClasses', 'operations'));
    }

    // Enregistrement du widget
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:widget_types,id',
            'model_id' => 'required|exists:sys_models,id',
            'operation_id' => 'required|exists:operations,id',
            'parameters' => 'nullable|array',
        ]);

        $widget = new Widget();
        $widget->name = $request->name;
        $widget->type_id = $request->type_id;
        $widget->model_id = $request->model_id;
        $widget->operation_id = $request->operation_id;
        $widget->color = $request->color ?? 'primary';
        $widget->icon = $request->icon ?? 'fa-chart-bar';
        $widget->label = $request->label ?? '';
        $widget->parameters = json_encode($request->parameters);
        $widget->save();

        return redirect()->route('widgets.index')->with('success', 'Widget créé avec succès');
    }
}
```

---

#### **2.2. WidgetService : Exécution de la Requête**

Un service dédié pour interpréter et exécuter les requêtes DSL à partir des informations stockées dans la table `widgets`.

```php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class WidgetService
{
    public function execute(array $query)
    {
        $modelClass = $query['model'];
        $queryBuilder = $modelClass::query();

        // Appliquer les conditions
        foreach ($query['conditions'] ?? [] as $column => $value) {
            $queryBuilder->where($column, $value);
        }

        // Grouper par une colonne
        if (!empty($query['group_by'])) {
            $queryBuilder->groupBy($query['group_by']);
        }

        // Appliquer l’ordre
        if (!empty($query['order_by'])) {
            $queryBuilder->orderBy(
                $query['order_by']['column'],
                $query['order_by']['direction']
            );
        }

        // Limiter les résultats
        return match ($query['operation'] ?? 'get') {
            'count' => $queryBuilder->count(),
            'sum' => $queryBuilder->sum($query['column'] ?? '*'),
            'getGroupedByColumn' => $queryBuilder->get(),
            default => $queryBuilder->get(),
        };
    }     if (!empty($query['limit'])) {
            $queryBuilder->limit($query['limit']);
        }

        // Exécuter l’opération
   
}
```

---

### **3. Formulaire de Création de Widgets**

Un formulaire dynamique pour configurer les widgets en utilisant les données des tables de paramétrage.

```html
<form action="{{ route('widgets.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="name">Nom du Widget</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="form-group">
        <label for="type_id">Type de Widget</label>
        <select class="form-control" id="type_id" name="type_id" required>
            @foreach($widgetTypes as $type)
                <option value="{{ $type->id }}">{{ $type->type }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="model_id">Modèle Laravel</label>
        <select class="form-control" id="model_id" name="model_id" required>
            @foreach($modelClasses as $model)
                <option value="{{ $model->id }}">{{ $model->model }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="operation_id">Opération</label>
        <select class="form-control" id="operation_id" name="operation_id" required>
            @foreach($operations as $operation)
                <option value="{{ $operation->id }}">{{ $operation->operation }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="parameters">Paramètres (JSON)</label>
        <textarea class="form-control" id="parameters" name="parameters"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>
```

---

### **4. Affichage des Widgets**

Les widgets configurés sont affichés dynamiquement à partir des données.

```php
public function index(WidgetService $widgetService)
{
    $widgets = Widget::with(['type', 'model', 'operation'])->get();

    foreach ($widgets as $widget) {
        $query = [
            'model' => $widget->model->model,
            'operation' => $widget->operation->operation,
            'conditions' => json_decode($widget->parameters, true) ?? [],
        ];

        try {
            $widget->data = $widgetService->execute($query);
        } catch (\Exception $e) {
            $widget->data = ['error' => $e->getMessage()];
        }
    }

    return view('dashboard.index', compact('widgets'));
}
```

---

### **5. Résumé des Améliorations**

1. **Tables de paramétrage** :
   - Les types de widgets, modèles et opérations sont centralisés dans des tables.
2. **Formulaire dynamique** :
   - Le formulaire est alimenté dynamiquement par les tables de paramétrage.
3. **WidgetService** :
   - Centralise la logique pour exécuter les requêtes.
4. **Extensibilité** :
   - Ajout facile de nouveaux types, modèles ou opérations.
5. **Clarté** :
   - Code plus propre et organisé.