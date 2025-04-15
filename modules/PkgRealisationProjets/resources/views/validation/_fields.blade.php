{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('validation-form')
<form 
    class="crud-form custom-form context-state container" 
    id="validationForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('validations.bulkUpdate') : ($itemValidation->id ? route('validations.update', $itemValidation->id) : route('validations.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemValidation->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($validation_ids))
        @foreach ($validation_ids as $id)
            <input type="hidden" name="validation_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="transfert_competence_id" id="bulk_field_transfert_competence_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="transfert_competence_id">
            {{ ucfirst(__('PkgCreationProjet::transfertCompetence.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="transfert_competence_id" 
            required
            
            
            name="transfert_competence_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($transfertCompetences as $transfertCompetence)
                    <option value="{{ $transfertCompetence->id }}"
                        {{ (isset($itemValidation) && $itemValidation->transfert_competence_id == $transfertCompetence->id) || (old('transfert_competence_id>') == $transfertCompetence->id) ? 'selected' : '' }}>
                        {{ $transfertCompetence }}
                    </option>
                @endforeach
            </select>
          @error('transfert_competence_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note" id="bulk_field_note" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note">
            {{ ucfirst(__('PkgRealisationProjets::validation.note')) }}
            
          </label>
              <input
        name="note"
        type="number"
        class="form-control"
        
        
        
        id="note"
        step="0.01"
        placeholder="{{ __('PkgRealisationProjets::validation.note') }}"
        value="{{ $itemValidation ? number_format($itemValidation->note, 2, '.', '') : old('note') }}">
          @error('note')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="message" id="bulk_field_message" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="message">
            {{ ucfirst(__('PkgRealisationProjets::validation.message')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="message"
                class="form-control richText"
                
                
                
                id="message"
                placeholder="{{ __('PkgRealisationProjets::validation.message') }}">{{ $itemValidation ? $itemValidation->message : old('message') }}</textarea>
          @error('message')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="is_valide" id="bulk_field_is_valide" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_valide">
            {{ ucfirst(__('PkgRealisationProjets::validation.is_valide')) }}
            <span class="text-danger">*</span>
          </label>
                      <input type="hidden" name="is_valide" value="0">
            <input
                name="is_valide"
                type="checkbox"
                class="form-control"
                required
                
                
                id="is_valide"
                value="1"
                {{ old('is_valide', $itemValidation ? $itemValidation->is_valide : 0) ? 'checked' : '' }}>
          @error('is_valide')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_projet_id" id="bulk_field_realisation_projet_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_projet_id">
            {{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_projet_id" 
            required
            
            
            name="realisation_projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationProjets as $realisationProjet)
                    <option value="{{ $realisationProjet->id }}"
                        {{ (isset($itemValidation) && $itemValidation->realisation_projet_id == $realisationProjet->id) || (old('realisation_projet_id>') == $realisationProjet->id) ? 'selected' : '' }}>
                        {{ $realisationProjet }}
                    </option>
                @endforeach
            </select>
          @error('realisation_projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('validations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemValidation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgRealisationProjets::validation.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgRealisationProjets::validation.singular") }} : {{$itemValidation}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
