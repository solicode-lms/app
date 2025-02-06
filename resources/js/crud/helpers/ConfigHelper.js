import { SessionStateService } from '../components/SessionStateService';
import { ContextStateService } from './../components/ContextStateService';

export default class ConfigHelper {
    /**
     * Constructeur de la classe ConfigHelper.
     * @param {Object} config - Configuration des opérations CRUD.
     */
    constructor(config, contextState, sessionState) {

        this.isDebug = false;
        this.isDebugInfo = true;
      

        this.contextStateService = new ContextStateService(contextState);
        this.sessionStatService = new SessionStateService(sessionState);
        
        this.entity_name = config.entity_name;
        this.formSelector = config.formSelector;

        this.indexUrl = config.indexUrl;
        this.editUrl = config.editUrl;
        this.csrfToken = config.csrfToken;
        this.editTitle = config.edit_title;
    }

     debugInfo(message){
        if( this.isDebugInfo){
            console.log(`[DEBUG] ${message}`);
        }

    }

    toString(){
        return `${this.id} : ${this.contextStateService}`
    }
    
}