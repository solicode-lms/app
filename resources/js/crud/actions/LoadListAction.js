import { LoadingIndicator } from '../components/LoadingIndicator';
import { NotificationHandler } from '../components/NotificationHandler';
import { Action } from './Action';
import { BaseAction } from './BaseAction';

// LoadListAction ne peut pas hérite de Action
// pour éviter une dépendance circulaire des imports
export class LoadListAction extends BaseAction {
    /**
     * Constructeur de LoadListAction.
     * @param {Object} config - Configuration pour le chargement des entités.
     */
    constructor(config) {
        super(config);
        this.indexUrl = this.appendParamsToUrl(
            config.indexUrl,
            this.contextManager.getContextParams()
        );
    }

    /**
     * Charge les entités depuis le serveur et met à jour la table ou la liste.
     * @param {number} page - Numéro de la page à charger (par défaut : 1).
     * @param {string} q - Valeur de recherche pour filtrer les entités.
     */
    loadEntities(page , q ) {


        // Extraire les paramètres de l'URL si les arguments sont nuls
        const urlParams = new URLSearchParams(window.location.search);
        const _page = urlParams.get('page'); // "2"
        const _query = urlParams.get('q');   // "test"

        page = page || _page || 1;
        q = q || _query || '';

      
        const search_params = `page=${page}&q=${q}`;

        this.indexUrl = this.appendParamsToUrl(
            this.indexUrl, search_params
        );

        // Afficher l'indicateur de chargement
        this.loader.show();

        // Requête AJAX pour charger les entités
        $.get(this.indexUrl)
            .done((html) => {
                // Mettre à jour le contenu de la table ou liste
                $(this.config.tableSelector).html(html);

                // Afficher un message de succès
                // NotificationHandler.showSuccess('Données chargées avec succès.');
            })
            .fail(() => {
                // Gérer les erreurs
                NotificationHandler.showError('Erreur lors du chargement des données.');
            })
            .always(() => {
                // Masquer l'indicateur de chargement
                this.loader.hide();
            });
    }
}
