import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import { Action } from './Action';
import EventUtil from './../utils/EventUtil';

export class ShowIndexAction extends Action {
    constructor(config,tableUI) {
        super(config); 
        this.config = config;
        this.tableUI = tableUI; 
        this.SuscesMessage = "Index chargé avec succès.";
    }

    /**
     * Initialise l'écouteur sur les liens avec .showIndex
     */
    init() {
        this.handleShowIndex();
    }

    /**
     * Gère les événements liés à l'affichage de l'index dans une modale.
     */
    handleShowIndex() {
        $(document).on('click', `${this.config.crudSelector} .showIndex`, (e) => {
            e.preventDefault();

            const url = e.currentTarget.href;
            this.loadIndexContent(url);
        });
    }

    /**
     * Charge le contenu de la page index dans un modal.
     * @param {string} url - URL à charger dans le modal.
     */
    loadIndexContent(url) {
        
        const fullUrl= this.appendParamsToUrl(
            url,
            this.viewStateService.getContextParams()
        );
 
        // Afficher le chargement dans le modal
        this.tableUI.indexUI.modalUI.showLoading('Chargement...');


        $.get(fullUrl)
            .done((html) => {

                this.tableUI.indexUI.modalUI.showContent(html);
                this.executeScripts(html);
                this.tableUI.indexUI.modalUI.setTitle(window.modalTitle);
                this.tableUI.indexUI.formUI.init();

            })
            .fail((xhr) => {
                           this.tableUI.indexUI.modalUI.close();
                           AjaxErrorHandler.handleError(xhr, 'Erreur lors du chargement des détails de l\'entité.');
            });
    }
}
