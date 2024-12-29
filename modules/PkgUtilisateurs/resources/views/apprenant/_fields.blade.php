{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="apprenantForm" action="{{ $itemApprenant->id ? route('apprenants.update', $itemApprenant->id) : route('apprenants.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemApprenant->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                id="nom"
                placeholder="{{ __('PkgUtilisateurs::apprenant.nom') }}"
                value="{{ $itemApprenant ? $itemApprenant->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        <div class="form-group">
            <label for="prenom">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.prenom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="prenom"
                type="input"
                class="form-control"
                required
                id="prenom"
                placeholder="{{ __('PkgUtilisateurs::apprenant.prenom') }}"
                value="{{ $itemApprenant ? $itemApprenant->prenom : old('prenom') }}">
            @error('prenom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        <div class="form-group">
            <label for="prenom_arab">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.prenom_arab')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="prenom_arab"
                type="input"
                class="form-control"
                required
                id="prenom_arab"
                placeholder="{{ __('PkgUtilisateurs::apprenant.prenom_arab') }}"
                value="{{ $itemApprenant ? $itemApprenant->prenom_arab : old('prenom_arab') }}">
            @error('prenom_arab')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        <div class="form-group">
            <label for="nom_arab">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.nom_arab')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom_arab"
                type="input"
                class="form-control"
                required
                id="nom_arab"
                placeholder="{{ __('PkgUtilisateurs::apprenant.nom_arab') }}"
                value="{{ $itemApprenant ? $itemApprenant->nom_arab : old('nom_arab') }}">
            @error('nom_arab')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        <div class="form-group">
            <label for="tele_num">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.tele_num')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="tele_num"
                type="input"
                class="form-control"
                required
                id="tele_num"
                placeholder="{{ __('PkgUtilisateurs::apprenant.tele_num') }}"
                value="{{ $itemApprenant ? $itemApprenant->tele_num : old('tele_num') }}">
            @error('tele_num')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        <div class="form-group">
            <label for="profile_image">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.profile_image')) }}
                
            </label>
            <input
                name="profile_image"
                type="input"
                class="form-control"
                
                id="profile_image"
                placeholder="{{ __('PkgUtilisateurs::apprenant.profile_image') }}"
                value="{{ $itemApprenant ? $itemApprenant->profile_image : old('profile_image') }}">
            @error('profile_image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        <div class="form-group">
            <label for="matricule">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.matricule')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="matricule"
                type="input"
                class="form-control"
                required
                id="matricule"
                placeholder="{{ __('PkgUtilisateurs::apprenant.matricule') }}"
                value="{{ $itemApprenant ? $itemApprenant->matricule : old('matricule') }}">
            @error('matricule')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        <div class="form-group">
            <label for="sexe">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.sexe')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="sexe"
                type="input"
                class="form-control"
                required
                id="sexe"
                placeholder="{{ __('PkgUtilisateurs::apprenant.sexe') }}"
                value="{{ $itemApprenant ? $itemApprenant->sexe : old('sexe') }}">
            @error('sexe')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        
        
        
        
        <div class="form-group">
            <label for="diplome">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.diplome')) }}
                
            </label>
            <input
                name="diplome"
                type="input"
                class="form-control"
                
                id="diplome"
                placeholder="{{ __('PkgUtilisateurs::apprenant.diplome') }}"
                value="{{ $itemApprenant ? $itemApprenant->diplome : old('diplome') }}">
            @error('diplome')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        <li>Attribut date : date_naissance</li>
        
        
        
        <li>Attribut date : date_inscription</li>
        
        
        
        <div class="form-group">
            <label for="lieu_naissance">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.lieu_naissance')) }}
                
            </label>
            <input
                name="lieu_naissance"
                type="input"
                class="form-control"
                
                id="lieu_naissance"
                placeholder="{{ __('PkgUtilisateurs::apprenant.lieu_naissance') }}"
                value="{{ $itemApprenant ? $itemApprenant->lieu_naissance : old('lieu_naissance') }}">
            @error('lieu_naissance')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        <div class="form-group">
            <label for="cin">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.cin')) }}
                
            </label>
            <input
                name="cin"
                type="input"
                class="form-control"
                
                id="cin"
                placeholder="{{ __('PkgUtilisateurs::apprenant.cin') }}"
                value="{{ $itemApprenant ? $itemApprenant->cin : old('cin') }}">
            @error('cin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        <div class="form-group">
            <label for="adresse">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.adresse')) }}
                
            </label>
            <input
                name="adresse"
                type="input"
                class="form-control"
                
                id="adresse"
                placeholder="{{ __('PkgUtilisateurs::apprenant.adresse') }}"
                value="{{ $itemApprenant ? $itemApprenant->adresse : old('adresse') }}">
            @error('adresse')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        
    <div class="form-group">
            <label for="groupe_id">
                {{ ucfirst(__('PkgUtilisateurs::groupe.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="groupe_id" 
            name="groupe_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($groupes as $groupe)
                    <option value="{{ $groupe->id }}"
                        {{ (isset($itemApprenant) && $itemApprenant->groupe_id == $groupe->id) || (old('groupe_id>') == $groupe->id) ? 'selected' : '' }}>
                        {{ $groupe }}
                    </option>
                @endforeach
            </select>
            @error('groupe_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

        
        
        
        
    <div class="form-group">
            <label for="niveaux_scolaire_id">
                {{ ucfirst(__('PkgUtilisateurs::niveauxScolaire.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="niveaux_scolaire_id" 
            name="niveaux_scolaire_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($niveauxScolaires as $niveauxScolaire)
                    <option value="{{ $niveauxScolaire->id }}"
                        {{ (isset($itemApprenant) && $itemApprenant->niveaux_scolaire_id == $niveauxScolaire->id) || (old('niveaux_scolaire_id>') == $niveauxScolaire->id) ? 'selected' : '' }}>
                        {{ $niveauxScolaire }}
                    </option>
                @endforeach
            </select>
            @error('niveaux_scolaire_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

        
        
        
        
    <div class="form-group">
            <label for="nationalite_id">
                {{ ucfirst(__('PkgUtilisateurs::nationalite.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="nationalite_id" 
            name="nationalite_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($nationalites as $nationalite)
                    <option value="{{ $nationalite->id }}"
                        {{ (isset($itemApprenant) && $itemApprenant->nationalite_id == $nationalite->id) || (old('nationalite_id>') == $nationalite->id) ? 'selected' : '' }}>
                        {{ $nationalite }}
                    </option>
                @endforeach
            </select>
            @error('nationalite_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

        
        
    </div>

    <div class="card-footer">
        <a href="{{ route('apprenants.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemApprenant->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


