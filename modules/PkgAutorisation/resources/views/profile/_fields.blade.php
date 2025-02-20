{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('profile-form')
<form class="crud-form custom-form context-state container" id="profileForm" action="{{ $itemProfile->id ? route('profiles.update', $itemProfile->id) : route('profiles.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemProfile->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-12">
            <label for="old_password">
                {{ ucfirst(__('PkgAutorisation::profile.old_password')) }}
                
            </label>
            <input
                name="old_password"
                type="password"
                class="form-control"
                
                
                id="old_password"
                placeholder="{{ __('PkgAutorisation::profile.old_password') }}"
                value="{{ $itemProfile ? $itemProfile->old_password : old('old_password') }}">
            @error('old_password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-12">
            <label for="password">
                {{ ucfirst(__('PkgAutorisation::profile.password')) }}
                
            </label>
            <input
                name="password"
                type="password"
                class="form-control"
                
                
                id="password"
                placeholder="{{ __('PkgAutorisation::profile.password') }}"
                value="{{ $itemProfile ? $itemProfile->password : old('password') }}">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>
<div class="form-group col-12 col-md-12">
            <label for="password_confirmation">
                {{ ucfirst(__('PkgAutorisation::profile.confirm_password')) }}
                
            </label>
            <input
                name="password_confirmation"
                type="password"
                class="form-control"
                
                
                id="password_confirmation"
                placeholder="{{ __('PkgAutorisation::profile.confirm_password') }}"
                value="{{ $itemProfile ? $itemProfile->password : old('password') }}">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="user_id">
                {{ ucfirst(__('PkgAutorisation::user.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="user_id" 
            required
            
            name="user_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ (isset($itemProfile) && $itemProfile->user_id == $user->id) || (old('user_id>') == $user->id) ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-12">
            <label for="phone">
                {{ ucfirst(__('PkgAutorisation::profile.phone')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="phone"
                type="input"
                class="form-control"
                required
                
                id="phone"
                placeholder="{{ __('PkgAutorisation::profile.phone') }}"
                value="{{ $itemProfile ? $itemProfile->phone : old('phone') }}">
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

    </div>

    <div class="card-footer">
        <a href="{{ route('profiles.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemProfile->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgAutorisation::profile.singular") }} : {{$itemProfile}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
