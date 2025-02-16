{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('profile-form')
<form class="crud-form custom-form context-state" id="profileForm" action="{{ $itemProfile->id ? route('profiles.update', $itemProfile->id) : route('profiles.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemProfile->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        
    <div class="form-group">
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


        
        <div class="form-group">
            <label for="phone">
                {{ ucfirst(__('PkgAutorisation::profile.phone')) }}
                
            </label>
            <input
                name="phone"
                type="input"
                class="form-control"
                
                
                id="phone"
                placeholder="{{ __('PkgAutorisation::profile.phone') }}"
                value="{{ $itemProfile ? $itemProfile->phone : old('phone') }}">
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="profile_picture">
                {{ ucfirst(__('PkgAutorisation::profile.profile_picture')) }}
                
            </label>
            <input
                name="profile_picture"
                type="input"
                class="form-control"
                
                
                id="profile_picture"
                placeholder="{{ __('PkgAutorisation::profile.profile_picture') }}"
                value="{{ $itemProfile ? $itemProfile->profile_picture : old('profile_picture') }}">
            @error('profile_picture')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="bio">
                {{ ucfirst(__('PkgAutorisation::profile.bio')) }}
                
            </label>
            <textarea rows="" cols=""
                name="bio"
                class="form-control richText"
                
                
                id="bio"
                placeholder="{{ __('PkgAutorisation::profile.bio') }}">
                {{ $itemProfile ? $itemProfile->bio : old('bio') }}
            </textarea>
            @error('bio')
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
