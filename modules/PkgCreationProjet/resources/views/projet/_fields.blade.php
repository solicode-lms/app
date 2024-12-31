{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="projetForm" action="{{ $itemProjet->id ? route('projets.update', $itemProjet->id) : route('projets.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemProjet->id)
        @method('PUT')
    @endif

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
                required
                id="titre"
                placeholder="{{ __('PkgCreationProjet::projet.titre') }}"
                value="{{ $itemProjet ? $itemProjet->titre : old('titre') }}">
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
                required
                id="travail_a_faire"
                placeholder="{{ __('PkgCreationProjet::projet.travail_a_faire') }}"
                value="{{ $itemProjet ? $itemProjet->travail_a_faire : old('travail_a_faire') }}">
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
                required
                id="critere_de_travail"
                placeholder="{{ __('PkgCreationProjet::projet.critere_de_travail') }}"
                value="{{ $itemProjet ? $itemProjet->critere_de_travail : old('critere_de_travail') }}">
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
                required
                id="description"
                placeholder="{{ __('PkgCreationProjet::projet.description') }}"
                value="{{ $itemProjet ? $itemProjet->description : old('description') }}">
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
                type="date"
                class="form-control"
                required
                id="date_debut"
                placeholder="{{ __('PkgCreationProjet::projet.date_debut') }}"
                value="{{ $itemProjet ? $itemProjet->date_debut : old('date_debut') }}">
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
                type="date"
                class="form-control"
                required
                id="date_fin"
                placeholder="{{ __('PkgCreationProjet::projet.date_fin') }}"
                value="{{ $itemProjet ? $itemProjet->date_fin : old('date_fin') }}">
            @error('date_fin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>
        
        
        
        
    <div class="form-group">
            <label for="formateur_id">
                {{ ucfirst(__('PkgUtilisateurs::formateur.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="formateur_id" 
            name="formateur_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemProjet) && $itemProjet->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
            @error('formateur_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

        
        
        
        <!--   Livrable_HasMany HasMany --> 
        
        
        
        <!--   Resource_HasMany HasMany --> 
        
        
        
        <!--   TransfertCompetence_HasMany HasMany --> 
        
        
    </div>

    <div class="card-footer">
        <a href="{{ route('projets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


