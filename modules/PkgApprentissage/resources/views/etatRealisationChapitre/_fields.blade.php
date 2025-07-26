{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationChapitre-form')
<form 
    class="crud-form custom-form context-state container" 
    id="etatRealisationChapitreForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('etatRealisationChapitres.bulkUpdate') : ($itemEtatRealisationChapitre->id ? route('etatRealisationChapitres.update', $itemEtatRealisationChapitre->id) : route('etatRealisationChapitres.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEtatRealisationChapitre->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($etatRealisationChapitre_ids))
        @foreach ($etatRealisationChapitre_ids as $id)
            <input type="hidden" name="etatRealisationChapitre_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatRealisationChapitre" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgApprentissage::etatRealisationChapitre.ordre')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                required
                
                
                id="ordre"
                placeholder="{{ __('PkgApprentissage::etatRealisationChapitre.ordre') }}"
                value="{{ $itemEtatRealisationChapitre ? $itemEtatRealisationChapitre->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatRealisationChapitre" field="nom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgApprentissage::etatRealisationChapitre.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgApprentissage::etatRealisationChapitre.nom') }}"
                value="{{ $itemEtatRealisationChapitre ? $itemEtatRealisationChapitre->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatRealisationChapitre" field="code" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="code" id="bulk_field_code" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="code">
            {{ ucfirst(__('PkgApprentissage::etatRealisationChapitre.code')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="code"
                type="input"
                class="form-control"
                required
                
                
                id="code"
                placeholder="{{ __('PkgApprentissage::etatRealisationChapitre.code') }}"
                value="{{ $itemEtatRealisationChapitre ? $itemEtatRealisationChapitre->code : old('code') }}">
          @error('code')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatRealisationChapitre" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgApprentissage::etatRealisationChapitre.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgApprentissage::etatRealisationChapitre.description') }}">{{ $itemEtatRealisationChapitre ? $itemEtatRealisationChapitre->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatRealisationChapitre" field="is_editable_only_by_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="is_editable_only_by_formateur" id="bulk_field_is_editable_only_by_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_editable_only_by_formateur">
            {{ ucfirst(__('PkgApprentissage::etatRealisationChapitre.is_editable_only_by_formateur')) }}
            <span class="text-danger">*</span>
          </label>
                      <input type="hidden" name="is_editable_only_by_formateur" value="0">
            <input
                name="is_editable_only_by_formateur"
                type="checkbox"
                class="form-control"
                required
                
                
                id="is_editable_only_by_formateur"
                value="1"
                {{ old('is_editable_only_by_formateur', $itemEtatRealisationChapitre ? $itemEtatRealisationChapitre->is_editable_only_by_formateur : 0) ? 'checked' : '' }}>
          @error('is_editable_only_by_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatRealisationChapitre" field="sys_color_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="sys_color_id" id="bulk_field_sys_color_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="sys_color_id">
            {{ ucfirst(__('Core::sysColor.singular')) }}
            
          </label>
                      <select 
            id="sys_color_id" 
            
            
            
            name="sys_color_id" 
            class="form-control select2Color">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysColors as $sysColor)
                    <option value="{{ $sysColor->id }}" data-color="{{ $sysColor->hex }}" 
                        {{ (isset($itemEtatRealisationChapitre) && $itemEtatRealisationChapitre->sys_color_id == $sysColor->id) || (old('sys_color_id>') == $sysColor->id) ? 'selected' : '' }}>
                        {{ $sysColor }}
                    </option>
                @endforeach
            </select>
          @error('sys_color_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('etatRealisationChapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEtatRealisationChapitre->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprentissage::etatRealisationChapitre.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprentissage::etatRealisationChapitre.singular") }} : {{$itemEtatRealisationChapitre}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
