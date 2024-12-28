export class CrudLoader {
    /**
     * Constructeur de la classe CrudLoader.
     * @param {string} containerSelector - Sélecteur CSS pour l'élément contenant le chargement (ex: une carte ou une table).
     */
    constructor(containerSelector = '#card_crud') {
      
        this.loadingElementId = 'loading';
        this.containerSelector = containerSelector;
        this.init();
    }

    init(){
        this.container = document.querySelector(this.containerSelector);
    }

    /**
     * Affiche l'indicateur de chargement.
     * @returns {boolean} - Retourne `true` si le conteneur existe et le chargement est affiché.
     */
    show() {
        if (!this.container) {
            console.error('Conteneur de chargement introuvable.');
            return false;
        }

        // Vérifier si le chargement est déjà affiché
        if (!this.isLoadingVisible()) {
            const loadingDiv = document.createElement('div');
            loadingDiv.id = this.loadingElementId;
            loadingDiv.className = 'd-flex justify-content-center align-items-center';
            loadingDiv.innerHTML = '<div class="spinner-border text-primary" role="status"></div>';
            this.container.appendChild(loadingDiv);
        }
        return true;
    }

    /**
     * Masque l'indicateur de chargement.
     */
    hide() {
        if (!this.container) {
            console.error('Conteneur de chargement introuvable.');
            return;
        }

        const loadingDiv = document.getElementById(this.loadingElementId);
        if (loadingDiv) {
            this.container.removeChild(loadingDiv);
        } else {
            // console.warn('Indicateur de chargement introuvable.');
        }
    }

    /**
     * Vérifie si l'indicateur de chargement est visible.
     * @returns {boolean} - `true` si le chargement est affiché, `false` sinon.
     */
    isLoadingVisible() {
        return document.getElementById(this.loadingElementId) !== null;
    }
}
