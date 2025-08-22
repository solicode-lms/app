<?php



namespace Modules\PkgApprentissage\App\Requests;
use Modules\PkgApprentissage\App\Requests\Base\BaseRealisationUaProjetRequest;

class RealisationUaProjetRequest extends BaseRealisationUaProjetRequest
{
     public function rules(): array
    {
        return [
            'realisation_tache_id' => 'required',
            'realisation_ua_id' => 'required',
             'note'                 => [
                    'nullable',
                    'numeric',
                    'min:0',
                    'lte:bareme', // 👈 Vérifie que note ≤ bareme
                ],
            'bareme' => 'required',
            'remarque_formateur' => 'nullable|string',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable'
        ];
    }
}
