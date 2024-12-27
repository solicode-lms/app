{{-- TODO : edit button edit--}}

<form action="{{ $item->id ? route('projets.update', $item->id) : route('projets.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    {{-- card-body déja dans workflow --}}
    <div class="card-body">
        
        <div class="form-group">
            <label for="titre">
                {{ ucfirst(__('PkgCreationProjet::projet.titre')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="titre"
                type="input"
                class="form-control"
                id="titre"
                placeholder="{{ __('PkgCreationProjet::projet.titre') }}"
                value="{{ $item ? $item->titre : old('titre') }}">
            @error('titre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="travail_a_faire">
                {{ ucfirst(__('PkgCreationProjet::projet.travail_a_faire')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="travail_a_faire"
                type="input"
                class="form-control"
                id="travail_a_faire"
                placeholder="{{ __('PkgCreationProjet::projet.travail_a_faire') }}"
                value="{{ $item ? $item->travail_a_faire : old('travail_a_faire') }}">
            @error('travail_a_faire')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="critere_de_travail">
                {{ ucfirst(__('PkgCreationProjet::projet.critere_de_travail')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="critere_de_travail"
                type="input"
                class="form-control"
                id="critere_de_travail"
                placeholder="{{ __('PkgCreationProjet::projet.critere_de_travail') }}"
                value="{{ $item ? $item->critere_de_travail : old('critere_de_travail') }}">
            @error('critere_de_travail')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCreationProjet::projet.description')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                id="description"
                placeholder="{{ __('PkgCreationProjet::projet.description') }}"
                value="{{ $item ? $item->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="date_debut">
                {{ ucfirst(__('PkgCreationProjet::projet.date_debut')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="date_debut"
                type="input"
                class="form-control"
                id="date_debut"
                placeholder="{{ __('PkgCreationProjet::projet.date_debut') }}"
                value="{{ $item ? $item->date_debut : old('date_debut') }}">
            @error('date_debut')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="date_fin">
                {{ ucfirst(__('PkgCreationProjet::projet.date_fin')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="date_fin"
                type="input"
                class="form-control"
                id="date_fin"
                placeholder="{{ __('PkgCreationProjet::projet.date_fin') }}"
                value="{{ $item ? $item->date_fin : old('date_fin') }}">
            @error('date_fin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="formateur_id">
                {{ ucfirst(__('PkgUtilisateurs::formateur.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="formateur_id" name="formateur_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('formateur_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        



    </div>

    <div class="card-footer">
        <a href="{{ route('projets.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : "Suivant - Affectation des compétences" }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'formateur_id',
            fetchUrl: "{{ route('formateurs.all') }}",
            selectedValue: {{ $item->formateur_id ? $item->formateur_id : 'undefined' }},
            fieldValue: 'nom'
        }
        
    ];
</script>
