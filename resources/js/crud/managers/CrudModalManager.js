

import { ContexteStateEventHandler } from '../eventsHandler/ContexteStateEventHandler';
import { TableUI } from "../components/TableUI";
import { FilterUI } from "../components/FilterUI";
import { PaginationUI } from '../components/PaginationUI';
import { FormUI } from '../components/FormUI';
import { ModalUI } from '../components/ModalUI';
import { ShowAction } from './../actions/ShowAction';
import { BulkActionsUI } from '../components/BulkActionsUI';
import { NotificationUI } from '../components/NotificationUI';
import { ShowUI } from '../components/ShowUI';


export class CrudModalManager {
    /**
     * Constructeur de CrudModalManager.
     * @param {Object} config - Configuration globale pour le CRUD.
     */
    constructor(config) {
        this.config = config;

        // Initialisation des composants UI
        this.notificationUI = new NotificationUI(config, this);
        this.filterUI = new FilterUI(config, this);
        this.tableUI = new TableUI(config, this);
        this.bulkActionsUI = new BulkActionsUI(config, this);
        this.paginationUI = new PaginationUI(config, this);
        this.formUI = new FormUI(config,this);
        this.showUI = new ShowUI(config,this);
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
        this.bulkActionsUI.init();
        this.paginationUI.init();
        this.notificationUI.init();
        if(this.config.isMany){
            this.adapterUiPour_isMany();         
        }

        this.applyActionFromURL();

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

    /**
     * Exécute une action basée sur les paramètres de l'URL (edit, show).
     */
    applyActionFromURL() {
        const url = new URL(window.location.href);
        const actionName = url.searchParams.get("action");
        const actionId = url.searchParams.get("id");
        const contextKey = url.searchParams.get("contextKey");
    
        if(contextKey && this.config.contextKey != contextKey) {
            return;
        }

        if (!actionName || !actionId) return; // Vérification : si action ou id est manquant, on arrête l'exécution

        switch (actionName.toLowerCase()) {
            case "edit":
                this.tableUI.entityEditor.editEntity(actionId);
                break;
            case "show":
                this.tableUI.entityEditor.showEntity(actionId); // Correction : appel correct de la méthode
                break;
            default:
                console.warn(`Action inconnue : ${actionName}`);
                break;
        }
    }

}
