  @php
    // Plafond de la note (= barème max)
    $maxNote = $entity->getMaxNote();
  @endphp
  <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note" id="bulk_field_note" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note">
            {{ ucfirst(__('PkgEvaluateurs::evaluationRealisationTache.note')) }}
          </label>
              <input
        name="note"
        type="number"
        class="form-control"
        min="0"
        max="{{ $maxNote }}"
        id="note"
        step="0.25"
        onfocus="this.select()"
        placeholder="Note (0 à {{ $maxNote }})"
        value="{{ old('note', ($entity && $entity->note !== null) ? number_format($entity->note, 2, '.', '') : '') }}">
          
      @if($maxNote)
        <small class="form-text text-muted">Barème : 0 à {{ number_format($maxNote, 2, '.', '') }}</small>
      @endif
        
        
        @error('note')
            <div class="text-danger">{{ $message }}</div>
          @enderror
</div>