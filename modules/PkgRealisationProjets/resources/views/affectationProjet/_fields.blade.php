{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('affectationProjet-form')
<form 
    class="crud-form custom-form context-state container" 
    id="affectationProjetForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('affectationProjets.bulkUpdate') : ($itemAffectationProjet->id ? route('affectationProjets.update', $itemAffectationProjet->id) : route('affectationProjets.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemAffectationProjet->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($affectationProjet_ids))
        @foreach ($affectationProjet_ids as $id)
            <input type="hidden" name="affectationProjet_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemAffectationProjet" field="projet_id" :bulkEdit="$bulkEdit">
      @php $canEditprojet_id = !$itemAffectationProjet || !$itemAffectationProjet->id || Auth::user()->hasAnyRole(explode(',', 'admin,formateur')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="projet_id" id="bulk_field_projet_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="projet_id">
            {{ ucfirst(__('PkgCreationProjet::projet.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="projet_id" 
            {{ $canEditprojet_id ? '' : 'disabled' }}
            required
            
            
            name="projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($projets as $projet)
                    <option value="{{ $projet->id }}"
                        {{ (isset($itemAffectationProjet) && $itemAffectationProjet->projet_id == $projet->id) || (old('projet_id>') == $projet->id) ? 'selected' : '' }}>
                        {{ $projet }}
                    </option>
                @endforeach
            </select>
          @error('projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemAffectationProjet" field="groupe_id" :bulkEdit="$bulkEdit">
      @php $canEditgroupe_id = !$itemAffectationProjet || !$itemAffectationProjet->id || Auth::user()->hasAnyRole(explode(',', 'formateur,admin')); @endphp

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="groupe_id" id="bulk_field_groupe_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="groupe_id">
            {{ ucfirst(__('PkgApprenants::groupe.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="groupe_id" 
            {{ $canEditgroupe_id ? '' : 'disabled' }}
            data-target-dynamic-dropdown='#sous_groupe_id'
            data-target-dynamic-dropdown-api-url='{{route('sousGroupes.getData')}}'
            data-target-dynamic-dropdown-filter='groupe_id'
            required
            
            
            name="groupe_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($groupes as $groupe)
                    <option value="{{ $groupe->id }}"
                        {{ (isset($itemAffectationProjet) && $itemAffectationProjet->groupe_id == $groupe->id) || (old('groupe_id>') == $groupe->id) ? 'selected' : '' }}>
                        {{ $groupe }}
                    </option>
                @endforeach
            </select>
          @error('groupe_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemAffectationProjet" field="annee_formation_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="annee_formation_id" id="bulk_field_annee_formation_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="annee_formation_id">
            {{ ucfirst(__('PkgFormation::anneeFormation.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="annee_formation_id" 
            required
            
            
            name="annee_formation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($anneeFormations as $anneeFormation)
                    <option value="{{ $anneeFormation->id }}"
                        {{ (isset($itemAffectationProjet) && $itemAffectationProjet->annee_formation_id == $anneeFormation->id) || (old('annee_formation_id>') == $anneeFormation->id) ? 'selected' : '' }}>
                        {{ $anneeFormation }}
                    </option>
                @endforeach
            </select>
          @error('annee_formation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemAffectationProjet" field="date_debut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_debut" id="bulk_field_date_debut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgRealisationProjets::affectationProjet.date_debut')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="date_debut"
                placeholder="{{ __('PkgRealisationProjets::affectationProjet.date_debut') }}"
                value="{{ $itemAffectationProjet ? $itemAffectationProjet->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemAffectationProjet" field="date_fin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_fin" id="bulk_field_date_fin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgRealisationProjets::affectationProjet.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgRealisationProjets::affectationProjet.date_fin') }}"
                value="{{ $itemAffectationProjet ? $itemAffectationProjet->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemAffectationProjet" field="sous_groupe_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="sous_groupe_id" id="bulk_field_sous_groupe_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="sous_groupe_id">
            {{ ucfirst(__('PkgApprenants::sousGroupe.singular')) }}
            
          </label>
                      <select 
            id="sous_groupe_id" 
            
            
            
            name="sous_groupe_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sousGroupes as $sousGroupe)
                    <option value="{{ $sousGroupe->id }}"
                        {{ (isset($itemAffectationProjet) && $itemAffectationProjet->sous_groupe_id == $sousGroupe->id) || (old('sous_groupe_id>') == $sousGroupe->id) ? 'selected' : '' }}>
                        {{ $sousGroupe }}
                    </option>
                @endforeach
            </select>
          @error('sous_groupe_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemAffectationProjet" field="echelle_note_cible" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="echelle_note_cible" id="bulk_field_echelle_note_cible" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="echelle_note_cible">
            {{ ucfirst(__('PkgRealisationProjets::affectationProjet.echelle_note_cible')) }}
            
          </label>
                      <input
                name="echelle_note_cible"
                type="number"
                class="form-control"
                
                
                
                id="echelle_note_cible"
                placeholder="{{ __('PkgRealisationProjets::affectationProjet.echelle_note_cible') }}"
                value="{{ $itemAffectationProjet ? $itemAffectationProjet->echelle_note_cible : old('echelle_note_cible') }}">
          @error('echelle_note_cible')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemAffectationProjet" field="evaluateurs" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="evaluateurs" id="bulk_field_evaluateurs" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="evaluateurs">
            {{ ucfirst(__('PkgValidationProjets::evaluateur.plural')) }}
            
          </label>
                      <select
                id="evaluateurs"
                name="evaluateurs[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($evaluateurs as $evaluateur)
                    <option value="{{ $evaluateur->id }}"
                        {{ (isset($itemAffectationProjet) && $itemAffectationProjet->evaluateurs && $itemAffectationProjet->evaluateurs->contains('id', $evaluateur->id)) || (is_array(old('evaluateurs')) && in_array($evaluateur->id, old('evaluateurs'))) ? 'selected' : '' }}>
                        {{ $evaluateur }}
                    </option>
                @endforeach
            </select>
          @error('evaluateurs')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemAffectationProjet" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgRealisationProjets::affectationProjet.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgRealisationProjets::affectationProjet.description') }}">{{ $itemAffectationProjet ? $itemAffectationProjet->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('affectationProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemAffectationProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgRealisationProjets::affectationProjet.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgRealisationProjets::affectationProjet.singular") }} : {{$itemAffectationProjet}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
