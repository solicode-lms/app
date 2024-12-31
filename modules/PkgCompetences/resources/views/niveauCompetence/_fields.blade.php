{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="niveauCompetenceForm" action="{{ $itemNiveauCompetence->id ? route('niveauCompetences.update', $itemNiveauCompetence->id) : route('niveauCompetences.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemNiveauCompetence->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgCompetences::niveauCompetence.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                id="nom"
                placeholder="{{ __('PkgCompetences::niveauCompetence.nom') }}"
                value="{{ $itemNiveauCompetence ? $itemNiveauCompetence->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCompetences::niveauCompetence.description')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                required
                id="description"
                placeholder="{{ __('PkgCompetences::niveauCompetence.description') }}">
                {{ $itemNiveauCompetence ? $itemNiveauCompetence->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
    <div class="form-group">
            <label for="competence_id">
                {{ ucfirst(__('PkgCompetences::competence.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="competence_id" 
            name="competence_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($competences as $competence)
                    <option value="{{ $competence->id }}"
                        {{ (isset($itemNiveauCompetence) && $itemNiveauCompetence->competence_id == $competence->id) || (old('competence_id>') == $competence->id) ? 'selected' : '' }}>
                        {{ $competence }}
                    </option>
                @endforeach
            </select>
            @error('competence_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


    </div>

    <div class="card-footer">
        <a href="{{ route('niveauCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNiveauCompetence->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


