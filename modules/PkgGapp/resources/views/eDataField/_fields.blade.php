{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eDataField-form')
<form class="crud-form custom-form context-state container" id="eDataFieldForm" action="{{ $itemEDataField->id ? route('eDataFields.update', $itemEDataField->id) : route('eDataFields.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemEDataField->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-3">
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
  


      <div class="form-group col-12 col-md-3">
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
  


      <div class="form-group col-12 col-md-6">
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
  


      <div class="form-group col-12 col-md-3">
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
  


      <div class="form-group col-12 col-md-3">
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
  


      <div class="form-group col-12 col-md-3">
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
  


      <div class="form-group col-12 col-md-3">
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
  


      <div class="form-group col-12 col-md-2">
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
  


      <div class="form-group col-12 col-md-2">
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
  


      <div class="form-group col-12 col-md-2">
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
  


      <div class="form-group col-12 col-md-2">
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
  


<!--   EMetadatum HasMany --> 


      <div class="form-group col-12 col-md-12">
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
  


      <div class="form-group col-12 col-md-12">
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
     window.modalTitle = '{{__("PkgGapp::eDataField.singular") }} : {{$itemEDataField}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
