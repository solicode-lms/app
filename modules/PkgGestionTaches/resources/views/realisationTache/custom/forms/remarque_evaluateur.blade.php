@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    // Afficher uniquement pour les évaluateurs ou formateurs présents dans la liste des évaluateurs
    $evaluateurs = $entity->realisationProjet?->affectationProjet
        ->evaluateurs
        ->pluck('id');

    $isEvaluatorOrAssignedFormateur = ($evaluateurs != null) &&  (
        $user->hasRole('evaluateur') || 
        ($user->hasRole('formateur') && $evaluateurs->contains($user->evaluateur->id))
    );

    if (! $isEvaluatorOrAssignedFormateur) {
        return;
    }

    // Récupère le message de l'évaluateur connecté si existant
    $evalMessage = null;
    if ($evaluateurs->contains($user->evaluateur->id)) {
        $eval = $entity->evaluationRealisationTaches()
            ->where('evaluateur_id', $user->evaluateur->id)
            ->first();
        $evalMessage = $eval->message ?? null;
    }

    // Message à afficher par défaut (formateur)
    $defaultMessage = $entity->remarque_evaluateur;
    // Choix du message final
    $displayMessage = $evalMessage ?? $defaultMessage;
@endphp

<div class="form-group col-12 col-md-6">
    @if (!empty($bulkEdit))
        <div class="bulk-check">
            <input type="checkbox"
                   class="check-input"
                   name="fields_modifiables[]"
                   value="remarque_evaluateur"
                   id="bulk_field_remarque_evaluateur"
                   title="Appliquer ce champ à tous les éléments sélectionnés"
                   data-toggle="tooltip">
        </div>
    @endif

    <label for="remarque_evaluateur">
        {{ ucfirst(__('PkgGestionTaches::realisationTache.remarque_evaluateur')) }}
    </label>

  
        <textarea
            name="remarque_evaluateur"
            id="remarque_evaluateur"
            class="form-control richText"
            placeholder="{{ __('PkgGestionTaches::realisationTache.remarque_evaluateur') }}"
        >{{ old('remarque_evaluateur', $displayMessage) }}</textarea>
  

    @error('remarque_evaluateur')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

