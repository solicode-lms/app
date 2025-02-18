{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('ePackage-form')
<form class="crud-form custom-form context-state container" id="ePackageForm" action="{{ $itemEPackage->id ? route('ePackages.update', $itemEPackage->id) : route('ePackages.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemEPackage->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="name">
                {{ ucfirst(__('PkgGapp::ePackage.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                
                id="name"
                placeholder="{{ __('PkgGapp::ePackage.name') }}"
                value="{{ $itemEPackage ? $itemEPackage->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-12">
            <label for="description">
                {{ ucfirst(__('PkgGapp::ePackage.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgGapp::ePackage.description') }}">
                {{ $itemEPackage ? $itemEPackage->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        

        <!--   EModel HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('ePackages.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEPackage->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgGapp::ePackage.singular") }} : {{$itemEPackage}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
