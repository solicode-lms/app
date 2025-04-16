{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('userModelFilter-form')
<form 
    class="crud-form custom-form context-state container" 
    id="userModelFilterForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('userModelFilters.bulkUpdate') : ($itemUserModelFilter->id ? route('userModelFilters.update', $itemUserModelFilter->id) : route('userModelFilters.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemUserModelFilter->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($userModelFilter_ids))
        @foreach ($userModelFilter_ids as $id)
            <input type="hidden" name="userModelFilter_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="user_id" id="bulk_field_user_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="user_id">
            {{ ucfirst(__('PkgAutorisation::user.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="user_id" 
            required
            
            
            name="user_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ (isset($itemUserModelFilter) && $itemUserModelFilter->user_id == $user->id) || (old('user_id>') == $user->id) ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
          @error('user_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="model_name" id="bulk_field_model_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="model_name">
            {{ ucfirst(__('Core::userModelFilter.model_name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="model_name"
                type="input"
                class="form-control"
                required
                
                
                id="model_name"
                placeholder="{{ __('Core::userModelFilter.model_name') }}"
                value="{{ $itemUserModelFilter ? $itemUserModelFilter->model_name : old('model_name') }}">
          @error('model_name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="filters" id="bulk_field_filters" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="filters">
            {{ ucfirst(__('Core::userModelFilter.filters')) }}
            
          </label>
              <div class="form-control editeur_json code-editor"
        contenteditable="true">{{ $itemUserModelFilter ? $itemUserModelFilter->filters : old('filters') }}</div>
    <input
        type="hidden"
        name="filters"
        class="form-control"
        id="filters"
         
        
        
        value = "{{ $itemUserModelFilter ? $itemUserModelFilter->filters : old('filters') }}"
    >
          @error('filters')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('userModelFilters.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemUserModelFilter->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("Core::userModelFilter.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("Core::userModelFilter.singular") }} : {{$itemUserModelFilter}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
