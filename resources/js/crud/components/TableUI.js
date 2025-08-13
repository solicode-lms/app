import { LoadListAction } from "../actions/LoadListAction";

import { CreateAction } from '../actions/CreateAction';
import { ShowAction } from '../actions/ShowAction';
import { EditAction } from '../actions/EditAction';
import { DeleteAction } from '../actions/DeleteAction';
import EventUtil from './../utils/EventUtil';
import { EntityAction } from './../actions/EntityAction';
import { IndexViewSwitcherAction } from "../actions/IndexViewSwitcherAction";
import { ShowIndexAction } from "../actions/ShowIndexAction";
import { BulkAction } from "../actions/BulkAction";
import { OrdreColumn } from "./TableUI/OrdreColumn";
import { InlineEdit } from "./TableUI/InlineEdit";

export class TableUI {
    constructor(config, indexUI) {
       
        this.config = config;
        this.indexUI = indexUI;
    
        // Initialisation des actions CRUD
        this.entityCreator = new CreateAction(config,this);
        this.entityViewer = new ShowAction(config,this);
        this.showIndex = new ShowIndexAction(config,this);
        this.entityEditor = new EditAction(config,this);
        this.entityDeleter = new DeleteAction(config,this);
        this.entityLoader = new LoadListAction(config,this);
        this.entityAction = new EntityAction(config,this);
        this.bulkAction = new BulkAction(config,this);
        this.viewSwitcherAction = new IndexViewSwitcherAction(config, this);
        this.ordreColumn = new OrdreColumn(config, this);
        this.inlineEdit = new InlineEdit(config, this);
    }

    init(){
        this.viewSwitcherAction.init();
        this.entityEditor.init();
        this.entityCreator.init(); 
        this.entityDeleter.init();
        this.entityViewer.init();
        this.showIndex.init();
        this.entityAction.init();
        this.bulkAction.init();
        this.handleSorting();
        TableUI.initTooltip();
        this.initTruncatText();
        this.initWidgets();
        this.ordreColumn.init();
        this.inlineEdit.init();
    }

    
    updateSortArray(sortArray, column) {

      
        // Trouver le tri existant pour la colonne
        const existingSort = sortArray.find((s) => s.startsWith(column + '_'));
    
        if (existingSort) {

            // Récupérer la direction actuelle (asc ou desc)
            const direction = existingSort.split('_').pop(); 

            if (direction === 'asc') {
                // Si actuellement trié en ascendant, passe à descendant
                return sortArray.map((s) =>
                    s.startsWith(column + '_') ? `${column}_desc` : s
                );
            } else if (direction === 'desc') {
                // Si actuellement trié en descendant, supprime le tri
                return sortArray.filter((s) => !s.startsWith(column + '_'));
            }
        }
    
        // Si aucun tri existant, ajouter tri ascendant
        return [...sortArray, `${column}_asc`];
    }
    

    handleSorting() {
        EventUtil.bindEvent('click', this.config.sortableColumnSelector, (e) => {
            e.preventDefault();
    
            // 🔹 Récupérer le nom complet de la colonne à trier (ex: realisationTache.RealisationProjet.Apprenant_id)
            const column = $(e.currentTarget).data('sort');
    
            // 🔹 Lire le tri actuel depuis le ViewState
            const currentSortVars = this.config.viewStateService.getSortVariables();
    
            // 🔹 Récupérer la direction actuelle de la colonne
            const currentDirection = currentSortVars[column];
    
            // 🔹 Déterminer la nouvelle direction (asc → desc → none)
            let newSortValue = null;
            if (currentDirection === 'asc') {
                newSortValue = 'desc';
            } else if (currentDirection === 'desc') {
                newSortValue = null;
            } else {
                newSortValue = 'asc';
            }
    
            // 🔹 Supprimer toutes les variables de tri du ViewState
            Object.keys(currentSortVars).forEach(col => {
                this.config.viewStateService.removeVariable(`sort.${this.config.entity_name}.${col}`);
            });
    
            // 🔹 Ajouter le nouveau tri dans le ViewState
            if (newSortValue !== null) {
                this.config.viewStateService.setVariable(`sort.${this.config.entity_name}.${column}`, newSortValue);
            }
    
            // 🔹 Construire sort string à partir du ViewState
            const updatedSortVars = this.config.viewStateService.getSortVariables();


            this.updateSortInURL(updatedSortVars);
    
            // 🔹 Recharger les entités avec les nouveaux paramètres
            this.entityLoader.loadEntities(1);
        });
    }
    
