{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('formation-form')
<form 
    class="crud-form custom-form context-state container" 
    id="formationForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('formations.bulkUpdate') : ($itemFormation->id ? route('formations.update', $itemFormation->id) : route('formations.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemFormation->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($formation_ids))
        @foreach ($formation_ids as $id)
            <input type="hidden" name="formation_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        
      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgAutoformation::formation.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgAutoformation::formation.nom') }}"
                value="{{ $itemFormation ? $itemFormation->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="lien" id="bulk_field_lien" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="lien">
            {{ ucfirst(__('PkgAutoformation::formation.lien')) }}
            
          </label>
           <input
                name="lien"
                type="input"
                class="form-control"
                
                
                
                id="lien"
                placeholder="{{ __('PkgAutoformation::formation.lien') }}"
                value="{{ $itemFormation ? $itemFormation->lien : old('lien') }}">
          @error('lien')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="filiere_id" id="bulk_field_filiere_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="filiere_id">
            {{ ucfirst(__('PkgAutoformation::formation.filiere_id')) }}
            
          </label>
                      <select 
            id="filiere_id" 
            data-target-dynamic-dropdown='#competence_id'
            data-target-dynamic-dropdown-api-url='{{route('competences.getData')}}'
            data-target-dynamic-dropdown-filter='module.filiere_id'
            
            
            
            name="filiere_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($filieres as $filiere)
                    <option value="{{ $filiere->id }}"
                        {{ (isset($itemFormation) && $itemFormation->filiere_id == $filiere->id) || (old('filiere_id>') == $filiere->id) ? 'selected' : '' }}>
                        {{ $filiere }}
                    </option>
                @endforeach
            </select>
          @error('filiere_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="competence_id" id="bulk_field_competence_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="competence_id">
            {{ ucfirst(__('PkgCompetences::competence.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="competence_id" 
            required
            
            
            name="competence_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($competences as $competence)
                    <option value="{{ $competence->id }}"
                        {{ (isset($itemFormation) && $itemFormation->competence_id == $competence->id) || (old('competence_id>') == $competence->id) ? 'selected' : '' }}>
                        {{ $competence }}
                    </option>
                @endforeach
            </select>
          @error('competence_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="technologies" id="bulk_field_technologies" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="technologies">
            {{ ucfirst(__('PkgCompetences::technology.plural')) }}
            
          </label>
                      <select
                id="technologies"
                name="technologies[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($technologies as $technology)
                    <option value="{{ $technology->id }}"
                        {{ (isset($itemFormation) && $itemFormation->technologies && $itemFormation->technologies->contains('id', $technology->id)) || (is_array(old('technologies')) && in_array($technology->id, old('technologies'))) ? 'selected' : '' }}>
                        {{ $technology }}
                    </option>
                @endforeach
            </select>
          @error('technologies')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

      @php $canEditis_officiel = !$itemFormation || !$itemFormation->id || Auth::user()->hasAnyRole(explode(',', 'admin,admin-formateur')); @endphp

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="is_officiel" id="bulk_field_is_officiel" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_officiel">
            {{ ucfirst(__('PkgAutoformation::formation.is_officiel')) }}
            
          </label>
                      <input type="hidden" name="is_officiel" value="0">
            <input
                name="is_officiel"
                type="checkbox"
                class="form-control"
                
                
                
                id="is_officiel"
                {{ $canEditis_officiel ? '' : 'disabled' }}
                value="1"
                {{ old('is_officiel', $itemFormation ? $itemFormation->is_officiel : 0) ? 'checked' : '' }}>
          @error('is_officiel')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


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
                        {{ (isset($itemFormation) && $itemFormation->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
          @error('formateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="formation_officiel_id" id="bulk_field_formation_officiel_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="formation_officiel_id">
            {{ ucfirst(__('PkgAutoformation::formation.singular')) }}
            
          </label>
                      <select 
            id="formation_officiel_id" 
            
            
            
            name="formation_officiel_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($formations as $formation)
                    <option value="{{ $formation->id }}"
                        {{ (isset($itemFormation) && $itemFormation->formation_officiel_id == $formation->id) || (old('formation_officiel_id>') == $formation->id) ? 'selected' : '' }}>
                        {{ $formation }}
                    </option>
                @endforeach
            </select>
          @error('formation_officiel_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgAutoformation::formation.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgAutoformation::formation.description') }}">{{ $itemFormation ? $itemFormation->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('formations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFormation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgAutoformation::formation.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgAutoformation::formation.singular") }} : {{$itemFormation}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
