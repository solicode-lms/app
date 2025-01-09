{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="fieldTypeForm" action="{{ $itemFieldType->id ? route('fieldTypes.update', $itemFieldType->id) : route('fieldTypes.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemFieldType->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgGapp::fieldType.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                id="name"
                placeholder="{{ __('PkgGapp::fieldType.name') }}"
                value="{{ $itemFieldType ? $itemFieldType->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgGapp::fieldType.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgGapp::fieldType.description') }}">
                {{ $itemFieldType ? $itemFieldType->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>


        <!--   DataField_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('fieldTypes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFieldType->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


