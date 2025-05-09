{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('ePackage-form')
<form 
    class="crud-form custom-form context-state container" 
    id="ePackageForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('ePackages.bulkUpdate') : ($itemEPackage->id ? route('ePackages.update', $itemEPackage->id) : route('ePackages.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEPackage->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($ePackage_ids))
        @foreach ($ePackage_ids as $id)
            <input type="hidden" name="ePackage_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        
      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="name" id="bulk_field_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="name">
            {{ ucfirst(__('PkgGapp::ePackage.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('PkgGapp::ePackage.name') }}"
                value="{{ $itemEPackage ? $itemEPackage->name : old('name') }}">
          @error('name')
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
            {{ ucfirst(__('PkgGapp::ePackage.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgGapp::ePackage.description') }}">{{ $itemEPackage ? $itemEPackage->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('ePackages.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEPackage->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgGapp::ePackage.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgGapp::ePackage.singular") }} : {{$itemEPackage}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