    updateSortInURL(sortVars) {
        const url = new URL(window.location.href);
    
        // 🔹 Supprimer tous les paramètres sort liés à l'entité (sort.entityName.xxx)
        const entityPrefix = `sort.${this.config.entity_name}.`;
        for (const [key] of url.searchParams.entries()) {
            if (key.startsWith(entityPrefix)) {
                url.searchParams.delete(key);
            }
        }
    
        // 🔹 Ajouter les nouveaux paramètres "sort.entityName.col" = direction
        Object.entries(sortVars).forEach(([col, value]) => {
            url.searchParams.set(`sort.${this.config.entity_name}.${col}`, value);
        });
    
        window.history.replaceState({}, '', url);
    }

 initTruncatText() {
        // Supprime les tooltips UNIQUEMENT sur les éléments text-truncate
        $('.text-truncate[data-toggle="tooltip"]').tooltip('dispose');
    
        // Parcourt tous les éléments text-truncate
        $('.text-truncate').each(function () {
            const $el = $(this);
            const isTruncated = this.offsetWidth < this.scrollWidth;
    
            if (isTruncated) {
                // Si texte tronqué → on ajoute le tooltip Bootstrap
                $el.attr('title', $el.text().trim());
                $el.attr('data-toggle', 'tooltip');
            } else {
                // Sinon, on supprime les attributs liés au tooltip
                $el.removeAttr('title').removeAttr('data-toggle');
            }
        });
    
        // Réactiver seulement les tooltips sur les éléments text-truncate tronqués
        $('.text-truncate[data-toggle="tooltip"]').tooltip({
            placement: 'auto'
        }).on('shown.bs.tooltip', function () {
            const $this = $(this);
            setTimeout(() => {
                $this.tooltip('hide');
            }, 3000);
        });
    }
    

    static initTooltip(){


        // Supprimer les tooltip existant avant d'initialiser
        // $('.tooltip').remove();

        // // tooltip-inner
        // // Supprimer tous les tooltips existants avant d'en créer de nouveaux
        $('[data-toggle="tooltip"]').tooltip('dispose');
            

        // Supprime tous les tooltips actifs avant de les recréer
        const tooltips = $('[data-toggle="tooltip"]').tooltip({
             placement: "auto"
        });

        tooltips.on('shown.bs.tooltip', function () {
            const $this = $(this);
            setTimeout(() => {
                $this.tooltip('hide'); // Cache le tooltip
            }, 3000); // Temps en ms avant de cacher le tooltip (ici 3 secondes)
        });

        // $('[data-toggle="tooltip"]').tooltip();
      
    }

    // TODO : Création d'un composant : WidgetUI pour traiter lew Widget
    initWidgets(){

        const self = this; // pour conserver `this` dans update

          // Make the dashboard widgets sortable Using jquery UI
        $('.widgets_container').sortable({
            placeholder: 'sort-highlight',
            handle: '.card-header, .nav-tabs, .icon',
            forcePlaceholderSize: true,
            zIndex: 999999 ,
            tolerance: "pointer",
            update: function (event, ui) {
                const widgetElement = ui.item;
                const id = widgetElement.data("id");
                const newPosition = widgetElement.index() + 1;
            
                self.entityEditor.update_attributes(
                        { id: id, ordre: newPosition },
                        () => NotificationHandler.showSuccess('Ordre mis à jour.')
                );
            }
        })
        $('.widgets_container .card-header').css('cursor', 'move')
        $('.widgets_container .icon').css('cursor', 'move')

        const widget_remove_buton_selector = `${this.config.dataContainerOutSelector} [data-card-widget="remove"]`
        EventUtil.bindEvent('click', widget_remove_buton_selector, (e) => {
            e.preventDefault();

            const $button = $(e.currentTarget); // 🔥 Le vrai bouton cliqué
            const $widget = $button.closest('.widget');
            const id = $widget.data('id');

            if (!id) return;

            self.entityEditor.update_attributes(
                { id: id, visible: 0 },
                () => {
                    $widget.fadeOut();
                    NotificationHandler.showSuccess('Widget masqué avec succès.');
                }
            );
        });

        this.widgetStateLocalStorage();
    }

    widgetStateLocalStorage() {
        document.querySelectorAll('[data-card-widget="collapse"]').forEach(button => {
            const card = button.closest('.card');
            const key = button.dataset.storeKey;
    
            if (!card || !key) return;
    
            // 🟢 Appliquer l'état initial
            const savedState = localStorage.getItem(key);
            if (savedState === 'collapsed' && !card.classList.contains('collapsed-card')) {
                button.click();
            }
    
            // 👁️ Observer le changement de classe collapsed-card
            const observer = new MutationObserver((mutationsList) => {
                for (const mutation of mutationsList) {
                    if (mutation.attributeName === 'class') {
                        if (card.classList.contains('collapsed-card')) {
                            localStorage.setItem(key, 'collapsed');
                        } else {
                            localStorage.setItem(key, 'expanded');
                        }
                    }
                }
            });
    
            observer.observe(card, { attributes: true });
        });
    }
}


