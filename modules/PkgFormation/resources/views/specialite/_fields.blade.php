{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('specialite-form')
<form 
    class="crud-form custom-form context-state container" 
    id="specialiteForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('specialites.bulkUpdate') : ($itemSpecialite->id ? route('specialites.update', $itemSpecialite->id) : route('specialites.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemSpecialite->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($specialite_ids))
        @foreach ($specialite_ids as $id)
            <input type="hidden" name="specialite_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgFormation::specialite.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgFormation::specialite.nom') }}"
                value="{{ $itemSpecialite ? $itemSpecialite->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgFormation::specialite.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgFormation::specialite.description') }}">{{ $itemSpecialite ? $itemSpecialite->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="formateurs" id="bulk_field_formateurs" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="formateurs">
            {{ ucfirst(__('PkgFormation::formateur.plural')) }}
            
          </label>
                      <select
                id="formateurs"
                name="formateurs[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemSpecialite) && $itemSpecialite->formateurs && $itemSpecialite->formateurs->contains('id', $formateur->id)) || (is_array(old('formateurs')) && in_array($formateur->id, old('formateurs'))) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
          @error('formateurs')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('specialites.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSpecialite->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgFormation::specialite.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgFormation::specialite.singular") }} : {{$itemSpecialite}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
