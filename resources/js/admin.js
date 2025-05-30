
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
    FormUI.initSelect2Color();
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


$('.widget').sortable({
    placeholder: 'sort-highlight',
    handle: '.handle',
    forcePlaceholderSize: true,
    zIndex: 999999
})



// Code à mettre dans _index : realisationTache

/**
 * Gère l’affichage/cachage du wrapper parent d’un <select> ou d’un Select2
 * @param {HTMLElement} el      L’élément <select>
 * @param {boolean}     visible true → afficher, false → masquer
 */
function toggleVisibility(el, visible) {
  const wrapper = el.parentElement;
  if (!wrapper) return;
  wrapper.style.display = visible ? '' : 'none';
}

/**
 * Gère l'affichage des deux <select> en fonction des sélections
 * @param {string} etatSelectId     ID du select “état”
 * @param {string} workflowSelectId ID du select “workflowTache”
 */
function manageSelectVisibility(etatSelectId, workflowSelectId) {
  const etatEl     = document.getElementById(etatSelectId);
  const workflowEl = document.getElementById(workflowSelectId);
  if (!etatEl || !workflowEl) return;

  const etatChoisi     = etatEl.value !== '';
  const workflowChoisi = workflowEl.value !== '';
  const etatVide       = etatEl.options.length === 1;

  // Si le select d'état n'a plus aucune option → forcer affichage du workflow
  if (etatVide) {
    toggleVisibility(etatEl, false);
    toggleVisibility(workflowEl, true);
    return;
  }

  // Cas normal : on veut qu'au moins un soit visible
  if (etatChoisi || workflowChoisi) {
    toggleVisibility(etatEl, true);
    toggleVisibility(workflowEl, workflowChoisi);
  } else {
    // aucun choix → afficher l'état par défaut
    toggleVisibility(etatEl, true);
    toggleVisibility(workflowEl, false);
  }
}

// IDs des selects
const ETAT_ID     = 'filter_etat_realisation_tache_id';
const WORKFLOW_ID = 'filter_etatRealisationTache.WorkflowTache.Code';

// Initialisation et branchements
document.addEventListener('DOMContentLoaded', () => {
  const etatEl = document.getElementById(ETAT_ID);
  const wfEl   = document.getElementById(WORKFLOW_ID);

  // Première exécution
  manageSelectVisibility(ETAT_ID, WORKFLOW_ID);

  // Observer les ajouts, suppressions ou modifications d'options sur le select "état"
  const observer = new MutationObserver(() => {
    manageSelectVisibility(ETAT_ID, WORKFLOW_ID);
  });
  observer.observe(etatEl, {
    childList: true,    // ajout/suppression d'options
    subtree: true,      // sous-arbre (pour les <option>)
    attributes: true,   // modifications d'attributs sur <option>
    attributeFilter: ['value', 'label', 'disabled'] 
  });

  // Lorsqu'une nouvelle option est sélectionnée dans le champ Select2 (ETAT_ID),
  // on réinitialise le champ WORKFLOW_ID si une valeur y est présente,
  // afin d'assurer que les filtres restent cohérents entre eux.
  // L'événement 'select2:select' est utilisé ici car l'événement natif 'change'
  // peut être déclenché trop tard ou de manière imprévisible avec Select2.
  // Ce traitement doit être effectué avant toute soumission automatique déclenchée ailleurs.
  // $("#" + ETAT_ID).on('select2:select', () => {
  //   if (wfEl.value !== '') {
  //     wfEl.selectedIndex = 0;
  //   }
  //   manageSelectVisibility(ETAT_ID, WORKFLOW_ID);
  // });
    const $etat = $("#" + ETAT_ID);
    // Fonction appelée "avant modification"
    function handleEtatFocus() {
      if (wfEl.value !== '') {
        wfEl.selectedIndex = 0;
        $(wfEl).trigger('change');
      }
      manageSelectVisibility(ETAT_ID, WORKFLOW_ID);
    }
    // ✅ Cas 1 : Select natif → focus
    $etat.on('focus', handleEtatFocus);
    // ✅ Cas 2 : Select2 → select2:opening (équivalent à focus)
    if ($etat.hasClass('select2-hidden-accessible')) {
      $etat.on('select2:select', handleEtatFocus);
    }

  $(etatEl).on('select2:select', function () {
  // 1. Réinitialiser wfEl
  if (wfEl.value !== '') {
    $(wfEl).val(null).trigger('change'); // change déclenché ici
  }

  // 2. Gérer la visibilité ou autres logiques liées
  manageSelectVisibility(ETAT_ID, WORKFLOW_ID);


  });

  // Sur changement de sélection dans le workflow
  wfEl.addEventListener('change', () => {
    manageSelectVisibility(ETAT_ID, WORKFLOW_ID);
  });
});
