{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('tacheAffectation-form')
<form 
    class="crud-form custom-form context-state container" 
    id="tacheAffectationForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('tacheAffectations.bulkUpdate') : ($itemTacheAffectation->id ? route('tacheAffectations.update', $itemTacheAffectation->id) : route('tacheAffectations.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemTacheAffectation->id)
        <input type="hidden" name="id" value="{{ $itemTacheAffectation->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($tacheAffectation_ids))
        @foreach ($tacheAffectation_ids as $id)
            <input type="hidden" name="tacheAffectation_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemTacheAffectation" field="tache_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="tache_id" id="bulk_field_tache_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="tache_id">
            {{ ucfirst(__('PkgCreationTache::tache.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="tache_id" 
            required
            
            
            name="tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($taches as $tache)
                    <option value="{{ $tache->id }}"
                        {{ (isset($itemTacheAffectation) && $itemTacheAffectation->tache_id == $tache->id) || (old('tache_id>') == $tache->id) ? 'selected' : '' }}>
                        {{ $tache }}
                    </option>
                @endforeach
            </select>
          @error('tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTacheAffectation" field="affectation_projet_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="affectation_projet_id" id="bulk_field_affectation_projet_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="affectation_projet_id">
            {{ ucfirst(__('PkgRealisationProjets::affectationProjet.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="affectation_projet_id" 
            required
            
            
            name="affectation_projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($affectationProjets as $affectationProjet)
                    <option value="{{ $affectationProjet->id }}"
                        {{ (isset($itemTacheAffectation) && $itemTacheAffectation->affectation_projet_id == $affectationProjet->id) || (old('affectation_projet_id>') == $affectationProjet->id) ? 'selected' : '' }}>
                        {{ $affectationProjet }}
                    </option>
                @endforeach
            </select>
          @error('affectation_projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTacheAffectation" field="pourcentage_realisation_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="pourcentage_realisation_cache" id="bulk_field_pourcentage_realisation_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="pourcentage_realisation_cache">
            {{ ucfirst(__('PkgRealisationTache::tacheAffectation.pourcentage_realisation_cache')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="pourcentage_realisation_cache"
        type="number"
        class="form-control"
        required
        
        
        id="pourcentage_realisation_cache"
        step="0.01"
        placeholder="{{ __('PkgRealisationTache::tacheAffectation.pourcentage_realisation_cache') }}"
        value="{{ $itemTacheAffectation ? number_format($itemTacheAffectation->pourcentage_realisation_cache, 2, '.', '') : old('pourcentage_realisation_cache') }}">
          @error('pourcentage_realisation_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTacheAffectation" field="apprenant_live_coding_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="apprenant_live_coding_cache" id="bulk_field_apprenant_live_coding_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="apprenant_live_coding_cache">
            {{ ucfirst(__('PkgRealisationTache::tacheAffectation.apprenant_live_coding_cache')) }}
            
          </label>
              <div class="form-control editeur_json code-editor"
        contenteditable="true">{{ $itemTacheAffectation ? $itemTacheAffectation->apprenant_live_coding_cache : old('apprenant_live_coding_cache') }}</div>
    <input
        type="hidden"
        name="apprenant_live_coding_cache"
        class="form-control"
        id="apprenant_live_coding_cache"
         
        
        
        value = "{{ $itemTacheAffectation ? $itemTacheAffectation->apprenant_live_coding_cache : old('apprenant_live_coding_cache') }}"
    >
          @error('apprenant_live_coding_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('tacheAffectations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemTacheAffectation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgRealisationTache::tacheAffectation.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgRealisationTache::tacheAffectation.singular") }} : {{$itemTacheAffectation}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
