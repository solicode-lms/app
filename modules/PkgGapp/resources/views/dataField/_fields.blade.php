{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="dataFieldForm" action="{{ $itemDataField->id ? route('dataFields.update', $itemDataField->id) : route('dataFields.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemDataField->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgGapp::dataField.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                id="name"
                placeholder="{{ __('PkgGapp::dataField.name') }}"
                value="{{ $itemDataField ? $itemDataField->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
    <div class="form-group">
            <label for="i_model_id">
                {{ ucfirst(__('PkgGapp::iModel.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="i_model_id" 
            required
            name="i_model_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($iModels as $iModel)
                    <option value="{{ $iModel->id }}"
                        {{ (isset($itemDataField) && $itemDataField->i_model_id == $iModel->id) || (old('i_model_id>') == $iModel->id) ? 'selected' : '' }}>
                        {{ $iModel }}
                    </option>
                @endforeach
            </select>
            @error('i_model_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
    <div class="form-group">
            <label for="field_type_id">
                {{ ucfirst(__('PkgGapp::fieldType.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="field_type_id" 
            required
            name="field_type_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($fieldTypes as $fieldType)
                    <option value="{{ $fieldType->id }}"
                        {{ (isset($itemDataField) && $itemDataField->field_type_id == $fieldType->id) || (old('field_type_id>') == $fieldType->id) ? 'selected' : '' }}>
                        {{ $fieldType }}
                    </option>
                @endforeach
            </select>
            @error('field_type_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgGapp::dataField.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgGapp::dataField.description') }}">
                {{ $itemDataField ? $itemDataField->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

    </div>

    <div class="card-footer">
        <a href="{{ route('dataFields.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemDataField->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


