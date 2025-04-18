{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('historiqueRealisationTache-form')
<form 
    class="crud-form custom-form context-state container" 
    id="historiqueRealisationTacheForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('historiqueRealisationTaches.bulkUpdate') : ($itemHistoriqueRealisationTache->id ? route('historiqueRealisationTaches.update', $itemHistoriqueRealisationTache->id) : route('historiqueRealisationTaches.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemHistoriqueRealisationTache->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($historiqueRealisationTache_ids))
        @foreach ($historiqueRealisationTache_ids as $id)
            <input type="hidden" name="historiqueRealisationTache_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="dateModification" id="bulk_field_dateModification" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dateModification">
            {{ ucfirst(__('PkgGestionTaches::historiqueRealisationTache.dateModification')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="dateModification"
                type="date"
                class="form-control datetimepicker"
                required
                
                
                id="dateModification"
                placeholder="{{ __('PkgGestionTaches::historiqueRealisationTache.dateModification') }}"
                value="{{ $itemHistoriqueRealisationTache ? $itemHistoriqueRealisationTache->dateModification : old('dateModification') }}">

          @error('dateModification')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="changement" id="bulk_field_changement" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="changement">
            {{ ucfirst(__('PkgGestionTaches::historiqueRealisationTache.changement')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="changement"
                class="form-control richText"
                required
                
                
                id="changement"
                placeholder="{{ __('PkgGestionTaches::historiqueRealisationTache.changement') }}">{{ $itemHistoriqueRealisationTache ? $itemHistoriqueRealisationTache->changement : old('changement') }}</textarea>
          @error('changement')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_tache_id" id="bulk_field_realisation_tache_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_tache_id">
            {{ ucfirst(__('PkgGestionTaches::realisationTache.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_tache_id" 
            required
            
            
            name="realisation_tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationTaches as $realisationTache)
                    <option value="{{ $realisationTache->id }}"
                        {{ (isset($itemHistoriqueRealisationTache) && $itemHistoriqueRealisationTache->realisation_tache_id == $realisationTache->id) || (old('realisation_tache_id>') == $realisationTache->id) ? 'selected' : '' }}>
                        {{ $realisationTache }}
                    </option>
                @endforeach
            </select>
          @error('realisation_tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('historiqueRealisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemHistoriqueRealisationTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgGestionTaches::historiqueRealisationTache.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgGestionTaches::historiqueRealisationTache.singular") }} : {{$itemHistoriqueRealisationTache}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
