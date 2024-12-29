{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="technologyForm" action="{{ $itemTechnology->id ? route('technologies.update', $itemTechnology->id) : route('technologies.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemTechnology->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgCompetences::technology.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                id="nom"
                placeholder="{{ __('PkgCompetences::technology.nom') }}"
                value="{{ $itemTechnology ? $itemTechnology->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCompetences::technology.description')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                required
                id="description"
                placeholder="{{ __('PkgCompetences::technology.description') }}"
                value="{{ $itemTechnology ? $itemTechnology->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="category_technology_id">
                {{ ucfirst(__('PkgCompetences::categoryTechnology.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="category_technology_id" 
            name="category_technology_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($categoryTechnologies as $categoryTechnology)
                    <option value="{{ $categoryTechnology->id }}"
                        {{ (isset($itemTechnology) && $itemTechnology->category_technology_id == $categoryTechnology->id) || (old('category_technology_id>') == $categoryTechnology->id) ? 'selected' : '' }}>
                        {{ $categoryTechnology }}
                    </option>
                @endforeach
            </select>
            @error('category_technology_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="competences">
                {{ ucfirst(__('PkgCompetences::Competence.plural')) }}
            </label>
            <select
                id="competences"
                name="competences[]"
                class="form-control select2"
                multiple="multiple">
               
                @foreach ($competences as $competence)
                    <option value="{{ $competence->id }}"
                        {{ (isset($itemTechnology) && $itemTechnology->competences && $itemTechnology->competences->contains('id', $competence->id)) || (is_array(old('competences')) && in_array($competence->id, old('competences'))) ? 'selected' : '' }}>
                        {{ $competence->code }}
                    </option>
                @endforeach
            </select>
            @error('competences')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        
        <div class="form-group">
            <label for="transfertCompetences">
                {{ ucfirst(__('PkgCreationProjet::TransfertCompetence.plural')) }}
            </label>
            <select
                id="transfertCompetences"
                name="transfertCompetences[]"
                class="form-control select2"
                multiple="multiple">
               
                @foreach ($transfertCompetences as $transfertCompetence)
                    <option value="{{ $transfertCompetence->id }}"
                        {{ (isset($itemTechnology) && $itemTechnology->transfertCompetences && $itemTechnology->transfertCompetences->contains('id', $transfertCompetence->id)) || (is_array(old('transfertCompetences')) && in_array($transfertCompetence->id, old('transfertCompetences'))) ? 'selected' : '' }}>
                        {{ $transfertCompetence->id }}
                    </option>
                @endforeach
            </select>
            @error('transfertCompetences')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        



    </div>


    <div class="card-footer">
        <a href="{{ route('technologies.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemTechnology->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


