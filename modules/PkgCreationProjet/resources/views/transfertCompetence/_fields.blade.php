{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('transfertCompetences.update', $item->id) : route('transfertCompetences.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCreationProjet::transfertCompetence.description')) }}
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                id="description"
                placeholder="{{ __('PkgCreationProjet::transfertCompetence.description') }}"
                value="{{ $item ? $item->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="appreciation_id">
                {{ ucfirst(__('PkgCompetences::appreciation.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="appreciation_id" name="appreciation_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('appreciation_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="competence_id">
                {{ ucfirst(__('PkgCompetences::competence.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="competence_id" name="competence_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('competence_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="projet_id">
                {{ ucfirst(__('PkgCreationProjet::projet.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="projet_id" name="projet_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('projet_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="technologies">
                {{ ucfirst(__('PkgCompetences::Technology.plural')) }}
            </label>
            <select
                id="technologies"
                name="technologies[]"
                class="form-control select2"
                multiple="multiple">
                @foreach ($technologies as $technology)
                    <option value="{{ $technology->id }}"
                        {{ (isset($item) && $item->technologies && $item->technologies->contains('id', $technology->id)) || (is_array(old('technologies')) && in_array($technology->id, old('technologies'))) ? 'selected' : '' }}>
                        {{ $technology->nom }}
                    </option>
                @endforeach
            </select>
            @error('technologies')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        



    </div>

    <div class="card-footer">
        <a href="{{ route('transfertCompetences.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'appreciation_id',
            fetchUrl: "{{ route('appreciations.all') }}",
            selectedValue: {{ $item->appreciation_id ? $item->appreciation_id : 'undefined' }},
            fieldValue: 'nom'
        },
        
        {
            fieldId: 'competence_id',
            fetchUrl: "{{ route('competences.all') }}",
            selectedValue: {{ $item->competence_id ? $item->competence_id : 'undefined' }},
            fieldValue: 'code'
        },
        
        {
            fieldId: 'projet_id',
            fetchUrl: "{{ route('projets.all') }}",
            selectedValue: {{ $item->projet_id ? $item->projet_id : 'undefined' }},
            fieldValue: 'titre'
        }
        
    ];
</script>
