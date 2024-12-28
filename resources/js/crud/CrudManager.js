import { EntityCreator } from './actions/EntityCreator';
import { EntityViewer } from './actions/EntityViewer';
import { EntityEditor } from './actions/EntityEditor';
import { EntityDeleter } from './actions/EntityDeleter';
import { CrudEventManager } from './events/CrudEventManager';
import { SearchAndPaginationManager } from './entities/SearchAndPaginationManager';
import { EntityLoader } from './entities/EntityLoader';

/**
 * Classe principale pour gérer le CRUD.
 */
export class CrudManager {
    /**
     * Constructeur de CrudManager.
     * @param {Object} config - Configuration globale pour le CRUD.
     */
    constructor(config) {
        this.config = config;

        // Initialisation des actions CRUD
        this.entityCreator = new EntityCreator(config);
        this.entityViewer = new EntityViewer(config);
        this.entityEditor = new EntityEditor(config);
        this.entityDeleter = new EntityDeleter(config);

        // Initialisation des gestionnaires
        this.entityLoader = new EntityLoader(config);
        this.eventManager = new CrudEventManager(config, {
            creator: this.entityCreator,
            viewer: this.entityViewer,
            editor: this.entityEditor,
            deleter: this.entityDeleter,
        });
        this.searchAndPaginationManager = new SearchAndPaginationManager(config, this.entityLoader);
    }

    /**
     * Initialise tous les gestionnaires et actions CRUD.
     */
    init() {
        // Charger les entités initiales
        // this.entityLoader.loadEntities();

        // Initialiser la gestion des événements CRUD
        this.eventManager.init();

        // Initialiser la recherche et la pagination
        this.searchAndPaginationManager.init();
    }
}
