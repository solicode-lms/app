import $ from 'admin-lte/plugins/jquery/jquery.min.js';
import { showLoading, hideLoading } from './GappLoading';
import { MessageHandler } from './MessageHandler';

export class SearchAndPaginationManager {
    /**
     * @param {Object} config - Configuration de recherche et pagination.
     * @param {string} config.searchInputSelector - Sélecteur CSS pour le champ de recherche.
     * @param {string} config.paginationSelector - Sélecteur CSS pour les liens de pagination.
     * @param {string} config.dataContainerSelector - Sélecteur CSS pour le conteneur de données à mettre à jour.
     * @param {string} config.baseUrl - URL de base pour récupérer les données (pagination et recherche).
     */
    constructor(config) {
        this.config = config;
        this.searchInputSelector = config.searchInputSelector;
        this.paginationSelector = config.paginationSelector;
        this.dataContainerSelector = config.dataContainerSelector;
        this.baseUrl = config.baseUrl; // URL de base pour les requêtes AJAX
        this.debounceTimeout = null;

        this.init();
    }

    /**
     * Initialise les gestionnaires d'événements pour la recherche et la pagination.
     */
    init() {
        $(document).ready(() => {
            this.handleSearch();
            this.handlePagination();
        });
    }

    /**
     * Gère les événements de recherche avec délai (debounce).
     */
    handleSearch() {
        $("body").on("keyup", this.searchInputSelector, () => {
            const searchValue = $(this.searchInputSelector).val();

            clearTimeout(this.debounceTimeout); // Annule le délai précédent
            this.debounceTimeout = setTimeout(() => {
                this.fetchData(1, searchValue); // Charge les données pour la recherche
                if (searchValue === "") {
                    MessageHandler.showInfo("Recherche réinitialisée, affichage des données par défaut.");
                } else {
                    MessageHandler.showInfo(`Recherche : "${searchValue}"`);
                }
            }, 500); // Délais de 500ms avant la requête
        });
    }

    /**
     * Gère les événements de pagination.
     */
    handlePagination() {
        $("body").on("click", `${this.paginationSelector} .page-link`, (event) => {
            event.preventDefault();

            const page = $(event.target).data("page") || $(event.target).attr("data-page") || $(event.target).text().trim();
            const searchValue = $(this.searchInputSelector).val();

            this.fetchData(page, searchValue); // Charge les données pour la page sélectionnée
            MessageHandler.showInfo(`Chargement de la page ${page}.`);
        });
    }

    /**
     * Récupère les données via une requête AJAX.
     * @param {number} page - Numéro de la page.
     * @param {string} searchValue - Valeur de recherche.
     */
    fetchData(page = 1, searchValue = "") {
        const url = `${this.config.indexUrl}?page=${page}&searchValue=${searchValue}`;

        if (showLoading()) {
            setTimeout(() => this.makeRequest(url), 300); // Ajoute un délai si nécessaire
        } else {
            this.makeRequest(url);
        }
    }

    /**
     * Effectue une requête AJAX pour récupérer des données.
     * @param {string} url - URL à appeler.
     */
    makeRequest(url) {
        $.ajax({
            url,
            method: "GET",
            success: (response) => {
                $(this.dataContainerSelector).html(response); // Met à jour le conteneur
                hideLoading();
                MessageHandler.showSuccess("Données chargées avec succès.");
            },
            error: (xhr) => {
                hideLoading();
                const errorMessage = xhr.responseJSON?.message || "Une erreur s'est produite lors du chargement des données.";
                MessageHandler.showError(errorMessage);
                console.error("Erreur AJAX :", errorMessage);
            },
        });
    }
}
