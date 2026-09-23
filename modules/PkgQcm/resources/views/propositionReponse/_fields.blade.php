{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('propositionReponse-form')
<form 
    class="crud-form custom-form context-state container" 
    id="propositionReponseForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('propositionReponses.bulkUpdate') : ($itemPropositionReponse->id ? route('propositionReponses.update', $itemPropositionReponse->id) : route('propositionReponses.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemPropositionReponse->id)
        <input type="hidden" name="id" value="{{ $itemPropositionReponse->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($propositionReponse_ids))
        @foreach ($propositionReponse_ids as $id)
            <input type="hidden" name="propositionReponse_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemPropositionReponse" field="ordre" :bulkEdit="$bulkEdit">

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
            {{ ucfirst(__('PkgQcm::propositionReponse.ordre')) }}
            
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                
                
                
                id="ordre"
                placeholder="{{ __('PkgQcm::propositionReponse.ordre') }}"
                value="{{ $itemPropositionReponse ? $itemPropositionReponse->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemPropositionReponse" field="libelle" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="libelle" 
              id="bulk_field_libelle" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="libelle">
            {{ ucfirst(__('PkgQcm::propositionReponse.libelle')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="libelle"
                class="form-control richText"
                required
                
                
                id="libelle">
                {!! \App\Helpers\TextHelper::sanitizeTextarea(old('libelle', $itemPropositionReponse->libelle ?? '')) !!}
                </textarea>
          @error('libelle')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemPropositionReponse" field="is_correcte" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="is_correcte" 
              id="bulk_field_is_correcte" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_correcte">
            {{ ucfirst(__('PkgQcm::propositionReponse.is_correcte')) }}
            
          </label>
                      <input type="hidden" name="is_correcte" value="0">
            <input
                name="is_correcte"
                type="checkbox"
                class="form-control d-block"
                
                
                
                id="is_correcte"
                value="1"
                {{ old('is_correcte', $itemPropositionReponse ? $itemPropositionReponse->is_correcte : 0) ? 'checked' : '' }}>
          @error('is_correcte')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemPropositionReponse" field="question_lib_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemPropositionReponse) && $itemPropositionReponse->question_lib_id == $questionLib->id) || (old('question_lib_id>') == $questionLib->id) ? 'selected' : '' }}>
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
        <a href="{{ route('propositionReponses.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemPropositionReponse->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgQcm::propositionReponse.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgQcm::propositionReponse.singular") }} : {{$itemPropositionReponse}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
