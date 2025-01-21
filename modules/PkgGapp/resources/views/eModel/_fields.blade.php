{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="eModelForm" action="{{ $itemEModel->id ? route('eModels.update', $itemEModel->id) : route('eModels.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemEModel->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="code">
                {{ ucfirst(__('PkgGapp::eModel.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                required
                id="code"
                placeholder="{{ __('PkgGapp::eModel.code') }}"
                value="{{ $itemEModel ? $itemEModel->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgGapp::eModel.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                id="name"
                placeholder="{{ __('PkgGapp::eModel.name') }}"
                value="{{ $itemEModel ? $itemEModel->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="table_name">
                {{ ucfirst(__('PkgGapp::eModel.table_name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="table_name"
                type="input"
                class="form-control"
                required
                id="table_name"
                placeholder="{{ __('PkgGapp::eModel.table_name') }}"
                value="{{ $itemEModel ? $itemEModel->table_name : old('table_name') }}">
            @error('table_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="icon">
                {{ ucfirst(__('PkgGapp::eModel.icon')) }}
                
            </label>
            <input
                name="icon"
                type="input"
                class="form-control"
                
                id="icon"
                placeholder="{{ __('PkgGapp::eModel.icon') }}"
                value="{{ $itemEModel ? $itemEModel->icon : old('icon') }}">
            @error('icon')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="is_pivot_table">
                {{ ucfirst(__('PkgGapp::eModel.is_pivot_table')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="is_pivot_table"
                type="number"
                class="form-control"
                required
                id="is_pivot_table"
                placeholder="{{ __('PkgGapp::eModel.is_pivot_table') }}"
                value="{{ $itemEModel ? $itemEModel->is_pivot_table : old('is_pivot_table') }}">
            @error('is_pivot_table')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgGapp::eModel.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgGapp::eModel.description') }}">
                {{ $itemEModel ? $itemEModel->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        
    <div class="form-group">
            <label for="e_package_id">
                {{ ucfirst(__('PkgGapp::ePackage.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="e_package_id" 
            required
            name="e_package_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($ePackages as $ePackage)
                    <option value="{{ $ePackage->id }}"
                        {{ (isset($itemEModel) && $itemEModel->e_package_id == $ePackage->id) || (old('e_package_id>') == $ePackage->id) ? 'selected' : '' }}>
                        {{ $ePackage }}
                    </option>
                @endforeach
            </select>
            @error('e_package_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        

        <!--   EDataField HasMany --> 

        

        <!--   ERelationship HasMany --> 

        

        <!--   ERelationship HasMany --> 

        

        <!--   EMetadatum HasManyPolymorphic --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('eModels.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEModel->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


