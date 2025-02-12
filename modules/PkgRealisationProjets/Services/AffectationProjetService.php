<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Services;
use Modules\PkgRealisationProjets\Services\Base\BaseAffectationProjetService;

/**
 * Classe AffectationProjetService pour gérer la persistance de l'entité AffectationProjet.
 */
class AffectationProjetService extends BaseAffectationProjetService
{
    public function dataCalcul($affectationProjet)
    {
        // En Cas d'édit
        if(isset($affectationProjet->id)){
          
        }
      
        return $affectationProjet;
    }


    public function create(array $data)
    {
        // Vérification des champs obligatoires
        if (empty($data['groupe_id']) || empty($data['projet_id'])) {
            throw new \InvalidArgumentException("Le groupe et le projet sont obligatoires.");
        }
    
        // Vérification de la cohérence des dates
        if (!empty($data['date_debut']) && !empty($data['date_fin']) && $data['date_debut'] > $data['date_fin']) {
            throw new \InvalidArgumentException("La date de début ne peut pas être après la date de fin.");
        }
    
        // Création de l'affectation de projet
        $affectationProjet = parent::create($data);
    
        // Récupération du service de gestion des groupes
        $groupeService = app(\Modules\PkgApprenants\Services\Base\BaseGroupeService::class);
        $groupe = $groupeService->find($data['groupe_id']);
    
        if (!$groupe) {
            throw new \Exception("Groupe non trouvé.");
        }
    
        // Récupération des apprenants du groupe via le service
        $apprenants = $groupe->apprenants;
    
        // Récupération du service de gestion des réalisations de projets
        $realisationProjetService = app(\Modules\PkgRealisationProjets\Services\Base\BaseRealisationProjetService::class);
    
        // Créer une réalisation de projet pour chaque apprenant
        foreach ($apprenants as $apprenant) {
            $realisationProjetService->create([
                'apprenant_id' => $apprenant->id,
                'affectation_projet_id' => $affectationProjet->id,
                'date_debut' => $affectationProjet->date_debut,
                'date_fin' => $affectationProjet->date_fin,
                'rapport' => null, // Peut être rempli plus tard
                'etats_realisation_projet_id' => null, // Peut être défini selon un état initial
            ]);
        }
    
        return $affectationProjet;
    }
    

}
