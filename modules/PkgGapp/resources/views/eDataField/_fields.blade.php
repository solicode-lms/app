{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="eDataFieldForm" action="{{ $itemEDataField->id ? route('eDataFields.update', $itemEDataField->id) : route('eDataFields.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemEDataField->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgGapp::eDataField.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                id="name"
                placeholder="{{ __('PkgGapp::eDataField.name') }}"
                value="{{ $itemEDataField ? $itemEDataField->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="type">
                {{ ucfirst(__('PkgGapp::eDataField.type')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="type"
                type="input"
                class="form-control"
                required
                id="type"
                placeholder="{{ __('PkgGapp::eDataField.type') }}"
                value="{{ $itemEDataField ? $itemEDataField->type : old('type') }}">
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        
    <div class="form-group">
            <label for="e_model_id">
                {{ ucfirst(__('PkgGapp::eModel.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="e_model_id" 
            required
            name="e_model_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eModels as $eModel)
                    <option value="{{ $eModel->id }}"
                        {{ (isset($itemEDataField) && $itemEDataField->e_model_id == $eModel->id) || (old('e_model_id>') == $eModel->id) ? 'selected' : '' }}>
                        {{ $eModel }}
                    </option>
                @endforeach
            </select>
            @error('e_model_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgGapp::eDataField.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgGapp::eDataField.description') }}">
                {{ $itemEDataField ? $itemEDataField->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

    </div>

    <div class="card-footer">
        <a href="{{ route('eDataFields.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEDataField->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


