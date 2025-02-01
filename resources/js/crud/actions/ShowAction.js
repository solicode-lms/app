import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import { Action } from './Action';

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
            this.contextService.getContextParams()
        );



        // Afficher le chargement dans le modal
        this.tableUI.indexUI.modalUI.showLoading('Détails de l\'entité');

        // Charger les détails de l'entité via AJAX
        $.get(showUrl)
            .done((html) => {
                // Injecter le contenu des détails dans le modal


                this.tableUI.indexUI.modalUI.showContent(html);

                this.executeScripts(html);
                this.tableUI.indexUI.formUI.init();
                this.tableUI.indexUI.formUI.setToReadOnly();


                // $(document).on('opened', this.tableUI.indexUI.modalUI.this.currentModalId, function (e) {
                  
                // });

                // Exécuter les scripts inclus dans le contenu AJAX
             
                // this.handleSuccess('Détails de l\'entité chargés avec succès.');
            })
            .fail((xhr) => {
                this.tableUI.indexUI.formUI.modalManager.close();
                AjaxErrorHandler.handleError(xhr, 'Erreur lors du chargement des détails de l\'entité.');
            });
            
    }


    /**
     * Gère les événements liés à l'affichage des détails d'une entité.
     */
    handleShowEntity() {
        $(document).on('click', `${this.config.crudSelector} .showEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité
            this.showEntity(id);
        });
    }


        /**
     * Exécute les scripts inclus dans le contenu HTML chargé via AJAX.
     * @param {string} html - Contenu HTML contenant potentiellement des scripts.
     */
        executeScripts(html) {
            const scriptTags = $("<div>").html(html).find("script");
    
            scriptTags.each(function () {
                const scriptText = $(this).text();
                const scriptSrc = $(this).attr("src");
    
                if (scriptSrc) {
                    // Charger et exécuter les scripts externes
                    $.getScript(scriptSrc);
                } else if (scriptText) {
                    // Exécuter les scripts inline
                    new Function(scriptText)();
                }
            });
        }
}
