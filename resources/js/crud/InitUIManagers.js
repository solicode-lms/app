

import ConfigCrudManagerHelper  from "./helpers/ConfigCrudManagerHelper";
import ConfigWithTabPanelManageHelper  from "./helpers/ConfigWithTabPanelManageHelper";
import { CrudModalManager } from './managers/CrudModalManager';
import { EditWithTabPanelManager } from './managers/EditWithTabPanel';

export default class InitUIManagers {
    
    static processedManagers = new Set(); // Stocke les identifiants des entités déjà traitées

    static init() {
        InitUIManagers.initCrudModalManagers();
        InitUIManagers.initEditWithTabPanelManagers();

        window.crudModalManagersConfig = [];
        window.editWithTabPanelManagersConfig = [];
    }

    static initCrudModalManagers() {
        if (!window.crudModalManagersConfig || !Array.isArray(window.crudModalManagersConfig)) {
            return; // Si la configuration des entités est absente ou invalide, on arrête
        }
        
        window.crudModalManagersConfig.forEach((crudModalManagerData) => {

            const configHelper = new ConfigCrudManagerHelper(crudModalManagerData, window.contextState,window.sessionState);

            const uniqueKey = configHelper.id || JSON.stringify(crudModalManagerData);
            if (InitUIManagers.processedManagers.has(uniqueKey)) 
                return;
            InitUIManagers.processedManagers.add(uniqueKey);
            
           
            const crudModalManager = new CrudModalManager(configHelper);
            crudModalManager.init();

            if (!window.crudModalManagers) {
                window.crudModalManagers = {};
            }
            window.crudModalManagers[configHelper.id] = crudModalManager;
        });
    }

    static initEditWithTabPanelManagers() {
        if (!window.editWithTabPanelManagersConfig || !Array.isArray(window.editWithTabPanelManagersConfig)) {
            return; // Si la configuration des entités est absente ou invalide, on arrête
        }
        
        window.editWithTabPanelManagersConfig.forEach((editWithTabPanelManagerData) => {
            const configHelper = new ConfigWithTabPanelManageHelper(editWithTabPanelManagerData, window.contextState, window.sessionState);
            
            const uniqueKey = configHelper.id || JSON.stringify(editWithTabPanelManagerData);
           
            if (InitUIManagers.processedManagers.has(uniqueKey)) return;
            InitUIManagers.processedManagers.add(uniqueKey);
            
          
            const editWithTabPanelManager = new EditWithTabPanelManager(configHelper);
            editWithTabPanelManager.init();

            if (!window.editWithTabPanelManagers) {
                window.editWithTabPanelManagers = {};
            }
            window.editWithTabPanelManagers[configHelper.id] = editWithTabPanelManager;
        });
    }
}
