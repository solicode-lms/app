{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="sysModuleForm" action="{{ $itemSysModule->id ? route('sysModules.update', $itemSysModule->id) : route('sysModules.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemSysModule->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
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

        <div class="form-group">
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

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('Core::sysModule.description')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                required
                id="description"
                placeholder="{{ __('Core::sysModule.description') }}"
                value="{{ $itemSysModule ? $itemSysModule->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        

        

        <div class="form-group">
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
                        {{ (isset($itemSysModule) && $itemSysModule->color_id == $sysColor->id) || (old('color_id>') == $sysColor->id) ? 'selected' : '' }}>
                        {{ $sysColor }}
                    </option>
                @endforeach
            </select>
            @error('color_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>



        <!--   FeatureDomain_HasMany HasMany --> 


        <!--   SysController_HasMany HasMany --> 


        <!--   SysModel_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('sysModules.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSysModule->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


