<?php


namespace Modules\PkgRealisationProjets\Services;

use Modules\PkgFormation\Models\Formateur;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\PkgRealisationProjets\Services\Base\BaseEtatsRealisationProjetService;

/**
 * Classe EtatsRealisationProjetService pour gérer la persistance de l'entité EtatsRealisationProjet.
 */
class EtatsRealisationProjetService extends BaseEtatsRealisationProjetService
{
    protected array $index_with_relations = [
        'sysColor',
    ];



    /**
     * Récupérer les états de réalisation de projet associés à un formateur donné.
     *
     * @param int $formateur_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByFormateur($formateur_id)
    {
        return EtatsRealisationProjet::whereHas('realisationProjets', function ($query) use ($formateur_id) {
            $query->whereHas('affectationProjet', function ($q) use ($formateur_id) {
                $q->whereHas('groupe', function ($g) use ($formateur_id) {
                    $g->whereHas('formateurs', function ($f) use ($formateur_id) {
                        $f->where('formateurs.id', $formateur_id);
                    });
                });
            });
        })->get();
    }

    /**
     * Récupérer les états de réalisation des projets du formateur ayant affecté le plus de projets pour un apprenant donné.
     *
     * @param int $apprenant_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEtatsByFormateurPrincipalForApprenant($apprenant_id)
    {
        // Trouver le formateur qui affecte le plus de projets à l'apprenant
        $formateurPrincipal = Formateur::whereHas('projets', function ($query) use ($apprenant_id) {
            $query->whereHas('affectationProjets', function ($q) use ($apprenant_id) {
                $q->whereHas('groupe', function ($g) use ($apprenant_id) {
                    $g->whereHas('apprenants', function ($a) use ($apprenant_id) {
                        $a->where('apprenants.id', $apprenant_id);
                    });
                });
            });
        })
            ->withCount([
                'projets' => function ($query) use ($apprenant_id) {
                    $query->whereHas('affectationProjets', function ($q) use ($apprenant_id) {
                        $q->whereHas('groupe', function ($g) use ($apprenant_id) {
                            $g->whereHas('apprenants', function ($a) use ($apprenant_id) {
                                $a->where('apprenants.id', $apprenant_id);
                            });
                        });
                    });
                }
            ])
            ->orderByDesc('projets_count')
            ->first();

        // Si aucun formateur n'est trouvé, retourner une collection vide
        if (!$formateurPrincipal) {
            return collect();
        }

        // Récupérer les états de réalisation liés aux projets du formateur principal
        return EtatsRealisationProjet::where('formateur_id', $formateurPrincipal->id)->get();
    }



    /**
     * Récupère un état par son code.
     *
     * @param string $code
     * @param array $columns
     * @return EtatsRealisationProjet|null
     */
    public function getByCode(string $code, array $columns = ['*'])
    {
        return $this->allQuery()->where('code', $code)->first($columns);
    }
}
