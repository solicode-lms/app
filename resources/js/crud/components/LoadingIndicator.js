export class LoadingIndicator {
    /**
     * Constructeur de la classe LoadingIndicator.
     * @param {string} containerSelector - Sélecteur CSS pour l'élément contenant le chargement (ex: une carte ou une table).
     */
    constructor(containerSelector = '#card_crud') {
      
        this.loadingElementId = containerSelector +  '-gapp-loading';
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
            loadingDiv.style.position = 'fixed';
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
     * Affiche un indicateur de chargement non bloquant sous forme de rectangle en haut à droite du formulaire.
     * À utiliser pendant le calcul des champs de formulaire.
     */
showNomBloquante(message) {
    const container = document.querySelector(this.containerSelector);
    const msg = message || "Chargement";

    if (!container) {
        console.error('Conteneur de chargement introuvable.');
        return false;
    }

    // Chercher si un message existe déjà pour CE loadingElementId
    let existingDiv = document.getElementById(this.loadingElementId);

    if (existingDiv) {
        // 🔄 Mettre à jour le texte si même instance
        const messageSpan = existingDiv.querySelector('.loading-message');
        if (messageSpan) {
            messageSpan.textContent = `${msg} ...`;
        }
    } else {
        // 📌 Sinon créer un nouveau bloc
        const allMessages = document.querySelectorAll('.loading-non-bloquant');
        const messageIndex = allMessages.length;

        const spacing = 10; // marge entre messages
        const messageHeight = 40; // hauteur approximative d’un bloc
        const topPosition = 10 + (messageIndex * (messageHeight + spacing));

        const loadingDiv = document.createElement('div');
        loadingDiv.id = this.loadingElementId;
        loadingDiv.className = 'loading-non-bloquant d-flex align-items-center p-2 shadow-sm';

        loadingDiv.style.position = 'fixed';
        loadingDiv.style.top = `${topPosition}px`;
        loadingDiv.style.left = '50%';
        loadingDiv.style.transform = 'translateX(-50%)';
        loadingDiv.style.background = '#f8d7da';
        loadingDiv.style.color = '#721c24';
        loadingDiv.style.border = '1px solid #f5c6cb';
        loadingDiv.style.borderRadius = '5px';
        loadingDiv.style.zIndex = '999999';
        loadingDiv.style.fontSize = '14px';
        loadingDiv.style.fontWeight = 'bold';
        loadingDiv.style.minWidth = '120px';
        loadingDiv.style.textAlign = 'center';

        loadingDiv.innerHTML = `
            <span class="spinner-border spinner-border-sm text-danger me-2"></span>
            <span style="padding:2px" class="loading-message">${msg} ...</span>
        `;

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

         // 📌 Repositionner les messages restants
        const allMessages = document.querySelectorAll('.loading-non-bloquant');
        allMessages.forEach((msgDiv, index) => {
            const spacing = 10;
            const messageHeight = 40;
            const topPosition = 10 + (index * (messageHeight + spacing));
            msgDiv.style.top = `${topPosition}px`;
        });
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
