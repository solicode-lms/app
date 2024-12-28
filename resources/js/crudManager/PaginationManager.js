export class PaginationManager {
    /**
     * Constructeur de la classe PaginationManager.
     * @param {string} containerSelector - Sélecteur CSS pour le conteneur où se trouve la pagination.
     * @param {Function} fetchData - Fonction pour récupérer les données d'une page spécifique.
     */
    constructor(containerSelector, fetchData) {
        this.containerSelector = containerSelector;
        this.fetchData = fetchData; // Callback pour récupérer les données
        this.init();
    }

    /**
     * Initialise les gestionnaires d'événements pour la pagination.
     */
    init() {
        $(document).on('click', `${this.containerSelector} .pagination .page-link`, (e) => {
            e.preventDefault();
            const page = this.getPageFromEvent(e);
            this.loadPage(page);
        });
    }

    /**
     * Récupère le numéro de page à partir de l'événement.
     * @param {Event} event - Événement de clic sur un lien de pagination.
     * @returns {number} - Numéro de la page.
     */
    getPageFromEvent(event) {
        const target = $(event.target);
        return parseInt(target.data('page') || target.attr('data-page') || target.text().trim(), 10);
    }

    /**
     * Charge une page spécifique.
     * @param {number} page - Numéro de la page à charger.
     */
    loadPage(page) {
        if (isNaN(page) || page <= 0) {
            console.error('Numéro de page invalide:', page);
            return;
        }
        this.fetchData(page); // Appelle la fonction pour récupérer les données
    }

    /**
     * Met à jour les liens de pagination.
     * @param {number} currentPage - Numéro de la page actuelle.
     * @param {number} totalPages - Nombre total de pages.
     */
    updatePaginationLinks(currentPage, totalPages) {
        const paginationContainer = $(`${this.containerSelector} .pagination`);
        paginationContainer.html(''); // Réinitialiser les liens existants

        for (let i = 1; i <= totalPages; i++) {
            const activeClass = i === currentPage ? 'active' : '';
            paginationContainer.append(
                `<li class="page-item ${activeClass}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`
            );
        }
    }
}
