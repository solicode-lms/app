{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="featureDomainForm" action="{{ $itemFeatureDomain->id ? route('featureDomains.update', $itemFeatureDomain->id) : route('featureDomains.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemFeatureDomain->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('Core::featureDomain.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                id="name"
                placeholder="{{ __('Core::featureDomain.name') }}"
                value="{{ $itemFeatureDomain ? $itemFeatureDomain->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="slug">
                {{ ucfirst(__('Core::featureDomain.slug')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="slug"
                type="input"
                class="form-control"
                required
                id="slug"
                placeholder="{{ __('Core::featureDomain.slug') }}"
                value="{{ $itemFeatureDomain ? $itemFeatureDomain->slug : old('slug') }}">
            @error('slug')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('Core::featureDomain.description')) }}
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                
                id="description"
                placeholder="{{ __('Core::featureDomain.description') }}"
                value="{{ $itemFeatureDomain ? $itemFeatureDomain->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
    <div class="form-group">
            <label for="module_id">
                {{ ucfirst(__('Core::sysModule.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="module_id" 
            name="module_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysModules as $sysModule)
                    <option value="{{ $sysModule->id }}"
                        {{ (isset($itemFeatureDomain) && $itemFeatureDomain->module_id == $sysModule->id) || (old('module_id>') == $sysModule->id) ? 'selected' : '' }}>
                        {{ $sysModule }}
                    </option>
                @endforeach
            </select>
            @error('module_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>



        <!--   Feature_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('featureDomains.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFeatureDomain->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


