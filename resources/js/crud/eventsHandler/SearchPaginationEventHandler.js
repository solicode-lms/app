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
        this.initializeFilterResetHandler();
        this.handleSorting();
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
                const filters = this.getFormData(true); // Inclure tous les champs, même vides
                filters.page = page; // Ajouter le numéro de page
    
                // Mettre à jour l'URL avec tous les paramètres
                this.updateURLParameters(filters);
    
                // Charger les entités avec les filtres et la page
                this.entityLoader.loadEntities(page, filters);
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
    
        // Parcourir les champs du formulaire
        form.serializeArray().forEach((field) => {
            const value = field.value.trim(); // Supprimer les espaces inutiles
            if (withEmpty || value) { // Inclure les champs vides seulement si demandé
                formData[field.name] = value;
            }
        });
    
        return formData;
    }


    /**
     * Soumet le formulaire en récupérant les données et recharge les entités.
     * @param {number} page - Numéro de la page à charger (par défaut : 1).
     */
    submitForm(page = 1) {
        const formData = this.getFormData(true); // Récupérer les données du formulaire
        formData.page = page; // Ajouter le numéro de page aux données
    

        // Mettre à jour l'URL avec les paramètres non vides
        this.updateURLParameters(formData);
    
        // Charger les entités avec les paramètres
        this.entityLoader.loadEntities(page, formData);
    }

    /**
     * Met à jour les paramètres dans l'URL sans recharger la page.
     * @param {Object} params - Données à inclure dans l'URL.
     */
    updateURLParameters(params) {
        const url = new URL(window.location.href);
    
        // Supprimer uniquement les anciens paramètres liés aux filtres
        Object.entries(params).forEach(([key, value]) => {
            if (value === undefined || value === null || value === '') {
                // Supprimer les filtres qui sont vides ou null
                url.searchParams.delete(key);
            } else {
                // Mettre à jour ou ajouter les autres paramètres
                url.searchParams.set(key, value);
            }
        });
    
        // Mettre à jour l'URL sans recharger la page
        window.history.replaceState({}, '', url);
    }

    initializeFilterResetHandler() {
        const filterIcon = document.querySelector(this.config.filterIconSelector);
        const filterForm = document.querySelector(this.config.filterFormSelector);
    
        // Fonction pour vérifier l'état des filtres
        const updateFilterState = () => {
            const filters = new FormData(filterForm);
            let hasActiveFilters = false;
    
            for (let [key, value] of filters.entries()) {
                if (value.trim() !== '' && key !== 'page') {
                    hasActiveFilters = true;
                    break;
                }
            }
    
            if (hasActiveFilters) {
                filterIcon.classList.remove('fa-filter');
                filterIcon.classList.add('fa-times-circle');
            } else {
                filterIcon.classList.remove('fa-times-circle');
                filterIcon.classList.add('fa-filter');
            }
        };
    
        // Réinitialiser les filtres au clic sur l'icône
        filterIcon.addEventListener('click', () => {
            filterForm.querySelectorAll('input, select').forEach((field) => {
                field.value = ''; // Réinitialiser les champs
            });
            updateFilterState();
            this.submitForm(); // Soumettre le formulaire après réinitialisation
        });
    
        // Vérifier l'état des filtres au chargement et sur modification
        updateFilterState();
        filterForm.addEventListener('input', updateFilterState);
    }


    handleSorting() {
        $(document).on('click', this.config.sortableColumnSelector, (e) => {
            e.preventDefault();

            const column = $(e.currentTarget).data('sort');
            const currentSort = new URLSearchParams(window.location.search).get('sort') || '';
            const sortArray = currentSort.split(',').filter(Boolean);
            const newSortArray = this.updateSortArray(sortArray, column);

            const filters = this.getFormData(); // Inclure les données de recherche et filtres
            filters.sort = newSortArray.join(',');

            this.updateURLParameters(filters); // Mettre à jour l'URL
            this.entityLoader.loadEntities(1, filters); // Recharger la table
        });
    }

    updateSortArray(sortArray, column) {

      
        // Trouver le tri existant pour la colonne
        const existingSort = sortArray.find((s) => s.startsWith(column + '_'));
    
        if (existingSort) {

            // Récupérer la direction actuelle (asc ou desc)
            const direction = existingSort.split('_').pop(); 

            if (direction === 'asc') {
                // Si actuellement trié en ascendant, passe à descendant
                return sortArray.map((s) =>
                    s.startsWith(column + '_') ? `${column}_desc` : s
                );
            } else if (direction === 'desc') {
                // Si actuellement trié en descendant, supprime le tri
                return sortArray.filter((s) => !s.startsWith(column + '_'));
            }
        }
    
        // Si aucun tri existant, ajouter tri ascendant
        return [...sortArray, `${column}_asc`];
    }
    

}
