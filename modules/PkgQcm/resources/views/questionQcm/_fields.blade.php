{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('questionQcm-form')
<form 
    class="crud-form custom-form context-state container" 
    id="questionQcmForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('questionQcms.bulkUpdate') : ($itemQuestionQcm->id ? route('questionQcms.update', $itemQuestionQcm->id) : route('questionQcms.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemQuestionQcm->id)
        <input type="hidden" name="id" value="{{ $itemQuestionQcm->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($questionQcm_ids))
        @foreach ($questionQcm_ids as $id)
            <input type="hidden" name="questionQcm_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestionQcm" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="ordre" 
              id="bulk_field_ordre" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgQcm::questionQcm.ordre')) }}
            
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                
                
                
                id="ordre"
                placeholder="{{ __('PkgQcm::questionQcm.ordre') }}"
                value="{{ $itemQuestionQcm ? $itemQuestionQcm->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestionQcm" field="bareme" :bulkEdit="$bulkEdit">

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
            {{ ucfirst(__('PkgQcm::questionQcm.bareme')) }}
            
          </label>
              <input
        name="bareme"
        type="number"
        class="form-control"
        
        
        
        id="bareme"
        step="0.01"
        placeholder="{{ __('PkgQcm::questionQcm.bareme') }}"
        value="{{ $itemQuestionQcm ? number_format($itemQuestionQcm->bareme, 2, '.', '') : old('bareme') }}">
          @error('bareme')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestionQcm" field="qcm_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemQuestionQcm) && $itemQuestionQcm->qcm_id == $qcm->id) || (old('qcm_id>') == $qcm->id) ? 'selected' : '' }}>
                        {{ $qcm }}
                    </option>
                @endforeach
            </select>
          @error('qcm_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestionQcm" field="question_lib_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="question_lib_id" 
              id="bulk_field_question_lib_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="question_lib_id">
            {{ ucfirst(__('PkgQcm::questionLib.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="question_lib_id" 
            required
            
            
            name="question_lib_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($questionLibs as $questionLib)
                    <option value="{{ $questionLib->id }}"
                        {{ (isset($itemQuestionQcm) && $itemQuestionQcm->question_lib_id == $questionLib->id) || (old('question_lib_id>') == $questionLib->id) ? 'selected' : '' }}>
                        {{ $questionLib }}
                    </option>
                @endforeach
            </select>
          @error('question_lib_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('questionQcms.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemQuestionQcm->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgQcm::questionQcm.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgQcm::questionQcm.singular") }} : {{$itemQuestionQcm}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
