
@section('role-form')
<form class="crud-form custom-form context-state" id="metadataTypeForm" action="{{ $itemMetadataType->id ? route('metadataTypes.update', $itemMetadataType->id) : route('metadataTypes.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemMetadataType->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgGapp::metadataType.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                id="name"
                placeholder="{{ __('PkgGapp::metadataType.name') }}"
                value="{{ $itemMetadataType ? $itemMetadataType->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="code">
                {{ ucfirst(__('PkgGapp::metadataType.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                required
                id="code"
                placeholder="{{ __('PkgGapp::metadataType.code') }}"
                value="{{ $itemMetadataType ? $itemMetadataType->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="type">
                {{ ucfirst(__('PkgGapp::metadataType.type')) }}
                
                    <span class="text-danger">*</span>
                
            </label>

<select
    name="type"
    id="type"
    class="form-control select2"
    required>
    <option value="">{{ __('PkgGapp::metadataType.select_type') }}</option>
    @foreach ($metaDataValueTypeCases as $case)
        <option 
            value="{{ $case->value }}"
            {{ old('type', $itemMetadataType->type ?? '') === $case->value ? 'selected' : '' }}>
            {{ ucfirst($case->value) }}
        </option>
    @endforeach
</select>
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="scope">
                {{ ucfirst(__('PkgGapp::metadataType.scope')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            {{$itemMetadataType->scope}}
            <select
            name="scope"
            id="scope"
            class="form-control select2"
            required>
            <option value="">{{ __('PkgGapp::metadataType.scope') }}</option>
            @foreach ($metadataScopeCases as $case)
                <option 
                    value="{{ $case->value }}"
                    {{ old('scope', $itemMetadataType->scope ?? '') === $case->value ? 'selected' : '' }}>
                    {{ ucfirst($case->value) }} 
                    {{ old('scope', $itemMetadataType->scope ?? '') === $case->value ? 'selected' : 'non' }}
                </option>
            @endforeach
        </select>
            @error('scope')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgGapp::metadataType.description')) }}
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                
                id="description"
                placeholder="{{ __('PkgGapp::metadataType.description') }}"
                value="{{ $itemMetadataType ? $itemMetadataType->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="default_value">
                {{ ucfirst(__('PkgGapp::metadataType.default_value')) }}
                
            </label>
            <input
                name="default_value"
                type="input"
                class="form-control"
                
                id="default_value"
                placeholder="{{ __('PkgGapp::metadataType.default_value') }}"
                value="{{ $itemMetadataType ? $itemMetadataType->default_value : old('default_value') }}">
            @error('default_value')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="validation_rules">
                {{ ucfirst(__('PkgGapp::metadataType.validation_rules')) }}
                
            </label>
            <textarea rows="" cols=""
                name="validation_rules"
                class="form-control richText"
                
                id="validation_rules"
                placeholder="{{ __('PkgGapp::metadataType.validation_rules') }}">
                {{ $itemMetadataType ? $itemMetadataType->validation_rules : old('validation_rules') }}
            </textarea>
            @error('validation_rules')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>


        <!--   Metadatum_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('metadataTypes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemMetadataType->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


