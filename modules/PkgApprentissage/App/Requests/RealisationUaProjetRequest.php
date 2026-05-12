<?php



namespace Modules\PkgApprentissage\App\Requests;

use Modules\Core\App\Rules\StepRule;
use Modules\PkgApprentissage\App\Requests\Base\BaseRealisationUaProjetRequest;

class RealisationUaProjetRequest extends BaseRealisationUaProjetRequest
{
    public function rules(): array
    {
        $rules = [
            'realisation_tache_id' => 'required',
            'realisation_ua_id' => 'required',
            'note' => [
                new StepRule(0.5),
                'nullable',
                'numeric',
                'min:0',
            ],
            'bareme' => 'nullable|numeric',
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
