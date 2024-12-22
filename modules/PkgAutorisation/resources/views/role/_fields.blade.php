<form action="{{ $item->id ? route('roles.update', $item->id) : route('roles.store') }}" method="POST">
    @csrf

    @if ($item->id)
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
                id="name"
                placeholder="{{ __('PkgAutorisation::role.name') }}"
                value="{{ $item ? $item->name : old('name') }}">
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
                id="guard_name"
                placeholder="{{ __('PkgAutorisation::role.guard_name') }}"
                value="{{ $item ? $item->guard_name : old('guard_name') }}">
            @error('guard_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        
        <div class="form-group">
            <label for="permissions">
                {{ ucfirst(__('PkgAutorisation::Feature.plural')) }}
            </label>
           


            <div class="features-list">
                @foreach ($features as $feature)
                    <div class="feature-item">
                        <input 
                            type="checkbox" 
                            id="feature-{{ $feature->id }}" 
                            name="features[]" 
                            value="{{ $feature->id }}"
                            {{ $item->permissions->pluck('id')->intersect($feature->permissions->pluck('id'))->isNotEmpty() ? 'checked' : '' }}>
                        <label for="feature-{{ $feature->id }}">
                            {{ $feature->name }}
                            @if ($feature->description)
                                <small class="text-muted">({{ $feature->description }})</small>
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>



            @error('features')
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
                        {{ (isset($item) && $item->users && $item->users->contains('id', $user->id)) || (is_array(old('users')) && in_array($user->id, old('users'))) ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('users')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        



    </div>

    <div class="card-footer">
        <a href="{{ route('roles.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
    ];
</script>
