Voici la **solution complète** pour permettre à l’utilisateur de **changer dynamiquement la vue de l’index (table, widgets...) via un menu dropdown**, avec rechargement AJAX et intégration à ton architecture existante (`viewStateService`, `tableUI`, etc.).

---

## ✅ 1. HTML – Menu Dropdown Bootstrap 4

À intégrer dans ta page `index.blade.php` ou vue équivalente :

```html
<div class="dropdown mb-3 text-right">
    <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-eye"></i> Vue
    </button>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
        <button class="dropdown-item view-switch-option" data-view-type="table">
            🗂️ Vue Tableau
        </button>
        <button class="dropdown-item view-switch-option" data-view-type="widgets">
            📊 Vue Widgets
        </button>
    </div>
</div>
```

---

## ✅ 2. Fichier JavaScript – `IndexViewSwitcher.js`

À mettre dans `components/IndexViewSwitcher.js` :

```js
export class IndexViewSwitcher {
    /**
     * 
     * @param {Object} config - La configuration générale (contenant viewStateService)
     * @param {Object} tableUI - L’instance de TableUI
     */
    constructor(config, tableUI) {
        this.config = config;
        this.tableUI = tableUI;
        this.contextState = config.viewStateService;
    }

    init() {
        const currentType = this.contextState.getVariables()?.view_type;
        if (currentType) {
            this.highlightSelected(currentType);
        }

        $(document).on('click', '.view-switch-option', (e) => {
            const selectedType = $(e.currentTarget).data('view-type');
            this.contextState.updateContext({ variables: { view_type: selectedType } });

            this.highlightSelected(selectedType);

            // Rechargement AJAX via tableUI → LoadListAction
            this.tableUI.entityLoader.loadEntities(1);
        });
    }

    highlightSelected(type) {
        $('.view-switch-option').removeClass('active');
        $(`.view-switch-option[data-view-type="${type}"]`).addClass('active');
    }
}
```

---

## ✅ 3. Intégration dans `IndexUI.js` ou équivalent

Dans ton gestionnaire principal d’interface `IndexUI` :

```js
import { IndexViewSwitcher } from './components/IndexViewSwitcher';

export class IndexUI {
    constructor(config) {
        this.config = config;
        this.tableUI = new TableUI(config, this);
        this.filterUI = new FilterUI(config, this);
        this.viewSwitcher = new IndexViewSwitcher(config, this.tableUI);
    }

    init() {
        this.tableUI.init();
        this.filterUI.init();
        this.viewSwitcher.init();
    }
}
```

---

## ✅ 4. Côté Backend – Contrôleur Laravel

Dans ton contrôleur Laravel :

```php
public function index(Request $request)
{
    $viewType = $request->get('context_view_type', 'table');
    $data = Entity::paginate(10); // Exemple

    return match($viewType) {
        'widgets' => view('entities.partials._index_widgets', compact('data')),
        'table' => view('entities.partials._index_table', compact('data')),
        default => view('entities.partials._index_table', compact('data')),
    };
}
```

> 📝 Tes fichiers `_index_table.blade.php` et `_index_widgets.blade.php` doivent retourner un HTML complet destiné à être injecté via AJAX dans `tableSelector`.

---

## ✅ 5. Résultat final

- ✅ **Interface avec menu dropdown**
- ✅ **Sélection dynamique de vue (table/widgets)**
- ✅ **Context stocké via `ContextStateService`**
- ✅ **Rechargement AJAX propre via `LoadListAction`**
- ✅ **Support côté serveur avec vue partielle dynamique**

---

Souhaites-tu que je te génère le fichier `IndexViewSwitcher.js` prêt à l’emploi ?