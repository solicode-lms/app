Voici comment utiliser les classes proposées pour structurer `GappCrud` selon une architecture modulaire et facile à maintenir.

---

### Organisation des Classes et Intégration

#### Étape 1 : Initialisation de `CrudConfig`
`CrudConfig` centralise la configuration pour les URLs, les sélecteurs CSS, et autres paramètres nécessaires aux opérations CRUD.

```javascript
import { CrudConfig } from './CrudConfig';

const config = new CrudConfig({
    indexUrl: '/entities',
    createUrl: '/entities/create',
    showUrl: '/entities/:id',
    editUrl: '/entities/:id/edit',
    deleteUrl: '/entities/:id/delete',
    csrfToken: 'your-csrf-token',
    tableSelector: '#entities_table',
    formSelector: '#entity_form',
    modalSelector: '#entity_modal',
    entity_name: 'entity',
    create_title: 'Créer une Entité',
    edit_title: 'Modifier une Entité',
});
```

---

#### Étape 2 : Initialisation des Gestionnaires et Actions
Chaque gestionnaire ou classe est initialisé avec les dépendances nécessaires.

##### Gestionnaire de Modal : `ModalManager`
```javascript
import { ModalManager } from './ModalManager';

const modalManager = new ModalManager('#entity_modal');
```

##### Gestionnaire de Chargement : `CrudLoader`
```javascript
import { CrudLoader } from './CrudLoader';

const loader = new CrudLoader('#card_crud');
```

##### Gestionnaire de Formulaires : `FormManager`
```javascript
import { FormManager } from './FormManager';

const formManager = new FormManager('#entity_form', modalManager);
formManager.init(); // Active la gestion des boutons d'annulation et autres interactions
```

##### Gestionnaire de Messages : `MessageHandler`
```javascript
import { MessageHandler } from './MessageHandler';
```

##### Gestionnaire des Actions CRUD : `CrudActions`
```javascript
import { CrudActions } from './CrudActions';

const actions = new CrudActions(config, modalManager, loader, MessageHandler.showToast);
```

##### Gestionnaire des Événements : `CrudEventManager`
```javascript
import { CrudEventManager } from './CrudEventManager';

const eventManager = new CrudEventManager(config, actions);
eventManager.init(); // Associe les événements aux actions CRUD
```

---

#### Étape 3 : Gestion de la Recherche et de la Pagination (Optionnel)
##### Gestionnaire de Recherche : `SearchManager`
```javascript
import { SearchManager } from './SearchManager';

function fetchSearchData(searchValue) {
    $.ajax({
        url: `/entities?search=${searchValue}`,
        method: 'GET',
        success(response) {
            $('#data-container').html(response.data);
        },
        error(xhr) {
            MessageHandler.showError('Erreur lors de la recherche.');
        },
    });
}

const searchManager = new SearchManager(
    { inputSelector: '#crud_search_input', resultContainerSelector: '#data-container' },
    fetchSearchData,
    500
);
```

##### Gestionnaire de Pagination : `PaginationManager`
```javascript
import { PaginationManager } from './PaginationManager';

function fetchPaginatedData(page) {
    $.ajax({
        url: `/entities?page=${page}`,
        method: 'GET',
        success(response) {
            $('#data-container').html(response.data);
        },
        error(xhr) {
            MessageHandler.showError('Erreur lors du chargement de la page.');
        },
    });
}

const paginationManager = new PaginationManager('#pagination-container', fetchPaginatedData);
```

---

### Flux d’Exécution Complet

1. **Initialisation des Classes** :
   - Toutes les classes nécessaires sont initialisées avec leurs dépendances.
   - Les gestionnaires d’événements, recherche, et pagination sont configurés.

2. **Gestion des Actions CRUD** :
   - Les actions comme `addEntity`, `editEntity`, `showEntity`, et `deleteEntity` sont appelées via les événements configurés dans `CrudEventManager`.

3. **Affichage des Résultats** :
   - `CrudLoader` affiche un indicateur de chargement.
   - `ModalManager` gère les interactions avec les modals (formulaires et détails).
   - `MessageHandler` affiche les messages de succès ou d’erreur.

4. **Recherche et Pagination** :
   - `SearchManager` met à jour les résultats en fonction de la saisie utilisateur.
   - `PaginationManager` charge dynamiquement les pages.

---

### Exemple d’Utilisation dans un Événement

```javascript
// Ouvrir le formulaire d'ajout
$('#addButton').on('click', () => {
    actions.addEntity();
});

// Supprimer une entité
$('#entities_table').on('click', '.deleteButton', (e) => {
    const id = $(e.currentTarget).data('id');
    actions.deleteEntity(id);
});

// Appliquer la recherche
$('#crud_search_input').on('keyup', () => {
    searchManager.handleInputChange();
});
```

---

### Points Forts de cette Structure

1. **Séparation des Préoccupations** :
   - Chaque fonctionnalité (CRUD, modals, formulaires, etc.) est gérée dans une classe dédiée.

2. **Réutilisabilité** :
   - Les classes comme `ModalManager` ou `CrudLoader` peuvent être utilisées dans d'autres projets.

3. **Facilité de Maintenance** :
   - Les mises à jour ou extensions (ajout de fonctionnalités) sont localisées à une seule classe.

4. **Modularité** :
   - La structure permet d'ajouter ou de retirer des fonctionnalités sans impact majeur sur le reste du système.

Avec cette approche, `GappCrud` devient beaucoup plus flexible et maintenable pour les projets évolutifs.