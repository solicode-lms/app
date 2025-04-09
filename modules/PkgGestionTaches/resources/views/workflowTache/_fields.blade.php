{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowTache-form')
<form class="crud-form custom-form context-state container" id="workflowTacheForm" action="{{ $itemWorkflowTache->id ? route('workflowTaches.update', $itemWorkflowTache->id) : route('workflowTaches.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemWorkflowTache->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          <label for="code">
            {{ ucfirst(__('PkgGestionTaches::workflowTache.code')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="code"
                type="input"
                class="form-control"
                required
                
                
                id="code"
                placeholder="{{ __('PkgGestionTaches::workflowTache.code') }}"
                value="{{ $itemWorkflowTache ? $itemWorkflowTache->code : old('code') }}">
          @error('code')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="titre">
            {{ ucfirst(__('PkgGestionTaches::workflowTache.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgGestionTaches::workflowTache.titre') }}"
                value="{{ $itemWorkflowTache ? $itemWorkflowTache->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          <label for="description">
            {{ ucfirst(__('PkgGestionTaches::workflowTache.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgGestionTaches::workflowTache.description') }}">{{ $itemWorkflowTache ? $itemWorkflowTache->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   EtatRealisationTache HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('workflowTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemWorkflowTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgGestionTaches::workflowTache.singular") }} : {{$itemWorkflowTache}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
