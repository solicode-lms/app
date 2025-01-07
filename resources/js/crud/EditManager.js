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
export class EditManager {
    /**
     * Constructeur de CrudManager.
     * @param {Object} config - Configuration globale pour le CRUD.
     */
    constructor(config) {
        this.config = config;
        this.contexteEventHandler = new ContexteStateEventHandler(config);
    }

    /**
     * Initialise tous les gestionnaires et actions CRUD.
     */
    init() {

        
    }
}
