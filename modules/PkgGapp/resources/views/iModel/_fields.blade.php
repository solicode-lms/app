{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="iModelForm" action="{{ $itemIModel->id ? route('iModels.update', $itemIModel->id) : route('iModels.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemIModel->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgGapp::iModel.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                id="name"
                placeholder="{{ __('PkgGapp::iModel.name') }}"
                value="{{ $itemIModel ? $itemIModel->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="icon">
                {{ ucfirst(__('PkgGapp::iModel.icon')) }}
                
            </label>
            <input
                name="icon"
                type="input"
                class="form-control"
                
                id="icon"
                placeholder="{{ __('PkgGapp::iModel.icon') }}"
                value="{{ $itemIModel ? $itemIModel->icon : old('icon') }}">
            @error('icon')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgGapp::iModel.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgGapp::iModel.description') }}">
                {{ $itemIModel ? $itemIModel->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
    <div class="form-group">
            <label for="i_package_id">
                {{ ucfirst(__('PkgGapp::iPackage.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="i_package_id" 
            required
            name="i_package_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($iPackages as $iPackage)
                    <option value="{{ $iPackage->id }}"
                        {{ (isset($itemIModel) && $itemIModel->i_package_id == $iPackage->id) || (old('i_package_id>') == $iPackage->id) ? 'selected' : '' }}>
                        {{ $iPackage }}
                    </option>
                @endforeach
            </select>
            @error('i_package_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>



        <!--   DataField_HasMany HasMany --> 


        <!--   Relationship_HasMany HasMany --> 


        <!--   Relationship_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('iModels.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemIModel->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


