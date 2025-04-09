{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('projet-form')
<form class="crud-form custom-form context-state container" id="projetForm" action="{{ $itemProjet->id ? route('projets.update', $itemProjet->id) : route('projets.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemProjet->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-12">
          <label for="titre">
            {{ ucfirst(__('PkgCreationProjet::projet.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgCreationProjet::projet.titre') }}"
                value="{{ $itemProjet ? $itemProjet->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          <label for="travail_a_faire">
            {{ ucfirst(__('PkgCreationProjet::projet.travail_a_faire')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="travail_a_faire"
                class="form-control richText"
                required
                
                
                id="travail_a_faire"
                placeholder="{{ __('PkgCreationProjet::projet.travail_a_faire') }}">{{ $itemProjet ? $itemProjet->travail_a_faire : old('travail_a_faire') }}</textarea>
          @error('travail_a_faire')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          <label for="critere_de_travail">
            {{ ucfirst(__('PkgCreationProjet::projet.critere_de_travail')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="critere_de_travail"
                class="form-control richText"
                required
                
                
                id="critere_de_travail"
                placeholder="{{ __('PkgCreationProjet::projet.critere_de_travail') }}">{{ $itemProjet ? $itemProjet->critere_de_travail : old('critere_de_travail') }}</textarea>
          @error('critere_de_travail')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="nombre_jour">
            {{ ucfirst(__('PkgCreationProjet::projet.nombre_jour')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="nombre_jour"
                type="number"
                class="form-control"
                required
                
                
                id="nombre_jour"
                placeholder="{{ __('PkgCreationProjet::projet.nombre_jour') }}"
                value="{{ $itemProjet ? $itemProjet->nombre_jour : old('nombre_jour') }}">
          @error('nombre_jour')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   TransfertCompetence HasMany --> 


<!--   AffectationProjet HasMany --> 


      <div class="form-group col-12 col-md-6">
          <label for="formateur_id">
            {{ ucfirst(__('PkgFormation::formateur.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="formateur_id" 
            required
            
            
            name="formateur_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemProjet) && $itemProjet->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
          @error('formateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   Livrable HasMany --> 


<!--   Tache HasMany --> 


      <div class="form-group col-12 col-md-6">
          <label for="filiere_id">
            {{ ucfirst(__('PkgFormation::filiere.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="filiere_id" 
            required
            
            
            name="filiere_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($filieres as $filiere)
                    <option value="{{ $filiere->id }}"
                        {{ (isset($itemProjet) && $itemProjet->filiere_id == $filiere->id) || (old('filiere_id>') == $filiere->id) ? 'selected' : '' }}>
                        {{ $filiere }}
                    </option>
                @endforeach
            </select>
          @error('filiere_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   Resource HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('projets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgCreationProjet::projet.singular") }} : {{$itemProjet}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
