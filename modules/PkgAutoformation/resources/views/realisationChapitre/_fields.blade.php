{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationChapitre-form')
<form 
    class="crud-form custom-form context-state container" 
    id="realisationChapitreForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('realisationChapitres.bulkUpdate') : ($itemRealisationChapitre->id ? route('realisationChapitres.update', $itemRealisationChapitre->id) : route('realisationChapitres.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRealisationChapitre->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($realisationChapitre_ids))
        @foreach ($realisationChapitre_ids as $id)
            <input type="hidden" name="realisationChapitre_ids[]" value="{{ $id }}">
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
            {{ ucfirst(__('PkgAutoformation::realisationChapitre.date_debut')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="date_debut"
                placeholder="{{ __('PkgAutoformation::realisationChapitre.date_debut') }}"
                value="{{ $itemRealisationChapitre ? $itemRealisationChapitre->date_debut : old('date_debut') }}">

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
            {{ ucfirst(__('PkgAutoformation::realisationChapitre.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgAutoformation::realisationChapitre.date_fin') }}"
                value="{{ $itemRealisationChapitre ? $itemRealisationChapitre->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="chapitre_id" id="bulk_field_chapitre_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="chapitre_id">
            {{ ucfirst(__('PkgAutoformation::chapitre.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="chapitre_id" 
            required
            
            
            name="chapitre_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($chapitres as $chapitre)
                    <option value="{{ $chapitre->id }}"
                        {{ (isset($itemRealisationChapitre) && $itemRealisationChapitre->chapitre_id == $chapitre->id) || (old('chapitre_id>') == $chapitre->id) ? 'selected' : '' }}>
                        {{ $chapitre }}
                    </option>
                @endforeach
            </select>
          @error('chapitre_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_formation_id" id="bulk_field_realisation_formation_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_formation_id">
            {{ ucfirst(__('PkgAutoformation::realisationFormation.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_formation_id" 
            required
            
            
            name="realisation_formation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationFormations as $realisationFormation)
                    <option value="{{ $realisationFormation->id }}"
                        {{ (isset($itemRealisationChapitre) && $itemRealisationChapitre->realisation_formation_id == $realisationFormation->id) || (old('realisation_formation_id>') == $realisationFormation->id) ? 'selected' : '' }}>
                        {{ $realisationFormation }}
                    </option>
                @endforeach
            </select>
          @error('realisation_formation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="etat_chapitre_id" id="bulk_field_etat_chapitre_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="etat_chapitre_id">
            {{ ucfirst(__('PkgAutoformation::etatChapitre.singular')) }}
            
          </label>
                      <select 
            id="etat_chapitre_id" 
            
            
            
            name="etat_chapitre_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($etatChapitres as $etatChapitre)
                    <option value="{{ $etatChapitre->id }}"
                        {{ (isset($itemRealisationChapitre) && $itemRealisationChapitre->etat_chapitre_id == $etatChapitre->id) || (old('etat_chapitre_id>') == $etatChapitre->id) ? 'selected' : '' }}>
                        {{ $etatChapitre }}
                    </option>
                @endforeach
            </select>
          @error('etat_chapitre_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('realisationChapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationChapitre->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgAutoformation::realisationChapitre.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgAutoformation::realisationChapitre.singular") }} : {{$itemRealisationChapitre}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
