{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationTache-form')
<form 
    class="crud-form custom-form context-state container" 
    id="etatRealisationTacheForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('etatRealisationTaches.bulkUpdate') : ($itemEtatRealisationTache->id ? route('etatRealisationTaches.update', $itemEtatRealisationTache->id) : route('etatRealisationTaches.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEtatRealisationTache->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($etatRealisationTache_ids))
        @foreach ($etatRealisationTache_ids as $id)
            <input type="hidden" name="etatRealisationTache_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgGestionTaches::etatRealisationTache.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgGestionTaches::etatRealisationTache.nom') }}"
                value="{{ $itemEtatRealisationTache ? $itemEtatRealisationTache->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="workflow_tache_id" id="bulk_field_workflow_tache_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="workflow_tache_id">
            {{ ucfirst(__('PkgGestionTaches::workflowTache.singular')) }}
            
          </label>
                      <select 
            id="workflow_tache_id" 
            
            
            
            name="workflow_tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($workflowTaches as $workflowTache)
                    <option value="{{ $workflowTache->id }}"
                        {{ (isset($itemEtatRealisationTache) && $itemEtatRealisationTache->workflow_tache_id == $workflowTache->id) || (old('workflow_tache_id>') == $workflowTache->id) ? 'selected' : '' }}>
                        {{ $workflowTache }}
                    </option>
                @endforeach
            </select>
          @error('workflow_tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="sys_color_id" id="bulk_field_sys_color_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="sys_color_id">
            {{ ucfirst(__('Core::sysColor.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="sys_color_id" 
            required
            
            
            name="sys_color_id" 
            class="form-control select2Color">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysColors as $sysColor)
                    <option value="{{ $sysColor->id }}" data-color="{{ $sysColor->hex }}" 
                        {{ (isset($itemEtatRealisationTache) && $itemEtatRealisationTache->sys_color_id == $sysColor->id) || (old('sys_color_id>') == $sysColor->id) ? 'selected' : '' }}>
                        {{ $sysColor }}
                    </option>
                @endforeach
            </select>
          @error('sys_color_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="is_editable_only_by_formateur" id="bulk_field_is_editable_only_by_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_editable_only_by_formateur">
            {{ ucfirst(__('PkgGestionTaches::etatRealisationTache.is_editable_only_by_formateur')) }}
            
          </label>
                      <input type="hidden" name="is_editable_only_by_formateur" value="0">
            <input
                name="is_editable_only_by_formateur"
                type="checkbox"
                class="form-control"
                
                
                
                id="is_editable_only_by_formateur"
                value="1"
                {{ old('is_editable_only_by_formateur', $itemEtatRealisationTache ? $itemEtatRealisationTache->is_editable_only_by_formateur : 0) ? 'checked' : '' }}>
          @error('is_editable_only_by_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="formateur_id" id="bulk_field_formateur_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="formateur_id">
            {{ ucfirst(__('PkgFormation::formateur.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="formateur_id" 
            required
            
            
            name="formateur_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemEtatRealisationTache) && $itemEtatRealisationTache->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
          @error('formateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgGestionTaches::etatRealisationTache.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgGestionTaches::etatRealisationTache.description') }}">{{ $itemEtatRealisationTache ? $itemEtatRealisationTache->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   RealisationTache HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('etatRealisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEtatRealisationTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgGestionTaches::etatRealisationTache.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgGestionTaches::etatRealisationTache.singular") }} : {{$itemEtatRealisationTache}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
