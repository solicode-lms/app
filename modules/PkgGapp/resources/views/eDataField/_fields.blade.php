{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eDataField-form')
<form 
    class="crud-form custom-form context-state container" 
    id="eDataFieldForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('eDataFields.bulkUpdate') : ($itemEDataField->id ? route('eDataFields.update', $itemEDataField->id) : route('eDataFields.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEDataField->id)
        <input type="hidden" name="id" value="{{ $itemEDataField->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($eDataField_ids))
        @foreach ($eDataField_ids as $id)
            <input type="hidden" name="eDataField_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="name" id="bulk_field_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="name">
            {{ ucfirst(__('PkgGapp::eDataField.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('PkgGapp::eDataField.name') }}"
                value="{{ $itemEDataField ? $itemEDataField->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="e_model_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="e_model_id" id="bulk_field_e_model_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="e_model_id">
            {{ ucfirst(__('PkgGapp::eModel.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="e_model_id" 
            required
            
            
            name="e_model_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eModels as $eModel)
                    <option value="{{ $eModel->id }}"
                        {{ (isset($itemEDataField) && $itemEDataField->e_model_id == $eModel->id) || (old('e_model_id>') == $eModel->id) ? 'selected' : '' }}>
                        {{ $eModel }}
                    </option>
                @endforeach
            </select>
          @error('e_model_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="data_type" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="data_type" id="bulk_field_data_type" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="data_type">
            {{ ucfirst(__('PkgGapp::eDataField.data_type')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="data_type"
                type="input"
                class="form-control"
                required
                
                
                id="data_type"
                placeholder="{{ __('PkgGapp::eDataField.data_type') }}"
                value="{{ $itemEDataField ? $itemEDataField->data_type : old('data_type') }}">
          @error('data_type')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="default_value" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="default_value" id="bulk_field_default_value" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="default_value">
            {{ ucfirst(__('PkgGapp::eDataField.default_value')) }}
            
          </label>
           <input
                name="default_value"
                type="input"
                class="form-control"
                
                
                
                id="default_value"
                placeholder="{{ __('PkgGapp::eDataField.default_value') }}"
                value="{{ $itemEDataField ? $itemEDataField->default_value : old('default_value') }}">
          @error('default_value')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="column_name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="column_name" id="bulk_field_column_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="column_name">
            {{ ucfirst(__('PkgGapp::eDataField.column_name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="column_name"
                type="input"
                class="form-control"
                required
                
                
                id="column_name"
                placeholder="{{ __('PkgGapp::eDataField.column_name') }}"
                value="{{ $itemEDataField ? $itemEDataField->column_name : old('column_name') }}">
          @error('column_name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="e_relationship_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="e_relationship_id" id="bulk_field_e_relationship_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="e_relationship_id">
            {{ ucfirst(__('PkgGapp::eRelationship.singular')) }}
            
          </label>
                      <select 
            id="e_relationship_id" 
            
            
            
            name="e_relationship_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eRelationships as $eRelationship)
                    <option value="{{ $eRelationship->id }}"
                        {{ (isset($itemEDataField) && $itemEDataField->e_relationship_id == $eRelationship->id) || (old('e_relationship_id>') == $eRelationship->id) ? 'selected' : '' }}>
                        {{ $eRelationship }}
                    </option>
                @endforeach
            </select>
          @error('e_relationship_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="field_order" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="field_order" id="bulk_field_field_order" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="field_order">
            {{ ucfirst(__('PkgGapp::eDataField.field_order')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="field_order"
                type="number"
                class="form-control"
                required
                
                
                id="field_order"
                placeholder="{{ __('PkgGapp::eDataField.field_order') }}"
                value="{{ $itemEDataField ? $itemEDataField->field_order : old('field_order') }}">
          @error('field_order')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="db_primaryKey" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-2">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="db_primaryKey" id="bulk_field_db_primaryKey" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="db_primaryKey">
            {{ ucfirst(__('PkgGapp::eDataField.db_primaryKey')) }}
            
          </label>
                      <input type="hidden" name="db_primaryKey" value="0">
            <input
                name="db_primaryKey"
                type="checkbox"
                class="form-control"
                
                
                
                id="db_primaryKey"
                value="1"
                {{ old('db_primaryKey', $itemEDataField ? $itemEDataField->db_primaryKey : 0) ? 'checked' : '' }}>
          @error('db_primaryKey')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="db_nullable" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-2">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="db_nullable" id="bulk_field_db_nullable" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="db_nullable">
            {{ ucfirst(__('PkgGapp::eDataField.db_nullable')) }}
            
          </label>
                      <input type="hidden" name="db_nullable" value="0">
            <input
                name="db_nullable"
                type="checkbox"
                class="form-control"
                
                
                
                id="db_nullable"
                value="1"
                {{ old('db_nullable', $itemEDataField ? $itemEDataField->db_nullable : 0) ? 'checked' : '' }}>
          @error('db_nullable')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="db_unique" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-2">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="db_unique" id="bulk_field_db_unique" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="db_unique">
            {{ ucfirst(__('PkgGapp::eDataField.db_unique')) }}
            
          </label>
                      <input type="hidden" name="db_unique" value="0">
            <input
                name="db_unique"
                type="checkbox"
                class="form-control"
                
                
                
                id="db_unique"
                value="1"
                {{ old('db_unique', $itemEDataField ? $itemEDataField->db_unique : 0) ? 'checked' : '' }}>
          @error('db_unique')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="calculable" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-2">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="calculable" id="bulk_field_calculable" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="calculable">
            {{ ucfirst(__('PkgGapp::eDataField.calculable')) }}
            
          </label>
                      <input type="hidden" name="calculable" value="0">
            <input
                name="calculable"
                type="checkbox"
                class="form-control"
                
                
                
                id="calculable"
                value="1"
                {{ old('calculable', $itemEDataField ? $itemEDataField->calculable : 0) ? 'checked' : '' }}>
          @error('calculable')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="calculable_sql" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="calculable_sql" id="bulk_field_calculable_sql" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="calculable_sql">
            {{ ucfirst(__('PkgGapp::eDataField.calculable_sql')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="calculable_sql"
                class="form-control "
                
                
                
                id="calculable_sql"
                placeholder="{{ __('PkgGapp::eDataField.calculable_sql') }}">{{ $itemEDataField ? $itemEDataField->calculable_sql : old('calculable_sql') }}</textarea>
          @error('calculable_sql')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEDataField" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgGapp::eDataField.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgGapp::eDataField.description') }}">{{ $itemEDataField ? $itemEDataField->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('eDataFields.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEDataField->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgGapp::eDataField.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgGapp::eDataField.singular") }} : {{$itemEDataField}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
