{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('phaseEvaluation-form')
<form 
    class="crud-form custom-form context-state container" 
    id="phaseEvaluationForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('phaseEvaluations.bulkUpdate') : ($itemPhaseEvaluation->id ? route('phaseEvaluations.update', $itemPhaseEvaluation->id) : route('phaseEvaluations.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemPhaseEvaluation->id)
        <input type="hidden" name="id" value="{{ $itemPhaseEvaluation->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($phaseEvaluation_ids))
        @foreach ($phaseEvaluation_ids as $id)
            <input type="hidden" name="phaseEvaluation_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemPhaseEvaluation" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgCompetences::phaseEvaluation.ordre')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                required
                
                
                id="ordre"
                placeholder="{{ __('PkgCompetences::phaseEvaluation.ordre') }}"
                value="{{ $itemPhaseEvaluation ? $itemPhaseEvaluation->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemPhaseEvaluation" field="code" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="code" id="bulk_field_code" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="code">
            {{ ucfirst(__('PkgCompetences::phaseEvaluation.code')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="code"
                type="input"
                class="form-control"
                required
                
                
                id="code"
                placeholder="{{ __('PkgCompetences::phaseEvaluation.code') }}"
                value="{{ $itemPhaseEvaluation ? $itemPhaseEvaluation->code : old('code') }}">
          @error('code')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemPhaseEvaluation" field="libelle" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="libelle" id="bulk_field_libelle" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="libelle">
            {{ ucfirst(__('PkgCompetences::phaseEvaluation.libelle')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="libelle"
                type="input"
                class="form-control"
                required
                
                
                id="libelle"
                placeholder="{{ __('PkgCompetences::phaseEvaluation.libelle') }}"
                value="{{ $itemPhaseEvaluation ? $itemPhaseEvaluation->libelle : old('libelle') }}">
          @error('libelle')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemPhaseEvaluation" field="coefficient" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="coefficient" id="bulk_field_coefficient" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="coefficient">
            {{ ucfirst(__('PkgCompetences::phaseEvaluation.coefficient')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="coefficient"
        type="number"
        class="form-control"
        required
        
        
        id="coefficient"
        step="0.01"
        placeholder="{{ __('PkgCompetences::phaseEvaluation.coefficient') }}"
        value="{{ $itemPhaseEvaluation ? number_format($itemPhaseEvaluation->coefficient, 2, '.', '') : old('coefficient') }}">
          @error('coefficient')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemPhaseEvaluation" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgCompetences::phaseEvaluation.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgCompetences::phaseEvaluation.description') }}">{{ $itemPhaseEvaluation ? $itemPhaseEvaluation->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('phaseEvaluations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemPhaseEvaluation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgCompetences::phaseEvaluation.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCompetences::phaseEvaluation.singular") }} : {{$itemPhaseEvaluation}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
