{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('users.update', $item->id) : route('users.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgAuthentification::user.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                id="name"
                placeholder="{{ __('Enter PkgAuthentification::user.name') }}"
                value="{{ $item ? $item->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="email">
                {{ ucfirst(__('PkgAuthentification::user.email')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="email"
                type="input"
                class="form-control"
                id="email"
                placeholder="{{ __('Enter PkgAuthentification::user.email') }}"
                value="{{ $item ? $item->email : old('email') }}">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="email_verified_at">
                {{ ucfirst(__('PkgAuthentification::user.email_verified_at')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="email_verified_at"
                type="input"
                class="form-control"
                id="email_verified_at"
                placeholder="{{ __('Enter PkgAuthentification::user.email_verified_at') }}"
                value="{{ $item ? $item->email_verified_at : old('email_verified_at') }}">
            @error('email_verified_at')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password">
                {{ ucfirst(__('PkgAuthentification::user.password')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="password"
                type="input"
                class="form-control"
                id="password"
                placeholder="{{ __('Enter PkgAuthentification::user.password') }}"
                value="{{ $item ? $item->password : old('password') }}">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="remember_token">
                {{ ucfirst(__('PkgAuthentification::user.remember_token')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="remember_token"
                type="input"
                class="form-control"
                id="remember_token"
                placeholder="{{ __('Enter PkgAuthentification::user.remember_token') }}"
                value="{{ $item ? $item->remember_token : old('remember_token') }}">
            @error('remember_token')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        



    </div>

    <div class="card-footer">
        <a href="{{ route('users.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
    ];
</script>