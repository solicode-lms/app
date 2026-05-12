<?php



namespace Modules\PkgApprentissage\App\Requests;
use Modules\PkgApprentissage\App\Requests\Base\BaseRealisationUaPrototypeRequest;

class RealisationUaPrototypeRequest extends BaseRealisationUaPrototypeRequest
{
    public function rules(): array
    {
        $rules = [
            'realisation_tache_id' => 'required',
            'realisation_ua_id' => 'required',
            'bareme' => 'nullable|numeric',
            'note' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'remarque_formateur' => 'nullable|string',
            'date_debut' => 'nullable',
            'date_fin' => 'nullable'
        ];

        // 👈 Ne vérifier "lte:bareme" que si bareme est soumis et non null
        if ($this->has('bareme') && $this->input('bareme') !== null) {
            $rules['note'][] = 'lte:bareme';
        }

        return $rules;
    }
}
