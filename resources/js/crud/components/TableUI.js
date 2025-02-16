import { LoadListAction } from "../actions/LoadListAction";

import { CreateAction } from '../actions/CreateAction';
import { ShowAction } from '../actions/ShowAction';
import { EditAction } from '../actions/EditAction';
import { DeleteAction } from '../actions/DeleteAction';
import EventUtil from './../utils/EventUtil';
import { EntityAction } from './../actions/EntityAction';

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
    }

    init(){

        this.entityEditor.init();
        this.entityCreator.init(); 
        this.entityDeleter.init();
        this.entityViewer.init();
        this.entityAction.init();
        this.handleSorting();
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
            filters.sort = newSortArray.join(',');

            this.indexUI.updateURLParameters(filters); // Mettre à jour l'URL
            this.entityLoader.loadEntities(1, filters); // Recharger la table
        });
    }

}
