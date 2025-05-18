
@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $canEditnote = !$entity || !$entity->id || $user->hasAnyRole(explode(',', 'formateur,evaluateur'));

    // Note définie sur la tâche par défaut (peut être null)
    $tacheNote = $entity->tache?->note;
    // Note à afficher : évaluation perso si évaluateur (et formateur parmi évaluateurs), sinon note générale
    $myNote = $entity->note;

    // Récupérer la liste des évaluateurs du projet
    $evaluateurs = $entity->realisationProjet
        ->affectationProjet
        ->evaluateurs
        ->pluck('id');

    if ($user->hasRole('evaluateur') || $evaluateurs->contains($user->evaluateur->id)) {
        $eval = $entity->evaluationRealisationTaches()
            ->where('evaluateur_id', $user->evaluateur->id)
            ->first();
        if ($eval && $eval->note !== null) {
            $myNote = $eval->note;
        }
    }

    // Calcul de la moyenne des évaluations existantes
    $moyenne = $entity->evaluationRealisationTaches()->avg('note');
    $moyenneAffiche = $moyenne !== null ? number_format($moyenne, 2, '.', '') : null;

    // Valeur finale à afficher dans le champ
    $inputValue = old('note', $myNote !== null ? number_format($myNote, 2, '.', '') : '');

    // Placeholder : note max possible (par défaut, note de la tâche)
    $inputPh = $tacheNote !== null
        ? number_format($tacheNote, 2, '.', '')
        : ($entity->note !== null ? number_format($entity->note, 2, '.', '') : '');

    $maxNote = $tacheNote !== null
        ? number_format($tacheNote, 2, '.', '')
        : '';
@endphp

<div class="form-group col-12 col-md-6">
    @if (!empty($bulkEdit))
        <div class="bulk-check">
            <input type="checkbox"
                   class="check-input"
                   name="fields_modifiables[]"
                   value="note"
                   id="bulk_field_note"
                   title="Appliquer ce champ à tous les éléments sélectionnés"
                   data-toggle="tooltip">
        </div>
    @endif

    <label for="note">
        {{ ucfirst(__('PkgGestionTaches::realisationTache.note')) }}
        @if($moyenneAffiche)
            <small class="text-muted">(Note moyenne actuelle : {{ $moyenneAffiche }})</small>
        @endif
    </label>

    <input
        name="note"
        type="number"
        class="form-control"
        min="0"
        max="{{ $maxNote }}"
        id="note"
        {{ $canEditnote ? '' : 'disabled' }}
        step="0.25"
        placeholder="{{ $inputPh }}"
        value="{{ $inputValue }}"
    >

    {{-- Barème de notation pour UX --}}
    @if($maxNote)
        <small class="form-text text-muted">Barème : 0 à {{ $maxNote }}</small>
    @endif

    @error('note')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

