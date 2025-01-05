import { NotificationHandler } from '../components/NotificationHandler';

export class SearchPaginationEventHandler {
    /**
     * Constructeur de SearchPaginationEventHandler.
     * @param {Object} config - Configuration contenant les sélecteurs et URLs.
     * @param {Object} entityLoader - Instance de LoadListAction pour recharger les entités.
     */
    constructor(config, entityLoader) {
        this.config = config;
        this.entityLoader = entityLoader;

        // Temps de délai pour limiter les requêtes fréquentes
        this.debounceTimeout = null;
        this.debounceDelay = 500; // Par défaut : 500ms
    }

    /**
     * Initialise les gestionnaires pour la recherche, les filtres et la pagination.
     */
    init() {
        this.handleFormInput(); // Gérer les entrées dans le formulaire (recherche + filtres)
        this.handlePaginationClick(); // Gérer les clics de pagination
    }

    /**
     * Gère les événements du formulaire (recherche et filtres) avec délai (debounce).
     */
    handleFormInput() {
        const formSelector = this.config.filterFormSelector;

        // Sur saisie dans les champs de recherche ou filtres
        $(document).on('input', `${formSelector} input, ${formSelector} select`, () => {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
                this.submitForm(); // Soumettre le formulaire après un délai
            }, this.debounceDelay);
        });

        // Soumission explicite du formulaire (par bouton ou utilisateur)
        $(document).on('submit', formSelector, (e) => {
            e.preventDefault();
            this.submitForm();
        });
    }

    /**
     * Gère les clics sur les liens de pagination.
     */
    handlePaginationClick() {
        $(document).on('click', this.config.paginationSelector, (e) => {
            e.preventDefault();
            const page = $(e.currentTarget).data('page') || $(e.target).text().trim();
    
            if (page) {
                const filters_with_empty = this.getFormData(true);
                const filters = this.getFormData(); // Récupérer les valeurs des filtres actifs
                filters.page = page; // Ajouter le numéro de page aux filtres
                filters_with_empty.page = page;
                this.updateURLParameters(filters_with_empty); // Mettre à jour l'URL avec tous les paramètres
                this.entityLoader.loadEntities(page, filters.q, filters); // Charger les entités avec les filtres
            }
        });
    }
    /**
     * Récupère les valeurs de tous les champs du formulaire (recherche + filtres).
     * @returns {Object} - Un objet contenant les données du formulaire.
     */
    getFormData(withEmpty = false) {
        const form = $(this.config.filterFormSelector);
        const formData = {};
        form.serializeArray().forEach((field) => {
            if (!withEmpty || field.value) { // Ne pas inclure les champs vides
                formData[field.name] = field.value;
            }
        });
        return formData;
    }

    /**
     * Soumet le formulaire en récupérant les données et recharge les entités.
     * @param {number} page - Numéro de la page à charger (par défaut : 1).
     */
    submitForm(page = 1) {
        const formData = this.getFormData(); // Récupérer les données du formulaire
        formData.page = page; // Ajouter le numéro de page aux données

        this.updateURLParameters(formData); // Mettre à jour les paramètres dans l'URL
        this.entityLoader.loadEntities(page, formData.q, formData); // Charger les entités avec recherche et filtres
    }

    /**
     * Met à jour les paramètres dans l'URL sans recharger la page.
     * @param {Object} params - Données à inclure dans l'URL.
     */
    updateURLParameters(params) {
        const url = new URL(window.location.href);

        // Met à jour chaque paramètre
        Object.keys(params).forEach((key) => {
            if (params[key]) {
                url.searchParams.set(key, params[key]);
            } else {
                url.searchParams.delete(key); // Supprime si la valeur est vide
            }
        });

        // Met à jour l'URL dans la barre d'adresse sans recharger la page
        window.history.replaceState({}, '', url);
    }
}
