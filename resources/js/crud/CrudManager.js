import { CreateAction } from './actions/CreateAction';
import { ShowAction } from './actions/ShowAction';
import { EditAction } from './actions/EditAction';
import { DeleteAction } from './actions/DeleteAction';

import { LoadListAction } from './actions/LoadListAction';
import { SearchPaginationEventHandler } from './eventsHandler/SearchPaginationEventHandler';
import { ActionsEventHandler } from './eventsHandler/ActionsEventHandler';
import { ContexteStateEventHandler } from './eventsHandler/ContexteStateEventHandler';
 
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
        this.entityCreator = new CreateAction(config);
        this.entityViewer = new ShowAction(config);
        this.entityEditor = new EditAction(config);
        this.entityDeleter = new DeleteAction(config);

        // Initialisation des gestionnaires
        this.entityLoader = new LoadListAction(config);
        this.eventManager = new ActionsEventHandler(config, {
            creator: this.entityCreator,
            viewer: this.entityViewer,
            editor: this.entityEditor,
            deleter: this.entityDeleter,
        });


        this.contexteEventHandler = new ContexteStateEventHandler(config);


        this.searchAndPaginationManager = new SearchPaginationEventHandler(config, this.entityLoader);
    }

    /**
     * Initialise tous les gestionnaires et actions CRUD.
     */
    init() {
        // Charger les entités initiales
        // this.entityLoader.loadEntities();

        // Initialiser la gestion des événements CRUD
       


        if (this.config.page == "index") {
            this.searchAndPaginationManager.init();
            this.eventManager.init();
        } 
        if (this.config.page == "edit") {
            this.eventManager.init();
        } 


        this.contexteEventHandler.init();

    }
}
