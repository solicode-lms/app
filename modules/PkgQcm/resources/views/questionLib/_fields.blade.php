{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('questionLib-form')
<form 
    class="crud-form custom-form context-state container" 
    id="questionLibForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('questionLibs.bulkUpdate') : ($itemQuestionLib->id ? route('questionLibs.update', $itemQuestionLib->id) : route('questionLibs.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemQuestionLib->id)
        <input type="hidden" name="id" value="{{ $itemQuestionLib->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($questionLib_ids))
        @foreach ($questionLib_ids as $id)
            <input type="hidden" name="questionLib_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestionLib" field="enonce" :bulkEdit="$bulkEdit">

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
            {{ ucfirst(__('PkgQcm::questionLib.enonce')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="enonce"
                class="form-control richText"
                required
                
                
                id="enonce">
                {!! \App\Helpers\TextHelper::sanitizeTextarea(old('enonce', $itemQuestionLib->enonce ?? '')) !!}
                </textarea>
          @error('enonce')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestionLib" field="type" :bulkEdit="$bulkEdit">

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
            {{ ucfirst(__('PkgQcm::questionLib.type')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="type"
                type="input"
                class="form-control"
                required
                
                
                id="type"
                placeholder="{{ __('PkgQcm::questionLib.type') }}"
                value="{{ $itemQuestionLib ? $itemQuestionLib->type : old('type') }}">
          @error('type')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestionLib" field="is_actif" :bulkEdit="$bulkEdit">

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
            {{ ucfirst(__('PkgQcm::questionLib.is_actif')) }}
            <span class="text-danger">*</span>
          </label>
                      <input type="hidden" name="is_actif" value="0">
            <input
                name="is_actif"
                type="checkbox"
                class="form-control d-block"
                required
                
                
                id="is_actif"
                value="1"
                {{ old('is_actif', $itemQuestionLib ? $itemQuestionLib->is_actif : 0) ? 'checked' : '' }}>
          @error('is_actif')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQuestionLib" field="unite_apprentissage_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemQuestionLib) && $itemQuestionLib->unite_apprentissage_id == $uniteApprentissage->id) || (old('unite_apprentissage_id>') == $uniteApprentissage->id) ? 'selected' : '' }}>
                        {{ $uniteApprentissage }}
                    </option>
                @endforeach
            </select>
          @error('unite_apprentissage_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


@if($itemQuestionLib->id)
@if(
  (auth()->user()?->can('show-propositionReponse') && $itemQuestionLib->propositionReponses->isNotEmpty())  
  || auth()->user()?->can('create-propositionReponse')
  || (auth()->user()?->can('edit-propositionReponse')  && $itemQuestionLib->propositionReponses->isNotEmpty() )
  )
@if (empty($bulkEdit))
<div class="col-12 col-md-12">
   <label for="PropositionReponse">
            {{ ucfirst(__('PkgQcm::propositionReponse.plural')) }}
            
    </label>

  @include('PkgQcm::propositionReponse._index',['isMany' => true, "edit_has_many" => false, "data_calcul" => false ,"parent_manager_id" => "questionLib-crud","contextKey" => 'questionLib.edit_' . $itemQuestionLib->id])
</div>
@endif
@endif
@endif


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('questionLibs.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemQuestionLib->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgQcm::questionLib.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgQcm::questionLib.singular") }} : {{$itemQuestionLib}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
