{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('technologies.update', $item->id) : route('technologies.store') }}" method="POST">
    @csrf

    @if ($item->id)
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
                id="nom"
                placeholder="{{ __('PkgCompetences::technology.nom') }}"
                value="{{ $item ? $item->nom : old('nom') }}">
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
                id="description"
                placeholder="{{ __('PkgCompetences::technology.description') }}"
                value="{{ $item ? $item->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="categorie_technologie_id">
                {{ ucfirst(__('PkgCompetences::categorieTechnology.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="categorie_technologie_id" name="categorie_technologie_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('categorie_technologie_id')
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
                        {{ (isset($item) && $item->competences && $item->competences->contains('id', $competence->id)) || (is_array(old('competences')) && in_array($competence->id, old('competences'))) ? 'selected' : '' }}>
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
                        {{ (isset($item) && $item->transfertCompetences && $item->transfertCompetences->contains('id', $transfertCompetence->id)) || (is_array(old('transfertCompetences')) && in_array($transfertCompetence->id, old('transfertCompetences'))) ? 'selected' : '' }}>
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
        <a href="{{ route('technologies.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'categorie_technologie_id',
            fetchUrl: "{{ route('categorieTechnologies.all') }}",
            selectedValue: {{ $item->categorie_technologie_id ? $item->categorie_technologie_id : 'undefined' }},
            fieldValue: 'nom'
        }
        
    ];
</script>
