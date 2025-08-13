{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('projet-form')
<form 
    class="crud-form custom-form context-state container" 
    id="projetForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('projets.bulkUpdate') : ($itemProjet->id ? route('projets.update', $itemProjet->id) : route('projets.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemProjet->id)
        <input type="hidden" name="id" value="{{ $itemProjet->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($projet_ids))
        @foreach ($projet_ids as $id)
            <input type="hidden" name="projet_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemProjet" field="session_formation_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="session_formation_id" id="bulk_field_session_formation_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="session_formation_id">
            {{ ucfirst(__('PkgSessions::sessionFormation.singular')) }}
            
          </label>
                      <select 
            id="session_formation_id" 
            
            data-calcul='true'
            
            name="session_formation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sessionFormations as $sessionFormation)
                    <option value="{{ $sessionFormation->id }}"
                        {{ (isset($itemProjet) && $itemProjet->session_formation_id == $sessionFormation->id) || (old('session_formation_id>') == $sessionFormation->id) ? 'selected' : '' }}>
                        {{ $sessionFormation }}
                    </option>
                @endforeach
            </select>
          @error('session_formation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemProjet" field="filiere_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="filiere_id" id="bulk_field_filiere_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemProjet" field="titre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="titre" id="bulk_field_titre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemProjet" field="travail_a_faire" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="travail_a_faire" id="bulk_field_travail_a_faire" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemProjet" field="critere_de_travail" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="critere_de_travail" id="bulk_field_critere_de_travail" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemProjet" field="formateur_id" :bulkEdit="$bulkEdit">
      @php $canEditformateur_id = !$itemProjet || !$itemProjet->id || Auth::user()->hasAnyRole(explode(',', 'admin')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="formateur_id" id="bulk_field_formateur_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="formateur_id">
            {{ ucfirst(__('PkgFormation::formateur.singular')) }}
            
          </label>
                      <select 
            id="formateur_id" 
            {{ $canEditformateur_id ? '' : 'disabled' }}
            
            
            
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
  
</x-form-field>


    </div>
  


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
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgCreationProjet::projet.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCreationProjet::projet.singular") }} : {{$itemProjet}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
