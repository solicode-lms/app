{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="sysModelForm" action="{{ $itemSysModel->id ? route('sysModels.update', $itemSysModel->id) : route('sysModels.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemSysModel->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        
        <div class="form-group">
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
        
        
        
        <div class="form-group">
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
        
        
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('Core::sysModel.description')) }}
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                
                id="description"
                placeholder="{{ __('Core::sysModel.description') }}"
                value="{{ $itemSysModel ? $itemSysModel->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        
    <div class="form-group">
            <label for="module_id">
                {{ ucfirst(__('Core::sysModule.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="module_id" 
            name="module_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysModules as $sysModule)
                    <option value="{{ $sysModule->id }}"
                        {{ (isset($itemSysModel) && $itemSysModel->module_id == $sysModule->id) || (old('module_id>') == $sysModule->id) ? 'selected' : '' }}>
                        {{ $sysModule }}
                    </option>
                @endforeach
            </select>
            @error('module_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

        
        
        
        
    <div class="form-group">
            <label for="color_id">
                {{ ucfirst(__('Core::sysColor.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="color_id" 
            name="color_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysColors as $sysColor)
                    <option value="{{ $sysColor->id }}"
                        {{ (isset($itemSysModel) && $itemSysModel->color_id == $sysColor->id) || (old('color_id>') == $sysColor->id) ? 'selected' : '' }}>
                        {{ $sysColor }}
                    </option>
                @endforeach
            </select>
            @error('color_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

        
        
        
        <!--   Widget_HasMany HasMany --> 
        
        
    </div>

    <div class="card-footer">
        <a href="{{ route('sysModels.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSysModel->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


