{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('qcm-form')
<form 
    class="crud-form custom-form context-state container" 
    id="qcmForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('qcms.bulkUpdate') : ($itemQcm->id ? route('qcms.update', $itemQcm->id) : route('qcms.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemQcm->id)
        <input type="hidden" name="id" value="{{ $itemQcm->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($qcm_ids))
        @foreach ($qcm_ids as $id)
            <input type="hidden" name="qcm_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemQcm" field="titre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="titre" 
              id="bulk_field_titre" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre">
            {{ ucfirst(__('PkgQcm::qcm.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgQcm::qcm.titre') }}"
                value="{{ $itemQcm ? $itemQcm->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQcm" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="description" 
              id="bulk_field_description" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgQcm::qcm.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description">
                {!! \App\Helpers\TextHelper::sanitizeTextarea(old('description', $itemQcm->description ?? '')) !!}
                </textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQcm" field="duree_minutes" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="duree_minutes" 
              id="bulk_field_duree_minutes" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="duree_minutes">
            {{ ucfirst(__('PkgQcm::qcm.duree_minutes')) }}
            
          </label>
                      <input
                name="duree_minutes"
                type="number"
                class="form-control"
                
                
                
                id="duree_minutes"
                placeholder="{{ __('PkgQcm::qcm.duree_minutes') }}"
                value="{{ $itemQcm ? $itemQcm->duree_minutes : old('duree_minutes') }}">
          @error('duree_minutes')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQcm" field="is_duree_limitee" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="is_duree_limitee" 
              id="bulk_field_is_duree_limitee" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_duree_limitee">
            {{ ucfirst(__('PkgQcm::qcm.is_duree_limitee')) }}
            
          </label>
                      <input type="hidden" name="is_duree_limitee" value="0">
            <input
                name="is_duree_limitee"
                type="checkbox"
                class="form-control d-block"
                
                
                
                id="is_duree_limitee"
                value="1"
                {{ old('is_duree_limitee', $itemQcm ? $itemQcm->is_duree_limitee : 0) ? 'checked' : '' }}>
          @error('is_duree_limitee')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQcm" field="is_publie" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="is_publie" 
              id="bulk_field_is_publie" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_publie">
            {{ ucfirst(__('PkgQcm::qcm.is_publie')) }}
            
          </label>
                      <input type="hidden" name="is_publie" value="0">
            <input
                name="is_publie"
                type="checkbox"
                class="form-control d-block"
                
                
                
                id="is_publie"
                value="1"
                {{ old('is_publie', $itemQcm ? $itemQcm->is_publie : 0) ? 'checked' : '' }}>
          @error('is_publie')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemQcm" field="formateur_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="formateur_id" 
              id="bulk_field_formateur_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="formateur_id">
            {{ ucfirst(__('PkgFormation::formateur.singular')) }}
            
          </label>
                      <select 
            id="formateur_id" 
            
            
            
            name="formateur_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemQcm) && $itemQcm->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
          @error('formateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('qcms.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemQcm->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgQcm::qcm.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgQcm::qcm.singular") }} : {{$itemQcm}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
