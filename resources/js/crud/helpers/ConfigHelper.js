import { SessionStateService } from '../components/SessionStateService';
import { ContextStateService } from './../components/ContextStateService';
import { ViewStateService } from './../components/ViewStateService';

export default class ConfigHelper {
    /**
     * Constructeur de la classe ConfigHelper.
     * @param {Object} config - Configuration des opérations CRUD.
     */
    constructor(config) {

        this.isDebug = true;
        this.isDebugInfo = true;
      

        this.contextStateService = new ContextStateService();
        this.sessionStatService = new SessionStateService();
        this.viewStatService = new ViewStateService();


        this.entity_name = config.entity_name;
        this.formSelector = config.formSelector;

        this.indexUrl = config.indexUrl;
        this.editUrl = config.editUrl;
        this.csrfToken = config.csrfToken;
        this.editTitle = config.edit_title;
    }

    init(){
        this.contextStateService.init();
        this.sessionStatService.init();
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