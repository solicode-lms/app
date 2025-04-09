{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('nationalite-form')
<form class="crud-form custom-form context-state container" id="nationaliteForm" action="{{ $itemNationalite->id ? route('nationalites.update', $itemNationalite->id) : route('nationalites.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemNationalite->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          <label for="code">
            {{ ucfirst(__('PkgApprenants::nationalite.code')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="code"
                type="input"
                class="form-control"
                required
                
                
                id="code"
                placeholder="{{ __('PkgApprenants::nationalite.code') }}"
                value="{{ $itemNationalite ? $itemNationalite->code : old('code') }}">
          @error('code')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="nom">
            {{ ucfirst(__('PkgApprenants::nationalite.nom')) }}
            
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                
                
                
                id="nom"
                placeholder="{{ __('PkgApprenants::nationalite.nom') }}"
                value="{{ $itemNationalite ? $itemNationalite->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          <label for="description">
            {{ ucfirst(__('PkgApprenants::nationalite.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgApprenants::nationalite.description') }}">{{ $itemNationalite ? $itemNationalite->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   Apprenant HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('nationalites.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNationalite->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgApprenants::nationalite.singular") }} : {{$itemNationalite}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
