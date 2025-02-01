import { CrudManager } from "./CrudManager";
import { EditWithHasManyManager } from "./EditWithHasManyManager";
import { ConfigHelper } from "./helpers/ConfigHelper";

export default class InitCrudManagers {
    
    static processedEntities = new Set(); // Stocke les identifiants des entités déjà traitées

    static init(isDebug) {
        if (!window.entitiesConfig || !Array.isArray(window.entitiesConfig)) {
            return; // Si la configuration des entités est absente ou invalide, on arrête
        }

        window.entitiesConfig.forEach((entityConfigData) => {
            // On suppose que chaque entité a un identifiant unique `id`
            const uniqueKey = entityConfigData.id || JSON.stringify(entityConfigData); 


            // Créer la configuration pour cette entité
            let entityConfig = new ConfigHelper(entityConfigData, contextState, sessionState);
            entityConfig.isDebug = isDebug;

               

            if (InitCrudManagers.processedEntities.has(uniqueKey)) {
                // Delete from DOM 
                return;
            }

            
            // Ajouter l'identifiant au Set pour éviter un traitement multiple
            InitCrudManagers.processedEntities.add(uniqueKey);


         
            // Vérifier et initialiser le bon gestionnaire selon la page
            if (entityConfig.page === "index") {
                const crudManager = new CrudManager(entityConfig);
                crudManager.init();
            } 
            else if (entityConfig.page === "edit") {
                const editWithHasManyManager = new EditWithHasManyManager(entityConfig);
                editWithHasManyManager.init();
            }
        });

        window.entitiesConfig = [];
    }
}
