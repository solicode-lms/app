<?php
 


namespace Modules\PkgRealisationProjets\App\Requests;
use Modules\PkgRealisationProjets\App\Requests\Base\BaseLivrablesRealisationRequest;

class LivrablesRealisationRequest extends BaseLivrablesRealisationRequest
{
    public function rules(): array
    {
        return [
            'livrable_id' => 'required',
            'lien' => 'required|url', // Validation du lien HTTP
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'realisation_projet_id' => 'required'
        ];
    }
 
}
