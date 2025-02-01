import $ from "jquery";
import iziModal from "izimodal/js/iziModal.min.js";
import "izimodal/css/iziModal.min.css";

// Vérifier si iziModal est bien attaché à jQuery
$.fn.iziModal = iziModal;

// Attacher iziModal à jQuery
if (!$.fn.iziModal) {
    $.fn.iziModal = iziModal;
}

export class ModalManager {
    constructor() {
        this.modalCounter = 0;
        this.currentModalId = null;
    }

    /**
     * Affiche une nouvelle modale avec du contenu dynamique.
     * @param {string} title - Titre de la modale.
     * @param {string} content - Contenu HTML à injecter dans la modale.
     */
    showContent(content , title = "titre test" ) {
        this.currentModalId = `dynamic-modal-${++this.modalCounter}`;

        // Supprimer l'ancienne modale si elle existe déjà
        $(`#${this.currentModalId}`).remove();

        // Ajouter la nouvelle modale
        $("body").append(`<div id="${this.currentModalId}" class="dynamic-modal"></div>`);

        // Initialiser iziModal
        $(`#${this.currentModalId}`).iziModal({
            title: title || "Modale Dynamique",
            headerColor: "#4CAF50",
            width: 900,
            transitionIn: "fadeIn",
            transitionOut: "fadeOut",
            closeButton: true,
            overlayClose: true ,
            onOpened: function () {
                $(".iziModal").css("z-index", 1050);
            }
        });

        // Ajouter le contenu dynamique
        $(`#${this.currentModalId}`).iziModal("setContent", content);
        $(`#${this.currentModalId}`).iziModal("open");
    }

    /**
     * Affiche le modal avec un titre et active l'indicateur de chargement.
     * @param {string} title - Titre à afficher dans le modal.
     */
    showLoading(title) {
        this.showContent(title, "<div class='loading-spinner'>Chargement...</div>");
    }

    /**
     * Ferme la modale actuellement ouverte.
     */
    close() {
        if (this.currentModalId) {
            $(`#${this.currentModalId}`).iziModal("close");
            this.currentModalId = null;
        }
    }

    /**
     * Ferme toutes les modales ouvertes.
     */
    closeAll() {
        $(".dynamic-modal").each(function () {
            $(this).iziModal("close");
        });
    }

    /**
     * Gère une erreur en affichant un message d'erreur dans une modale.
     * @param {string} errorMessage - Message d'erreur à afficher.
     */
    showError(errorMessage) {
        this.showContent("Erreur", `<div class="alert alert-danger">${errorMessage}</div>`);
        console.error(`Erreur dans le modal : ${errorMessage}`);
    }
}
