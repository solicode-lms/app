{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationFormation-form')
<form 
    class="crud-form custom-form context-state container" 
    id="realisationFormationForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('realisationFormations.bulkUpdate') : ($itemRealisationFormation->id ? route('realisationFormations.update', $itemRealisationFormation->id) : route('realisationFormations.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRealisationFormation->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($realisationFormation_ids))
        @foreach ($realisationFormation_ids as $id)
            <input type="hidden" name="realisationFormation_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_debut" id="bulk_field_date_debut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgAutoformation::realisationFormation.date_debut')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="date_debut"
                placeholder="{{ __('PkgAutoformation::realisationFormation.date_debut') }}"
                value="{{ $itemRealisationFormation ? $itemRealisationFormation->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_fin" id="bulk_field_date_fin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgAutoformation::realisationFormation.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgAutoformation::realisationFormation.date_fin') }}"
                value="{{ $itemRealisationFormation ? $itemRealisationFormation->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="formation_id" id="bulk_field_formation_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="formation_id">
            {{ ucfirst(__('PkgAutoformation::formation.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="formation_id" 
            required
            
            
            name="formation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($formations as $formation)
                    <option value="{{ $formation->id }}"
                        {{ (isset($itemRealisationFormation) && $itemRealisationFormation->formation_id == $formation->id) || (old('formation_id>') == $formation->id) ? 'selected' : '' }}>
                        {{ $formation }}
                    </option>
                @endforeach
            </select>
          @error('formation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="apprenant_id" id="bulk_field_apprenant_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="apprenant_id">
            {{ ucfirst(__('PkgApprenants::apprenant.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="apprenant_id" 
            required
            
            
            name="apprenant_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($apprenants as $apprenant)
                    <option value="{{ $apprenant->id }}"
                        {{ (isset($itemRealisationFormation) && $itemRealisationFormation->apprenant_id == $apprenant->id) || (old('apprenant_id>') == $apprenant->id) ? 'selected' : '' }}>
                        {{ $apprenant }}
                    </option>
                @endforeach
            </select>
          @error('apprenant_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="etat_formation_id" id="bulk_field_etat_formation_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="etat_formation_id">
            {{ ucfirst(__('PkgAutoformation::etatFormation.singular')) }}
            
          </label>
                      <select 
            id="etat_formation_id" 
            
            
            
            name="etat_formation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($etatFormations as $etatFormation)
                    <option value="{{ $etatFormation->id }}"
                        {{ (isset($itemRealisationFormation) && $itemRealisationFormation->etat_formation_id == $etatFormation->id) || (old('etat_formation_id>') == $etatFormation->id) ? 'selected' : '' }}>
                        {{ $etatFormation }}
                    </option>
                @endforeach
            </select>
          @error('etat_formation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('realisationFormations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationFormation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgAutoformation::realisationFormation.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgAutoformation::realisationFormation.singular") }} : {{$itemRealisationFormation}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
