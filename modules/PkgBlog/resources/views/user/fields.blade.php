{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}  

<form action="{{ $item->id ? route('users.update', $item->id) : route('users.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgBlog::user.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                id="name"
                placeholder="{{ __('Enter PkgBlog::user.name') }}"
                value="{{ $item ? $item->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="email">
                {{ ucfirst(__('PkgBlog::user.email')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="email"
                type="input"
                class="form-control"
                id="email"
                placeholder="{{ __('Enter PkgBlog::user.email') }}"
                value="{{ $item ? $item->email : old('email') }}">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password">
                {{ ucfirst(__('PkgBlog::user.password')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="password"
                type="input"
                class="form-control"
                id="password"
                placeholder="{{ __('Enter PkgBlog::user.password') }}"
                value="{{ $item ? $item->password : old('password') }}">
            @error('password')
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
