{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('ville-form')
<form class="crud-form custom-form context-state container" id="villeForm" action="{{ $itemVille->id ? route('villes.update', $itemVille->id) : route('villes.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemVille->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          <label for="nom">
            {{ ucfirst(__('PkgApprenants::ville.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgApprenants::ville.nom') }}"
                value="{{ $itemVille ? $itemVille->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('villes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemVille->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgApprenants::ville.singular") }} : {{$itemVille}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
