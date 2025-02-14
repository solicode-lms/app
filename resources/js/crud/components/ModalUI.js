import EventUtil from '../utils/EventUtil';
import { LoadListAction } from './../actions/LoadListAction';

export class ModalUI {

    static modalCounter = 0;
    static wasFullscreen = false;
    constructor(config, indexUI) {
        this.config = config;
        this.indexUI = indexUI;
        
        this.modalCounter = 0;
        this.curd_id = this.config.id;
        
        this.currentModalId = `${this.curd_id}-dynamic-modal`;
        this.modal = null;
        this.parentModal = null;
        this.isParentFullscreen = false; // Pour stocker l’état précédent du modal parent

        //  // 🔹 Ajouter l'écouteur global pour activer le mode plein écran au premier clic
        //  document.addEventListener('click', () => {
        //     if (!document.fullscreenElement) {
        //         document.documentElement.requestFullscreen().then(() => {
        //             ModalUI.wasFullscreen = true;
        //         }).catch(err => {
        //             console.warn(`Erreur lors du passage en plein écran : ${err.message}`);
        //         });
        //     }
        // }, { once: true }); // S'exécute une seule fois


    }

    /**
     * Supprime l'ancienne modale et crée une nouvelle.
     * @param {string} title - Titre de la modale.
     * @param {boolean} showLoading - Afficher un spinner de chargement.
     */
    showModal(title = "titre test", showLoading = false) {

        // Supprimer l'ancienne modale si elle existe déjà
        this.destroy();
      

        // if ($(".dynamic-modal:visible").length === 0) {
        //     // ✅ Vérifier si le document est déjà en plein écran AVANT d’activer fullscreen
        //     if (!document.fullscreenElement) {
        //         ModalUI.wasFullscreen = true; // Plein écran activé par le script
        //         document.documentElement.requestFullscreen().catch(err => {
        //             console.warn(`Erreur lors du passage en plein écran : ${err.message}`);
        //         });
        //     } else {
        //         ModalUI.wasFullscreen = false; // Déjà en plein écran, donc on ne change rien
        //     }
        // }

        // Ajouter la nouvelle modale
        $("body").append(`<div><div id="${this.currentModalId}" class="dynamic-modal"></div></div>`);
            

        // Détecter le modal parent (le dernier modal ouvert)
        this.parentModal = $(".dynamic-modal").not(`#${this.currentModalId}`).last();
        
        if (this.parentModal.length > 0) {
            // Vérifier si le modal parent est déjà en fullscreen
            this.isParentFullscreen = this.parentModal.hasClass('isFullscreen');
    
            // Sauvegarder la couleur actuelle du header
            this.originalHeaderColor = this.parentModal.data('headerColor') || "#17a2b8";
    
            // Changer la couleur du header en gris (`#6c757d`) pour indiquer que c'est un parent
            this.parentModal.iziModal('setHeaderColor', '#6c757d');
            this.parentModal.data('headerColor', this.originalHeaderColor); // Stocker la couleur originale
    
            // Mettre le modal parent en plein écran si nécessaire
            if (!this.isParentFullscreen) {
                this.parentModal.iziModal('setFullscreen', true);
                this.parentModal.addClass('isFullscreen');
            }
        }
    
        

        this.modal =  $(`#${this.currentModalId}`).iziModal({
            title: title,
            headerColor: "#17a2b8", // Couleur normale du nouveau modal
            width: 900,
            minHeight: 300,
            padding: 20,
            radius: 10,
            zindex: 1050,
            appendTo: 'body',
            closeButton: true,
            fullscreen: true,
            borderBottom: true,
            transitionIn: "fadeIn",
            transitionOut: "fadeOut",
            overlayClose: true,
            autoOpen: true,
            onClosed: () => this.handleClose()
        });

        if(this.config.editOnFullScreen){
            this.modal.iziModal('setFullscreen', true);
        }
    }
    

    /**
     * Affiche le contenu dans la modale existante sans recréer la modale.
     * @param {string} content - Contenu HTML à injecter dans la modale.
     */
    showContent(content) {
       $(`#${this.currentModalId}`).iziModal("stopLoading");
        $(`#${this.currentModalId}`).iziModal("setContent", content);
     
    }


    setTitle(title){
        $(`#${this.currentModalId}`).iziModal("setTitle", title);
    }
    
    /**
     * Affiche le modal avec un titre et active l'indicateur de chargement.
     * @param {string} title - Titre à afficher dans le modal.
     */
    showLoading(title) {
        this.showModal(title, true);
        $(`#${this.currentModalId}`).iziModal("startLoading");
     
    }
    startLoading(){
        $(`#${this.currentModalId}`).iziModal("startLoading"); 
    }
    stopLoading(){
        $(`#${this.currentModalId}`).iziModal("stopLoading");
    }
  

    /**
     * Ferme la modale actuellement ouverte.
     */
    close() {

        // const modal = document.getElementById(this.currentModalId);

        // // Check if any child inside the modal is currently focused
        // if (modal.contains(document.activeElement)) {
        //     document.activeElement.blur(); // Remove focus from it
        // }

         $(`#${this.currentModalId}`).iziModal("close");
    }

    /**
     * Ferme toutes les modales ouvertes.
     */
    closeAll() {
        $(".dynamic-modal").each(function () {
            $(this).iziModal("close");
            $(this).iziModal('destroy');
            $(this).remove();

        });
    }

    destroy() {
        if ($(`#${this.currentModalId}`).length > 0) {
            $(`#${this.currentModalId}`).iziModal('destroy');
            $(`#${this.currentModalId}`).remove();
        }
    
        // 🔹 Réactiver le défilement du body
        // if ($(".dynamic-modal:visible").length === 0) {
        //     $("body").css({
        //         "overflow": "auto",
        //         "padding-right": "0px" // Corrige le décalage de la page dû aux modales
        //     });
        // }
    }
    /**
     * Gère une erreur en affichant un message d'erreur dans une modale.
     * @param {string} errorMessage - Message d'erreur à afficher.
     */
    showError(errorMessage) {
        this.showContent(`<div class="alert alert-danger">${errorMessage}</div>`);
        console.error(`Erreur dans le modal : ${errorMessage}`);
    }

    handleClose() {

        this.restoreParentModal();
        
        this.indexUI.tableUI.entityLoader.loadEntities();
    
        // setTimeout(() => {
        //     if ($(".dynamic-modal:visible").length === 0) {
        //         // Vérifier si le mode plein écran a été activé par la modale
        //         if (ModalUI.wasFullscreen) {
        //             document.exitFullscreen().then(() => {
        //                 ModalUI.wasFullscreen = false; // Réinitialiser après la sortie du mode plein écran
        //             }).catch(err => {
        //                 console.warn(`Erreur lors de la sortie du mode plein écran : ${err.message}`);
        //             });
        //         }
        //     }
        // }, 100);
    }
    

    restoreParentModal() {
        if (this.parentModal && this.parentModal.length > 0) {
            // Restaurer la couleur originale du header
            const originalColor = this.parentModal.data('headerColor') || "#17a2b8";
            this.parentModal.iziModal('setHeaderColor', originalColor);
    
            // Si le modal parent n'était pas en fullscreen avant, le restaurer
            if (!this.isParentFullscreen) {
                this.parentModal.iziModal('setFullscreen', false);
                this.parentModal.removeClass('isFullscreen');
            }
        }
    }
}


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


