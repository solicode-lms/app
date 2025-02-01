// import $ from "jquery";
// import iziModal from "izimodal/js/iziModal.min.js";
// import "izimodal/css/iziModal.min.css";
// $.fn.iziModal = iziModal;


export class ModalUI {

    static modalCounter = 0;

    constructor(config, indexUI) {
        this.config = config;
        this.indexUI = indexUI;

        this.modalCounter = 0;
        this.curd_id = this.config.id;
        
        this.currentModalId = `${this.curd_id}-dynamic-modal`;
        this.modal = null;
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

        
        // subtitle: '',
        // headerColor: '#88A0B9',
        // background: null,
        // theme: '',  // light
        // icon: null,
        // iconText: null,
        // iconColor: '',
        // rtl: false,
        // width: 600,
        // top: null,
        // bottom: null,
        // borderBottom: true,
        // padding: 0,
        // radius: 3,
        // zindex: 999,
        // focusInput: true,
        // group: '',
        // loop: false,
        // arrowKeys: true,
        // navigateCaption: true,
        // navigateArrows: true, // Boolean, 'closeToModal', 'closeScreenEdge'
        // history: false,
        // restoreDefaultContent: false,
        // autoOpen: 0, // Boolean, Number
        // bodyOverflow: false,
        // fullscreen: false,
        // openFullscreen: false,
        // closeOnEscape: true,
        // closeButton: true,
        // appendTo: 'body', // or false
        // appendToOverlay: 'body', // or false
        // overlay: true,
        // overlayClose: true,
        // overlayColor: 'rgba(0, 0, 0, 0.4)',
        // timeout: false,
        // timeoutProgressbar: false,
        // pauseOnHover: false,
        // timeoutProgressbarColor: 'rgba(255,255,255,0.5)',
        // transitionIn: 'comingIn',
        // transitionOut: 'comingOut',
        // transitionInOverlay: 'fadeIn',
        // transitionOutOverlay: 'fadeOut',




        this.modal =  $(`#${this.currentModalId}`).iziModal({
            title: title,
            theme: '',  // light
            headerColor: "#2973B2",
            width: 900,
            padding: 10,
            zindex: 1050,
            appendTo: 'body',
            closeButton  : true,
            fullscreen: true,
            borderBottom: true,
            transitionIn: "fadeIn",
            transitionOut: "fadeOut",
            closeButton: true,
            overlayClose: true,
            autoOpen: true, // Boolean, Number
        });

        // this.modal.iziModal('startProgress');
        // this.modal.iziModal('setTop', 100);

         // onFullscreen: function(){},
        // onResize: function(){},
        // onOpening: function(){},
        // onOpened: function(){},
        // onClosing: function(){},
        // onClosed: function(){},
        // afterRender: function(){}
    }

    /**
     * Affiche le contenu dans la modale existante sans recréer la modale.
     * @param {string} content - Contenu HTML à injecter dans la modale.
     */
    showContent(content) {
       $(`#${this.currentModalId}`).iziModal("stopLoading");
        $(`#${this.currentModalId}`).iziModal("setContent", content);
     
    }

    /**
     * Affiche le modal avec un titre et active l'indicateur de chargement.
     * @param {string} title - Titre à afficher dans le modal.
     */
    showLoading(title) {
        this.showModal(title, true);
        $(`#${this.currentModalId}`).iziModal("startLoading");
     
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
