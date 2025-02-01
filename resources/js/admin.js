
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
import { ContextStateService } from './crud/components/ContextStateService';
import { EditWithHasManyManager } from './crud/EditWithHasManyManager';
import DynamicFieldVisibilityTreatment from './crud/treatments/form/DynamicFieldVisibilityTreatment';
import { DashboardUI } from './crud/components/DashboardUI';
import InitCrudManagers from './crud/InitCrudManagers';

import "izimodal/js/iziModal.min.js";
import "izimodal/css/iziModal.min.css";



// Init CrudManagers in the page
document.addEventListener("DOMContentLoaded", function () {

    const isDebug = false;

    // Contexte State 
    let contextState = window.contextState;
    let sessionState = window.sessionState;
    
 
    const dashboardUI = new DashboardUI(sessionState);
    dashboardUI.init();

    InitCrudManagers.init(),

    window.notifications.forEach((notificationData) => {
        new NotificationHandler(notificationData).show();
    });
    

    
    // TODO : Appliquer la validation de formulaire dans une page création sans Model


    FormManager.initializeSelect2();
    FormManager.initializeRichText();
    FormManager.initializeDate();
    FormManager.initCodeJar();

    // if(window.dynamicFieldVisibilityTreatments){
    //     new DynamicFieldVisibilityTreatment(window.dynamicFieldVisibilityTreatments)
    //     .initialize();
    // }

});


document.addEventListener("DOMContentLoaded", function () {

});
