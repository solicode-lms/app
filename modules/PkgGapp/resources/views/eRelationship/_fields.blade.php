{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="eRelationshipForm" action="{{ $itemERelationship->id ? route('eRelationships.update', $itemERelationship->id) : route('eRelationships.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemERelationship->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        
    <div class="form-group">
            <label for="source_model_id">
                {{ ucfirst(__('PkgGapp::eModel.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="source_model_id" 
            required
            name="source_model_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eModels as $eModel)
                    <option value="{{ $eModel->id }}"
                        {{ (isset($itemERelationship) && $itemERelationship->source_model_id == $eModel->id) || (old('source_model_id>') == $eModel->id) ? 'selected' : '' }}>
                        {{ $eModel }}
                    </option>
                @endforeach
            </select>
            @error('source_model_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        
    <div class="form-group">
            <label for="target_model_id">
                {{ ucfirst(__('PkgGapp::eModel.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="target_model_id" 
            required
            name="target_model_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eModels as $eModel)
                    <option value="{{ $eModel->id }}"
                        {{ (isset($itemERelationship) && $itemERelationship->target_model_id == $eModel->id) || (old('target_model_id>') == $eModel->id) ? 'selected' : '' }}>
                        {{ $eModel }}
                    </option>
                @endforeach
            </select>
            @error('target_model_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group">
            <label for="type">
                {{ ucfirst(__('PkgGapp::eRelationship.type')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="type"
                type="input"
                class="form-control"
                required
                id="type"
                placeholder="{{ __('PkgGapp::eRelationship.type') }}"
                value="{{ $itemERelationship ? $itemERelationship->type : old('type') }}">
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="source_field">
                {{ ucfirst(__('PkgGapp::eRelationship.source_field')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="source_field"
                type="input"
                class="form-control"
                required
                id="source_field"
                placeholder="{{ __('PkgGapp::eRelationship.source_field') }}"
                value="{{ $itemERelationship ? $itemERelationship->source_field : old('source_field') }}">
            @error('source_field')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="target_field">
                {{ ucfirst(__('PkgGapp::eRelationship.target_field')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="target_field"
                type="input"
                class="form-control"
                required
                id="target_field"
                placeholder="{{ __('PkgGapp::eRelationship.target_field') }}"
                value="{{ $itemERelationship ? $itemERelationship->target_field : old('target_field') }}">
            @error('target_field')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        

        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgGapp::eRelationship.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgGapp::eRelationship.description') }}">
                {{ $itemERelationship ? $itemERelationship->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

    </div>

    <div class="card-footer">
        <a href="{{ route('eRelationships.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemERelationship->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


