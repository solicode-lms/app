{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('filiere-form')
<form 
    class="crud-form custom-form context-state container" 
    id="filiereForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('filieres.bulkUpdate') : ($itemFiliere->id ? route('filieres.update', $itemFiliere->id) : route('filieres.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemFiliere->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($filiere_ids))
        @foreach ($filiere_ids as $id)
            <input type="hidden" name="filiere_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="code" id="bulk_field_code" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="code">
            {{ ucfirst(__('PkgFormation::filiere.code')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="code"
                type="input"
                class="form-control"
                required
                
                
                id="code"
                placeholder="{{ __('PkgFormation::filiere.code') }}"
                value="{{ $itemFiliere ? $itemFiliere->code : old('code') }}">
          @error('code')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgFormation::filiere.nom')) }}
            
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                
                
                
                id="nom"
                placeholder="{{ __('PkgFormation::filiere.nom') }}"
                value="{{ $itemFiliere ? $itemFiliere->nom : old('nom') }}">
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
            {{ ucfirst(__('PkgFormation::filiere.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgFormation::filiere.description') }}">{{ $itemFiliere ? $itemFiliere->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('filieres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFiliere->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgFormation::filiere.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgFormation::filiere.singular") }} : {{$itemFiliere}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
