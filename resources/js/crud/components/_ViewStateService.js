export class ViewStateService {
    /**
     * Constructeur pour initialiser ViewStateService.
     */
    constructor() {
        this.init();
    }

    /**
     * Initialise le ViewState depuis la fenêtre globale.
     */
    init() {
        this.viewState = window.viewState || { views: {}, current_view: null };
    }

    /**
     * Définit la vue actuelle.
     * @param {string} viewKey - La clé de la vue actuelle.
     */
    setViewKey(viewKey) {
        this.viewState.current_view = viewKey;
    }

    /**
     * Récupère la clé de la vue actuelle.
     * @returns {string|null} - La clé de la vue actuelle.
     */
    getViewKey() {
        return this.viewState.current_view;
    }

    /**
     * Récupérer les variables spécifiques à la vue actuelle.
     * @returns {Object} - Variables associées à la vue actuelle.
     */
    getVariables() {
        const viewKey = this.getViewKey();
        return this.viewState.views[viewKey] || {};
    }

    /**
     * Ajoute une variable à la vue actuelle.
     * @param {string} key - La clé de la variable.
     * @param {*} value - La valeur de la variable.
     */
    addVariable(key, value) {
        const viewKey = this.getViewKey();
        if (!this.viewState.views[viewKey]) {
            this.viewState.views[viewKey] = {};
        }
        this.viewState.views[viewKey][key] = value;
    }

    /**
     * Ajoute plusieurs variables à la vue actuelle.
     * @param {Object} data - Objet contenant les paires clé/valeur.
     */
    addData(data) {
        const viewKey = this.getViewKey();
        if (!this.viewState.views[viewKey]) {
            this.viewState.views[viewKey] = {};
        }
        Object.assign(this.viewState.views[viewKey], data);
    }

    /**
     * Met à jour le ViewState de la vue actuelle.
     * @param {Object} newState - Nouvelles variables à ajouter ou remplacer.
     */
    updateViewState(newState) {
        const viewKey = this.getViewKey();
        this.viewState.views[viewKey] = { ...this.viewState.views[viewKey], ...newState };
    }

    /**
     * Supprime une variable spécifique de la vue actuelle.
     * @param {string} key - La clé à supprimer.
     */
    removeVariable(key) {
        const viewKey = this.getViewKey();
        if (this.viewState.views[viewKey]) {
            delete this.viewState.views[viewKey][key];
        }
    }

    /**
     * Réinitialise les variables de la vue actuelle.
     */
    clearViewState() {
        const viewKey = this.getViewKey();
        this.viewState.views[viewKey] = {};
    }

    /**
     * Sérialisation du ViewState en JSON.
     * @returns {string} - JSON stringifié des variables de la vue actuelle.
     */
    toString() {
        return JSON.stringify(this.getVariables());
    }
}
