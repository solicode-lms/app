{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eRelationship-form')
<form 
    class="crud-form custom-form context-state container" 
    id="eRelationshipForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('eRelationships.bulkUpdate') : ($itemERelationship->id ? route('eRelationships.update', $itemERelationship->id) : route('eRelationships.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemERelationship->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($eRelationship_ids))
        @foreach ($eRelationship_ids as $id)
            <input type="hidden" name="eRelationship_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="name" id="bulk_field_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="name">
            {{ ucfirst(__('PkgGapp::eRelationship.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('PkgGapp::eRelationship.name') }}"
                value="{{ $itemERelationship ? $itemERelationship->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="type" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="type" id="bulk_field_type" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="type">
            {{ ucfirst(__('PkgGapp::eRelationship.type')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="type"
                type="input"
                class="form-control"
                required
                
                
                id="type"
                placeholder="{{ __('PkgGapp::eRelationship.type') }}"
                value="{{ $itemERelationship ? $itemERelationship->type : old('type') }}">
          @error('type')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="source_e_model_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="source_e_model_id" id="bulk_field_source_e_model_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="source_e_model_id">
            {{ ucfirst(__('PkgGapp::eModel.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="source_e_model_id" 
            required
            
            
            name="source_e_model_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eModels as $eModel)
                    <option value="{{ $eModel->id }}"
                        {{ (isset($itemERelationship) && $itemERelationship->source_e_model_id == $eModel->id) || (old('source_e_model_id>') == $eModel->id) ? 'selected' : '' }}>
                        {{ $eModel }}
                    </option>
                @endforeach
            </select>
          @error('source_e_model_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="target_e_model_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="target_e_model_id" id="bulk_field_target_e_model_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="target_e_model_id">
            {{ ucfirst(__('PkgGapp::eModel.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="target_e_model_id" 
            required
            
            
            name="target_e_model_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eModels as $eModel)
                    <option value="{{ $eModel->id }}"
                        {{ (isset($itemERelationship) && $itemERelationship->target_e_model_id == $eModel->id) || (old('target_e_model_id>') == $eModel->id) ? 'selected' : '' }}>
                        {{ $eModel }}
                    </option>
                @endforeach
            </select>
          @error('target_e_model_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="cascade_on_delete" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="cascade_on_delete" id="bulk_field_cascade_on_delete" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="cascade_on_delete">
            {{ ucfirst(__('PkgGapp::eRelationship.cascade_on_delete')) }}
            <span class="text-danger">*</span>
          </label>
                      <input type="hidden" name="cascade_on_delete" value="0">
            <input
                name="cascade_on_delete"
                type="checkbox"
                class="form-control"
                required
                
                
                id="cascade_on_delete"
                value="1"
                {{ old('cascade_on_delete', $itemERelationship ? $itemERelationship->cascade_on_delete : 0) ? 'checked' : '' }}>
          @error('cascade_on_delete')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="is_cascade" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="is_cascade" id="bulk_field_is_cascade" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_cascade">
            {{ ucfirst(__('PkgGapp::eRelationship.is_cascade')) }}
            <span class="text-danger">*</span>
          </label>
                      <input type="hidden" name="is_cascade" value="0">
            <input
                name="is_cascade"
                type="checkbox"
                class="form-control"
                required
                
                
                id="is_cascade"
                value="1"
                {{ old('is_cascade', $itemERelationship ? $itemERelationship->is_cascade : 0) ? 'checked' : '' }}>
          @error('is_cascade')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgGapp::eRelationship.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgGapp::eRelationship.description') }}">{{ $itemERelationship ? $itemERelationship->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="column_name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="column_name" id="bulk_field_column_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="column_name">
            {{ ucfirst(__('PkgGapp::eRelationship.column_name')) }}
            
          </label>
           <input
                name="column_name"
                type="input"
                class="form-control"
                
                
                
                id="column_name"
                placeholder="{{ __('PkgGapp::eRelationship.column_name') }}"
                value="{{ $itemERelationship ? $itemERelationship->column_name : old('column_name') }}">
          @error('column_name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="referenced_table" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="referenced_table" id="bulk_field_referenced_table" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="referenced_table">
            {{ ucfirst(__('PkgGapp::eRelationship.referenced_table')) }}
            
          </label>
           <input
                name="referenced_table"
                type="input"
                class="form-control"
                
                
                
                id="referenced_table"
                placeholder="{{ __('PkgGapp::eRelationship.referenced_table') }}"
                value="{{ $itemERelationship ? $itemERelationship->referenced_table : old('referenced_table') }}">
          @error('referenced_table')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="referenced_column" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="referenced_column" id="bulk_field_referenced_column" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="referenced_column">
            {{ ucfirst(__('PkgGapp::eRelationship.referenced_column')) }}
            
          </label>
           <input
                name="referenced_column"
                type="input"
                class="form-control"
                
                
                
                id="referenced_column"
                placeholder="{{ __('PkgGapp::eRelationship.referenced_column') }}"
                value="{{ $itemERelationship ? $itemERelationship->referenced_column : old('referenced_column') }}">
          @error('referenced_column')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="through" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="through" id="bulk_field_through" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="through">
            {{ ucfirst(__('PkgGapp::eRelationship.through')) }}
            
          </label>
           <input
                name="through"
                type="input"
                class="form-control"
                
                
                
                id="through"
                placeholder="{{ __('PkgGapp::eRelationship.through') }}"
                value="{{ $itemERelationship ? $itemERelationship->through : old('through') }}">
          @error('through')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="with_column" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="with_column" id="bulk_field_with_column" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="with_column">
            {{ ucfirst(__('PkgGapp::eRelationship.with_column')) }}
            
          </label>
           <input
                name="with_column"
                type="input"
                class="form-control"
                
                
                
                id="with_column"
                placeholder="{{ __('PkgGapp::eRelationship.with_column') }}"
                value="{{ $itemERelationship ? $itemERelationship->with_column : old('with_column') }}">
          @error('with_column')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemERelationship" field="morph_name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="morph_name" id="bulk_field_morph_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="morph_name">
            {{ ucfirst(__('PkgGapp::eRelationship.morph_name')) }}
            
          </label>
           <input
                name="morph_name"
                type="input"
                class="form-control"
                
                
                
                id="morph_name"
                placeholder="{{ __('PkgGapp::eRelationship.morph_name') }}"
                value="{{ $itemERelationship ? $itemERelationship->morph_name : old('morph_name') }}">
          @error('morph_name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('eRelationships.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemERelationship->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgGapp::eRelationship.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgGapp::eRelationship.singular") }} : {{$itemERelationship}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
