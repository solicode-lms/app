@php $canEditnote = !$entity || !$entity->id || Auth::user()->hasAnyRole(explode(',', 'formateur,evaluateur')); @endphp
@php
    // Note définie sur la tâche par défaut (peut être null)
    $tacheNote   = $entity->tache?->note;
    // Note déjà enregistrée sur cette entité (peut être null)
    $myNote      = $entity->note;
    // Valeur finale à afficher dans le champ (priorité à l’ancien input, sinon à la note existante)
    $inputValue  = old('note', $myNote !== null ? number_format($myNote, 2, '.', '') : '');
    // Placeholder : on affiche la note de la tâche si elle existe, sinon vide
    $inputPh     = $tacheNote !== null ? number_format($tacheNote, 2, '.', '') : number_format($entity->note, 2, '.', '');
    $maxNote   = $tacheNote !== null 
        ? number_format($tacheNote, 2, '.', '') 
        : '';
@endphp

<div class="form-group col-12 col-md-6">
    @if (!empty($bulkEdit))
    <div class="bulk-check">
        <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note" id="bulk_field_note" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
    </div>
    @endif
    <label for="note">
    {{ ucfirst(__('PkgGestionTaches::realisationTache.note')) }}
    </label>
<input
    name="note"
    type="number"
    class="form-control"
    min="0"
    max="{{$maxNote}}"
    id="note"
    {{ $canEditnote ? '' : 'disabled' }}
    step="0.25"
    placeholder="{{ $inputPh }}"
    value="{{ $inputValue }}"
    >
@error('note')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>