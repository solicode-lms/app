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
            this.contextManager.getContextParams()
        );
    }

    /**
     * Charge les entités depuis le serveur et met à jour la table ou la liste.
     * @param {number} page - Numéro de la page à charger (par défaut : 1).
     * @param {string} q - Valeur de recherche pour filtrer les entités.
     * @param {Object} filters - Objets contenant les filtres actifs.
     */
    loadEntities(page = 1, q = '', filters = {}) {
        // Récupérer les paramètres actuels depuis l'URL
        const urlParams = new URLSearchParams(window.location.search);

        // Extraire les valeurs actuelles de l'URL si les arguments sont vides
        page = page || urlParams.get('page') || 1;
        q = q || urlParams.get('q') || '';

        // Préparer les paramètres de recherche
        const searchParams = { page, q, ...filters };
        const queryString = new URLSearchParams(searchParams).toString();

        // Construire l'URL avec les paramètres
        const requestUrl = this.appendParamsToUrl(this.indexUrl, queryString);

        // Afficher l'indicateur de chargement
        this.loader.show();

        // Requête AJAX pour charger les entités
        $.get(requestUrl)
            .done((html) => {
                // Mettre à jour le contenu de la table ou liste
                $(this.config.tableSelector).html(html);

                // Afficher un message de succès (facultatif)
                NotificationHandler.showSuccess('Données chargées avec succès.');
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
