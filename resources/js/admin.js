
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


$(document).ready(function () {
    let activeMenuItems = JSON.parse(localStorage.getItem('activeMenuItems')) || [];

    // Restaurer l'état des éléments actifs du menu
    $('.nav-sidebar .nav-item').each(function () {
        let menuItem = $(this);
        let itemId = menuItem.attr('id');

        if (itemId && activeMenuItems.includes(itemId)) {
            menuItem.addClass('menu-open');
            menuItem.children('.nav-link').addClass('active');
            menuItem.children('.nav-treeview').show();
        }
    });

    // Écouter les clics sur les éléments de menu pour sauvegarder leur état
    $('.nav-sidebar .nav-item .nav-link').on('click', function () {
        let menuItem = $(this).parent();
        let itemId = menuItem.attr('id');

        if (itemId) {
            if (menuItem.hasClass('menu-open')) {
                // Si l'élément est ouvert, on l'enlève de la liste
                activeMenuItems = activeMenuItems.filter(item => item !== itemId);
            } else {
                // Sinon, on l'ajoute
                activeMenuItems.push(itemId);
            }
            localStorage.setItem('activeMenuItems', JSON.stringify(activeMenuItems));
        }
    });

    // Gérer la fermeture/activation de la sidebar
    $('.nav-link[data-widget="pushmenu"]').on('click', function () {
        setTimeout(() => {
            if ($('body').hasClass('sidebar-collapse')) {
                localStorage.setItem('sidebarState', 'collapsed');
            } else {
                localStorage.setItem('sidebarState', 'expanded');
            }
        }, 200);
    });

    // Appliquer l'état de la sidebar au chargement
    if (localStorage.getItem('sidebarState') === 'collapsed') {
        $('body').addClass('sidebar-collapse');
    }
});
