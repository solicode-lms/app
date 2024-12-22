{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('permissions.update', $item->id) : route('permissions.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgAutorisation::permission.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                id="name"
                placeholder="{{ __('PkgAutorisation::permission.name') }}"
                value="{{ $item ? $item->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="guard_name">
                {{ ucfirst(__('PkgAutorisation::permission.guard_name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="guard_name"
                type="input"
                class="form-control"
                id="guard_name"
                placeholder="{{ __('PkgAutorisation::permission.guard_name') }}"
                value="{{ $item ? $item->guard_name : old('guard_name') }}">
            @error('guard_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="controller_id">
                {{ ucfirst(__('Core::sysController.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="controller_id" name="controller_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('controller_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="features">
                {{ ucfirst(__('Core::Feature.plural')) }}
            </label>
            <select
                id="features"
                name="features[]"
                class="form-control select2"
                multiple="multiple">
                @foreach ($features as $feature)
                    <option value="{{ $feature->id }}"
                        {{ (isset($item) && $item->features && $item->features->contains('id', $feature->id)) || (is_array(old('features')) && in_array($feature->id, old('features'))) ? 'selected' : '' }}>
                        {{ $feature->name }}
                    </option>
                @endforeach
            </select>
            @error('features')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        
        <div class="form-group">
            <label for="roles">
                {{ ucfirst(__('PkgAutorisation::Role.plural')) }}
            </label>
            <select
                id="roles"
                name="roles[]"
                class="form-control select2"
                multiple="multiple">
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ (isset($item) && $item->roles && $item->roles->contains('id', $role->id)) || (is_array(old('roles')) && in_array($role->id, old('roles'))) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            @error('roles')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        



    </div>

    <div class="card-footer">
        <a href="{{ route('permissions.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'controller_id',
            fetchUrl: "{{ route('sysControllers.all') }}",
            selectedValue: {{ $item->controller_id ? $item->controller_id : 'undefined' }},
            fieldValue: 'name'
        }
        
    ];
</script>
