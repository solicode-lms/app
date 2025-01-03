
import $ from 'jquery';


import select2 from 'select2';
select2();
window.$ = $;

import 'summernote/dist/summernote-bs4.min';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle';
import "admin-lte/dist/js/adminlte";


// Import Flatpickr CSS
import 'flatpickr/dist/flatpickr.min.css';

// Import Flatpickr
import flatpickr from 'flatpickr';
// Import the French locale
import { French } from 'flatpickr/dist/l10n/fr.js';



import { CrudManager } from './crud/CrudManager';
import { ConfigHelper } from './crud/helpers/ConfigHelper';
import { NotificationHandler } from './crud/components/NotificationHandler';
import { FormManager } from './crud/components/FormManager';
import { ContexteStateEventHandler } from './crud/eventsHandler/ContexteStateEventHandler';
import { ContextStateManager } from './crud/components/ContextStateManager';

// Init CrudManagers in the page
document.addEventListener("DOMContentLoaded", function () {

    const isDebug = false;

    // Contexte State 
    let contextState = window.contextState;
    
    const contextManager = new ContextStateManager(contextState);
    const contexteEventHandler = new ContexteStateEventHandler(contextState);


    // Vérifie si la configuration des entités est disponible
    if (window.entitiesConfig && Array.isArray(window.entitiesConfig)) {
        // Initialiser les gestionnaires pour chaque entité
        window.entitiesConfig.forEach((entityConfigData) => {
            let entityConfig = new ConfigHelper(entityConfigData);
            entityConfig.isDebug = isDebug;
            entityConfig.contextState = contextState;
            // à ajouter pendant l'envoye de requête ajax
            // entityConfig = contextManager.addContextToConfig(entityConfig);
            const crudManager = new CrudManager(entityConfig);
            crudManager.init();
        });
    }
    contexteEventHandler.init();


    window.notifications.forEach((notificationData) => {
        new NotificationHandler(notificationData).show();
    });
    
    FormManager.initializeSelect2();
    FormManager.initializeRichText();
    FormManager.initializeDate();
});


 