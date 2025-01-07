import { LoadingIndicator } from '../components/LoadingIndicator';
import { NotificationHandler } from '../components/NotificationHandler';
import { BaseAction } from './BaseAction';

export class LoadListAction extends BaseAction {
    /**
     * Constructeur de LoadListAction.
     * @param {Object} config - Configuration pour le chargement des entités.
     */
    constructor(config) {
        super(config);
        this.indexUrl = this.appendParamsToUrl(
            config.indexUrl,
            this.contextService.getContextParams()
        );
    }

    /**
     * Charge les entités depuis le serveur et met à jour la table ou la liste.
     * @param {number} page - Numéro de la page à charger (par défaut : 1).
     * @param {Object} filters - Objets contenant les filtres actifs.
     */
    loadEntities(page = 1, filters = {}) {


         // Filtrer les filtres pour exclure les champs avec des valeurs vides
        const cleanedFilters = Object.fromEntries(
            Object.entries(filters).filter(([key, value]) => value !== null && value !== undefined && value !== '')
        );
        
        // Récupérer les paramètres actuels depuis l'URL
        const urlParams = new URLSearchParams(window.location.search);
    
        // Intégrer les paramètres existants de l'URL et les nouveaux filtres
        const searchParams = { ...Object.fromEntries(urlParams.entries()), ...cleanedFilters, page };
    
        // Générer la chaîne de requête
        const queryString = new URLSearchParams(searchParams).toString();
    
        // Construire l'URL finale
        const requestUrl = this.appendParamsToUrl(this.indexUrl, queryString);
    
        // Afficher l'indicateur de chargement
        this.loader.show();
    
        // Requête AJAX pour charger les données
        $.get(requestUrl)
            .done((html) => {
                // Mettre à jour le conteneur avec les nouvelles données
                $(this.config.tableSelector).html(html);
    
                // Afficher un message de succès (optionnel)
                // NotificationHandler.showSuccess('Données chargées avec succès.');
            })
            .fail((xhr) => {
                // Gérer les erreurs de la requête
                const errorMessage = xhr.responseJSON?.message || 'Une erreur s\'est produite';
                NotificationHandler.showAlert("error", "Erreur lors du chargement des données.", errorMessage);
            })
            .always(() => {
                // Masquer l'indicateur de chargement
                this.loader.hide();
            });
    }
    
}
