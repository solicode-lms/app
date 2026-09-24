{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('question-form')
<form 
    class="crud-form custom-form context-state container" 
    id="questionForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('questions.bulkUpdate') : ($itemQuestion->id ? route('questions.update', $itemQuestion->id) : route('questions.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemQuestion->id)
        <input type="hidden" name="id" value="{{ $itemQuestion->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($question_ids))
        @foreach ($question_ids as $id)
            <input type="hidden" name="question_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestion" field="enonce" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="enonce" 
              id="bulk_field_enonce" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="enonce">
            {{ ucfirst(__('PkgQcm::question.enonce')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="enonce"
                class="form-control richText"
                required
                
                
                id="enonce">
                {!! \App\Helpers\TextHelper::sanitizeTextarea(old('enonce', $itemQuestion->enonce ?? '')) !!}
                </textarea>
          @error('enonce')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


@if($itemQuestion->id)
@if(
  (auth()->user()?->can('show-propositionReponse') && $itemQuestion->propositionReponses->isNotEmpty())  
  || auth()->user()?->can('create-propositionReponse')
  || (auth()->user()?->can('edit-propositionReponse')  && $itemQuestion->propositionReponses->isNotEmpty() )
  )
@if (empty($bulkEdit))
<div class="col-12 col-md-12">
   <label for="PropositionReponse">
            {{ ucfirst(__('PkgQcm::propositionReponse.plural')) }}
            
    </label>

  @include('PkgQcm::propositionReponse._index',['isMany' => true, "edit_has_many" => false, "data_calcul" => false ,"parent_manager_id" => "question-crud","contextKey" => 'question.edit_' . $itemQuestion->id])
</div>
@endif
@endif
@endif

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestion" field="type" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="type" 
              id="bulk_field_type" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="type">
            {{ ucfirst(__('PkgQcm::question.type')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="type"
                type="input"
                class="form-control"
                required
                
                
                id="type"
                placeholder="{{ __('PkgQcm::question.type') }}"
                value="{{ $itemQuestion ? $itemQuestion->type : old('type') }}">
          @error('type')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestion" field="is_actif" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="is_actif" 
              id="bulk_field_is_actif" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_actif">
            {{ ucfirst(__('PkgQcm::question.is_actif')) }}
            
          </label>
                      <input type="hidden" name="is_actif" value="0">
            <input
                name="is_actif"
                type="checkbox"
                class="form-control d-block"
                
                
                
                id="is_actif"
                value="1"
                {{ old('is_actif', $itemQuestion ? $itemQuestion->is_actif : 0) ? 'checked' : '' }}>
          @error('is_actif')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestion" field="bareme" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="bareme" 
              id="bulk_field_bareme" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="bareme">
            {{ ucfirst(__('PkgQcm::question.bareme')) }}
            
          </label>
              <input
        name="bareme"
        type="number"
        class="form-control"
        
        
        
        id="bareme"
        step="0.01"
        placeholder="{{ __('PkgQcm::question.bareme') }}"
        value="{{ $itemQuestion ? number_format($itemQuestion->bareme, 2, '.', '') : old('bareme') }}">
          @error('bareme')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestion" field="qcm_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemQuestion) && $itemQuestion->qcm_id == $qcm->id) || (old('qcm_id>') == $qcm->id) ? 'selected' : '' }}>
                        {{ $qcm }}
                    </option>
                @endforeach
            </select>
          @error('qcm_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestion" field="unite_apprentissage_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="unite_apprentissage_id" 
              id="bulk_field_unite_apprentissage_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="unite_apprentissage_id">
            {{ ucfirst(__('PkgCompetences::uniteApprentissage.singular')) }}
            
          </label>
                      <select 
            id="unite_apprentissage_id" 
            
            
            
            name="unite_apprentissage_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($uniteApprentissages as $uniteApprentissage)
                    <option value="{{ $uniteApprentissage->id }}"
                        {{ (isset($itemQuestion) && $itemQuestion->unite_apprentissage_id == $uniteApprentissage->id) || (old('unite_apprentissage_id>') == $uniteApprentissage->id) ? 'selected' : '' }}>
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
        <a href="{{ route('questions.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemQuestion->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgQcm::question.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgQcm::question.singular") }} : {{$itemQuestion}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
