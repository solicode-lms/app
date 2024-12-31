{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="featureForm" action="{{ $itemFeature->id ? route('features.update', $itemFeature->id) : route('features.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemFeature->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('Core::feature.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                id="name"
                placeholder="{{ __('Core::feature.name') }}"
                value="{{ $itemFeature ? $itemFeature->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('Core::feature.description')) }}
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                
                id="description"
                placeholder="{{ __('Core::feature.description') }}"
                value="{{ $itemFeature ? $itemFeature->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
    <div class="form-group">
            <label for="domain_id">
                {{ ucfirst(__('Core::featureDomain.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="domain_id" 
            name="domain_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($featureDomains as $featureDomain)
                    <option value="{{ $featureDomain->id }}"
                        {{ (isset($itemFeature) && $itemFeature->domain_id == $featureDomain->id) || (old('domain_id>') == $featureDomain->id) ? 'selected' : '' }}>
                        {{ $featureDomain }}
                    </option>
                @endforeach
            </select>
            @error('domain_id')
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
                        {{ (isset($itemFeature) && $itemFeature->permissions && $itemFeature->permissions->contains('id', $permission->id)) || (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'selected' : '' }}>
                        {{ $permission }}
                    </option>
                @endforeach
            </select>
            @error('permissions')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>


    </div>

    <div class="card-footer">
        <a href="{{ route('features.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFeature->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


