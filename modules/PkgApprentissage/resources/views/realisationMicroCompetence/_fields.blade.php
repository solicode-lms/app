{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationMicroCompetence-form')
<form 
    class="crud-form custom-form context-state container" 
    id="realisationMicroCompetenceForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('realisationMicroCompetences.bulkUpdate') : ($itemRealisationMicroCompetence->id ? route('realisationMicroCompetences.update', $itemRealisationMicroCompetence->id) : route('realisationMicroCompetences.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRealisationMicroCompetence->id)
        <input type="hidden" name="id" value="{{ $itemRealisationMicroCompetence->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($realisationMicroCompetence_ids))
        @foreach ($realisationMicroCompetence_ids as $id)
            <input type="hidden" name="realisationMicroCompetence_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="micro_competence_id" :bulkEdit="$bulkEdit">
      @php $canEditmicro_competence_id = !$itemRealisationMicroCompetence || !$itemRealisationMicroCompetence->id || Auth::user()->hasAnyRole(explode(',', 'root')); @endphp

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="micro_competence_id" id="bulk_field_micro_competence_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="micro_competence_id">
            {{ ucfirst(__('PkgCompetences::microCompetence.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="micro_competence_id" 
            {{ $canEditmicro_competence_id ? '' : 'disabled' }}
            required
            
            
            name="micro_competence_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($microCompetences as $microCompetence)
                    <option value="{{ $microCompetence->id }}"
                        {{ (isset($itemRealisationMicroCompetence) && $itemRealisationMicroCompetence->micro_competence_id == $microCompetence->id) || (old('micro_competence_id>') == $microCompetence->id) ? 'selected' : '' }}>
                        {{ $microCompetence }}
                    </option>
                @endforeach
            </select>
          @error('micro_competence_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="apprenant_id" :bulkEdit="$bulkEdit">
      @php $canEditapprenant_id = !$itemRealisationMicroCompetence || !$itemRealisationMicroCompetence->id || Auth::user()->hasAnyRole(explode(',', 'root')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="apprenant_id" id="bulk_field_apprenant_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="apprenant_id">
            {{ ucfirst(__('PkgApprenants::apprenant.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="apprenant_id" 
            {{ $canEditapprenant_id ? '' : 'disabled' }}
            required
            
            
            name="apprenant_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($apprenants as $apprenant)
                    <option value="{{ $apprenant->id }}"
                        {{ (isset($itemRealisationMicroCompetence) && $itemRealisationMicroCompetence->apprenant_id == $apprenant->id) || (old('apprenant_id>') == $apprenant->id) ? 'selected' : '' }}>
                        {{ $apprenant }}
                    </option>
                @endforeach
            </select>
          @error('apprenant_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="etat_realisation_micro_competence_id" :bulkEdit="$bulkEdit">
      @php $canEditetat_realisation_micro_competence_id = !$itemRealisationMicroCompetence || !$itemRealisationMicroCompetence->id || Auth::user()->hasAnyRole(explode(',', 'root')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="etat_realisation_micro_competence_id" id="bulk_field_etat_realisation_micro_competence_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="etat_realisation_micro_competence_id">
            {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.etat_realisation_micro_competence_id')) }}
            
          </label>
                      <select 
            id="etat_realisation_micro_competence_id" 
            {{ $canEditetat_realisation_micro_competence_id ? '' : 'disabled' }}
            
            
            
            name="etat_realisation_micro_competence_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($etatRealisationMicroCompetences as $etatRealisationMicroCompetence)
                    <option value="{{ $etatRealisationMicroCompetence->id }}"
                        {{ (isset($itemRealisationMicroCompetence) && $itemRealisationMicroCompetence->etat_realisation_micro_competence_id == $etatRealisationMicroCompetence->id) || (old('etat_realisation_micro_competence_id>') == $etatRealisationMicroCompetence->id) ? 'selected' : '' }}>
                        {{ $etatRealisationMicroCompetence }}
                    </option>
                @endforeach
            </select>
          @error('etat_realisation_micro_competence_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="lien_livrable" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="lien_livrable" id="bulk_field_lien_livrable" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="lien_livrable">
            {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.lien_livrable')) }}
            
          </label>
           <input
                name="lien_livrable"
                type="input"
                class="form-control"
                
                
                
                id="lien_livrable"
                placeholder="{{ __('PkgApprentissage::realisationMicroCompetence.lien_livrable') }}"
                value="{{ $itemRealisationMicroCompetence ? $itemRealisationMicroCompetence->lien_livrable : old('lien_livrable') }}">
          @error('lien_livrable')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="progression_ideal_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="progression_ideal_cache" id="bulk_field_progression_ideal_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="progression_ideal_cache">
            {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.progression_ideal_cache')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="progression_ideal_cache"
        type="number"
        class="form-control"
        required
        
        
        id="progression_ideal_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationMicroCompetence.progression_ideal_cache') }}"
        value="{{ $itemRealisationMicroCompetence ? number_format($itemRealisationMicroCompetence->progression_ideal_cache, 2, '.', '') : old('progression_ideal_cache') }}">
          @error('progression_ideal_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="taux_rythme_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="taux_rythme_cache" id="bulk_field_taux_rythme_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="taux_rythme_cache">
            {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.taux_rythme_cache')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="taux_rythme_cache"
        type="number"
        class="form-control"
        required
        
        
        id="taux_rythme_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationMicroCompetence.taux_rythme_cache') }}"
        value="{{ $itemRealisationMicroCompetence ? number_format($itemRealisationMicroCompetence->taux_rythme_cache, 2, '.', '') : old('taux_rythme_cache') }}">
          @error('taux_rythme_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('realisationMicroCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationMicroCompetence->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprentissage::realisationMicroCompetence.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprentissage::realisationMicroCompetence.singular") }} : {{$itemRealisationMicroCompetence}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
