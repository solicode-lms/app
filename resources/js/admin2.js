// import $ from 'admin-lte/plugins/jquery/jquery.min.js';
// import jquery and select2
import $ from "jquery";
import select2 from 'select2';

 select2(); // <-- select2 must be called


console.log($);
console.log($.fn.select2); // Devrait afficher la fonction Select2

// ManyToMany Select 2 
$(document).ready(function() {
    $('#tags').select2({
        placeholder: "Donner Tags",
        allowClear: true,
        width: '100%' // S'assure que le champ s'adapte correctement
    });
});
