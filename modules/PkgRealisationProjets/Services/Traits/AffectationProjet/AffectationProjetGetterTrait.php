<?php

namespace Modules\PkgRealisationProjets\Services\Traits\AffectationProjet;

use Modules\PkgRealisationProjets\Models\AffectationProjet;

/**
 * Trait AffectationProjetGetterTrait
 * 
 * Gestion des requêtes de lecture et des filtres spécifiques.
 */
trait AffectationProjetGetterTrait
{
    /**
     * Récupère toutes les affectations de projet qui ont au moins un évaluateur.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAffectationProjetsAvecEvaluateurs()
    {
        return AffectationProjet::whereHas('evaluateurs')->get();
    }


    /**
     * Trouver la liste des affectations de projets d'un évaluateur donné.
     *
     * @param int $evaluateur_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAffectationProjetsByEvaluateurId($evaluateur_id)
    {
        return AffectationProjet::whereHas('evaluateurs', function ($query) use ($evaluateur_id) {
            $query->where('evaluateurs.id', $evaluateur_id);
        })->get();
    }

    /**
     * Trouve la liste des affectations de projets d'un formateur.
     * 
     * Cette méthode récupère les projets :
     * 1. Dont le formateur est le propriétaire direct (via projets.formateur_id).
     * 2. (Commenté) Dont le formateur est assigné comme évaluateur.
     *
     * @param int $formateur_id L'ID du formateur.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAffectationProjetsByFormateurId($formateur_id)
    {
        return AffectationProjet::where(function ($query) use ($formateur_id) {
            // Cas 1 : Le formateur est lié au projet via projets.formateur_id
            $query->whereHas('projet', function ($q) use ($formateur_id) {
                $q->where('formateur_id', $formateur_id);
            });
            // Cas 2 : Le formateur est un évaluateur via affectation_projet_evaluateur
            // ->orWhereHas('evaluateurs', function ($q) use ($formateur_id) {
            //     $q->whereHas('user', function ($subQuery) use ($formateur_id) {
            //         $subQuery->whereIn('id', function ($innerQuery) use ($formateur_id) {
            //             $innerQuery->select('user_id')
            //                     ->from('formateurs')
            //                     ->where('id', $formateur_id);
            //         });
            //     });
            // });
        })->get();
    }


    /**
     * Trouver la liste des affectations de projets d'un apprenant donné.
     * 
     * Recherche uniquement les affectations pour lesquelles l'apprenant a une réalisation de projet.
     * (Cela suppose que les RealisationProjet sont créées lors de l'affectation ou que l'apprenant n'est concerné que s'il en a une).
     *
     * @param int $apprenant_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAffectationProjetsByApprenantId($apprenant_id)
    {
        return AffectationProjet::whereHas('realisationProjets', function ($query) use ($apprenant_id) {
            $query->where('apprenant_id', $apprenant_id);
        })
            ->get();
    }


    /**
     * Récupérer la dernière affectation de projet active d'un formateur.
     *
     * Sélectionne le projet le plus récent (date de début desc) qui est 
     * actuellement en cours (débuté et non terminé ou sans date de fin).
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
     * Récupérer la dernière affectation de projet active d'un apprenant.
     * 
     * Sélectionne le projet du groupe de l'apprenant le plus récent 
     * qui est actuellement en cours.
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


    /**
     * Filtre les données pour ne retourner que celles ayant des évaluateurs assignés.
     * 
     * Applique un filtre dynamique sur la requête courante.
     *
     * @param string $filter Nom du filtre.
     * @param mixed $value Valeur du filtre.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDataHasEvaluateurs(string $filter, $value)
    {


        //  TODO : $query = $this->newQuery();
        $query = $this->allQuery(); // Créer une nouvelle requête


        // Ajouter la condition : présence d’au moins un évaluateur
        $query->whereHas('evaluateurs');


        // Construire le tableau de filtres pour la méthode `filter()`
        $filters = [$filter => $value];

        // Appliquer le filtre existant du service
        $this->filter($query, $this->model, $filters);

        return $query->get();
    }
}
