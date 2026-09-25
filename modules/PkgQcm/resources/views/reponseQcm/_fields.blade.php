{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('reponseQcm-form')
<form 
    class="crud-form custom-form context-state container" 
    id="reponseQcmForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('reponseQcms.bulkUpdate') : ($itemReponseQcm->id ? route('reponseQcms.update', $itemReponseQcm->id) : route('reponseQcms.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemReponseQcm->id)
        <input type="hidden" name="id" value="{{ $itemReponseQcm->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($reponseQcm_ids))
        @foreach ($reponseQcm_ids as $id)
            <input type="hidden" name="reponseQcm_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemReponseQcm" field="realisation_qcm_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="realisation_qcm_id" 
              id="bulk_field_realisation_qcm_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_qcm_id">
            {{ ucfirst(__('PkgQcm::realisationQcm.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_qcm_id" 
            required
            
            
            name="realisation_qcm_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationQcms as $realisationQcm)
                    <option value="{{ $realisationQcm->id }}"
                        {{ (isset($itemReponseQcm) && $itemReponseQcm->realisation_qcm_id == $realisationQcm->id) || (old('realisation_qcm_id>') == $realisationQcm->id) ? 'selected' : '' }}>
                        {{ $realisationQcm }}
                    </option>
                @endforeach
            </select>
          @error('realisation_qcm_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemReponseQcm" field="question_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="question_id" 
              id="bulk_field_question_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="question_id">
            {{ ucfirst(__('PkgQcm::question.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="question_id" 
            required
            
            
            name="question_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($questions as $question)
                    <option value="{{ $question->id }}"
                        {{ (isset($itemReponseQcm) && $itemReponseQcm->question_id == $question->id) || (old('question_id>') == $question->id) ? 'selected' : '' }}>
                        {{ $question }}
                    </option>
                @endforeach
            </select>
          @error('question_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemReponseQcm" field="date_reponse" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="date_reponse" 
              id="bulk_field_date_reponse" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_reponse">
            {{ ucfirst(__('PkgQcm::reponseQcm.date_reponse')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_reponse"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="date_reponse"
                placeholder="{{ __('PkgQcm::reponseQcm.date_reponse') }}"
                value="{{ $itemReponseQcm ? $itemReponseQcm->date_reponse : old('date_reponse') }}">

          @error('date_reponse')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemReponseQcm" field="propositionReponses" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="propositionReponses" 
              id="bulk_field_propositionReponses" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="propositionReponses">
            {{ ucfirst(__('PkgQcm::propositionReponse.plural')) }}
            
          </label>
                      <select
                id="propositionReponses"
                name="propositionReponses[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($propositionReponses as $propositionReponse)
                    <option value="{{ $propositionReponse->id }}"
                        {{ (isset($itemReponseQcm) && $itemReponseQcm->propositionReponses && $itemReponseQcm->propositionReponses->contains('id', $propositionReponse->id)) || (is_array(old('propositionReponses')) && in_array($propositionReponse->id, old('propositionReponses'))) ? 'selected' : '' }}>
                        {{ $propositionReponse }}
                    </option>
                @endforeach
            </select>
          @error('propositionReponses')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemReponseQcm" field="realisationUaPrototypes" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="realisationUaPrototypes" 
              id="bulk_field_realisationUaPrototypes" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisationUaPrototypes">
            {{ ucfirst(__('PkgApprentissage::realisationUaPrototype.plural')) }}
            
          </label>
                      <select
                id="realisationUaPrototypes"
                name="realisationUaPrototypes[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($realisationUaPrototypes as $realisationUaPrototype)
                    <option value="{{ $realisationUaPrototype->id }}"
                        {{ (isset($itemReponseQcm) && $itemReponseQcm->realisationUaPrototypes && $itemReponseQcm->realisationUaPrototypes->contains('id', $realisationUaPrototype->id)) || (is_array(old('realisationUaPrototypes')) && in_array($realisationUaPrototype->id, old('realisationUaPrototypes'))) ? 'selected' : '' }}>
                        {{ $realisationUaPrototype }}
                    </option>
                @endforeach
            </select>
          @error('realisationUaPrototypes')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('reponseQcms.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemReponseQcm->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgQcm::reponseQcm.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgQcm::reponseQcm.singular") }} : {{$itemReponseQcm}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
