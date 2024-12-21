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
            <label for="module">
                {{ ucfirst(__('PkgAutorisation::permission.module')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="module"
                type="input"
                class="form-control"
                id="module"
                placeholder="{{ __('PkgAutorisation::permission.module') }}"
                value="{{ $item ? $item->module : old('module') }}">
            @error('module')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="type">
                {{ ucfirst(__('PkgAutorisation::permission.type')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="type"
                type="input"
                class="form-control"
                id="type"
                placeholder="{{ __('PkgAutorisation::permission.type') }}"
                value="{{ $item ? $item->type : old('type') }}">
            @error('type')
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
            <label for="permissions">
                {{ ucfirst(__('PkgAutorisation::Permission.plural')) }}
            </label>
            <select
                id="permissions"
                name="permissions[]"
                class="form-control select2"
                multiple="multiple">
                @foreach ($permissions as $permission)
                    <option value="{{ $permission->id }}"
                        {{ (isset($item) && $item->permissions && $item->permissions->contains('id', $permission->id)) || (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'selected' : '' }}>
                        {{ $permission->name }}
                    </option>
                @endforeach
            </select>
            @error('permissions')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        
        <div class="form-group">
            <label for="permissions">
                {{ ucfirst(__('PkgAutorisation::Permission.plural')) }}
            </label>
            <select
                id="permissions"
                name="permissions[]"
                class="form-control select2"
                multiple="multiple">
                @foreach ($permissions as $permission)
                    <option value="{{ $permission->id }}"
                        {{ (isset($item) && $item->permissions && $item->permissions->contains('id', $permission->id)) || (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'selected' : '' }}>
                        {{ $permission->name }}
                    </option>
                @endforeach
            </select>
            @error('permissions')
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
        
    ];
</script>
