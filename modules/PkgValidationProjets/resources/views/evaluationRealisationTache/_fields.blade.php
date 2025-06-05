{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluationRealisationTache-form')
<form 
    class="crud-form custom-form context-state container" 
    id="evaluationRealisationTacheForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('evaluationRealisationTaches.bulkUpdate') : ($itemEvaluationRealisationTache->id ? route('evaluationRealisationTaches.update', $itemEvaluationRealisationTache->id) : route('evaluationRealisationTaches.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEvaluationRealisationTache->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($evaluationRealisationTache_ids))
        @foreach ($evaluationRealisationTache_ids as $id)
            <input type="hidden" name="evaluationRealisationTache_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemEvaluationRealisationTache" field="realisation_tache_id" :bulkEdit="$bulkEdit">
      @php $canEditrealisation_tache_id = !$itemEvaluationRealisationTache || !$itemEvaluationRealisationTache->id || Auth::user()->hasAnyRole(explode(',', 'admin')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_tache_id" id="bulk_field_realisation_tache_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_tache_id">
            {{ ucfirst(__('PkgGestionTaches::realisationTache.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_tache_id" 
            {{ $canEditrealisation_tache_id ? '' : 'disabled' }}
            required
            
            
            name="realisation_tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationTaches as $realisationTache)
                    <option value="{{ $realisationTache->id }}"
                        {{ (isset($itemEvaluationRealisationTache) && $itemEvaluationRealisationTache->realisation_tache_id == $realisationTache->id) || (old('realisation_tache_id>') == $realisationTache->id) ? 'selected' : '' }}>
                        {{ $realisationTache }}
                    </option>
                @endforeach
            </select>
          @error('realisation_tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEvaluationRealisationTache" field="evaluateur_id" :bulkEdit="$bulkEdit">
      @php $canEditevaluateur_id = !$itemEvaluationRealisationTache || !$itemEvaluationRealisationTache->id || Auth::user()->hasAnyRole(explode(',', 'admin')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="evaluateur_id" id="bulk_field_evaluateur_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="evaluateur_id">
            {{ ucfirst(__('PkgValidationProjets::evaluateur.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="evaluateur_id" 
            {{ $canEditevaluateur_id ? '' : 'disabled' }}
            required
            
            
            name="evaluateur_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($evaluateurs as $evaluateur)
                    <option value="{{ $evaluateur->id }}"
                        {{ (isset($itemEvaluationRealisationTache) && $itemEvaluationRealisationTache->evaluateur_id == $evaluateur->id) || (old('evaluateur_id>') == $evaluateur->id) ? 'selected' : '' }}>
                        {{ $evaluateur }}
                    </option>
                @endforeach
            </select>
          @error('evaluateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEvaluationRealisationTache" field="note" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note" id="bulk_field_note" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note">
            {{ ucfirst(__('PkgValidationProjets::evaluationRealisationTache.note')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="note"
        type="number"
        class="form-control"
        required
        
        
        id="note"
        step="0.01"
        placeholder="{{ __('PkgValidationProjets::evaluationRealisationTache.note') }}"
        value="{{ $itemEvaluationRealisationTache ? number_format($itemEvaluationRealisationTache->note, 2, '.', '') : old('note') }}">
          @error('note')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEvaluationRealisationTache" field="message" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="message" id="bulk_field_message" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="message">
            {{ ucfirst(__('PkgValidationProjets::evaluationRealisationTache.message')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="message"
                class="form-control richText"
                
                
                
                id="message"
                placeholder="{{ __('PkgValidationProjets::evaluationRealisationTache.message') }}">{{ $itemEvaluationRealisationTache ? $itemEvaluationRealisationTache->message : old('message') }}</textarea>
          @error('message')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEvaluationRealisationTache" field="evaluation_realisation_projet_id" :bulkEdit="$bulkEdit">
      @php $canEditevaluation_realisation_projet_id = !$itemEvaluationRealisationTache || !$itemEvaluationRealisationTache->id || Auth::user()->hasAnyRole(explode(',', 'admin')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="evaluation_realisation_projet_id" id="bulk_field_evaluation_realisation_projet_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="evaluation_realisation_projet_id">
            {{ ucfirst(__('PkgValidationProjets::evaluationRealisationProjet.singular')) }}
            
          </label>
                      <select 
            id="evaluation_realisation_projet_id" 
            {{ $canEditevaluation_realisation_projet_id ? '' : 'disabled' }}
            
            
            
            name="evaluation_realisation_projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($evaluationRealisationProjets as $evaluationRealisationProjet)
                    <option value="{{ $evaluationRealisationProjet->id }}"
                        {{ (isset($itemEvaluationRealisationTache) && $itemEvaluationRealisationTache->evaluation_realisation_projet_id == $evaluationRealisationProjet->id) || (old('evaluation_realisation_projet_id>') == $evaluationRealisationProjet->id) ? 'selected' : '' }}>
                        {{ $evaluationRealisationProjet }}
                    </option>
                @endforeach
            </select>
          @error('evaluation_realisation_projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('evaluationRealisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEvaluationRealisationTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgValidationProjets::evaluationRealisationTache.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgValidationProjets::evaluationRealisationTache.singular") }} : {{$itemEvaluationRealisationTache}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
