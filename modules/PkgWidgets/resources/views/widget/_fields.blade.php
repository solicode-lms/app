{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widget-form')
<form 
    class="crud-form custom-form context-state container" 
    id="widgetForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('widgets.bulkUpdate') : ($itemWidget->id ? route('widgets.update', $itemWidget->id) : route('widgets.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemWidget->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($widget_ids))
        @foreach ($widget_ids as $id)
            <input type="hidden" name="widget_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemWidget" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-2">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgWidgets::widget.ordre')) }}
            
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                
                
                
                id="ordre"
                placeholder="{{ __('PkgWidgets::widget.ordre') }}"
                value="{{ $itemWidget ? $itemWidget->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemWidget" field="icon" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="icon" id="bulk_field_icon" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="icon">
            {{ ucfirst(__('PkgWidgets::widget.icon')) }}
            
          </label>
           <input
                name="icon"
                type="input"
                class="form-control"
                
                
                
                id="icon"
                placeholder="{{ __('PkgWidgets::widget.icon') }}"
                value="{{ $itemWidget ? $itemWidget->icon : old('icon') }}">
          @error('icon')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemWidget" field="name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-4">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="name" id="bulk_field_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="name">
            {{ ucfirst(__('PkgWidgets::widget.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('PkgWidgets::widget.name') }}"
                value="{{ $itemWidget ? $itemWidget->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemWidget" field="label" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="label" id="bulk_field_label" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="label">
            {{ ucfirst(__('PkgWidgets::widget.label')) }}
            
          </label>
           <input
                name="label"
                type="input"
                class="form-control"
                
                
                
                id="label"
                placeholder="{{ __('PkgWidgets::widget.label') }}"
                value="{{ $itemWidget ? $itemWidget->label : old('label') }}">
          @error('label')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemWidget" field="type_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="type_id" id="bulk_field_type_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="type_id">
            {{ ucfirst(__('PkgWidgets::widgetType.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="type_id" 
            required
            
            
            name="type_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($widgetTypes as $widgetType)
                    <option value="{{ $widgetType->id }}"
                        {{ (isset($itemWidget) && $itemWidget->type_id == $widgetType->id) || (old('type_id>') == $widgetType->id) ? 'selected' : '' }}>
                        {{ $widgetType }}
                    </option>
                @endforeach
            </select>
          @error('type_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemWidget" field="model_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="model_id" id="bulk_field_model_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="model_id">
            {{ ucfirst(__('Core::sysModel.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="model_id" 
            required
            
            
            name="model_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysModels as $sysModel)
                    <option value="{{ $sysModel->id }}"
                        {{ (isset($itemWidget) && $itemWidget->model_id == $sysModel->id) || (old('model_id>') == $sysModel->id) ? 'selected' : '' }}>
                        {{ $sysModel }}
                    </option>
                @endforeach
            </select>
          @error('model_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemWidget" field="operation_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="operation_id" id="bulk_field_operation_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="operation_id">
            {{ ucfirst(__('PkgWidgets::widgetOperation.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="operation_id" 
            required
            
            
            name="operation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($widgetOperations as $widgetOperation)
                    <option value="{{ $widgetOperation->id }}"
                        {{ (isset($itemWidget) && $itemWidget->operation_id == $widgetOperation->id) || (old('operation_id>') == $widgetOperation->id) ? 'selected' : '' }}>
                        {{ $widgetOperation }}
                    </option>
                @endforeach
            </select>
          @error('operation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemWidget" field="sys_color_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="sys_color_id" id="bulk_field_sys_color_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
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
                        {{ (isset($itemWidget) && $itemWidget->sys_color_id == $sysColor->id) || (old('sys_color_id>') == $sysColor->id) ? 'selected' : '' }}>
                        {{ $sysColor }}
                    </option>
                @endforeach
            </select>
          @error('sys_color_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemWidget" field="roles" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="roles" id="bulk_field_roles" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="roles">
            {{ ucfirst(__('PkgAutorisation::role.plural')) }}
            
          </label>
                      <select
                id="roles"
                name="roles[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ (isset($itemWidget) && $itemWidget->roles && $itemWidget->roles->contains('id', $role->id)) || (is_array(old('roles')) && in_array($role->id, old('roles'))) ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>
          @error('roles')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemWidget" field="section_widget_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="section_widget_id" id="bulk_field_section_widget_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="section_widget_id">
            {{ ucfirst(__('PkgWidgets::sectionWidget.singular')) }}
            
          </label>
                      <select 
            id="section_widget_id" 
            
            
            
            name="section_widget_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sectionWidgets as $sectionWidget)
                    <option value="{{ $sectionWidget->id }}"
                        {{ (isset($itemWidget) && $itemWidget->section_widget_id == $sectionWidget->id) || (old('section_widget_id>') == $sectionWidget->id) ? 'selected' : '' }}>
                        {{ $sectionWidget }}
                    </option>
                @endforeach
            </select>
          @error('section_widget_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemWidget" field="parameters" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="parameters" id="bulk_field_parameters" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="parameters">
            {{ ucfirst(__('PkgWidgets::widget.parameters')) }}
            
          </label>
              <div class="form-control editeur_json code-editor"
        contenteditable="true">{{ $itemWidget ? $itemWidget->parameters : old('parameters') }}</div>
    <input
        type="hidden"
        name="parameters"
        class="form-control"
        id="parameters"
         
        
        
        value = "{{ $itemWidget ? $itemWidget->parameters : old('parameters') }}"
    >
          @error('parameters')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('widgets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemWidget->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgWidgets::widget.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgWidgets::widget.singular") }} : {{$itemWidget}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
