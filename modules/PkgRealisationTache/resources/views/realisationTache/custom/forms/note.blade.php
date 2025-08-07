@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $canEditnote = !$entity || !$entity->id || $user->hasAnyRole(['formateur', 'evaluateur']);

    // Note personnelle ou moyenne encapsulée
    $myNote = $entity->getDisplayNote();

    // Moyenne des évaluations
    $moyenneAffiche = $entity->getAverageNote();

    // Plafond de la note (= barème max)
    $maxNote = $entity->getMaxNote();

    // Placeholder basé sur le barème
    $inputPh = $maxNote !== null ? number_format($maxNote, 2, '.', '') : '';

  
    // Valeur à afficher dans le champ
    $inputValue = old('note', $myNote !== null ? number_format($myNote, 2, '.', '') : '');
@endphp

<div class="form-group col-12 col-md-6">
    @if ($bulkEdit)
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
        {{ ucfirst(__('PkgRealisationTache::realisationTache.note')) }}
        @if($moyenneAffiche !== null)
            <small class="text-muted">(Moyenne : {{ number_format($moyenneAffiche, 2, '.', '') }})</small>
        @endif
    </label>

    <input
        name="note"
        type="number"
        class="form-control"
        min="0"
        max="{{ $maxNote }}"
        id="note"
        data-calcul='true'
        {{ $canEditnote && $maxNote > 0 ? '' : 'disabled' }}
        step="0.25"
        value="{{ $inputValue }}"
    >

    @if($maxNote)
        <small class="form-text text-muted">Barème : 0 à {{ number_format($maxNote, 2, '.', '') }}</small>
    @endif

    @error('note')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

