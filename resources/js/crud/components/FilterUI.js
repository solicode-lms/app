import { TableUI } from './TableUI';
import EventUtil from './../utils/EventUtil';
import DynamicDropdownTreatment from '../treatments/global/DynamicDropdownTreatment';
import { BulkActionsUI } from './BulkActionsUI';

export class FilterUI {


    constructor(config, indexUI) {
        this.config = config;
        this.indexUI = indexUI;
        // Temps de délai pour limiter les requêtes fréquentes
        this.debounceTimeout = null;
        this.debounceDelay = 500; // Par défaut : 500ms
        this.page = 0;
    }

    init() {
        this.config.init();
        this.initFilterToogle();
        this.handleFormInput(); // Gérer les entrées dans le formulaire (recherche + filtres)
        this.adapterPourContext(); // Masquer les filtres dynamiquement selon le contexte
        this.initializeFilterResetHandler();
        this.initStats();
     

        // DynamicDropdownTreatment
        document.querySelectorAll(this.config.filterFormSelector + " [data-target-dynamic-dropdown]").forEach((element) => {
            new DynamicDropdownTreatment(element,this.config);
        });
    }


    /**
     * Récupère les valeurs de tous les champs du formulaire (recherche + filtres).
     * @returns {Object} - Un objet contenant les données du formulaire.
     */
    getFormData(withEmpty = false) {

        const form = $(this.config.filterFormSelector);
        const formData = {};
        
        // serializeArray() récupère tous les champs du formulaire sous forme [{ name, value }]
        const dataArray = form.serializeArray();
        
        // ✅ On construit formData : si un champ a plusieurs entrées (ex: hidden + checkbox), seule la dernière est conservée
        dataArray.forEach(({ name, value }) => {
            formData[name] = value;
        });
       
        // Parcourir les champs du formulaire
        form.serializeArray().forEach((field) => {
            const value = field.value.trim(); // Supprimer les espaces inutiles
            if (withEmpty || value) { // Inclure les champs vides seulement si demandé
                field.name = field.name.replace("/",".");
                formData[field.name] = value;
            }
        });



        return formData;
    }

    /**
     * get les varaibles à ajouter dans le context pour assurer la persistance des valeurs de
     * de filtre dans tous les interface
     */
    getFormDataAsStateVariables(){
        const data = {};
        const formData  = this.getFormData(true)
        Object.entries(formData).forEach(([key, value]) => {
            data[`filter.${this.config.entity_name}.${key}`] = value;
        });

     
         
        return data;
    }

    getUrlParams() {
        const prefixedContext = {};
        Object.entries(this. getFormData()).forEach(([key, value]) => {
            prefixedContext[`${key}`] = value;
        });
        return new URLSearchParams(prefixedContext).toString();
    }

    /**
     * Soumet le formulaire en récupérant les données et recharge les entités.
     * @param {number} page - Numéro de la page à charger (par défaut : 1).
     */
    submitForm(page = 1) {
        const formData = this.getFormData(true); // Récupérer les données du formulaire

       
        formData.page = page; // Ajouter le numéro de page aux données
    
        // ✅ Ajouter le flag "reset_filter" si nécessaire
       
        
        // View State Filter 
        this.config.viewStateService.updatFilterVariables(this.getFormData(true));
        // Mettre à jour l'URL avec les paramètres non vides
        this.indexUI.updateURLParameters(this.getFormDataAsStateVariables());
    
        // Charger les entités avec les paramètres
        this.indexUI.tableUI.entityLoader.loadEntities(page, formData);

        // TODO 
        // Update Context : ajouter ou supprimer les filtre de contexte pour adapter le formulaire 
        // Création au filtre, c'est appliquer les valeurs de filtre pour les valeurs de formulaire
        // Il faut gérer l'insertion et la suppression
    }

