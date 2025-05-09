{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('nationalite-form')
<form 
    class="crud-form custom-form context-state container" 
    id="nationaliteForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('nationalites.bulkUpdate') : ($itemNationalite->id ? route('nationalites.update', $itemNationalite->id) : route('nationalites.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemNationalite->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($nationalite_ids))
        @foreach ($nationalite_ids as $id)
            <input type="hidden" name="nationalite_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        
      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="code" id="bulk_field_code" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="code">
            {{ ucfirst(__('PkgApprenants::nationalite.code')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="code"
                type="input"
                class="form-control"
                required
                
                
                id="code"
                placeholder="{{ __('PkgApprenants::nationalite.code') }}"
                value="{{ $itemNationalite ? $itemNationalite->code : old('code') }}">
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
            {{ ucfirst(__('PkgApprenants::nationalite.nom')) }}
            
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                
                
                
                id="nom"
                placeholder="{{ __('PkgApprenants::nationalite.nom') }}"
                value="{{ $itemNationalite ? $itemNationalite->nom : old('nom') }}">
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
            {{ ucfirst(__('PkgApprenants::nationalite.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgApprenants::nationalite.description') }}">{{ $itemNationalite ? $itemNationalite->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('nationalites.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNationalite->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgApprenants::nationalite.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprenants::nationalite.singular") }} : {{$itemNationalite}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
