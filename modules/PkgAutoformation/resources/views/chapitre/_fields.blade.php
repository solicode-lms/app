{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('chapitre-form')
<form class="crud-form custom-form context-state container" id="chapitreForm" action="{{ $itemChapitre->id ? route('chapitres.update', $itemChapitre->id) : route('chapitres.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemChapitre->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          <label for="nom">
            {{ ucfirst(__('PkgAutoformation::chapitre.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgAutoformation::chapitre.nom') }}"
                value="{{ $itemChapitre ? $itemChapitre->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="lien">
            {{ ucfirst(__('PkgAutoformation::chapitre.lien')) }}
            
          </label>
           <input
                name="lien"
                type="input"
                class="form-control"
                
                
                
                id="lien"
                placeholder="{{ __('PkgAutoformation::chapitre.lien') }}"
                value="{{ $itemChapitre ? $itemChapitre->lien : old('lien') }}">
          @error('lien')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="coefficient">
            {{ ucfirst(__('PkgAutoformation::chapitre.coefficient')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="coefficient"
                type="number"
                class="form-control"
                required
                
                
                id="coefficient"
                placeholder="{{ __('PkgAutoformation::chapitre.coefficient') }}"
                value="{{ $itemChapitre ? $itemChapitre->coefficient : old('coefficient') }}">
          @error('coefficient')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          <label for="description">
            {{ ucfirst(__('PkgAutoformation::chapitre.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgAutoformation::chapitre.description') }}">{{ $itemChapitre ? $itemChapitre->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="ordre">
            {{ ucfirst(__('PkgAutoformation::chapitre.ordre')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                required
                
                
                id="ordre"
                placeholder="{{ __('PkgAutoformation::chapitre.ordre') }}"
                value="{{ $itemChapitre ? $itemChapitre->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="is_officiel">
            {{ ucfirst(__('PkgAutoformation::chapitre.is_officiel')) }}
            <span class="text-danger">*</span>
          </label>
                      <input type="hidden" name="is_officiel" value="0">
            <input
                name="is_officiel"
                type="checkbox"
                class="form-control"
                required
                
                
                id="is_officiel"
                value="1"
                {{ old('is_officiel', $itemChapitre ? $itemChapitre->is_officiel : 0) ? 'checked' : '' }}>
          @error('is_officiel')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="formation_id">
            {{ ucfirst(__('PkgAutoformation::formation.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="formation_id" 
            required
            
            
            name="formation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($formations as $formation)
                    <option value="{{ $formation->id }}"
                        {{ (isset($itemChapitre) && $itemChapitre->formation_id == $formation->id) || (old('formation_id>') == $formation->id) ? 'selected' : '' }}>
                        {{ $formation }}
                    </option>
                @endforeach
            </select>
          @error('formation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="niveau_competence_id">
            {{ ucfirst(__('PkgCompetences::niveauCompetence.singular')) }}
            
          </label>
                      <select 
            id="niveau_competence_id" 
            
            
            
            name="niveau_competence_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($niveauCompetences as $niveauCompetence)
                    <option value="{{ $niveauCompetence->id }}"
                        {{ (isset($itemChapitre) && $itemChapitre->niveau_competence_id == $niveauCompetence->id) || (old('niveau_competence_id>') == $niveauCompetence->id) ? 'selected' : '' }}>
                        {{ $niveauCompetence }}
                    </option>
                @endforeach
            </select>
          @error('niveau_competence_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="formateur_id">
            {{ ucfirst(__('PkgFormation::formateur.singular')) }}
            
          </label>
                      <select 
            id="formateur_id" 
            
            
            
            name="formateur_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemChapitre) && $itemChapitre->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
          @error('formateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="chapitre_officiel_id">
            {{ ucfirst(__('PkgAutoformation::chapitre.singular')) }}
            
          </label>
                      <select 
            id="chapitre_officiel_id" 
            
            
            
            name="chapitre_officiel_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($chapitres as $chapitre)
                    <option value="{{ $chapitre->id }}"
                        {{ (isset($itemChapitre) && $itemChapitre->chapitre_officiel_id == $chapitre->id) || (old('chapitre_officiel_id>') == $chapitre->id) ? 'selected' : '' }}>
                        {{ $chapitre }}
                    </option>
                @endforeach
            </select>
          @error('chapitre_officiel_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   Chapitre HasMany --> 


<!--   RealisationChapitre HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('chapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemChapitre->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgAutoformation::chapitre.singular") }} : {{$itemChapitre}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
