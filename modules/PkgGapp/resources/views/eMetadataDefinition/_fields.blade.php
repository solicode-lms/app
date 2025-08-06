{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eMetadataDefinition-form')
<form 
    class="crud-form custom-form context-state container" 
    id="eMetadataDefinitionForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('eMetadataDefinitions.bulkUpdate') : ($itemEMetadataDefinition->id ? route('eMetadataDefinitions.update', $itemEMetadataDefinition->id) : route('eMetadataDefinitions.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEMetadataDefinition->id)
        <input type="hidden" name="id" value="{{ $itemEMetadataDefinition->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($eMetadataDefinition_ids))
        @foreach ($eMetadataDefinition_ids as $id)
            <input type="hidden" name="eMetadataDefinition_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadataDefinition" field="name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="name" id="bulk_field_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="name">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('PkgGapp::eMetadataDefinition.name') }}"
                value="{{ $itemEMetadataDefinition ? $itemEMetadataDefinition->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadataDefinition" field="groupe" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="groupe" id="bulk_field_groupe" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="groupe">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.groupe')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="groupe"
                type="input"
                class="form-control"
                required
                
                
                id="groupe"
                placeholder="{{ __('PkgGapp::eMetadataDefinition.groupe') }}"
                value="{{ $itemEMetadataDefinition ? $itemEMetadataDefinition->groupe : old('groupe') }}">
          @error('groupe')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadataDefinition" field="type" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="type" id="bulk_field_type" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="type">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.type')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="type"
                type="input"
                class="form-control"
                required
                
                
                id="type"
                placeholder="{{ __('PkgGapp::eMetadataDefinition.type') }}"
                value="{{ $itemEMetadataDefinition ? $itemEMetadataDefinition->type : old('type') }}">
          @error('type')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadataDefinition" field="scope" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="scope" id="bulk_field_scope" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="scope">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.scope')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="scope"
                type="input"
                class="form-control"
                required
                
                
                id="scope"
                placeholder="{{ __('PkgGapp::eMetadataDefinition.scope') }}"
                value="{{ $itemEMetadataDefinition ? $itemEMetadataDefinition->scope : old('scope') }}">
          @error('scope')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadataDefinition" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgGapp::eMetadataDefinition.description') }}">{{ $itemEMetadataDefinition ? $itemEMetadataDefinition->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadataDefinition" field="default_value" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="default_value" id="bulk_field_default_value" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="default_value">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.default_value')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="default_value"
                class="form-control "
                
                
                
                id="default_value"
                placeholder="{{ __('PkgGapp::eMetadataDefinition.default_value') }}">{{ $itemEMetadataDefinition ? $itemEMetadataDefinition->default_value : old('default_value') }}</textarea>
          @error('default_value')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('eMetadataDefinitions.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEMetadataDefinition->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgGapp::eMetadataDefinition.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgGapp::eMetadataDefinition.singular") }} : {{$itemEMetadataDefinition}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
