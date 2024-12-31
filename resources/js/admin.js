import $ from 'jquery';


import select2 from 'select2';
select2();
window.$ = $;

import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle';
import "admin-lte/dist/js/adminlte";
import { CrudManager } from './crud/CrudManager';
import { ConfigHelper } from './crud/helpers/ConfigHelper';
import { NotificationHandler } from './crud/components/NotificationHandler';

// Init CrudManagers in the page
document.addEventListener("DOMContentLoaded", function () {
    // Vérifie si la configuration des entités est disponible
    if (!window.entitiesConfig || !Array.isArray(window.entitiesConfig)) {
        console.error('La configuration des entités est manquante ou invalide.');
        return;
    }

    // Initialiser les gestionnaires pour chaque entité
    window.entitiesConfig.forEach((entityConfigData) => {
        const entityConfig = new ConfigHelper(entityConfigData);
        const crudManager = new CrudManager(entityConfig);
        crudManager.init();
    });

    window.notifications.forEach((notificationData) => {
        new NotificationHandler(notificationData).show();
    });

    $('.select2').select2()
    
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
});
