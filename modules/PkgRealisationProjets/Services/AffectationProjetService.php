<?php

namespace Modules\PkgRealisationProjets\Services;

use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
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

    /**
     * Affecter un projet à un groupe
     * - ajouter une Réalisation de projet pour chaque apprenant
     * @param mixed $data
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function create($data)
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
        $groupeService = new GroupeService();
        $groupe = $groupeService->find($data['groupe_id']);
    
        if (!$groupe) {
            throw new \Exception("Groupe non trouvé.");
        }
    
        // Récupération des apprenants du groupe via le service
        $apprenants = $groupe->apprenants;
    
        // Récupération du service de gestion des réalisations de projets
        $realisationProjetService = new RealisationProjetService();
    
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
    
    /**
     * Trouver la liste des affectations de projets d'un formateur donné.
     *
     * @param int $formateur_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAffectationProjetsByFormateurId($formateur_id)
    {
        return AffectationProjet::whereHas('groupe', function ($query) use ($formateur_id) {
            $query->whereHas('formateurs', function ($q) use ($formateur_id) {
                $q->where('formateurs.id', $formateur_id);
            });
        })->get();
    }

    /**
     * Trouver la liste des affectations de projets d'un apprenant donné.
     *
     * @param int $apprenant_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAffectationProjetsByApprenantId($apprenant_id)
    {
        return AffectationProjet::whereHas('groupe', function ($query) use ($apprenant_id) {
            $query->whereHas('apprenants', function ($q) use ($apprenant_id) {
                $q->where('apprenants.id', $apprenant_id);
            });
        })->get();
    }


    /**
     * Récupérer la dernière affectation de projet d'un formateur en fonction de la date actuelle.
     *
     * @param int $formateur_id
     * @return AffectationProjet|null
     */
    public function getCurrentFormateurAffectation($formateur_id)
    {
        return AffectationProjet::whereHas('groupe', function ($query) use ($formateur_id) {
                $query->whereHas('formateurs', function ($q) use ($formateur_id) {
                    $q->where('formateurs.id', $formateur_id);
                });
            })
            ->where('date_debut', '<=', now()) // Date de début <= aujourd'hui
            ->where(function ($query) {
                $query->whereNull('date_fin') // Si pas de date de fin, considéré comme en cours
                    ->orWhere('date_fin', '>=', now()); // Ou date de fin >= aujourd’hui
            })
            ->orderBy('date_debut', 'desc') // Trier par date de début descendante (dernier projet en premier)
            ->first(); // Prendre le plus récent
    }

    /**
     * Récupérer la dernière affectation de projet d'un apprenant en fonction de la date actuelle.
     *
     * @param int $apprenant_id
     * @return AffectationProjet|null
     */
    public function getCurrentApprenantAffectation($apprenant_id)
    {
        return AffectationProjet::whereHas('groupe', function ($query) use ($apprenant_id) {
                $query->whereHas('apprenants', function ($q) use ($apprenant_id) {
                    $q->where('apprenants.id', $apprenant_id);
                });
            })
            ->where('date_debut', '<=', now()) // L'affectation doit avoir déjà commencé
            ->where(function ($query) {
                $query->whereNull('date_fin') // Considérer actif si pas de date de fin
                    ->orWhere('date_fin', '>=', now()); // Ou si la date de fin est dans le futur
            })
            ->orderBy('date_debut', 'desc') // Trier par la date de début la plus récente
            ->first(); // Récupérer la dernière affectation active
    }

}
