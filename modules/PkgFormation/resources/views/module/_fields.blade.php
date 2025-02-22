{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('module-form')
<form class="crud-form custom-form context-state container" id="moduleForm" action="{{ $itemModule->id ? route('modules.update', $itemModule->id) : route('modules.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemModule->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="code">
                {{ ucfirst(__('PkgFormation::module.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                required
                
                id="code"
                placeholder="{{ __('PkgFormation::module.code') }}"
                value="{{ $itemModule ? $itemModule->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="nom">
                {{ ucfirst(__('PkgFormation::module.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                id="nom"
                placeholder="{{ __('PkgFormation::module.nom') }}"
                value="{{ $itemModule ? $itemModule->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-12">
            <label for="description">
                {{ ucfirst(__('PkgFormation::module.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgFormation::module.description') }}">
                {{ $itemModule ? $itemModule->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="masse_horaire">
                {{ ucfirst(__('PkgFormation::module.masse_horaire')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="masse_horaire"
                type="input"
                class="form-control"
                required
                
                id="masse_horaire"
                placeholder="{{ __('PkgFormation::module.masse_horaire') }}"
                value="{{ $itemModule ? $itemModule->masse_horaire : old('masse_horaire') }}">
            @error('masse_horaire')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="filiere_id">
                {{ ucfirst(__('PkgFormation::filiere.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="filiere_id" 
            required
            
            name="filiere_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($filieres as $filiere)
                    <option value="{{ $filiere->id }}"
                        {{ (isset($itemModule) && $itemModule->filiere_id == $filiere->id) || (old('filiere_id>') == $filiere->id) ? 'selected' : '' }}>
                        {{ $filiere }}
                    </option>
                @endforeach
            </select>
            @error('filiere_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        

        <!--   Competence HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('modules.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemModule->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgFormation::module.singular") }} : {{$itemModule}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
