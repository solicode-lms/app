@php 
 $canEditis_live_coding = $bulkEdit ? Auth::user()->hasAnyRole(explode(',', 'formateur,admin')) : (empty($itemRealisationTache->id) || Auth::user()->hasAnyRole(explode(',', 'formateur,admin')) ); 
  $phaseEvaluation = $entity->tache?->phaseEvaluation?->code;
@endphp
    
{{-- @if($phaseEvaluation == "N1") --}}
      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="is_live_coding" id="bulk_field_is_live_coding" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_live_coding">
            {{ ucfirst(__('PkgRealisationTache::realisationTache.is_live_coding')) }}
            
          </label>
                      <input type="hidden" name="is_live_coding" value="0">
            <input
                name="is_live_coding"
                type="checkbox"
                class="form-control"
                
                
                
                id="is_live_coding"
                {{ $canEditis_live_coding ? '' : 'disabled' }}
                value="1"
                {{ old('is_live_coding', $entity ? $entity->is_live_coding : 0) ? 'checked' : '' }}>
          @error('is_live_coding')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
{{-- @endif --}}