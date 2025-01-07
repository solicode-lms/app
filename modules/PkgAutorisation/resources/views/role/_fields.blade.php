{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="roleForm" action="{{ $itemRole->id ? route('roles.update', $itemRole->id) : route('roles.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemRole->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgAutorisation::role.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                id="name"
                placeholder="{{ __('PkgAutorisation::role.name') }}"
                value="{{ $itemRole ? $itemRole->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="guard_name">
                {{ ucfirst(__('PkgAutorisation::role.guard_name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="guard_name"
                type="input"
                class="form-control"
                required
                id="guard_name"
                placeholder="{{ __('PkgAutorisation::role.guard_name') }}"
                value="{{ $itemRole ? $itemRole->guard_name : old('guard_name') }}">
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
                        {{ (isset($itemRole) && $itemRole->permissions && $itemRole->permissions->contains('id', $permission->id)) || (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'selected' : '' }}>
                        {{ $permission }}
                    </option>
                @endforeach
            </select>
            @error('permissions')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>


                <div class="form-group">
            <label for="users">
                {{ ucfirst(__('PkgAutorisation::User.plural')) }}
            </label>
            <select
                id="users"
                name="users[]"
                class="form-control select2"
                multiple="multiple">
               
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ (isset($itemRole) && $itemRole->users && $itemRole->users->contains('id', $user->id)) || (is_array(old('users')) && in_array($user->id, old('users'))) ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
            @error('users')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>


    </div>

    <div class="card-footer">
        <a href="{{ route('roles.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRole->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


