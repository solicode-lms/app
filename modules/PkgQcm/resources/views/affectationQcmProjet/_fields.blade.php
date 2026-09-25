{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('affectationQcmProjet-form')
<form 
    class="crud-form custom-form context-state container" 
    id="affectationQcmProjetForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('affectationQcmProjets.bulkUpdate') : ($itemAffectationQcmProjet->id ? route('affectationQcmProjets.update', $itemAffectationQcmProjet->id) : route('affectationQcmProjets.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemAffectationQcmProjet->id)
        <input type="hidden" name="id" value="{{ $itemAffectationQcmProjet->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($affectationQcmProjet_ids))
        @foreach ($affectationQcmProjet_ids as $id)
            <input type="hidden" name="affectationQcmProjet_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemAffectationQcmProjet" field="qcm_id" :bulkEdit="$bulkEdit">

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
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="qcm_id" 
            required
            
            
            name="qcm_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($qcms as $qcm)
                    <option value="{{ $qcm->id }}"
                        {{ (isset($itemAffectationQcmProjet) && $itemAffectationQcmProjet->qcm_id == $qcm->id) || (old('qcm_id>') == $qcm->id) ? 'selected' : '' }}>
                        {{ $qcm }}
                    </option>
                @endforeach
            </select>
          @error('qcm_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemAffectationQcmProjet" field="affectation_projet_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="affectation_projet_id" 
              id="bulk_field_affectation_projet_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
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
                        {{ (isset($itemAffectationQcmProjet) && $itemAffectationQcmProjet->affectation_projet_id == $affectationProjet->id) || (old('affectation_projet_id>') == $affectationProjet->id) ? 'selected' : '' }}>
                        {{ $affectationProjet }}
                    </option>
                @endforeach
            </select>
          @error('affectation_projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemAffectationQcmProjet" field="date_affectation" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="date_affectation" 
              id="bulk_field_date_affectation" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_affectation">
            {{ ucfirst(__('PkgQcm::affectationQcmProjet.date_affectation')) }}
            
          </label>
                      <input
                name="date_affectation"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_affectation"
                placeholder="{{ __('PkgQcm::affectationQcmProjet.date_affectation') }}"
                value="{{ $itemAffectationQcmProjet ? $itemAffectationQcmProjet->date_affectation : old('date_affectation') }}">

          @error('date_affectation')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemAffectationQcmProjet" field="saise_automatique_note_qcm" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="saise_automatique_note_qcm" 
              id="bulk_field_saise_automatique_note_qcm" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="saise_automatique_note_qcm">
            {{ ucfirst(__('PkgQcm::affectationQcmProjet.saise_automatique_note_qcm')) }}
            <span class="text-danger">*</span>
          </label>
                      <input type="hidden" name="saise_automatique_note_qcm" value="0">
            <input
                name="saise_automatique_note_qcm"
                type="checkbox"
                class="form-control d-block"
                required
                
                
                id="saise_automatique_note_qcm"
                value="1"
                {{ old('saise_automatique_note_qcm', $itemAffectationQcmProjet ? $itemAffectationQcmProjet->saise_automatique_note_qcm : 0) ? 'checked' : '' }}>
          @error('saise_automatique_note_qcm')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('affectationQcmProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemAffectationQcmProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgQcm::affectationQcmProjet.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgQcm::affectationQcmProjet.singular") }} : {{$itemAffectationQcmProjet}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
