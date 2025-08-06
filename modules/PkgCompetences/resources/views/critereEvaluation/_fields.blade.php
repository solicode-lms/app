{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('critereEvaluation-form')
<form 
    class="crud-form custom-form context-state container" 
    id="critereEvaluationForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('critereEvaluations.bulkUpdate') : ($itemCritereEvaluation->id ? route('critereEvaluations.update', $itemCritereEvaluation->id) : route('critereEvaluations.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemCritereEvaluation->id)
        <input type="hidden" name="id" value="{{ $itemCritereEvaluation->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($critereEvaluation_ids))
        @foreach ($critereEvaluation_ids as $id)
            <input type="hidden" name="critereEvaluation_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemCritereEvaluation" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgCompetences::critereEvaluation.ordre')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                required
                
                
                id="ordre"
                placeholder="{{ __('PkgCompetences::critereEvaluation.ordre') }}"
                value="{{ $itemCritereEvaluation ? $itemCritereEvaluation->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemCritereEvaluation" field="intitule" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="intitule" id="bulk_field_intitule" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="intitule">
            {{ ucfirst(__('PkgCompetences::critereEvaluation.intitule')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="intitule"
                class="form-control richText"
                required
                
                
                id="intitule"
                placeholder="{{ __('PkgCompetences::critereEvaluation.intitule') }}">{{ $itemCritereEvaluation ? $itemCritereEvaluation->intitule : old('intitule') }}</textarea>
          @error('intitule')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemCritereEvaluation" field="bareme" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="bareme" id="bulk_field_bareme" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="bareme">
            {{ ucfirst(__('PkgCompetences::critereEvaluation.bareme')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="bareme"
        type="number"
        class="form-control"
        required
        
        
        id="bareme"
        step="0.01"
        placeholder="{{ __('PkgCompetences::critereEvaluation.bareme') }}"
        value="{{ $itemCritereEvaluation ? number_format($itemCritereEvaluation->bareme, 2, '.', '') : old('bareme') }}">
          @error('bareme')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemCritereEvaluation" field="phase_evaluation_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="phase_evaluation_id" id="bulk_field_phase_evaluation_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="phase_evaluation_id">
            {{ ucfirst(__('PkgCompetences::phaseEvaluation.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="phase_evaluation_id" 
            required
            
            
            name="phase_evaluation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($phaseEvaluations as $phaseEvaluation)
                    <option value="{{ $phaseEvaluation->id }}"
                        {{ (isset($itemCritereEvaluation) && $itemCritereEvaluation->phase_evaluation_id == $phaseEvaluation->id) || (old('phase_evaluation_id>') == $phaseEvaluation->id) ? 'selected' : '' }}>
                        {{ $phaseEvaluation }}
                    </option>
                @endforeach
            </select>
          @error('phase_evaluation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemCritereEvaluation" field="unite_apprentissage_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="unite_apprentissage_id" id="bulk_field_unite_apprentissage_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="unite_apprentissage_id">
            {{ ucfirst(__('PkgCompetences::uniteApprentissage.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="unite_apprentissage_id" 
            required
            
            
            name="unite_apprentissage_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($uniteApprentissages as $uniteApprentissage)
                    <option value="{{ $uniteApprentissage->id }}"
                        {{ (isset($itemCritereEvaluation) && $itemCritereEvaluation->unite_apprentissage_id == $uniteApprentissage->id) || (old('unite_apprentissage_id>') == $uniteApprentissage->id) ? 'selected' : '' }}>
                        {{ $uniteApprentissage }}
                    </option>
                @endforeach
            </select>
          @error('unite_apprentissage_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('critereEvaluations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemCritereEvaluation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgCompetences::critereEvaluation.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCompetences::critereEvaluation.singular") }} : {{$itemCritereEvaluation}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
