{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('widgets.update', $item->id) : route('widgets.store') }}" method="POST">
    @csrf

    @if ($item->id)
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
                value="{{ $item ? $item->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="color">
                {{ ucfirst(__('PkgWidgets::widget.color')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="color"
                type="input"
                class="form-control"
                id="color"
                placeholder="{{ __('PkgWidgets::widget.color') }}"
                value="{{ $item ? $item->color : old('color') }}">
            @error('color')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="icon">
                {{ ucfirst(__('PkgWidgets::widget.icon')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="icon"
                type="input"
                class="form-control"
                id="icon"
                placeholder="{{ __('PkgWidgets::widget.icon') }}"
                value="{{ $item ? $item->icon : old('icon') }}">
            @error('icon')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="label">
                {{ ucfirst(__('PkgWidgets::widget.label')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="label"
                type="input"
                class="form-control"
                id="label"
                placeholder="{{ __('PkgWidgets::widget.label') }}"
                value="{{ $item ? $item->label : old('label') }}">
            @error('label')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="parameters">
                {{ ucfirst(__('PkgWidgets::widget.parameters')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="parameters"
                type="input"
                class="form-control"
                id="parameters"
                placeholder="{{ __('PkgWidgets::widget.parameters') }}"
                value="{{ $item ? $item->parameters : old('parameters') }}">
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
        <a href="{{ route('widgets.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'model_id',
            fetchUrl: "{{ route('sysModels.all') }}",
            selectedValue: {{ $item->model_id ? $item->model_id : 'undefined' }},
            fieldValue: 'id'
        },
        
        {
            fieldId: 'operation_id',
            fetchUrl: "{{ route('widgetOperations.all') }}",
            selectedValue: {{ $item->operation_id ? $item->operation_id : 'undefined' }},
            fieldValue: 'operation'
        },
        
        {
            fieldId: 'type_id',
            fetchUrl: "{{ route('widgetTypes.all') }}",
            selectedValue: {{ $item->type_id ? $item->type_id : 'undefined' }},
            fieldValue: 'type'
        }
        
    ];
</script>
