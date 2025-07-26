{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysModule-form')
<form 
    class="crud-form custom-form context-state container" 
    id="sysModuleForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('sysModules.bulkUpdate') : ($itemSysModule->id ? route('sysModules.update', $itemSysModule->id) : route('sysModules.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemSysModule->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($sysModule_ids))
        @foreach ($sysModule_ids as $id)
            <input type="hidden" name="sysModule_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModule" field="name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="name" id="bulk_field_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="name">
            {{ ucfirst(__('Core::sysModule.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('Core::sysModule.name') }}"
                value="{{ $itemSysModule ? $itemSysModule->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModule" field="slug" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="slug" id="bulk_field_slug" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="slug">
            {{ ucfirst(__('Core::sysModule.slug')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="slug"
                type="input"
                class="form-control"
                required
                
                
                id="slug"
                placeholder="{{ __('Core::sysModule.slug') }}"
                value="{{ $itemSysModule ? $itemSysModule->slug : old('slug') }}">
          @error('slug')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModule" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('Core::sysModule.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('Core::sysModule.description') }}">{{ $itemSysModule ? $itemSysModule->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModule" field="is_active" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="is_active" id="bulk_field_is_active" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_active">
            {{ ucfirst(__('Core::sysModule.is_active')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="is_active"
                type="number"
                class="form-control"
                required
                
                
                id="is_active"
                placeholder="{{ __('Core::sysModule.is_active') }}"
                value="{{ $itemSysModule ? $itemSysModule->is_active : old('is_active') }}">
          @error('is_active')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModule" field="order" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="order" id="bulk_field_order" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="order">
            {{ ucfirst(__('Core::sysModule.order')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="order"
                type="number"
                class="form-control"
                required
                
                
                id="order"
                placeholder="{{ __('Core::sysModule.order') }}"
                value="{{ $itemSysModule ? $itemSysModule->order : old('order') }}">
          @error('order')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModule" field="version" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="version" id="bulk_field_version" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="version">
            {{ ucfirst(__('Core::sysModule.version')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="version"
                type="input"
                class="form-control"
                required
                
                
                id="version"
                placeholder="{{ __('Core::sysModule.version') }}"
                value="{{ $itemSysModule ? $itemSysModule->version : old('version') }}">
          @error('version')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModule" field="sys_color_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="sys_color_id" id="bulk_field_sys_color_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="sys_color_id">
            {{ ucfirst(__('Core::sysColor.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="sys_color_id" 
            required
            
            
            name="sys_color_id" 
            class="form-control select2Color">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysColors as $sysColor)
                    <option value="{{ $sysColor->id }}" data-color="{{ $sysColor->hex }}" 
                        {{ (isset($itemSysModule) && $itemSysModule->sys_color_id == $sysColor->id) || (old('sys_color_id>') == $sysColor->id) ? 'selected' : '' }}>
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
        <a href="{{ route('sysModules.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSysModule->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("Core::sysModule.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("Core::sysModule.singular") }} : {{$itemSysModule}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
