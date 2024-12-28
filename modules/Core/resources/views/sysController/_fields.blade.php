{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="sysControllerForm" action="{{ $itemSysController->id ? route('sysControllers.update', $itemSysController->id) : route('sysControllers.store') }}" method="POST">
    @csrf

    @if ($itemSysController->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('Core::sysController.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                id="name"
                placeholder="{{ __('Core::sysController.name') }}"
                value="{{ $itemSysController ? $itemSysController->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="slug">
                {{ ucfirst(__('Core::sysController.slug')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="slug"
                type="input"
                class="form-control"
                id="slug"
                placeholder="{{ __('Core::sysController.slug') }}"
                value="{{ $itemSysController ? $itemSysController->slug : old('slug') }}">
            @error('slug')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('Core::sysController.description')) }}
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                id="description"
                placeholder="{{ __('Core::sysController.description') }}"
                value="{{ $itemSysController ? $itemSysController->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="is_active">
                {{ ucfirst(__('Core::sysController.is_active')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="is_active"
                type="input"
                class="form-control"
                id="is_active"
                placeholder="{{ __('Core::sysController.is_active') }}"
                value="{{ $itemSysController ? $itemSysController->is_active : old('is_active') }}">
            @error('is_active')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="module_id">
                {{ ucfirst(__('Core::sysModule.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="module_id" name="module_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('module_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        



    </div>


    <div class="card-footer">
        <a href="{{ route('sysControllers.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSysController->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'module_id',
            fetchUrl: "{{ route('sysModules.all') }}",
            selectedValue: {{ $itemSysController->module_id ? $itemSysController->module_id : 'undefined' }},
            fieldValue: 'name'
        }
        
    ];
</script>


