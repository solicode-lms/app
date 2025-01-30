{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('featureDomain-form')
<form class="crud-form custom-form context-state" id="featureDomainForm" action="{{ $itemFeatureDomain->id ? route('featureDomains.update', $itemFeatureDomain->id) : route('featureDomains.store') }}" method="POST" novalidate>
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
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('Core::featureDomain.description') }}">
                {{ $itemFeatureDomain ? $itemFeatureDomain->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        
    <div class="form-group">
            <label for="sys_module_id">
                {{ ucfirst(__('Core::sysModule.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="sys_module_id" 
            required
            name="sys_module_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysModules as $sysModule)
                    <option value="{{ $sysModule->id }}"
                        {{ (isset($itemFeatureDomain) && $itemFeatureDomain->sys_module_id == $sysModule->id) || (old('sys_module_id>') == $sysModule->id) ? 'selected' : '' }}>
                        {{ $sysModule }}
                    </option>
                @endforeach
            </select>
            @error('sys_module_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        

        <!--   Feature HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('featureDomains.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFeatureDomain->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>

