{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eDataField-form')
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
            <label for="column_name">
                {{ ucfirst(__('PkgGapp::eDataField.column_name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="column_name"
                type="input"
                class="form-control"
                required
                id="column_name"
                placeholder="{{ __('PkgGapp::eDataField.column_name') }}"
                value="{{ $itemEDataField ? $itemEDataField->column_name : old('column_name') }}">
            @error('column_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="data_type">
                {{ ucfirst(__('PkgGapp::eDataField.data_type')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="data_type"
                type="input"
                class="form-control"
                required
                id="data_type"
                placeholder="{{ __('PkgGapp::eDataField.data_type') }}"
                value="{{ $itemEDataField ? $itemEDataField->data_type : old('data_type') }}">
            @error('data_type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="db_nullable">
                {{ ucfirst(__('PkgGapp::eDataField.db_nullable')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="db_nullable"
                type="number"
                class="form-control"
                required
                id="db_nullable"
                placeholder="{{ __('PkgGapp::eDataField.db_nullable') }}"
                value="{{ $itemEDataField ? $itemEDataField->db_nullable : old('db_nullable') }}">
            @error('db_nullable')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="db_primaryKey">
                {{ ucfirst(__('PkgGapp::eDataField.db_primaryKey')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="db_primaryKey"
                type="number"
                class="form-control"
                required
                id="db_primaryKey"
                placeholder="{{ __('PkgGapp::eDataField.db_primaryKey') }}"
                value="{{ $itemEDataField ? $itemEDataField->db_primaryKey : old('db_primaryKey') }}">
            @error('db_primaryKey')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="db_unique">
                {{ ucfirst(__('PkgGapp::eDataField.db_unique')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="db_unique"
                type="number"
                class="form-control"
                required
                id="db_unique"
                placeholder="{{ __('PkgGapp::eDataField.db_unique') }}"
                value="{{ $itemEDataField ? $itemEDataField->db_unique : old('db_unique') }}">
            @error('db_unique')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="default_value">
                {{ ucfirst(__('PkgGapp::eDataField.default_value')) }}
                
            </label>
            <input
                name="default_value"
                type="input"
                class="form-control"
                
                id="default_value"
                placeholder="{{ __('PkgGapp::eDataField.default_value') }}"
                value="{{ $itemEDataField ? $itemEDataField->default_value : old('default_value') }}">
            @error('default_value')
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


        

        <!--   EMetadatum HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('eDataFields.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEDataField->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


