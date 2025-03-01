<?php

namespace Modules\PkgGestionTaches\Services;
use Modules\PkgGestionTaches\Services\Base\BaseTacheService;

/**
 * Classe TacheService pour gérer la persistance de l'entité Tache.
 */
class TacheService extends BaseTacheService
{
    public function dataCalcul($tache)
    {
        // En Cas d'édit
        if(isset($tache->id)){
          
        }
      
        return $tache;
    }

    public function create(array|object $data)
    {
        // Créer la tâche
        $tache = parent::create($data);
    
        // Vérifier si la tâche est bien créée et qu'elle est associée à un projet
        if ($tache && isset($tache->projet)) {
            // Récupérer tous les apprenants liés au projet via les affectations et réalisations
            $realisationProjets = $tache->projet->affectationProjets
                ->flatMap(fn($affectation) => $affectation->realisationProjets);
    
            // Instance du service RealisationTacheService
            $realisationTacheService = new \Modules\PkgGestionTaches\Services\RealisationTacheService();
    
            // Création des réalisations des tâches pour les apprenants concernés
            foreach ($realisationProjets as $realisationProjet) {
                $realisationTacheService->create([
                    'tache_id' => $tache->id,
                    'realisation_projet_id' => $realisationProjet->id, // Associer à la bonne réalisation de projet
                    'etat_realisation_tache_id' => null, // Valeur par défaut à définir si nécessaire
                    'dateDebut' => $tache->dateDebut,
                    'dateFin' => $tache->dateFin
                ]);
            }
        }
    
        return $tache;
    }

    /**
     * Met à jour un élément existant.
     *
     * @param mixed $id Identifiant de l'élément à mettre à jour.
     * @param array $data Données à mettre à jour.
     * @return Entity modifié
     */
    public function update($id, array $data)
    {
        return parent::update($id,$data);
    }

   

}
