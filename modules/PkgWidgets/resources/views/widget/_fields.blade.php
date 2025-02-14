{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widget-form')
<form class="crud-form custom-form context-state" id="widgetForm" action="{{ $itemWidget->id ? route('widgets.update', $itemWidget->id) : route('widgets.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemWidget->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
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

        
        
    <div class="form-group">
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


        
        
    <div class="form-group">
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


        
        
    <div class="form-group">
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


        
        <div class="form-group">
            <label for="color">
                {{ ucfirst(__('PkgWidgets::widget.color')) }}
                
            </label>
            <input
                name="color"
                type="input"
                class="form-control"
                
                
                id="color"
                placeholder="{{ __('PkgWidgets::widget.color') }}"
                value="{{ $itemWidget ? $itemWidget->color : old('color') }}">
            @error('color')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
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

        
        <div class="form-group">
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

        
        <div class="form-group">
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
     window.modalTitle = '{{__("PkgWidgets::widget.singular") }} : {{$itemWidget}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
