{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form id="featureDomainForm" action="{{ $itemFeatureDomain->id ? route('featureDomains.update', $itemFeatureDomain->id) : route('featureDomains.store') }}" method="POST">
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
            <select id="module_id" name="module_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('module_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        



    </div>


    <div class="card-footer">
        <a href="{{ route('featureDomains.index') }}" id="featureDomain_form_cancel" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFeatureDomain->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'module_id',
            fetchUrl: "{{ route('sysModules.all') }}",
            selectedValue: {{ $itemFeatureDomain->module_id ? $itemFeatureDomain->module_id : 'undefined' }},
            fieldValue: 'name'
        }
        
    ];
</script>
