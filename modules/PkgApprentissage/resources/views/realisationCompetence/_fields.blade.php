{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationCompetence-form')
<form 
    class="crud-form custom-form context-state container" 
    id="realisationCompetenceForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('realisationCompetences.bulkUpdate') : ($itemRealisationCompetence->id ? route('realisationCompetences.update', $itemRealisationCompetence->id) : route('realisationCompetences.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRealisationCompetence->id)
        <input type="hidden" name="id" value="{{ $itemRealisationCompetence->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($realisationCompetence_ids))
        @foreach ($realisationCompetence_ids as $id)
            <input type="hidden" name="realisationCompetence_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationCompetence" field="date_debut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_debut" id="bulk_field_date_debut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgApprentissage::realisationCompetence.date_debut')) }}
            
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_debut"
                placeholder="{{ __('PkgApprentissage::realisationCompetence.date_debut') }}"
                value="{{ $itemRealisationCompetence ? $itemRealisationCompetence->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationCompetence" field="date_fin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_fin" id="bulk_field_date_fin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgApprentissage::realisationCompetence.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgApprentissage::realisationCompetence.date_fin') }}"
                value="{{ $itemRealisationCompetence ? $itemRealisationCompetence->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationCompetence" field="progression_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="progression_cache" id="bulk_field_progression_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="progression_cache">
            {{ ucfirst(__('PkgApprentissage::realisationCompetence.progression_cache')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="progression_cache"
        type="number"
        class="form-control"
        required
        
        
        id="progression_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationCompetence.progression_cache') }}"
        value="{{ $itemRealisationCompetence ? number_format($itemRealisationCompetence->progression_cache, 2, '.', '') : old('progression_cache') }}">
          @error('progression_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationCompetence" field="note_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note_cache" id="bulk_field_note_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note_cache">
            {{ ucfirst(__('PkgApprentissage::realisationCompetence.note_cache')) }}
            
          </label>
              <input
        name="note_cache"
        type="number"
        class="form-control"
        
        
        
        id="note_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationCompetence.note_cache') }}"
        value="{{ $itemRealisationCompetence ? number_format($itemRealisationCompetence->note_cache, 2, '.', '') : old('note_cache') }}">
          @error('note_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationCompetence" field="bareme_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="bareme_cache" id="bulk_field_bareme_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="bareme_cache">
            {{ ucfirst(__('PkgApprentissage::realisationCompetence.bareme_cache')) }}
            
          </label>
              <input
        name="bareme_cache"
        type="number"
        class="form-control"
        
        
        
        id="bareme_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationCompetence.bareme_cache') }}"
        value="{{ $itemRealisationCompetence ? number_format($itemRealisationCompetence->bareme_cache, 2, '.', '') : old('bareme_cache') }}">
          @error('bareme_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationCompetence" field="commentaire_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="commentaire_formateur" id="bulk_field_commentaire_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="commentaire_formateur">
            {{ ucfirst(__('PkgApprentissage::realisationCompetence.commentaire_formateur')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="commentaire_formateur"
                class="form-control richText"
                
                
                
                id="commentaire_formateur"
                placeholder="{{ __('PkgApprentissage::realisationCompetence.commentaire_formateur') }}">{{ $itemRealisationCompetence ? $itemRealisationCompetence->commentaire_formateur : old('commentaire_formateur') }}</textarea>
          @error('commentaire_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationCompetence" field="dernier_update" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="dernier_update" id="bulk_field_dernier_update" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dernier_update">
            {{ ucfirst(__('PkgApprentissage::realisationCompetence.dernier_update')) }}
            
          </label>
                      <input
                name="dernier_update"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="dernier_update"
                placeholder="{{ __('PkgApprentissage::realisationCompetence.dernier_update') }}"
                value="{{ $itemRealisationCompetence ? $itemRealisationCompetence->dernier_update : old('dernier_update') }}">

          @error('dernier_update')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationCompetence" field="apprenant_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemRealisationCompetence) && $itemRealisationCompetence->apprenant_id == $apprenant->id) || (old('apprenant_id>') == $apprenant->id) ? 'selected' : '' }}>
                        {{ $apprenant }}
                    </option>
                @endforeach
            </select>
          @error('apprenant_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationCompetence" field="realisation_module_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_module_id" id="bulk_field_realisation_module_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_module_id">
            {{ ucfirst(__('PkgApprentissage::realisationModule.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_module_id" 
            required
            
            
            name="realisation_module_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationModules as $realisationModule)
                    <option value="{{ $realisationModule->id }}"
                        {{ (isset($itemRealisationCompetence) && $itemRealisationCompetence->realisation_module_id == $realisationModule->id) || (old('realisation_module_id>') == $realisationModule->id) ? 'selected' : '' }}>
                        {{ $realisationModule }}
                    </option>
                @endforeach
            </select>
          @error('realisation_module_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationCompetence" field="competence_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
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
                        {{ (isset($itemRealisationCompetence) && $itemRealisationCompetence->competence_id == $competence->id) || (old('competence_id>') == $competence->id) ? 'selected' : '' }}>
                        {{ $competence }}
                    </option>
                @endforeach
            </select>
          @error('competence_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationCompetence" field="etat_realisation_competence_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="etat_realisation_competence_id" id="bulk_field_etat_realisation_competence_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="etat_realisation_competence_id">
            {{ ucfirst(__('PkgApprentissage::etatRealisationCompetence.singular')) }}
            
          </label>
                      <select 
            id="etat_realisation_competence_id" 
            
            
            
            name="etat_realisation_competence_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($etatRealisationCompetences as $etatRealisationCompetence)
                    <option value="{{ $etatRealisationCompetence->id }}"
                        {{ (isset($itemRealisationCompetence) && $itemRealisationCompetence->etat_realisation_competence_id == $etatRealisationCompetence->id) || (old('etat_realisation_competence_id>') == $etatRealisationCompetence->id) ? 'selected' : '' }}>
                        {{ $etatRealisationCompetence }}
                    </option>
                @endforeach
            </select>
          @error('etat_realisation_competence_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('realisationCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationCompetence->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprentissage::realisationCompetence.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprentissage::realisationCompetence.singular") }} : {{$itemRealisationCompetence}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
