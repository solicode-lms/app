<?php



namespace Modules\PkgApprentissage\App\Requests;
use Modules\PkgApprentissage\App\Requests\Base\BaseRealisationUaPrototypeRequest;

class RealisationUaPrototypeRequest extends BaseRealisationUaPrototypeRequest
{
    public function rules(): array
    {
        return [
            'realisation_tache_id' => 'required',
            'realisation_ua_id' => 'required',
            'bareme' => 'required',
            'note'                 => [
                    'nullable',
                    'numeric',
                    'min:0',
                    'lte:bareme', // 👈 Vérifie que note ≤ bareme
                ],
            'remarque_formateur' => 'nullable|string',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable'
        ];
    }
}
