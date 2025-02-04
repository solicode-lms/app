export class LoadingIndicator {
    /**
     * Constructeur de la classe LoadingIndicator.
     * @param {string} containerSelector - Sélecteur CSS pour l'élément contenant le chargement (ex: une carte ou une table).
     */
    constructor(containerSelector = '#card_crud') {
      
        this.loadingElementId = 'gapp-loading';
        this.containerSelector = containerSelector;
    }
   
    init (){

    }
    

    /**
     * Affiche l'indicateur de chargement.
     * @returns {boolean} - Retourne `true` si le conteneur existe et le chargement est affiché.
     */
    show() {


     
        const container  = document.querySelector(this.containerSelector);

        if (!container) {
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
            container.appendChild(loadingDiv);
        }

        return true;
    }

    /**
     * Masque l'indicateur de chargement.
     */
    hide() {

        const container  = document.querySelector(this.containerSelector);

        if (!container) {
            console.error('Conteneur de chargement introuvable.');
            return;
        }

        const loadingDiv = document.getElementById(this.loadingElementId);
        if (loadingDiv) {
            container.removeChild(loadingDiv);
        } else {
            // console.warn('Indicateur de chargement introuvable  : ' + this.loadingElementId);
        }
    }

    /**
     * Vérifie si l'indicateur de chargement est visible.
     * @returns {boolean} - `true` si le chargement est affiché, `false` sinon.
     */
    isLoadingVisible() {
        const v =  document.getElementById(this.loadingElementId) !== null;
        return v;
    }
}
