{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('typeDependanceTache-form')
<form class="crud-form custom-form context-state container" id="typeDependanceTacheForm" action="{{ $itemTypeDependanceTache->id ? route('typeDependanceTaches.update', $itemTypeDependanceTache->id) : route('typeDependanceTaches.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemTypeDependanceTache->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          <label for="titre">
            {{ ucfirst(__('PkgGestionTaches::typeDependanceTache.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                id="titre"
                placeholder="{{ __('PkgGestionTaches::typeDependanceTache.titre') }}"
                value="{{ $itemTypeDependanceTache ? $itemTypeDependanceTache->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          <label for="description">
            {{ ucfirst(__('PkgGestionTaches::typeDependanceTache.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgGestionTaches::typeDependanceTache.description') }}">{{ $itemTypeDependanceTache ? $itemTypeDependanceTache->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   DependanceTache HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('typeDependanceTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemTypeDependanceTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgGestionTaches::typeDependanceTache.singular") }} : {{$itemTypeDependanceTache}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
