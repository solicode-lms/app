import $ from "jquery";
import iziModal from "izimodal/js/iziModal.min.js";
import "izimodal/css/iziModal.min.css";

// Vérifier si iziModal est bien attaché à jQuery
$.fn.iziModal = iziModal;

// Attacher iziModal à jQuery
// if (!$.fn.iziModal) {
//     $.fn.iziModal = iziModal;
// }

export class ModalUI {

    static modalCounter = 0;

    constructor(config, indexUI) {
        this.config = config;
        this.indexUI = indexUI;

        this.modalCounter = 0;
        this.curd_id = this.config.id;
        this.currentModalId = `${this.curd_id}-dynamic-modal`;
    }

    /**
     * Supprime l'ancienne modale et crée une nouvelle.
     * @param {string} title - Titre de la modale.
     * @param {boolean} showLoading - Afficher un spinner de chargement.
     */
    showModal(title = "titre test", showLoading = false) {
        // Supprimer l'ancienne modale si elle existe déjà
        $(`#${this.currentModalId}`).remove();

        // Ajouter la nouvelle modale
        $("body").append(`<div id="${this.currentModalId}" class="dynamic-modal"></div>`);


        $(`#${this.currentModalId}`).iziModal();  
        $(`#${this.currentModalId}`).iziModal();     
        $(`#${this.currentModalId}`).iziModal("open");



        // // Initialiser iziModal
        // $(`#${this.currentModalId}`).iziModal({
        //     title: title,
        //     headerColor: "#4CAF50",
        //     width: 900,
        //     transitionIn: "fadeIn",
        //     transitionOut: "fadeOut",
        //     closeButton: true,
        //     overlayClose: true,
        //     onOpened: function () {
        //         $(".iziModal").css("z-index", 1050);

        //          // ✅ Supprimer aria-hidden pour l'accessibilité
        //         $(`#${this.currentModalId}`).removeAttr("aria-hidden");

        //         // ✅ Donner le focus à un élément interactif
        //         setTimeout(() => {
        //             $(`#${this.currentModalId}`).find("a, button, input, select, textarea").first().focus();
        //         }, 100);


        //     }
        // });
        // $(`#${this.currentModalId}`).iziModal("open");

        // Si showLoading est vrai, afficher un spinner de chargement
        if (showLoading) {
            this.showContent("<div class='loading-spinner'>Chargement...</div>");
        }
    }

    /**
     * Affiche le contenu dans la modale existante sans recréer la modale.
     * @param {string} content - Contenu HTML à injecter dans la modale.
     */
    showContent(content) {
        $(`#${this.currentModalId}`).iziModal("setContent", content);
       
    }

    /**
     * Affiche le modal avec un titre et active l'indicateur de chargement.
     * @param {string} title - Titre à afficher dans le modal.
     */
    showLoading(title) {
        this.showModal(title, true);
   
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
        this.showContent(`<div class="alert alert-danger">${errorMessage}</div>`);
        console.error(`Erreur dans le modal : ${errorMessage}`);
    }
}
