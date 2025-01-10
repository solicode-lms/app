{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="relationshipForm" action="{{ $itemRelationship->id ? route('relationships.update', $itemRelationship->id) : route('relationships.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemRelationship->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
    <div class="form-group">
            <label for="source_model_id">
                {{ ucfirst(__('PkgGapp::iModel.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="source_model_id" 
            required
            name="source_model_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($iModels as $iModel)
                    <option value="{{ $iModel->id }}"
                        {{ (isset($itemRelationship) && $itemRelationship->source_model_id == $iModel->id) || (old('source_model_id>') == $iModel->id) ? 'selected' : '' }}>
                        {{ $iModel }}
                    </option>
                @endforeach
            </select>
            @error('source_model_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
    <div class="form-group">
            <label for="target_model_id">
                {{ ucfirst(__('PkgGapp::iModel.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="target_model_id" 
            required
            name="target_model_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($iModels as $iModel)
                    <option value="{{ $iModel->id }}"
                        {{ (isset($itemRelationship) && $itemRelationship->target_model_id == $iModel->id) || (old('target_model_id>') == $iModel->id) ? 'selected' : '' }}>
                        {{ $iModel }}
                    </option>
                @endforeach
            </select>
            @error('target_model_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        <div class="form-group">
            <label for="type">
                {{ ucfirst(__('PkgGapp::relationship.type')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="type"
                type="input"
                class="form-control"
                required
                id="type"
                placeholder="{{ __('PkgGapp::relationship.type') }}"
                value="{{ $itemRelationship ? $itemRelationship->type : old('type') }}">
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="source_field">
                {{ ucfirst(__('PkgGapp::relationship.source_field')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="source_field"
                type="input"
                class="form-control"
                required
                id="source_field"
                placeholder="{{ __('PkgGapp::relationship.source_field') }}"
                value="{{ $itemRelationship ? $itemRelationship->source_field : old('source_field') }}">
            @error('source_field')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="target_field">
                {{ ucfirst(__('PkgGapp::relationship.target_field')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="target_field"
                type="input"
                class="form-control"
                required
                id="target_field"
                placeholder="{{ __('PkgGapp::relationship.target_field') }}"
                value="{{ $itemRelationship ? $itemRelationship->target_field : old('target_field') }}">
            @error('target_field')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgGapp::relationship.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgGapp::relationship.description') }}">
                {{ $itemRelationship ? $itemRelationship->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

    </div>

    <div class="card-footer">
        <a href="{{ route('relationships.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRelationship->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


