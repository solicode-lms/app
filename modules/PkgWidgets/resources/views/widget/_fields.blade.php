{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="widgetForm" action="{{ $itemWidget->id ? route('widgets.update', $itemWidget->id) : route('widgets.store') }}" method="POST">
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
                id="name"
                placeholder="{{ __('PkgWidgets::widget.name') }}"
                value="{{ $itemWidget ? $itemWidget->name : old('name') }}">
            @error('name')
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
            <input
                name="parameters"
                type="input"
                class="form-control"
                id="parameters"
                placeholder="{{ __('PkgWidgets::widget.parameters') }}"
                value="{{ $itemWidget ? $itemWidget->parameters : old('parameters') }}">
            @error('parameters')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="model_id">
                {{ ucfirst(__('Core::sysModel.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="model_id" name="model_id" class="form-control">
                <option value="">Sélectionnez une option</option>
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
            <select id="operation_id" name="operation_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('operation_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="type_id">
                {{ ucfirst(__('PkgWidgets::widgetType.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="type_id" name="type_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('type_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        



    </div>


    <div class="card-footer">
        <a href="{{ route('widgets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemWidget->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'model_id',
            fetchUrl: "{{ route('sysModels.all') }}",
            selectedValue: {{ $itemWidget->model_id ? $itemWidget->model_id : 'undefined' }},
            fieldValue: 'name'
        },
        
        {
            fieldId: 'operation_id',
            fetchUrl: "{{ route('widgetOperations.all') }}",
            selectedValue: {{ $itemWidget->operation_id ? $itemWidget->operation_id : 'undefined' }},
            fieldValue: 'operation'
        },
        
        {
            fieldId: 'type_id',
            fetchUrl: "{{ route('widgetTypes.all') }}",
            selectedValue: {{ $itemWidget->type_id ? $itemWidget->type_id : 'undefined' }},
            fieldValue: 'type'
        }
        
    ];
</script>


