{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationProjet-form')
<form 
    class="crud-form custom-form context-state container" 
    id="realisationProjetForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('realisationProjets.bulkUpdate') : ($itemRealisationProjet->id ? route('realisationProjets.update', $itemRealisationProjet->id) : route('realisationProjets.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRealisationProjet->id)
        <input type="hidden" name="id" value="{{ $itemRealisationProjet->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($realisationProjet_ids))
        @foreach ($realisationProjet_ids as $id)
            <input type="hidden" name="realisationProjet_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationProjet" field="affectation_projet_id" :bulkEdit="$bulkEdit">
      @php $canEditaffectation_projet_id = !$itemRealisationProjet || !$itemRealisationProjet->id || Auth::user()->hasAnyRole(explode(',', 'formateur')); @endphp

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
            {{ $canEditaffectation_projet_id ? '' : 'disabled' }}
            required
            
            
            name="affectation_projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($affectationProjets as $affectationProjet)
                    <option value="{{ $affectationProjet->id }}"
                        {{ (isset($itemRealisationProjet) && $itemRealisationProjet->affectation_projet_id == $affectationProjet->id) || (old('affectation_projet_id>') == $affectationProjet->id) ? 'selected' : '' }}>
                        {{ $affectationProjet }}
                    </option>
                @endforeach
            </select>
          @error('affectation_projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationProjet" field="apprenant_id" :bulkEdit="$bulkEdit">
      @php $canEditapprenant_id = !$itemRealisationProjet || !$itemRealisationProjet->id || Auth::user()->hasAnyRole(explode(',', 'formateur')); @endphp

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
                        {{ (isset($itemRealisationProjet) && $itemRealisationProjet->apprenant_id == $apprenant->id) || (old('apprenant_id>') == $apprenant->id) ? 'selected' : '' }}>
                        {{ $apprenant }}
                    </option>
                @endforeach
            </select>
          @error('apprenant_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationProjet" field="date_debut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_debut" id="bulk_field_date_debut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgRealisationProjets::realisationProjet.date_debut')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="date_debut"
                placeholder="{{ __('PkgRealisationProjets::realisationProjet.date_debut') }}"
                value="{{ $itemRealisationProjet ? $itemRealisationProjet->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationProjet" field="date_fin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_fin" id="bulk_field_date_fin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgRealisationProjets::realisationProjet.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgRealisationProjets::realisationProjet.date_fin') }}"
                value="{{ $itemRealisationProjet ? $itemRealisationProjet->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationProjet" field="etats_realisation_projet_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="etats_realisation_projet_id" id="bulk_field_etats_realisation_projet_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="etats_realisation_projet_id">
            {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="etats_realisation_projet_id" 
            required
            
            
            name="etats_realisation_projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($etatsRealisationProjets as $etatsRealisationProjet)
                    <option value="{{ $etatsRealisationProjet->id }}"
                        {{ (isset($itemRealisationProjet) && $itemRealisationProjet->etats_realisation_projet_id == $etatsRealisationProjet->id) || (old('etats_realisation_projet_id>') == $etatsRealisationProjet->id) ? 'selected' : '' }}>
                        {{ $etatsRealisationProjet }}
                    </option>
                @endforeach
            </select>
          @error('etats_realisation_projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationProjet" field="note_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note_cache" id="bulk_field_note_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note_cache">
            {{ ucfirst(__('PkgRealisationProjets::realisationProjet.note_cache')) }}
            
          </label>
              <input
        name="note_cache"
        type="number"
        class="form-control"
        
        
        
        id="note_cache"
        step="0.01"
        placeholder="{{ __('PkgRealisationProjets::realisationProjet.note_cache') }}"
        value="{{ $itemRealisationProjet ? number_format($itemRealisationProjet->note_cache, 2, '.', '') : old('note_cache') }}">
          @error('note_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationProjet" field="rapport" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="rapport" id="bulk_field_rapport" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="rapport">
            {{ ucfirst(__('PkgRealisationProjets::realisationProjet.rapport')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="rapport"
                class="form-control richText"
                
                
                
                id="rapport"
                placeholder="{{ __('PkgRealisationProjets::realisationProjet.rapport') }}">{{ $itemRealisationProjet ? $itemRealisationProjet->rapport : old('rapport') }}</textarea>
          @error('rapport')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationProjet" field="bareme_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="bareme_cache" id="bulk_field_bareme_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="bareme_cache">
            {{ ucfirst(__('PkgRealisationProjets::realisationProjet.bareme_cache')) }}
            
          </label>
              <input
        name="bareme_cache"
        type="number"
        class="form-control"
        
        
        
        id="bareme_cache"
        step="0.01"
        placeholder="{{ __('PkgRealisationProjets::realisationProjet.bareme_cache') }}"
        value="{{ $itemRealisationProjet ? number_format($itemRealisationProjet->bareme_cache, 2, '.', '') : old('bareme_cache') }}">
          @error('bareme_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationProjet" field="progression_execution_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="progression_execution_cache" id="bulk_field_progression_execution_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="progression_execution_cache">
            {{ ucfirst(__('PkgRealisationProjets::realisationProjet.progression_execution_cache')) }}
            
          </label>
              <input
        name="progression_execution_cache"
        type="number"
        class="form-control"
        
        
        
        id="progression_execution_cache"
        step="0.01"
        placeholder="{{ __('PkgRealisationProjets::realisationProjet.progression_execution_cache') }}"
        value="{{ $itemRealisationProjet ? number_format($itemRealisationProjet->progression_execution_cache, 2, '.', '') : old('progression_execution_cache') }}">
          @error('progression_execution_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationProjet" field="progression_validation_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="progression_validation_cache" id="bulk_field_progression_validation_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="progression_validation_cache">
            {{ ucfirst(__('PkgRealisationProjets::realisationProjet.progression_validation_cache')) }}
            
          </label>
              <input
        name="progression_validation_cache"
        type="number"
        class="form-control"
        
        
        
        id="progression_validation_cache"
        step="0.01"
        placeholder="{{ __('PkgRealisationProjets::realisationProjet.progression_validation_cache') }}"
        value="{{ $itemRealisationProjet ? number_format($itemRealisationProjet->progression_validation_cache, 2, '.', '') : old('progression_validation_cache') }}">
          @error('progression_validation_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('realisationProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgRealisationProjets::realisationProjet.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgRealisationProjets::realisationProjet.singular") }} : {{$itemRealisationProjet}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
