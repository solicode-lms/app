import $ from 'jquery';
// window.$ = window.jQuery = $; // S'assurer que jQuery est global


// import iziModal from "izimodal/js/iziModal.min.js";
import iziModal from "izimodal/js/iziModal.min.js";

import "izimodal/css/iziModal.min.css";

$.fn.iziModal = iziModal;

// Vérifier si iziModal est bien attaché à jQuery
if (typeof $.fn.iziModal === 'undefined') {
    console.error("iziModal n'est pas chargé correctement !");
} else {
    console.log("iziModal chargé avec succès !");
}

document.addEventListener("DOMContentLoaded", function () {

    iziModal();

    // $("#modal-example").iziModal({
    //     title: "Titre de la Modale",
    //     headerColor: "#4CAF50",
    // });

    $("#modal-example").iziModal({
        title: "Titre de la Modale",
        headerColor: "#4CAF50",
    });

    $("#modal-example").iziModal("setContent", "bonjour");
    $("#modal-example").iziModal("open");
});
