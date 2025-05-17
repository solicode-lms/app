{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('chapitre-form')
<form 
    class="crud-form custom-form context-state container" 
    id="chapitreForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('chapitres.bulkUpdate') : ($itemChapitre->id ? route('chapitres.update', $itemChapitre->id) : route('chapitres.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemChapitre->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($chapitre_ids))
        @foreach ($chapitre_ids as $id)
            <input type="hidden" name="chapitre_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemChapitre" field="nom">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="lien">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="lien" id="bulk_field_lien" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="coefficient">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="coefficient" id="bulk_field_coefficient" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="description">

      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="ordre">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="is_officiel">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="is_officiel" id="bulk_field_is_officiel" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="formation_id">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="formation_id" id="bulk_field_formation_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="niveau_competence_id">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="niveau_competence_id" id="bulk_field_niveau_competence_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="formateur_id">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="formateur_id" id="bulk_field_formateur_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="chapitre_officiel_id">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="chapitre_officiel_id" id="bulk_field_chapitre_officiel_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>


    </div>
  


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
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgAutoformation::chapitre.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgAutoformation::chapitre.singular") }} : {{$itemChapitre}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
