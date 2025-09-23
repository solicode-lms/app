{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysController-form')
<form 
    class="crud-form custom-form context-state container" 
    id="sysControllerForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('sysControllers.bulkUpdate') : ($itemSysController->id ? route('sysControllers.update', $itemSysController->id) : route('sysControllers.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemSysController->id)
        <input type="hidden" name="id" value="{{ $itemSysController->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($sysController_ids))
        @foreach ($sysController_ids as $id)
            <input type="hidden" name="sysController_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysController" field="sys_module_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="sys_module_id" 
              id="bulk_field_sys_module_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="sys_module_id">
            {{ ucfirst(__('Core::sysModule.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="sys_module_id" 
            required
            
            
            name="sys_module_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysModules as $sysModule)
                    <option value="{{ $sysModule->id }}"
                        {{ (isset($itemSysController) && $itemSysController->sys_module_id == $sysModule->id) || (old('sys_module_id>') == $sysModule->id) ? 'selected' : '' }}>
                        {{ $sysModule }}
                    </option>
                @endforeach
            </select>
          @error('sys_module_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysController" field="name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="name" 
              id="bulk_field_name" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="name">
            {{ ucfirst(__('Core::sysController.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('Core::sysController.name') }}"
                value="{{ $itemSysController ? $itemSysController->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysController" field="slug" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="slug" 
              id="bulk_field_slug" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="slug">
            {{ ucfirst(__('Core::sysController.slug')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="slug"
                type="input"
                class="form-control"
                required
                
                
                id="slug"
                placeholder="{{ __('Core::sysController.slug') }}"
                value="{{ $itemSysController ? $itemSysController->slug : old('slug') }}">
          @error('slug')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysController" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
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
            {{ ucfirst(__('Core::sysController.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('Core::sysController.description') }}">{{ $itemSysController ? $itemSysController->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysController" field="is_active" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="is_active" 
              id="bulk_field_is_active" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_active">
            {{ ucfirst(__('Core::sysController.is_active')) }}
            <span class="text-danger">*</span>
          </label>
                      <input type="hidden" name="is_active" value="0">
            <input
                name="is_active"
                type="checkbox"
                class="form-control d-block"
                required
                
                
                id="is_active"
                value="1"
                {{ old('is_active', $itemSysController ? $itemSysController->is_active : 0) ? 'checked' : '' }}>
          @error('is_active')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('sysControllers.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSysController->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("Core::sysController.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("Core::sysController.singular") }} : {{$itemSysController}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
