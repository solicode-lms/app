
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
    let sidebarState = localStorage.getItem('sidebarState') || 'expanded';

    // Appliquer l'état initial de la sidebar et restaurer l'état des menus actifs
    if (sidebarState === 'collapsed') {
        $('body').addClass('sidebar-collapse');
    } else {
        restoreMenuState();
    }

    // Gérer le bouton toggle de la sidebar
    $('.nav-link[data-widget="pushmenu"]').on('click', function () {
        setTimeout(() => {
            if ($('body').hasClass('sidebar-collapse')) {
                localStorage.setItem('sidebarState', 'collapsed');
                closeAllMenus();
            } else {
                localStorage.setItem('sidebarState', 'expanded');
                restoreMenuState(); // Restaurer les menus actifs après l'expansion
            }
        }, 200);
    });

    // Ouvrir la sidebar au survol (hover) si elle est réduite
    $('.main-sidebar').hover(
        function () {
            if ($('body').hasClass('sidebar-collapse')) {
                $('body').removeClass('sidebar-collapse').addClass('sidebar-open');
                restoreMenuState(); // Restaurer les menus ouverts au survol
            }
        },
        function () {
            if ($('body').hasClass('sidebar-open')) {
                $('body').removeClass('sidebar-open').addClass('sidebar-collapse');
                closeAllMenus(); // Fermer les menus quand on quitte la sidebar
            }
        }
    );

    // Gestion des clics sur les éléments de menu
    $('.nav-sidebar .nav-item .nav-link').on('click', function (e) {
        let menuItem = $(this).parent();
        let itemId = menuItem.attr('id');

        if (menuItem.hasClass('menu-open')) {
            menuItem.removeClass('menu-open');
            menuItem.children('.nav-treeview').slideUp();
            menuItem.children('.nav-link').removeClass('active');
            activeMenuItems = activeMenuItems.filter(item => item !== itemId);
        } else {
            $('.nav-sidebar .nav-item').removeClass('menu-open');
            $('.nav-sidebar .nav-item .nav-link').removeClass('active');
            $('.nav-sidebar .nav-treeview').slideUp();

            menuItem.addClass('menu-open');
            menuItem.children('.nav-link').addClass('active');
            menuItem.children('.nav-treeview').slideDown();
            activeMenuItems = [itemId];
        }

        localStorage.setItem('activeMenuItems', JSON.stringify(activeMenuItems));

        e.stopPropagation();
    });

    // Fonction pour fermer tous les menus
    function closeAllMenus() {
        $('.nav-item').removeClass('menu-open');
        $('.nav-item .nav-link').removeClass('active');
        $('.nav-item .nav-treeview').slideUp();
    }

    // Fonction pour restaurer l'état des menus actifs
    function restoreMenuState() {
        $('.nav-item').each(function () {
            let menuItem = $(this);
            let itemId = menuItem.attr('id');

            if (activeMenuItems.includes(itemId)) {
                menuItem.addClass('menu-open');
                menuItem.children('.nav-link').addClass('active');
                menuItem.children('.nav-treeview').show();
            }
        });
    }
});
