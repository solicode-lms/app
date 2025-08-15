import { LoadingIndicator } from "../components/LoadingIndicator";
import { NotificationHandler } from "../components/NotificationHandler";
import EventUtil from "../utils/EventUtil";

export class NotificationUI {
    static autoRefreshStarted = false;

    constructor(config, indexUI) {
        this.config = config;
        this.indexUI = indexUI;
        this.loader = new LoadingIndicator("#notificationUI");
        this.isTabActive = true; // ✅ On considère actif par défaut
        this.initVisibilityDetection(); // ➡️ Ajouter la détection
    }

    /**
     * Initialise les événements pour les notifications.
     */
    init() {
        this.loadNotifications();
        if (!NotificationUI.autoRefreshStarted) {
            this.startAutoRefresh();
            NotificationUI.autoRefreshStarted = true;
        }
    }

    /**
     * Lance un auto-refresh toutes les 5 secondes.
     */
    startAutoRefresh() {
        setInterval(() => {
            if (this.isTabActive) { // ✅ Ne charger que si l'onglet est actif
                this.loadNotifications();
                this.indexUI.tableUI.entityLoader.loadEntities(undefined, {},true);
            }
        }, 100000); // 10 secondes
    }
    
    /**
     * Charge les notifications depuis le serveur.
     */
    loadNotifications() {
        if (!this.config.getUserNotificationsUrl) {
            return;
        }

        const dropdown = document.querySelector('#notificationDropdown');
        const wasOpen = dropdown && dropdown.getAttribute('aria-expanded') === 'true';

        // this.loader.show();

        $.ajax({
            url: this.config.getUserNotificationsUrl,
            method: "GET",
        })
        .done((html) => {
            this.updateNotificationUI(html);
            if (wasOpen) {
                const newDropdown = document.querySelector('#notificationDropdown');
                $(newDropdown).dropdown('show');
            }
        })
        .fail((xhr) => {
           
            const errorMessage = xhr.responseText || "Erreur lors du chargement des notifications.";
            console.log("NotificationUI : " + errorMessage);
            console.log(xhr);
            // il générer parfois une erreur 500
            // NotificationHandler.showAlert("error", "Erreur Notifications", errorMessage);
        })
        .always(() => {
            // this.loader.hide();
        });
    }

    /**
     * Met à jour l'UI avec le HTML reçu.
     * @param {string} html - HTML du composant notifications.
     */
    updateNotificationUI(html) {
        const container = document.querySelector("#notificationUI");
        if (!container) return;

        container.innerHTML = html;
    }

 

    /**
     * Initialise la détection de visibilité du navigateur.
     */
    initVisibilityDetection() {
        EventUtil.bindEvent('visibilitychange', document, () => {
            this.isTabActive = !document.hidden;
        });
    
        // window.addEventListener('focus', () => {
        //     this.isTabActive = true;
        // });
    
        // window.addEventListener('blur', () => {
        //     this.isTabActive = false;
        // });
    }
}
