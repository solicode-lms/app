{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form context-state" id="moduleForm" action="{{ $itemModule->id ? route('modules.update', $itemModule->id) : route('modules.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemModule->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgCompetences::module.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                id="nom"
                placeholder="{{ __('PkgCompetences::module.nom') }}"
                value="{{ $itemModule ? $itemModule->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCompetences::module.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgCompetences::module.description') }}">
                {{ $itemModule ? $itemModule->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="masse_horaire">
                {{ ucfirst(__('PkgCompetences::module.masse_horaire')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="masse_horaire"
                type="input"
                class="form-control"
                required
                id="masse_horaire"
                placeholder="{{ __('PkgCompetences::module.masse_horaire') }}"
                value="{{ $itemModule ? $itemModule->masse_horaire : old('masse_horaire') }}">
            @error('masse_horaire')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
    <div class="form-group">
            <label for="filiere_id">
                {{ ucfirst(__('PkgCompetences::filiere.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="filiere_id" 
            required
            name="filiere_id" 
            class="form-control">
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



        <!--   Competence_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('modules.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemModule->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


