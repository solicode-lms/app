{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationModule-form')
<form 
    class="crud-form custom-form context-state container" 
    id="realisationModuleForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('realisationModules.bulkUpdate') : ($itemRealisationModule->id ? route('realisationModules.update', $itemRealisationModule->id) : route('realisationModules.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRealisationModule->id)
        <input type="hidden" name="id" value="{{ $itemRealisationModule->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($realisationModule_ids))
        @foreach ($realisationModule_ids as $id)
            <input type="hidden" name="realisationModule_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationModule" field="module_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="module_id" id="bulk_field_module_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="module_id">
            {{ ucfirst(__('PkgFormation::module.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="module_id" 
            required
            
            
            name="module_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($modules as $module)
                    <option value="{{ $module->id }}"
                        {{ (isset($itemRealisationModule) && $itemRealisationModule->module_id == $module->id) || (old('module_id>') == $module->id) ? 'selected' : '' }}>
                        {{ $module }}
                    </option>
                @endforeach
            </select>
          @error('module_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationModule" field="apprenant_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
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
                        {{ (isset($itemRealisationModule) && $itemRealisationModule->apprenant_id == $apprenant->id) || (old('apprenant_id>') == $apprenant->id) ? 'selected' : '' }}>
                        {{ $apprenant }}
                    </option>
                @endforeach
            </select>
          @error('apprenant_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationModule" field="progression_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="progression_cache" id="bulk_field_progression_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="progression_cache">
            {{ ucfirst(__('PkgApprentissage::realisationModule.progression_cache')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="progression_cache"
        type="number"
        class="form-control"
        required
        
        
        id="progression_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationModule.progression_cache') }}"
        value="{{ $itemRealisationModule ? number_format($itemRealisationModule->progression_cache, 2, '.', '') : old('progression_cache') }}">
          @error('progression_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationModule" field="etat_realisation_module_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="etat_realisation_module_id" id="bulk_field_etat_realisation_module_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="etat_realisation_module_id">
            {{ ucfirst(__('PkgApprentissage::realisationModule.etat_realisation_module_id')) }}
            
          </label>
                      <select 
            id="etat_realisation_module_id" 
            
            
            
            name="etat_realisation_module_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($etatRealisationModules as $etatRealisationModule)
                    <option value="{{ $etatRealisationModule->id }}"
                        {{ (isset($itemRealisationModule) && $itemRealisationModule->etat_realisation_module_id == $etatRealisationModule->id) || (old('etat_realisation_module_id>') == $etatRealisationModule->id) ? 'selected' : '' }}>
                        {{ $etatRealisationModule }}
                    </option>
                @endforeach
            </select>
          @error('etat_realisation_module_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationModule" field="note_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note_cache" id="bulk_field_note_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note_cache">
            {{ ucfirst(__('PkgApprentissage::realisationModule.note_cache')) }}
            
          </label>
              <input
        name="note_cache"
        type="number"
        class="form-control"
        
        
        
        id="note_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationModule.note_cache') }}"
        value="{{ $itemRealisationModule ? number_format($itemRealisationModule->note_cache, 2, '.', '') : old('note_cache') }}">
          @error('note_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationModule" field="bareme_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="bareme_cache" id="bulk_field_bareme_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="bareme_cache">
            {{ ucfirst(__('PkgApprentissage::realisationModule.bareme_cache')) }}
            
          </label>
              <input
        name="bareme_cache"
        type="number"
        class="form-control"
        
        
        
        id="bareme_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationModule.bareme_cache') }}"
        value="{{ $itemRealisationModule ? number_format($itemRealisationModule->bareme_cache, 2, '.', '') : old('bareme_cache') }}">
          @error('bareme_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationModule" field="commentaire_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="commentaire_formateur" id="bulk_field_commentaire_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="commentaire_formateur">
            {{ ucfirst(__('PkgApprentissage::realisationModule.commentaire_formateur')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="commentaire_formateur"
                class="form-control richText"
                
                
                
                id="commentaire_formateur"
                placeholder="{{ __('PkgApprentissage::realisationModule.commentaire_formateur') }}">{{ $itemRealisationModule ? $itemRealisationModule->commentaire_formateur : old('commentaire_formateur') }}</textarea>
          @error('commentaire_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationModule" field="date_fin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_fin" id="bulk_field_date_fin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgApprentissage::realisationModule.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgApprentissage::realisationModule.date_fin') }}"
                value="{{ $itemRealisationModule ? $itemRealisationModule->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationModule" field="date_debut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_debut" id="bulk_field_date_debut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgApprentissage::realisationModule.date_debut')) }}
            
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_debut"
                placeholder="{{ __('PkgApprentissage::realisationModule.date_debut') }}"
                value="{{ $itemRealisationModule ? $itemRealisationModule->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationModule" field="dernier_update" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="dernier_update" id="bulk_field_dernier_update" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dernier_update">
            {{ ucfirst(__('PkgApprentissage::realisationModule.dernier_update')) }}
            
          </label>
                      <input
                name="dernier_update"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="dernier_update"
                placeholder="{{ __('PkgApprentissage::realisationModule.dernier_update') }}"
                value="{{ $itemRealisationModule ? $itemRealisationModule->dernier_update : old('dernier_update') }}">

          @error('dernier_update')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('realisationModules.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationModule->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprentissage::realisationModule.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprentissage::realisationModule.singular") }} : {{$itemRealisationModule}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
