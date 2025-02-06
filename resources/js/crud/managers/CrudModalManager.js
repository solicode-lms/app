

import { ContexteStateEventHandler } from '../eventsHandler/ContexteStateEventHandler';
import { TableUI } from "../components/TableUI";
import { FilterUI } from "../components/FilterUI";
import { PaginationUI } from '../components/PaginationUI';
import { FormUI } from '../components/FormUI';
import { ModalUI } from '../components/ModalUI';


export class CrudModalManager {
    /**
     * Constructeur de CrudModalManager.
     * @param {Object} config - Configuration globale pour le CRUD.
     */
    constructor(config) {
        this.config = config;

        // Initialisation des composants UI
        this.filterUI = new FilterUI(config, this);
        this.tableUI = new TableUI(config, this);
        this.paginationUI = new PaginationUI(config, this);
        this.formUI = new FormUI(config,this);
        this.modalUI = new ModalUI(config,this);
        this.contexteEventHandler = new ContexteStateEventHandler(config);
    }

    /**
     * Initialise tous les gestionnaires et actions CRUD.
     */
    init() {
        // Init Components 
        this.filterUI.init();
        this.tableUI.init();
        this.paginationUI.init();

        if(this.config.isMany){
            this.adapterUiPour_isMany();         
        }

        this.config.debugInfo("Init : " + this.config);
    }

    adapterUiPour_isMany(){
        const crud_header = document.querySelector(`#${this.config.entity_name}-crud-header`);
        if (crud_header) {
            crud_header.style.display = 'none'; // Masquer le filtre
        }

        const crud_table_card = document.querySelector(`#${this.config.entity_name}-crud-table #card_crud`);
        if (crud_table_card) {
            crud_table_card.classList.remove("card-info");
        }
    }
    /**
     * Met à jour les paramètres dans l'URL sans recharger la page.
     * @param {Object} params - Données à inclure dans l'URL.
     */
    updateURLParameters(params) {
    const url = new URL(window.location.href);

    // Supprimer uniquement les anciens paramètres liés aux filtres
    Object.entries(params).forEach(([key, value]) => {
        if (value === undefined || value === null || value === '') {
            // Supprimer les filtres qui sont vides ou null
            url.searchParams.delete(key);
        } else {
            // Mettre à jour ou ajouter les autres paramètres
            url.searchParams.set(key, value);
        }
    });

    // Mettre à jour l'URL sans recharger la page
    window.history.replaceState({}, '', url);
}
}
