{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eRelationship-form')
<form class="crud-form custom-form context-state" id="eRelationshipForm" action="{{ $itemERelationship->id ? route('eRelationships.update', $itemERelationship->id) : route('eRelationships.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemERelationship->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgGapp::eRelationship.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                required
                
                id="name"
                placeholder="{{ __('PkgGapp::eRelationship.name') }}"
                value="{{ $itemERelationship ? $itemERelationship->name : old('name') }}">
            @error('name')
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
            <label for="source_e_model_id">
                {{ ucfirst(__('PkgGapp::eModel.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="source_e_model_id" 
            required
            
            name="source_e_model_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eModels as $eModel)
                    <option value="{{ $eModel->id }}"
                        {{ (isset($itemERelationship) && $itemERelationship->source_e_model_id == $eModel->id) || (old('source_e_model_id>') == $eModel->id) ? 'selected' : '' }}>
                        {{ $eModel }}
                    </option>
                @endforeach
            </select>
            @error('source_e_model_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        
    <div class="form-group">
            <label for="target_e_model_id">
                {{ ucfirst(__('PkgGapp::eModel.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="target_e_model_id" 
            required
            
            name="target_e_model_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($eModels as $eModel)
                    <option value="{{ $eModel->id }}"
                        {{ (isset($itemERelationship) && $itemERelationship->target_e_model_id == $eModel->id) || (old('target_e_model_id>') == $eModel->id) ? 'selected' : '' }}>
                        {{ $eModel }}
                    </option>
                @endforeach
            </select>
            @error('target_e_model_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group">
            <label for="cascade_on_delete">
                {{ ucfirst(__('PkgGapp::eRelationship.cascade_on_delete')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="cascade_on_delete"
                type="number"
                class="form-control"
                required
                
                id="cascade_on_delete"
                placeholder="{{ __('PkgGapp::eRelationship.cascade_on_delete') }}"
                value="{{ $itemERelationship ? $itemERelationship->cascade_on_delete : old('cascade_on_delete') }}">
            @error('cascade_on_delete')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="is_cascade">
                {{ ucfirst(__('PkgGapp::eRelationship.is_cascade')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="is_cascade"
                type="number"
                class="form-control"
                required
                
                id="is_cascade"
                placeholder="{{ __('PkgGapp::eRelationship.is_cascade') }}"
                value="{{ $itemERelationship ? $itemERelationship->is_cascade : old('is_cascade') }}">
            @error('is_cascade')
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

        
        <div class="form-group">
            <label for="column_name">
                {{ ucfirst(__('PkgGapp::eRelationship.column_name')) }}
                
            </label>
            <input
                name="column_name"
                type="input"
                class="form-control"
                
                
                id="column_name"
                placeholder="{{ __('PkgGapp::eRelationship.column_name') }}"
                value="{{ $itemERelationship ? $itemERelationship->column_name : old('column_name') }}">
            @error('column_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="referenced_table">
                {{ ucfirst(__('PkgGapp::eRelationship.referenced_table')) }}
                
            </label>
            <input
                name="referenced_table"
                type="input"
                class="form-control"
                
                
                id="referenced_table"
                placeholder="{{ __('PkgGapp::eRelationship.referenced_table') }}"
                value="{{ $itemERelationship ? $itemERelationship->referenced_table : old('referenced_table') }}">
            @error('referenced_table')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="referenced_column">
                {{ ucfirst(__('PkgGapp::eRelationship.referenced_column')) }}
                
            </label>
            <input
                name="referenced_column"
                type="input"
                class="form-control"
                
                
                id="referenced_column"
                placeholder="{{ __('PkgGapp::eRelationship.referenced_column') }}"
                value="{{ $itemERelationship ? $itemERelationship->referenced_column : old('referenced_column') }}">
            @error('referenced_column')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="through">
                {{ ucfirst(__('PkgGapp::eRelationship.through')) }}
                
            </label>
            <input
                name="through"
                type="input"
                class="form-control"
                
                
                id="through"
                placeholder="{{ __('PkgGapp::eRelationship.through') }}"
                value="{{ $itemERelationship ? $itemERelationship->through : old('through') }}">
            @error('through')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="with_column">
                {{ ucfirst(__('PkgGapp::eRelationship.with_column')) }}
                
            </label>
            <input
                name="with_column"
                type="input"
                class="form-control"
                
                
                id="with_column"
                placeholder="{{ __('PkgGapp::eRelationship.with_column') }}"
                value="{{ $itemERelationship ? $itemERelationship->with_column : old('with_column') }}">
            @error('with_column')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="morph_name">
                {{ ucfirst(__('PkgGapp::eRelationship.morph_name')) }}
                
            </label>
            <input
                name="morph_name"
                type="input"
                class="form-control"
                
                
                id="morph_name"
                placeholder="{{ __('PkgGapp::eRelationship.morph_name') }}"
                value="{{ $itemERelationship ? $itemERelationship->morph_name : old('morph_name') }}">
            @error('morph_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        

        <!--   EDataField HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('eRelationships.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemERelationship->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgGapp::eRelationship.singular") }} : {{$itemERelationship}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
