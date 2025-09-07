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
        EventUtil.bindEvent('click', `${this.config.crudSelector} .showIndex`, (e) => {
            e.preventDefault();
    
            const $target = $(e.currentTarget);
            const url = $target.attr('href');
    
           
    
            if ($target.hasClass('readOnly')) {
                this.loadIndexContent(url,true);
              
            }else{
                this.loadIndexContent(url, false);
            }
        });
    }

    /**
     * Charge le contenu de la page index dans un modal.
     * @param {string} url - URL à charger dans le modal.
     */
    loadIndexContent(url,readOnly) {
        
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
                let showUIId = window.showUIId;

                this.tableUI.indexUI.showUI.init("#" + showUIId);
                if(readOnly){
                    this.tableUI.indexUI.formUI.setToReadOnly();
                }

            })
            .fail((xhr) => {
                           this.tableUI.indexUI.modalUI.close();
                           AjaxErrorHandler.handleError(xhr, 'Erreur lors du chargement des détails de l\'entité.');
            });
    }
}
