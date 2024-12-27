
import $ from 'jquery';
window.$ = $;

import select2 from 'select2';
select2(); // <-- select2 must be called




import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle';
import "admin-lte/dist/js/adminlte";

import GenericCrud from  './modules/GenericCrud';
window.GenericCrud = GenericCrud;

import { setupSearchHandler } from './app.recherche';


// console.log($.fn.select2); // Devrait afficher la fonction Select2

setupSearchHandler();

// ManyToOne Select 
$(document).ready(function () {
    // Vérifier si des champs dynamiques à remplir sont spécifiés
    if (window.dynamicSelectManyToOne && Array.isArray(window.dynamicSelectManyToOne)) {
        window.dynamicSelectManyToOne.forEach(function (selectConfig) {
            let { fieldId, fetchUrl, selectedValue,fieldValue } = selectConfig;

            // Vérifiez si le champ existe avant de procéder
            if ($('#' + fieldId).length > 0) {
                $.ajax({
                    url: fetchUrl, // URL pour récupérer les données dynamiques
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        let selectElement = $('#' + fieldId);

                        // Ajouter les options au champ
                        data.forEach(function (item) {
                            let option = new Option(item[fieldValue], item.id);
                            selectElement.append(option);
                        });

                        // Pré-sélectionner la valeur si spécifiée
                        if (selectedValue) {
                            selectElement.val(selectedValue);
                        }
                    },
                    error: function () {
                        alert('Erreur lors du chargement des données pour ' + fieldId);
                    }
                });
            }
        });
    }
});


// ManyToMany Select 2 
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
    
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })
