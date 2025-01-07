{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="userForm" action="{{ $itemUser->id ? route('users.update', $itemUser->id) : route('users.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemUser->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgAutorisation::user.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                id="name"
                placeholder="{{ __('PkgAutorisation::user.name') }}"
                value="{{ $itemUser ? $itemUser->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="email">
                {{ ucfirst(__('PkgAutorisation::user.email')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="email"
                type="input"
                class="form-control"
                required
                id="email"
                placeholder="{{ __('PkgAutorisation::user.email') }}"
                value="{{ $itemUser ? $itemUser->email : old('email') }}">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="email_verified_at">
                {{ ucfirst(__('PkgAutorisation::user.email_verified_at')) }}
                
            </label>
            <input
                name="email_verified_at"
                type="date"
                class="form-control datetimepicker"
                
                id="email_verified_at"
                placeholder="{{ __('PkgAutorisation::user.email_verified_at') }}"
                value="{{ $itemUser ? $itemUser->email_verified_at : old('email_verified_at') }}">
            @error('email_verified_at')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>





        <div class="form-group">
            <label for="password">
                {{ ucfirst(__('PkgAutorisation::user.password')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="password"
                type="input"
                class="form-control"
                required
                id="password"
                placeholder="{{ __('PkgAutorisation::user.password') }}"
                value="{{ $itemUser ? $itemUser->password : old('password') }}">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="remember_token">
                {{ ucfirst(__('PkgAutorisation::user.remember_token')) }}
                
            </label>
            <input
                name="remember_token"
                type="input"
                class="form-control"
                
                id="remember_token"
                placeholder="{{ __('PkgAutorisation::user.remember_token') }}"
                value="{{ $itemUser ? $itemUser->remember_token : old('remember_token') }}">
            @error('remember_token')
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
                        {{ (isset($itemUser) && $itemUser->roles && $itemUser->roles->contains('id', $role->id)) || (is_array(old('roles')) && in_array($role->id, old('roles'))) ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>
            @error('roles')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>


    </div>

    <div class="card-footer">
        <a href="{{ route('users.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemUser->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


