{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eMetadatum-form')
<form 
    class="crud-form custom-form context-state container" 
    id="eMetadatumForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('eMetadata.bulkUpdate') : ($itemEMetadatum->id ? route('eMetadata.update', $itemEMetadatum->id) : route('eMetadata.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEMetadatum->id)
        <input type="hidden" name="id" value="{{ $itemEMetadatum->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($eMetadatum_ids))
        @foreach ($eMetadatum_ids as $id)
            <input type="hidden" name="eMetadatum_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadatum" field="value_boolean" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="value_boolean" id="bulk_field_value_boolean" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="value_boolean">
            {{ ucfirst(__('PkgGapp::eMetadatum.value_boolean')) }}
            
          </label>
                      <input type="hidden" name="value_boolean" value="0">
            <input
                name="value_boolean"
                type="checkbox"
                class="form-control"
                
                
                
                id="value_boolean"
                value="1"
                {{ old('value_boolean', $itemEMetadatum ? $itemEMetadatum->value_boolean : 0) ? 'checked' : '' }}>
          @error('value_boolean')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadatum" field="value_string" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="value_string" id="bulk_field_value_string" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="value_string">
            {{ ucfirst(__('PkgGapp::eMetadatum.value_string')) }}
            
          </label>
           <input
                name="value_string"
                type="input"
                class="form-control"
                
                
                
                id="value_string"
                placeholder="{{ __('PkgGapp::eMetadatum.value_string') }}"
                value="{{ $itemEMetadatum ? $itemEMetadatum->value_string : old('value_string') }}">
          @error('value_string')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadatum" field="value_integer" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="value_integer" id="bulk_field_value_integer" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="value_integer">
            {{ ucfirst(__('PkgGapp::eMetadatum.value_integer')) }}
            
          </label>
                      <input
                name="value_integer"
                type="number"
                class="form-control"
                
                
                
                id="value_integer"
                placeholder="{{ __('PkgGapp::eMetadatum.value_integer') }}"
                value="{{ $itemEMetadatum ? $itemEMetadatum->value_integer : old('value_integer') }}">
          @error('value_integer')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadatum" field="value_float" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="value_float" id="bulk_field_value_float" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="value_float">
            {{ ucfirst(__('PkgGapp::eMetadatum.value_float')) }}
            
          </label>
              <input
        name="value_float"
        type="number"
        class="form-control"
        
        
        
        id="value_float"
        step="0.01"
        placeholder="{{ __('PkgGapp::eMetadatum.value_float') }}"
        value="{{ $itemEMetadatum ? number_format($itemEMetadatum->value_float, 2, '.', '') : old('value_float') }}">
          @error('value_float')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadatum" field="value_date" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="value_date" id="bulk_field_value_date" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="value_date">
            {{ ucfirst(__('PkgGapp::eMetadatum.value_date')) }}
            
          </label>
                      <input
                name="value_date"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="value_date"
                placeholder="{{ __('PkgGapp::eMetadatum.value_date') }}"
                value="{{ $itemEMetadatum ? $itemEMetadatum->value_date : old('value_date') }}">

          @error('value_date')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadatum" field="value_datetime" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="value_datetime" id="bulk_field_value_datetime" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="value_datetime">
            {{ ucfirst(__('PkgGapp::eMetadatum.value_datetime')) }}
            
          </label>
                      <input
                name="value_datetime"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="value_datetime"
                placeholder="{{ __('PkgGapp::eMetadatum.value_datetime') }}"
                value="{{ $itemEMetadatum ? $itemEMetadatum->value_datetime : old('value_datetime') }}">

          @error('value_datetime')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadatum" field="value_enum" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="value_enum" id="bulk_field_value_enum" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="value_enum">
            {{ ucfirst(__('PkgGapp::eMetadatum.value_enum')) }}
            
          </label>
           <input
                name="value_enum"
                type="input"
                class="form-control"
                
                
                
                id="value_enum"
                placeholder="{{ __('PkgGapp::eMetadatum.value_enum') }}"
                value="{{ $itemEMetadatum ? $itemEMetadatum->value_enum : old('value_enum') }}">
          @error('value_enum')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadatum" field="value_json" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="value_json" id="bulk_field_value_json" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="value_json">
            {{ ucfirst(__('PkgGapp::eMetadatum.value_json')) }}
            
          </label>
              <div class="form-control editeur_json code-editor"
        contenteditable="true">{{ $itemEMetadatum ? $itemEMetadatum->value_json : old('value_json') }}</div>
    <input
        type="hidden"
        name="value_json"
        class="form-control"
        id="value_json"
         
        
        
        value = "{{ $itemEMetadatum ? $itemEMetadatum->value_json : old('value_json') }}"
    >
          @error('value_json')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadatum" field="value_text" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="value_text" id="bulk_field_value_text" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="value_text">
            {{ ucfirst(__('PkgGapp::eMetadatum.value_text')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="value_text"
                class="form-control richText"
                
                
                
                id="value_text"
                placeholder="{{ __('PkgGapp::eMetadatum.value_text') }}">{{ $itemEMetadatum ? $itemEMetadatum->value_text : old('value_text') }}</textarea>
          @error('value_text')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadatum" field="e_model_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="e_model_id" id="bulk_field_e_model_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="e_model_id">
            {{ ucfirst(__('PkgGapp::eModel.singular')) }}
            
          </label>
                      <select 
            id="e_model_id" 
            
            
            
            name="e_model_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eModels as $eModel)
                    <option value="{{ $eModel->id }}"
                        {{ (isset($itemEMetadatum) && $itemEMetadatum->e_model_id == $eModel->id) || (old('e_model_id>') == $eModel->id) ? 'selected' : '' }}>
                        {{ $eModel }}
                    </option>
                @endforeach
            </select>
          @error('e_model_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadatum" field="e_data_field_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="e_data_field_id" id="bulk_field_e_data_field_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="e_data_field_id">
            {{ ucfirst(__('PkgGapp::eDataField.singular')) }}
            
          </label>
                      <select 
            id="e_data_field_id" 
            
            
            
            name="e_data_field_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eDataFields as $eDataField)
                    <option value="{{ $eDataField->id }}"
                        {{ (isset($itemEMetadatum) && $itemEMetadatum->e_data_field_id == $eDataField->id) || (old('e_data_field_id>') == $eDataField->id) ? 'selected' : '' }}>
                        {{ $eDataField }}
                    </option>
                @endforeach
            </select>
          @error('e_data_field_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEMetadatum" field="e_metadata_definition_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="e_metadata_definition_id" id="bulk_field_e_metadata_definition_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="e_metadata_definition_id">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="e_metadata_definition_id" 
            required
            data-calcul='true'
            
            name="e_metadata_definition_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eMetadataDefinitions as $eMetadataDefinition)
                    <option value="{{ $eMetadataDefinition->id }}"
                        {{ (isset($itemEMetadatum) && $itemEMetadatum->e_metadata_definition_id == $eMetadataDefinition->id) || (old('e_metadata_definition_id>') == $eMetadataDefinition->id) ? 'selected' : '' }}>
                        {{ $eMetadataDefinition }}
                    </option>
                @endforeach
            </select>
          @error('e_metadata_definition_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('eMetadata.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEMetadatum->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

    if(!window.isDynamicFieldVisibility_e_metadata_definition_id_type_Initialized){
        window.isDynamicFieldVisibility_e_metadata_definition_id_type_Initialized = true;
        window.dynamicFieldVisibilityTreatments = window.dynamicFieldVisibilityTreatments || [];
    
        const fieldMappings = [
        {
                type: "String",
                fieldId: "value_string"
        },
        {
                type: "Boolean",
                fieldId: "value_boolean"
        },
        {
                type: "Integer",
                fieldId: "value_integer"
        },
        {
                type: "Object",
                fieldId: "value_object"
        },
        {
                type: "Float",
                fieldId: "value_float"
        },
        {
                type: "Date",
                fieldId: "value_date"
        },
        {
                type: "Datetime",
                fieldId: "value_datetime"
        },
        {
                type: "Enum",
                fieldId: "value_enum"
        },
        {
                type: "Json",
                fieldId: "value_json"
        },
        {
                type: "Text",
                fieldId: "value_text"
        }
];

        // Ajouter une configuration générique pour l'entité
        window.dynamicFieldVisibilityTreatments.push({
            dataDefinitions: @json($eMetadataDefinitions), // Les données associées
            targetDropdownId: 'e_metadata_definition_id', // L'ID du dropdown cible
            fieldMappings: fieldMappings,
            typeField: 'type'
        });
    }
</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgGapp::eMetadatum.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgGapp::eMetadatum.singular") }} : {{$itemEMetadatum}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
