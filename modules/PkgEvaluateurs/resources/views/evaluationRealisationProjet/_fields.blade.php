{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluationRealisationProjet-form')
<form 
    class="crud-form custom-form context-state container" 
    id="evaluationRealisationProjetForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('evaluationRealisationProjets.bulkUpdate') : ($itemEvaluationRealisationProjet->id ? route('evaluationRealisationProjets.update', $itemEvaluationRealisationProjet->id) : route('evaluationRealisationProjets.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEvaluationRealisationProjet->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($evaluationRealisationProjet_ids))
        @foreach ($evaluationRealisationProjet_ids as $id)
            <input type="hidden" name="evaluationRealisationProjet_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemEvaluationRealisationProjet" field="realisation_projet_id" :bulkEdit="$bulkEdit">
      @php $canEditrealisation_projet_id = !$itemEvaluationRealisationProjet || !$itemEvaluationRealisationProjet->id || Auth::user()->hasAnyRole(explode(',', 'admin')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_projet_id" id="bulk_field_realisation_projet_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_projet_id">
            {{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_projet_id" 
            {{ $canEditrealisation_projet_id ? '' : 'disabled' }}
            required
            
            
            name="realisation_projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationProjets as $realisationProjet)
                    <option value="{{ $realisationProjet->id }}"
                        {{ (isset($itemEvaluationRealisationProjet) && $itemEvaluationRealisationProjet->realisation_projet_id == $realisationProjet->id) || (old('realisation_projet_id>') == $realisationProjet->id) ? 'selected' : '' }}>
                        {{ $realisationProjet }}
                    </option>
                @endforeach
            </select>
          @error('realisation_projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEvaluationRealisationProjet" field="evaluateur_id" :bulkEdit="$bulkEdit">
      @php $canEditevaluateur_id = !$itemEvaluationRealisationProjet || !$itemEvaluationRealisationProjet->id || Auth::user()->hasAnyRole(explode(',', 'admin')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="evaluateur_id" id="bulk_field_evaluateur_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="evaluateur_id">
            {{ ucfirst(__('PkgEvaluateurs::evaluateur.singular')) }}
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
                        {{ (isset($itemEvaluationRealisationProjet) && $itemEvaluationRealisationProjet->evaluateur_id == $evaluateur->id) || (old('evaluateur_id>') == $evaluateur->id) ? 'selected' : '' }}>
                        {{ $evaluateur }}
                    </option>
                @endforeach
            </select>
          @error('evaluateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEvaluationRealisationProjet" field="date_evaluation" :bulkEdit="$bulkEdit">
      @php $canEditdate_evaluation = !$itemEvaluationRealisationProjet || !$itemEvaluationRealisationProjet->id || Auth::user()->hasAnyRole(explode(',', 'admin')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_evaluation" id="bulk_field_date_evaluation" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_evaluation">
            {{ ucfirst(__('PkgEvaluateurs::evaluationRealisationProjet.date_evaluation')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_evaluation"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="date_evaluation"
                {{ $canEditdate_evaluation ? '' : 'disabled' }}
                placeholder="{{ __('PkgEvaluateurs::evaluationRealisationProjet.date_evaluation') }}"
                value="{{ $itemEvaluationRealisationProjet ? $itemEvaluationRealisationProjet->date_evaluation : old('date_evaluation') }}">

          @error('date_evaluation')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEvaluationRealisationProjet" field="etat_evaluation_projet_id" :bulkEdit="$bulkEdit">
      @php $canEditetat_evaluation_projet_id = !$itemEvaluationRealisationProjet || !$itemEvaluationRealisationProjet->id || Auth::user()->hasAnyRole(explode(',', 'admin')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="etat_evaluation_projet_id" id="bulk_field_etat_evaluation_projet_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="etat_evaluation_projet_id">
            {{ ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.singular')) }}
            
          </label>
                      <select 
            id="etat_evaluation_projet_id" 
            {{ $canEditetat_evaluation_projet_id ? '' : 'disabled' }}
            
            
            
            name="etat_evaluation_projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($etatEvaluationProjets as $etatEvaluationProjet)
                    <option value="{{ $etatEvaluationProjet->id }}"
                        {{ (isset($itemEvaluationRealisationProjet) && $itemEvaluationRealisationProjet->etat_evaluation_projet_id == $etatEvaluationProjet->id) || (old('etat_evaluation_projet_id>') == $etatEvaluationProjet->id) ? 'selected' : '' }}>
                        {{ $etatEvaluationProjet }}
                    </option>
                @endforeach
            </select>
          @error('etat_evaluation_projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>



@if($itemEvaluationRealisationProjet->id)
@if (empty($bulkEdit))
<div class="col-12 col-md-12">
   <label for="EvaluationRealisationTache">
            {{ ucfirst(__('PkgEvaluateurs::evaluationRealisationTache.plural')) }}
            
    </label>

  @include('PkgEvaluateurs::evaluationRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'evaluationRealisationProjet.edit_' . $itemEvaluationRealisationProjet->id])
</div>
@endif
@endif


<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEvaluationRealisationProjet" field="remarques" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="remarques" id="bulk_field_remarques" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="remarques">
            {{ ucfirst(__('PkgEvaluateurs::evaluationRealisationProjet.remarques')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="remarques"
                class="form-control richText"
                
                
                
                id="remarques"
                placeholder="{{ __('PkgEvaluateurs::evaluationRealisationProjet.remarques') }}">{{ $itemEvaluationRealisationProjet ? $itemEvaluationRealisationProjet->remarques : old('remarques') }}</textarea>
          @error('remarques')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('evaluationRealisationProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEvaluationRealisationProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgEvaluateurs::evaluationRealisationProjet.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgEvaluateurs::evaluationRealisationProjet.singular") }} : {{$itemEvaluationRealisationProjet}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
