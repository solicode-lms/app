import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import { Action } from './Action';
import EventUtil from './../utils/EventUtil';
import { ShowUI } from './../components/ShowUI';

export class ShowAction extends Action {


    constructor(config, tableUI) {
        super(config); 
        this.config = config;
        this.tableUI = tableUI; 
       
    }


    init(){
        this.handleShowEntity()
    }
    /**
     * Affiche les détails d'une entité dans un modal.
     * @param {number|string} id - Identifiant de l'entité à afficher.
     */
    showEntity(id) {


        let showUrl = this.getUrlWithId(this.config.showUrl, id); // Générer l'URL dynamique

        showUrl = this.appendParamsToUrl(
            showUrl,
            this.viewStateService.getContextParams()
        );



        // Afficher le chargement dans le modal
        this.tableUI.indexUI.modalUI.showLoading('Chargement...');

        // Charger les détails de l'entité via AJAX
        $.get(showUrl)
            .done((html) => {
                // Injecter le contenu des détails dans le modal


                this.tableUI.indexUI.modalUI.showContent(html);

                this.executeScripts(html);
                this.tableUI.indexUI.modalUI.setTitle(window.modalTitle);
                this.tableUI.indexUI.showUI.init();
     
            })
            .fail((xhr) => {
                this.tableUI.indexUI.modalUI.close();
                AjaxErrorHandler.handleError(xhr, 'Erreur lors du chargement des détails de l\'entité.');
            });
            
    }


    /**
     * Gère les événements liés à l'affichage des détails d'une entité.
     */
    handleShowEntity() {
        EventUtil.bindEvent('click', `${this.config.crudSelector} .showEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité
            this.showEntity(id);
        });
    }



}
