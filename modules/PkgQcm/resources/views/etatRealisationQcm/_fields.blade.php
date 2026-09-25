{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationQcm-form')
<form 
    class="crud-form custom-form context-state container" 
    id="etatRealisationQcmForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('etatRealisationQcms.bulkUpdate') : ($itemEtatRealisationQcm->id ? route('etatRealisationQcms.update', $itemEtatRealisationQcm->id) : route('etatRealisationQcms.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEtatRealisationQcm->id)
        <input type="hidden" name="id" value="{{ $itemEtatRealisationQcm->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($etatRealisationQcm_ids))
        @foreach ($etatRealisationQcm_ids as $id)
            <input type="hidden" name="etatRealisationQcm_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatRealisationQcm" field="titre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="titre" 
              id="bulk_field_titre" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre">
            {{ ucfirst(__('PkgQcm::etatRealisationQcm.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgQcm::etatRealisationQcm.titre') }}"
                value="{{ $itemEtatRealisationQcm ? $itemEtatRealisationQcm->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatRealisationQcm" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="description" 
              id="bulk_field_description" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgQcm::etatRealisationQcm.description')) }}
            
          </label>
           <input
                name="description"
                type="input"
                class="form-control"
                
                
                
                id="description"
                placeholder="{{ __('PkgQcm::etatRealisationQcm.description') }}"
                value="{{ $itemEtatRealisationQcm ? $itemEtatRealisationQcm->description : old('description') }}">
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatRealisationQcm" field="is_editable_by_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="is_editable_by_formateur" 
              id="bulk_field_is_editable_by_formateur" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_editable_by_formateur">
            {{ ucfirst(__('PkgQcm::etatRealisationQcm.is_editable_by_formateur')) }}
            
          </label>
                      <input type="hidden" name="is_editable_by_formateur" value="0">
            <input
                name="is_editable_by_formateur"
                type="checkbox"
                class="form-control d-block"
                
                
                
                id="is_editable_by_formateur"
                value="1"
                {{ old('is_editable_by_formateur', $itemEtatRealisationQcm ? $itemEtatRealisationQcm->is_editable_by_formateur : 0) ? 'checked' : '' }}>
          @error('is_editable_by_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatRealisationQcm" field="sys_color_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="sys_color_id" 
              id="bulk_field_sys_color_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
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
                        {{ (isset($itemEtatRealisationQcm) && $itemEtatRealisationQcm->sys_color_id == $sysColor->id) || (old('sys_color_id>') == $sysColor->id) ? 'selected' : '' }}>
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
        <a href="{{ route('etatRealisationQcms.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEtatRealisationQcm->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgQcm::etatRealisationQcm.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgQcm::etatRealisationQcm.singular") }} : {{$itemEtatRealisationQcm}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
