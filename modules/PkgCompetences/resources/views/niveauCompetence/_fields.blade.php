{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form id="niveauCompetenceForm" action="{{ $itemNiveauCompetence->id ? route('niveauCompetences.update', $itemNiveauCompetence->id) : route('niveauCompetences.store') }}" method="POST">
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
            <input
                name="description"
                type="input"
                class="form-control"
                id="description"
                placeholder="{{ __('PkgCompetences::niveauCompetence.description') }}"
                value="{{ $itemNiveauCompetence ? $itemNiveauCompetence->description : old('description') }}">
            @error('description')
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
        

        



    </div>


    <div class="card-footer">
        <a href="{{ route('niveauCompetences.index') }}" id="niveauCompetence_form_cancel" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNiveauCompetence->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'competence_id',
            fetchUrl: "{{ route('competences.all') }}",
            selectedValue: {{ $itemNiveauCompetence->competence_id ? $itemNiveauCompetence->competence_id : 'undefined' }},
            fieldValue: 'code'
        }
        
    ];
</script>
