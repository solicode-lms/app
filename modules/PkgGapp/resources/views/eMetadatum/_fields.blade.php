{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="eMetadatumForm" action="{{ $itemEMetadatum->id ? route('eMetadata.update', $itemEMetadatum->id) : route('eMetadata.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemEMetadatum->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        

        
        <div class="form-group">
            <label for="value_string">
                {{ ucfirst(__('PkgGapp::eMetadatum.value_string')) }}
                
            </label>
            <input
                name="value_string"
                type="input"
                class="form-control"
                
                id="value_string"
                placeholder="{{ __('PkgGapp::eMetadatum.value_string') }}"
                value="{{ $itemEMetadatum ? $itemEMetadatum->value_string : old('value_string') }}">
            @error('value_string')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        

        

        <!--   value_object JSON --> 

        
        

        
        <div class="form-group">
            <label for="object_type">
                {{ ucfirst(__('PkgGapp::eMetadatum.object_type')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="object_type"
                type="input"
                class="form-control"
                required
                id="object_type"
                placeholder="{{ __('PkgGapp::eMetadatum.object_type') }}"
                value="{{ $itemEMetadatum ? $itemEMetadatum->object_type : old('object_type') }}">
            @error('object_type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        
    <div class="form-group">
            <label for="e_metadata_definition_id">
                {{ ucfirst(__('PkgGapp::eMetadataDefinition.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="e_metadata_definition_id" 
            required
            name="e_metadata_definition_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eMetadataDefinitions as $eMetadataDefinition)
                    <option value="{{ $eMetadataDefinition->id }}"
                        {{ (isset($itemEMetadatum) && $itemEMetadatum->e_metadata_definition_id == $eMetadataDefinition->id) || (old('e_metadata_definition_id>') == $eMetadataDefinition->id) ? 'selected' : '' }}>
                        {{ $eMetadataDefinition }}
                    </option>
                @endforeach
            </select>
            @error('e_metadata_definition_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


    </div>

    <div class="card-footer">
        <a href="{{ route('eMetadata.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEMetadatum->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


