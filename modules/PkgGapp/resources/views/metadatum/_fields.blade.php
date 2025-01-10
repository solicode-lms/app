{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="metadatumForm" action="{{ $itemMetadatum->id ? route('metadata.update', $itemMetadatum->id) : route('metadata.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemMetadatum->id)
        @method('PUT')
    @endif

    <div class="card-body">
        

        <div class="form-group">
            <label for="value_string">
                {{ ucfirst(__('PkgGapp::metadatum.value_string')) }}
                
            </label>
            <input
                name="value_string"
                type="input"
                class="form-control"
                
                id="value_string"
                placeholder="{{ __('PkgGapp::metadatum.value_string') }}"
                value="{{ $itemMetadatum ? $itemMetadatum->value_string : old('value_string') }}">
            @error('value_string')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        


        <!--   value_object JSON --> 

        

        <div class="form-group">
            <label for="object_type">
                {{ ucfirst(__('PkgGapp::metadatum.object_type')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="object_type"
                type="input"
                class="form-control"
                required
                id="object_type"
                placeholder="{{ __('PkgGapp::metadatum.object_type') }}"
                value="{{ $itemMetadatum ? $itemMetadatum->object_type : old('object_type') }}">
            @error('object_type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
    <div class="form-group">
            <label for="metadata_type_id">
                {{ ucfirst(__('PkgGapp::metadataType.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="metadata_type_id" 
            required
            name="metadata_type_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($metadataTypes as $metadataType)
                    <option value="{{ $metadataType->id }}"
                        {{ (isset($itemMetadatum) && $itemMetadatum->metadata_type_id == $metadataType->id) || (old('metadata_type_id>') == $metadataType->id) ? 'selected' : '' }}>
                        {{ $metadataType }}
                    </option>
                @endforeach
            </select>
            @error('metadata_type_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


    </div>

    <div class="card-footer">
        <a href="{{ route('metadata.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemMetadatum->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


