{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="iPackageForm" action="{{ $itemIPackage->id ? route('iPackages.update', $itemIPackage->id) : route('iPackages.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemIPackage->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgGapp::iPackage.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                id="name"
                placeholder="{{ __('PkgGapp::iPackage.name') }}"
                value="{{ $itemIPackage ? $itemIPackage->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgGapp::iPackage.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgGapp::iPackage.description') }}">
                {{ $itemIPackage ? $itemIPackage->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        

        <!--   IModel_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('iPackages.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemIPackage->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


