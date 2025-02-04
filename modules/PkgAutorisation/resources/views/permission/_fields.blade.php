{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('permission-form')
<form class="crud-form custom-form context-state" id="permissionForm" action="{{ $itemPermission->id ? route('permissions.update', $itemPermission->id) : route('permissions.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemPermission->id)
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
                required
                id="name"
                placeholder="{{ __('PkgAutorisation::permission.name') }}"
                value="{{ $itemPermission ? $itemPermission->name : old('name') }}">
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
                required
                id="guard_name"
                placeholder="{{ __('PkgAutorisation::permission.guard_name') }}"
                value="{{ $itemPermission ? $itemPermission->guard_name : old('guard_name') }}">
            @error('guard_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        
    <div class="form-group">
            <label for="controller_id">
                {{ ucfirst(__('Core::sysController.singular')) }}
                
            </label>
            <select 
            id="controller_id" 
            
            name="controller_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysControllers as $sysController)
                    <option value="{{ $sysController->id }}"
                        {{ (isset($itemPermission) && $itemPermission->controller_id == $sysController->id) || (old('controller_id>') == $sysController->id) ? 'selected' : '' }}>
                        {{ $sysController }}
                    </option>
                @endforeach
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
                        {{ (isset($itemPermission) && $itemPermission->features && $itemPermission->features->contains('id', $feature->id)) || (is_array(old('features')) && in_array($feature->id, old('features'))) ? 'selected' : '' }}>
                        {{ $feature }}
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
                        {{ (isset($itemPermission) && $itemPermission->roles && $itemPermission->roles->contains('id', $role->id)) || (is_array(old('roles')) && in_array($role->id, old('roles'))) ? 'selected' : '' }}>
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
        <a href="{{ route('permissions.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemPermission->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgAutorisation::permission.singular") }} : {{$itemPermission}}'
</script>
