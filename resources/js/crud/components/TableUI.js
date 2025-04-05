import { LoadListAction } from "../actions/LoadListAction";

import { CreateAction } from '../actions/CreateAction';
import { ShowAction } from '../actions/ShowAction';
import { EditAction } from '../actions/EditAction';
import { DeleteAction } from '../actions/DeleteAction';
import EventUtil from './../utils/EventUtil';
import { EntityAction } from './../actions/EntityAction';
import { IndexViewSwitcherAction } from "../actions/IndexViewSwitcherAction";

export class TableUI {
    constructor(config, indexUI) {
       
        this.config = config;
        this.indexUI = indexUI;
    
        // Initialisation des actions CRUD
        this.entityCreator = new CreateAction(config,this);
        this.entityViewer = new ShowAction(config,this);
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
        this.entityAction.init();
        this.handleSorting();
        TableUI.initTooltip();
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

}
