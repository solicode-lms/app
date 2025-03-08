
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
import AsideMenu from './AsideMenu';


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

    AsideMenu.init();



    // /**
    //  * Gère le changement de valeur d'un champ de sélection et met à jour dynamiquement une liste d'options basée sur une requête API.
    //  * @param {string} triggerSelector - Sélecteur du champ qui déclenche le changement.
    //  * @param {string} targetSelector - Sélecteur du champ à mettre à jour.
    //  * @param {string} apiUrlTemplate - Modèle d'URL pour récupérer les données dynamiquement.
    //  */
    // function setupDynamicDropdown(triggerSelector, targetSelector, apiUrlTemplate) {
    //     const triggerElement = document.querySelector(triggerSelector);
    //     const targetElement = document.querySelector(targetSelector);

    //     if (!triggerElement || !targetElement) return;

    //     triggerElement.addEventListener('change', async function () {
    //         const selectedValue = this.value;
    //         const apiUrl = apiUrlTemplate.replace('{id}', selectedValue);
    //         const previousSelection = targetElement.value;

    //         try {
    //             const response = await fetch(apiUrl);
    //             if (!response.ok) throw new Error('Erreur lors du chargement des données');
                
    //             const data = await response.json();
    //             targetElement.innerHTML = ''; // Vider les anciennes options
                
    //             data.forEach(item => {
    //                 const option = document.createElement('option');
    //                 option.value = item.id;
    //                 option.textContent = item.titre;
    //                 targetElement.appendChild(option);
    //             });

    //             // Restaurer la sélection précédente si valide
    //             if ([...targetElement.options].some(option => option.value == previousSelection)) {
    //                 targetElement.value = previousSelection;
    //             }
    //         } catch (error) {
    //             console.error('Erreur lors du chargement des options:', error);
    //         }
    //     });
    // }

    // // Initialisation avec des sélecteurs dynamiques et un modèle d'URL
    // setupDynamicDropdown(
    //     '[name="realisationProjet.affectation_projet_id"]', 
    //     '[name="tache_id"]', 
    //     'http://localhost/admin/PkgGestionTaches/taches/getTacheByAffectationProjetId/{id}'
    // );

    
   

});

