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

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="note_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note_cache" id="bulk_field_note_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note_cache">
            {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.note_cache')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="note_cache"
        type="number"
        class="form-control"
        required
        
        
        id="note_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationMicroCompetence.note_cache') }}"
        value="{{ $itemRealisationMicroCompetence ? number_format($itemRealisationMicroCompetence->note_cache, 2, '.', '') : old('note_cache') }}">
          @error('note_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="progression_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="progression_cache" id="bulk_field_progression_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="progression_cache">
            {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.progression_cache')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="progression_cache"
        type="number"
        class="form-control"
        required
        
        
        id="progression_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationMicroCompetence.progression_cache') }}"
        value="{{ $itemRealisationMicroCompetence ? number_format($itemRealisationMicroCompetence->progression_cache, 2, '.', '') : old('progression_cache') }}">
          @error('progression_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="etat_realisation_micro_competence_id" :bulkEdit="$bulkEdit">

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

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="bareme_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="bareme_cache" id="bulk_field_bareme_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="bareme_cache">
            {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.bareme_cache')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="bareme_cache"
        type="number"
        class="form-control"
        required
        
        
        id="bareme_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationMicroCompetence.bareme_cache') }}"
        value="{{ $itemRealisationMicroCompetence ? number_format($itemRealisationMicroCompetence->bareme_cache, 2, '.', '') : old('bareme_cache') }}">
          @error('bareme_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="commentaire_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="commentaire_formateur" id="bulk_field_commentaire_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="commentaire_formateur">
            {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.commentaire_formateur')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="commentaire_formateur"
                class="form-control richText"
                
                
                
                id="commentaire_formateur"
                placeholder="{{ __('PkgApprentissage::realisationMicroCompetence.commentaire_formateur') }}">{{ $itemRealisationMicroCompetence ? $itemRealisationMicroCompetence->commentaire_formateur : old('commentaire_formateur') }}</textarea>
          @error('commentaire_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="date_debut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_debut" id="bulk_field_date_debut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.date_debut')) }}
            
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_debut"
                placeholder="{{ __('PkgApprentissage::realisationMicroCompetence.date_debut') }}"
                value="{{ $itemRealisationMicroCompetence ? $itemRealisationMicroCompetence->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="date_fin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_fin" id="bulk_field_date_fin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgApprentissage::realisationMicroCompetence.date_fin') }}"
                value="{{ $itemRealisationMicroCompetence ? $itemRealisationMicroCompetence->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationMicroCompetence" field="dernier_update" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="dernier_update" id="bulk_field_dernier_update" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dernier_update">
            {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.dernier_update')) }}
            
          </label>
                      <input
                name="dernier_update"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="dernier_update"
                placeholder="{{ __('PkgApprentissage::realisationMicroCompetence.dernier_update') }}"
                value="{{ $itemRealisationMicroCompetence ? $itemRealisationMicroCompetence->dernier_update : old('dernier_update') }}">

          @error('dernier_update')
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
