import { CrudLoader } from '../components/CrudLoader';
import { MessageHandler } from '../components/MessageHandler';

export class EntityLoader {
    /**
     * Constructeur de EntityLoader.
     * @param {Object} config - Configuration pour le chargement des entités.
     */
    constructor(config) {
        this.config = config;

        // Initialisation du gestionnaire de chargement
        this.loader = new CrudLoader(config.tableSelector);
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

        const url = `${this.config.indexUrl}?page=${page}&q=${q}`;

        // Afficher l'indicateur de chargement
        this.loader.show();

        // Requête AJAX pour charger les entités
        $.get(url)
            .done((html) => {
                // Mettre à jour le contenu de la table ou liste
                $(this.config.tableSelector).html(html);

                // Afficher un message de succès
                MessageHandler.showSuccess('Données chargées avec succès.');
            })
            .fail(() => {
                // Gérer les erreurs
                MessageHandler.showError('Erreur lors du chargement des données.');
            })
            .always(() => {
                // Masquer l'indicateur de chargement
                this.loader.hide();
            });
    }
}
