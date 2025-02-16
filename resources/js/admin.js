
// Utilisation de JQuery et Plugin JQuery sans Vite : car il ne sont pas ES6
// Le chargement de JQuery doit être avant le chargement des fichiers Vite, car les classe Vite 
// utilise les plugin JQuery


// Import Flatpickr CSS
import 'flatpickr/dist/flatpickr.min.css';

import { NotificationHandler } from './crud/components/NotificationHandler';
import { FormUI } from './crud/components/FormUI';
import { DashboardUI } from './crud/components/DashboardUI';
import InitUIManagers from './crud/InitUIManagers';
import { TableUI } from './crud/components/TableUI';


// Init CrudModalManagers in the page
document.addEventListener("DOMContentLoaded", function () {

    // Contexte State 
    let contextState = window.contextState;
    let sessionState = window.sessionState;
    
    // Adaptation de Dashboard à l'utilisateur
    const dashboardUI = new DashboardUI(sessionState);
    dashboardUI.init();

    // Initialisation des UI Manager comme CrudModalmanager, ..
    InitUIManagers.init(),

    // Affichage des notification global
    window.notifications.forEach((notificationData) => {
        new NotificationHandler(notificationData).show();
    });
    

    
    // TODO : il faut ajouter une condition pour gérer une page PageUIManager
    FormUI.initializeSelect2();
    FormUI.initializeRichText();
    FormUI.initializeDate();
    FormUI.initCodeJar();
    TableUI.initTooltip();

   

});