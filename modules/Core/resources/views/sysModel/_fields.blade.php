{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysModel-form')
<form 
    class="crud-form custom-form context-state container" 
    id="sysModelForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('sysModels.bulkUpdate') : ($itemSysModel->id ? route('sysModels.update', $itemSysModel->id) : route('sysModels.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemSysModel->id)
        <input type="hidden" name="id" value="{{ $itemSysModel->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($sysModel_ids))
        @foreach ($sysModel_ids as $id)
            <input type="hidden" name="sysModel_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModel" field="name" :bulkEdit="$bulkEdit">

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
            {{ ucfirst(__('Core::sysModel.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('Core::sysModel.name') }}"
                value="{{ $itemSysModel ? $itemSysModel->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModel" field="model" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="model" 
              id="bulk_field_model" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="model">
            {{ ucfirst(__('Core::sysModel.model')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="model"
                type="input"
                class="form-control"
                required
                
                
                id="model"
                placeholder="{{ __('Core::sysModel.model') }}"
                value="{{ $itemSysModel ? $itemSysModel->model : old('model') }}">
          @error('model')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModel" field="sys_module_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemSysModel) && $itemSysModel->sys_module_id == $sysModule->id) || (old('sys_module_id>') == $sysModule->id) ? 'selected' : '' }}>
                        {{ $sysModule }}
                    </option>
                @endforeach
            </select>
          @error('sys_module_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModel" field="sys_color_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemSysModel) && $itemSysModel->sys_color_id == $sysColor->id) || (old('sys_color_id>') == $sysColor->id) ? 'selected' : '' }}>
                        {{ $sysColor }}
                    </option>
                @endforeach
            </select>
          @error('sys_color_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModel" field="icone" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="icone" 
              id="bulk_field_icone" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="icone">
            {{ ucfirst(__('Core::sysModel.icone')) }}
            
          </label>
           <input
                name="icone"
                type="input"
                class="form-control"
                
                
                
                id="icone"
                placeholder="{{ __('Core::sysModel.icone') }}"
                value="{{ $itemSysModel ? $itemSysModel->icone : old('icone') }}">
          @error('icone')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSysModel" field="description" :bulkEdit="$bulkEdit">

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
            {{ ucfirst(__('Core::sysModel.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('Core::sysModel.description') }}">{{ $itemSysModel ? $itemSysModel->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('sysModels.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSysModel->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("Core::sysModel.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("Core::sysModel.singular") }} : {{$itemSysModel}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
