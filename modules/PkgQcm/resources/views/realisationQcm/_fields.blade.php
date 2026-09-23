{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationQcm-form')
<form 
    class="crud-form custom-form context-state container" 
    id="realisationQcmForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('realisationQcms.bulkUpdate') : ($itemRealisationQcm->id ? route('realisationQcms.update', $itemRealisationQcm->id) : route('realisationQcms.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRealisationQcm->id)
        <input type="hidden" name="id" value="{{ $itemRealisationQcm->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($realisationQcm_ids))
        @foreach ($realisationQcm_ids as $id)
            <input type="hidden" name="realisationQcm_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationQcm" field="affectation_qcm_projet_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="affectation_qcm_projet_id" 
              id="bulk_field_affectation_qcm_projet_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="affectation_qcm_projet_id">
            {{ ucfirst(__('PkgQcm::affectationQcmProjet.singular')) }}
            
          </label>
                      <select 
            id="affectation_qcm_projet_id" 
            
            
            
            name="affectation_qcm_projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($affectationQcmProjets as $affectationQcmProjet)
                    <option value="{{ $affectationQcmProjet->id }}"
                        {{ (isset($itemRealisationQcm) && $itemRealisationQcm->affectation_qcm_projet_id == $affectationQcmProjet->id) || (old('affectation_qcm_projet_id>') == $affectationQcmProjet->id) ? 'selected' : '' }}>
                        {{ $affectationQcmProjet }}
                    </option>
                @endforeach
            </select>
          @error('affectation_qcm_projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationQcm" field="qcm_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="qcm_id" 
              id="bulk_field_qcm_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="qcm_id">
            {{ ucfirst(__('PkgQcm::qcm.singular')) }}
            
          </label>
                      <select 
            id="qcm_id" 
            
            
            
            name="qcm_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($qcms as $qcm)
                    <option value="{{ $qcm->id }}"
                        {{ (isset($itemRealisationQcm) && $itemRealisationQcm->qcm_id == $qcm->id) || (old('qcm_id>') == $qcm->id) ? 'selected' : '' }}>
                        {{ $qcm }}
                    </option>
                @endforeach
            </select>
          @error('qcm_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationQcm" field="apprenant_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="apprenant_id" 
              id="bulk_field_apprenant_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="apprenant_id">
            {{ ucfirst(__('PkgApprenants::apprenant.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="apprenant_id" 
            required
            
            
            name="apprenant_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($apprenants as $apprenant)
                    <option value="{{ $apprenant->id }}"
                        {{ (isset($itemRealisationQcm) && $itemRealisationQcm->apprenant_id == $apprenant->id) || (old('apprenant_id>') == $apprenant->id) ? 'selected' : '' }}>
                        {{ $apprenant }}
                    </option>
                @endforeach
            </select>
          @error('apprenant_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationQcm" field="etat_realisation_qcm_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="etat_realisation_qcm_id" 
              id="bulk_field_etat_realisation_qcm_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="etat_realisation_qcm_id">
            {{ ucfirst(__('PkgQcm::etatRealisationQcm.singular')) }}
            
          </label>
                      <select 
            id="etat_realisation_qcm_id" 
            
            
            
            name="etat_realisation_qcm_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($etatRealisationQcms as $etatRealisationQcm)
                    <option value="{{ $etatRealisationQcm->id }}"
                        {{ (isset($itemRealisationQcm) && $itemRealisationQcm->etat_realisation_qcm_id == $etatRealisationQcm->id) || (old('etat_realisation_qcm_id>') == $etatRealisationQcm->id) ? 'selected' : '' }}>
                        {{ $etatRealisationQcm }}
                    </option>
                @endforeach
            </select>
          @error('etat_realisation_qcm_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationQcm" field="date_debut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="date_debut" 
              id="bulk_field_date_debut" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgQcm::realisationQcm.date_debut')) }}
            
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_debut"
                placeholder="{{ __('PkgQcm::realisationQcm.date_debut') }}"
                value="{{ $itemRealisationQcm ? $itemRealisationQcm->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationQcm" field="date_fin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="date_fin" 
              id="bulk_field_date_fin" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgQcm::realisationQcm.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgQcm::realisationQcm.date_fin') }}"
                value="{{ $itemRealisationQcm ? $itemRealisationQcm->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationQcm" field="date_soumission" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="date_soumission" 
              id="bulk_field_date_soumission" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_soumission">
            {{ ucfirst(__('PkgQcm::realisationQcm.date_soumission')) }}
            
          </label>
                      <input
                name="date_soumission"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_soumission"
                placeholder="{{ __('PkgQcm::realisationQcm.date_soumission') }}"
                value="{{ $itemRealisationQcm ? $itemRealisationQcm->date_soumission : old('date_soumission') }}">

          @error('date_soumission')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationQcm" field="date_validation" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="date_validation" 
              id="bulk_field_date_validation" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_validation">
            {{ ucfirst(__('PkgQcm::realisationQcm.date_validation')) }}
            
          </label>
                      <input
                name="date_validation"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_validation"
                placeholder="{{ __('PkgQcm::realisationQcm.date_validation') }}"
                value="{{ $itemRealisationQcm ? $itemRealisationQcm->date_validation : old('date_validation') }}">

          @error('date_validation')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationQcm" field="note_obtenu" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="note_obtenu" 
              id="bulk_field_note_obtenu" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note_obtenu">
            {{ ucfirst(__('PkgQcm::realisationQcm.note_obtenu')) }}
            
          </label>
              <input
        name="note_obtenu"
        type="number"
        class="form-control"
        
        
        
        id="note_obtenu"
        step="0.01"
        placeholder="{{ __('PkgQcm::realisationQcm.note_obtenu') }}"
        value="{{ $itemRealisationQcm ? number_format($itemRealisationQcm->note_obtenu, 2, '.', '') : old('note_obtenu') }}">
          @error('note_obtenu')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationQcm" field="statut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="statut" 
              id="bulk_field_statut" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="statut">
            {{ ucfirst(__('PkgQcm::realisationQcm.statut')) }}
            
          </label>
           <input
                name="statut"
                type="input"
                class="form-control"
                
                
                
                id="statut"
                placeholder="{{ __('PkgQcm::realisationQcm.statut') }}"
                value="{{ $itemRealisationQcm ? $itemRealisationQcm->statut : old('statut') }}">
          @error('statut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('realisationQcms.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationQcm->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgQcm::realisationQcm.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgQcm::realisationQcm.singular") }} : {{$itemRealisationQcm}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