    /**
     * Gère les événements du formulaire (recherche et filtres) avec délai (debounce).
     */
    handleFormInput() {
        const formSelector = this.config.filterFormSelector;

        // Sur saisie dans les champs de recherche ou filtres
        EventUtil.bindEvent('input', `${formSelector} input, ${formSelector} select`, () => {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
                this.updateFilterState();
                this.submitForm(); // Soumettre le formulaire après un délai
            }, this.debounceDelay);
        });

        // Soumission explicite du formulaire (par bouton ou utilisateur)
        EventUtil.bindEvent('submit', formSelector, (e) => {
            e.preventDefault();
            this.submitForm();
        });
    }


    /**
     * Masque les éléments <select> dont l'id correspond à une clé dans le contexte (state).
     */
    adapterPourContext() {
        
        const scopeData = this.config.viewStateService.getScopeVariables();
        const filterData = this.config.viewStateService.getFilterVariables();
    
        // Appliquer les variables de scope pour masquer ou surligner les filtres
        Object.keys(scopeData).forEach((key) => {
            const filterElement = document.querySelector(`${this.config.filterFormSelector} [name="${key}"]`);
            if (filterElement) {
                if (this.config.isDebug) {
                    filterElement.parentElement.style.backgroundColor = 'lightblue'; // Mode debug : surligner
                } else {
                    filterElement.parentElement.style.display = 'none'; // Masquer l'élément du filtre
                }
            }
        });
    
        // Appliquer les valeurs des filtres et masquer si nécessaire
        // Seulement dans le cas de insert, dans le cas de edit il faut garder les valeurs de l'objet
        Object.keys(filterData).forEach((key) => {
            const filterElement = document.querySelector(`${this.config.filterFormSelector} [name="${key}"]:not([data-filter-ignore])`);
            if (filterElement) {
                    if (filterElement.type === "checkbox") {
                    // Si la valeur est "1", on coche la case, sinon on décoche
                    filterElement.checked = filterData[key] === "1" || filterData[key] === 1 || filterData[key] === true;
                    } else if (filterElement.tagName === "INPUT" || filterElement.tagName === "TEXTAREA") {
                        filterElement.value = filterData[key];
                    } else if (filterElement.tagName === "SELECT") {
                        filterElement.value = filterData[key];
                        filterElement.dispatchEvent(new Event("change"));
                    }
            }
        });
    }
    

      // Fonction pour vérifier l'état des filtres
      updateFilterState (){
        const filterIcon = document.querySelector(this.config.filterIconSelector);
        const filterForm = document.querySelector(this.config.filterFormSelector);
        if(!filterForm) 
            return;
        const filters = new FormData(filterForm);
        
        let hasActiveFilters = false;

        for (let [key, value] of filters.entries()) {
            if (value.trim() !== '' && key !== 'page') {
                hasActiveFilters = true;
                break;
            }
        }

        if (hasActiveFilters) {
            filterIcon.classList.remove('fa-filter');
            filterIcon.classList.add('fa-times-circle');
        } else {
            filterIcon.classList.remove('fa-times-circle');
            filterIcon.classList.add('fa-filter');
        }
    };


    initializeFilterResetHandler() {
        const filterIcon = document.querySelector(this.config.filterIconSelector);
        const filterForm = document.querySelector(this.config.filterFormSelector);
    

        EventUtil.bindEvent('click', this.config.filterIconSelector, (e) => {
            filterForm.querySelectorAll('input, select').forEach((field) => {
                field.value = ''; // Réinitialiser les champs
            });

             // Réinitialiser Select2
            $(filterForm).find('.select2').val(null).trigger('change');


            this.updateFilterState();
            this.submitForm(); // Soumettre le formulaire après réinitialisation


        });

      
        // Réinitialiser les filtres au clic sur l'icône
        // filterIcon.addEventListener('click', () => {
        //     filterForm.querySelectorAll('input, select').forEach((field) => {
        //         field.value = ''; // Réinitialiser les champs
        //     });

        //      // Réinitialiser Select2
        //     $(filterForm).find('.select2').val(null).trigger('change');


        //     this.updateFilterState();
        //     this.submitForm(); // Soumettre le formulaire après réinitialisation
        // });
    
        // Vérifier l'état des filtres au chargement et sur modification
        this.updateFilterState();
    }


/**
 * Initialise l'affichage des statistiques.
 */
initStats() {
    // Sélectionner le conteneur des statistiques
    const statsContainer = document.querySelector(`${this.config.crudSelector} .stats-summary-items`);

    if (!statsContainer) {
        console.warn("Le conteneur des statistiques n'a pas été trouvé.");
        return;
    }

    // Récupérer les statistiques depuis le service de contexte
    const statsData = this.config.viewStateService.getStatsVariables();

    // Vérifier si l'objet statsData et statsData.stats existent et sont valides
    if (!statsData || !Array.isArray(statsData.stats) || statsData.stats.length === 0) {
        statsContainer.innerHTML = '<span class="text-muted">Aucune statistique disponible</span>';
        return;
    }

    // Générer les badges de statistiques dynamiquement
    statsContainer.innerHTML = statsData.stats
        .map(stat => `
            <span class="badge badge-info mr-2 p-1" style="margin-bottom: 4px">
                <i class="${stat.icon}"></i> ${stat.label} : ${stat.value}
            </span>
        `)
        .join("");
}

//   

initFilterToogle() {
    const toggleButtonSelector = `${this.config.crudSelector} #toggle-filter`;
    const $btn = $(toggleButtonSelector);
    const $card = $(`${this.config.crudSelector} #card_crud`);
    const storageKey = `toggle_filter_state_${this.config.crudSelector}`;
    const $contentToToggle = $card.find('.card-header:not(:first)');

    // Fonction pour cacher/montrer les filtres
    const setFilterVisibility = (visible) => {
        if (visible) {
            $contentToToggle.show();
            $btn.attr('data-visible', '1');
        } else {
            $contentToToggle.hide();
            $btn.attr('data-visible', '0');
        }
        $btn.html('<i class="fas fa-filter"></i>');
        localStorage.setItem(storageKey, visible ? '1' : '0');
    };

    // 🔹 Rétablir l'état au chargement
    const savedState = localStorage.getItem(storageKey);
    setFilterVisibility(savedState === '1');

    // 🔸 Gérer le clic sur le bouton
    EventUtil.bindEvent('click', toggleButtonSelector, (e) => {
        const self = $(e.currentTarget);
        const isVisible = self.attr('data-visible') === '1';
        setFilterVisibility(!isVisible);
    });
}




}
