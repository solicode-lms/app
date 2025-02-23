import { SessionStateService } from '../components/SessionStateService';
import { ViewStateService } from '../components/ViewStateService';

export default class ConfigHelper {
    /**
     * Constructeur de la classe ConfigHelper.
     * @param {Object} config - Configuration des opérations CRUD.
     */
    constructor(config) {

        this.isDebug = false;
        this.isDebugInfo = true;
      

        this.entity_name = config.entity_name;
        this.contextKey = config.contextKey;
        this.formSelector = config.formSelector;

        this.sessionStatService = new SessionStateService();
        this.viewStateService = new ViewStateService(this.contextKey,this.entity_name);

        this.indexUrl = config.indexUrl;
        this.editUrl = config.editUrl;
        this.csrfToken = config.csrfToken;
        this.editTitle = config.edit_title;
    }

    init(){
        ViewStateService.init();
    }
     debugInfo(message){
        if( this.isDebugInfo){
            console.log(`[DEBUG] ${message}`);
        }

    }

    toString(){
        return `${this.id} : ${this.ViewStateService}`
    }
    
}