{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="sysControllerForm" action="{{ $itemSysController->id ? route('sysControllers.update', $itemSysController->id) : route('sysControllers.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemSysController->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
    <div class="form-group">
            <label for="module_id">
                {{ ucfirst(__('Core::sysModule.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="module_id" 
            required
            name="module_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysModules as $sysModule)
                    <option value="{{ $sysModule->id }}"
                        {{ (isset($itemSysController) && $itemSysController->module_id == $sysModule->id) || (old('module_id>') == $sysModule->id) ? 'selected' : '' }}>
                        {{ $sysModule }}
                    </option>
                @endforeach
            </select>
            @error('module_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        <div class="form-group">
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

        <div class="form-group">
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

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('Core::sysController.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('Core::sysController.description') }}">
                {{ $itemSysController ? $itemSysController->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        


        <!--   Permission_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('sysControllers.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSysController->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


