@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    // Vérifie si le formateur peut éditer
    $canEditremarques_formateur = !$entity || !$entity->id || $user->hasAnyRole(['formateur', 'evaluateur']);

    // Liste des évaluateurs du projet
    $evaluateurs = $entity->realisationProjet
        ->affectationProjet
        ->evaluateurs
        ->pluck('id');

    // Récupère le message de l'évaluateur connecté s'il est évaluateur du projet
    $evalMessage = null;
    if ($user->hasRole('evaluateur') || $evaluateurs->contains($user->evaluateur->id)) {
        $eval = $entity->evaluationRealisationTaches()
            ->where('evaluateur_id', $user->evaluateur->id)
            ->first();
        $evalMessage = $eval->message ?? null;
    }

    // Message à afficher par défaut (formateur)
    $defaultMessage = $entity->remarques_formateur;
    // Choix du message final
    $displayMessage = $evalMessage ?? $defaultMessage;
@endphp

<div class="form-group col-12 col-md-6">
    @if (!empty($bulkEdit))
        <div class="bulk-check">
            <input type="checkbox"
                   class="check-input"
                   name="fields_modifiables[]"
                   value="remarques_formateur"
                   id="bulk_field_remarques_formateur"
                   title="Appliquer ce champ à tous les éléments sélectionnés"
                   data-toggle="tooltip">
        </div>
    @endif

    <label for="remarques_formateur">
        {{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_formateur')) }}
    </label>

    @if($canEditremarques_formateur)
        <textarea
            name="remarques_formateur"
            id="remarques_formateur"
            class="form-control {{ $canEditremarques_formateur ? 'richText' : '' }} "
            {{ $canEditremarques_formateur ? '' : 'disabled' }}
            placeholder="{{ __('PkgGestionTaches::realisationTache.remarques_formateur') }}"
        >{{ old('remarques_formateur', $displayMessage) }}</textarea>
    @else
        <div id="remarques_formateur" class="border p-2">
            {!! $displayMessage !!}
        </div>
    @endif

    @error('remarques_formateur')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

