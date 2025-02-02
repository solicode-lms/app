export class LoadingIndicator {
    /**
     * Constructeur de la classe LoadingIndicator.
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
    
            // Appliquer le style pour s'assurer que l'élément reste sous le modal
            loadingDiv.style.position = 'absolute';
            loadingDiv.style.top = '0';
            loadingDiv.style.left = '0';
            loadingDiv.style.width = '100%';
            loadingDiv.style.height = '100%';
            loadingDiv.style.background = 'rgba(255, 255, 255, 0.5)'; // Optionnel pour effet de transparence
            loadingDiv.style.zIndex = '1060'; // Inférieur à celui du modal (1050)
    
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
