import { LoadListAction } from "../actions/LoadListAction";

import { CreateAction } from '../actions/CreateAction';
import { ShowAction } from '../actions/ShowAction';
import { EditAction } from '../actions/EditAction';
import { DeleteAction } from '../actions/DeleteAction';
import EventUtil from './../utils/EventUtil';
import { EntityAction } from './../actions/EntityAction';
import { IndexViewSwitcherAction } from "../actions/IndexViewSwitcherAction";
import { ShowIndexAction } from "../actions/ShowIndexAction";

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
        this.viewSwitcherAction = new IndexViewSwitcherAction(config, this);
    }

    init(){
        this.viewSwitcherAction.init();
        this.entityEditor.init();
        this.entityCreator.init(); 
        this.entityDeleter.init();
        this.entityViewer.init();
        this.showIndex.init();
        this.entityAction.init();
        this.handleSorting();
        TableUI.initTooltip();
        this.initWidgets();

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

            const column = $(e.currentTarget).data('sort');
            const currentSort = new URLSearchParams(window.location.search).get('sort') || '';
            const sortArray = currentSort.split(',').filter(Boolean);
            const newSortArray = this.updateSortArray(sortArray, column);

            const filters = this.indexUI.filterUI.getFormData(); // Inclure les données de recherche et filtres
            const sort = newSortArray.join(',');
            filters.sort = sort;
            this.config.viewStateService.updatSortVariables({"sort" : sort});


            this.indexUI.updateURLParameters(filters); // Mettre à jour l'URL
            this.entityLoader.loadEntities(1, filters); // Recharger la table
        });
    }

    static initTooltip(){

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
