{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eMetadataDefinition-form')
<form class="crud-form custom-form context-state container" id="eMetadataDefinitionForm" action="{{ $itemEMetadataDefinition->id ? route('eMetadataDefinitions.update', $itemEMetadataDefinition->id) : route('eMetadataDefinitions.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemEMetadataDefinition->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          <label for="name">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('PkgGapp::eMetadataDefinition.name') }}"
                value="{{ $itemEMetadataDefinition ? $itemEMetadataDefinition->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="groupe">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.groupe')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="groupe"
                type="input"
                class="form-control"
                required
                
                
                id="groupe"
                placeholder="{{ __('PkgGapp::eMetadataDefinition.groupe') }}"
                value="{{ $itemEMetadataDefinition ? $itemEMetadataDefinition->groupe : old('groupe') }}">
          @error('groupe')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="type">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.type')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="type"
                type="input"
                class="form-control"
                required
                
                
                id="type"
                placeholder="{{ __('PkgGapp::eMetadataDefinition.type') }}"
                value="{{ $itemEMetadataDefinition ? $itemEMetadataDefinition->type : old('type') }}">
          @error('type')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="scope">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.scope')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="scope"
                type="input"
                class="form-control"
                required
                
                
                id="scope"
                placeholder="{{ __('PkgGapp::eMetadataDefinition.scope') }}"
                value="{{ $itemEMetadataDefinition ? $itemEMetadataDefinition->scope : old('scope') }}">
          @error('scope')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          <label for="description">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgGapp::eMetadataDefinition.description') }}">{{ $itemEMetadataDefinition ? $itemEMetadataDefinition->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="default_value">
            {{ ucfirst(__('PkgGapp::eMetadataDefinition.default_value')) }}
            
          </label>
           <input
                name="default_value"
                type="input"
                class="form-control"
                
                
                
                id="default_value"
                placeholder="{{ __('PkgGapp::eMetadataDefinition.default_value') }}"
                value="{{ $itemEMetadataDefinition ? $itemEMetadataDefinition->default_value : old('default_value') }}">
          @error('default_value')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   EMetadatum HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('eMetadataDefinitions.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEMetadataDefinition->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgGapp::eMetadataDefinition.singular") }} : {{$itemEMetadataDefinition}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
