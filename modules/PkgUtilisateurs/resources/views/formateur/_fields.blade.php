{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="formateurForm" action="{{ $itemFormateur->id ? route('formateurs.update', $itemFormateur->id) : route('formateurs.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemFormateur->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="matricule">
                {{ ucfirst(__('PkgUtilisateurs::formateur.matricule')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="matricule"
                type="input"
                class="form-control"
                required
                id="matricule"
                placeholder="{{ __('PkgUtilisateurs::formateur.matricule') }}"
                value="{{ $itemFormateur ? $itemFormateur->matricule : old('matricule') }}">
            @error('matricule')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgUtilisateurs::formateur.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                id="nom"
                placeholder="{{ __('PkgUtilisateurs::formateur.nom') }}"
                value="{{ $itemFormateur ? $itemFormateur->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="prenom">
                {{ ucfirst(__('PkgUtilisateurs::formateur.prenom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="prenom"
                type="input"
                class="form-control"
                required
                id="prenom"
                placeholder="{{ __('PkgUtilisateurs::formateur.prenom') }}"
                value="{{ $itemFormateur ? $itemFormateur->prenom : old('prenom') }}">
            @error('prenom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="prenom_arab">
                {{ ucfirst(__('PkgUtilisateurs::formateur.prenom_arab')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="prenom_arab"
                type="input"
                class="form-control"
                required
                id="prenom_arab"
                placeholder="{{ __('PkgUtilisateurs::formateur.prenom_arab') }}"
                value="{{ $itemFormateur ? $itemFormateur->prenom_arab : old('prenom_arab') }}">
            @error('prenom_arab')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="nom_arab">
                {{ ucfirst(__('PkgUtilisateurs::formateur.nom_arab')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom_arab"
                type="input"
                class="form-control"
                required
                id="nom_arab"
                placeholder="{{ __('PkgUtilisateurs::formateur.nom_arab') }}"
                value="{{ $itemFormateur ? $itemFormateur->nom_arab : old('nom_arab') }}">
            @error('nom_arab')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="tele_num">
                {{ ucfirst(__('PkgUtilisateurs::formateur.tele_num')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="tele_num"
                type="input"
                class="form-control"
                required
                id="tele_num"
                placeholder="{{ __('PkgUtilisateurs::formateur.tele_num') }}"
                value="{{ $itemFormateur ? $itemFormateur->tele_num : old('tele_num') }}">
            @error('tele_num')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="adresse">
                {{ ucfirst(__('PkgUtilisateurs::formateur.adresse')) }}
                
            </label>
            <input
                name="adresse"
                type="input"
                class="form-control"
                
                id="adresse"
                placeholder="{{ __('PkgUtilisateurs::formateur.adresse') }}"
                value="{{ $itemFormateur ? $itemFormateur->adresse : old('adresse') }}">
            @error('adresse')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="diplome">
                {{ ucfirst(__('PkgUtilisateurs::formateur.diplome')) }}
                
            </label>
            <input
                name="diplome"
                type="input"
                class="form-control"
                
                id="diplome"
                placeholder="{{ __('PkgUtilisateurs::formateur.diplome') }}"
                value="{{ $itemFormateur ? $itemFormateur->diplome : old('diplome') }}">
            @error('diplome')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="echelle">
                {{ ucfirst(__('PkgUtilisateurs::formateur.echelle')) }}
                
            </label>
            <input
                name="echelle"
                type="input"
                class="form-control"
                
                id="echelle"
                placeholder="{{ __('PkgUtilisateurs::formateur.echelle') }}"
                value="{{ $itemFormateur ? $itemFormateur->echelle : old('echelle') }}">
            @error('echelle')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="echelon">
                {{ ucfirst(__('PkgUtilisateurs::formateur.echelon')) }}
                
            </label>
            <input
                name="echelon"
                type="input"
                class="form-control"
                
                id="echelon"
                placeholder="{{ __('PkgUtilisateurs::formateur.echelon') }}"
                value="{{ $itemFormateur ? $itemFormateur->echelon : old('echelon') }}">
            @error('echelon')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="profile_image">
                {{ ucfirst(__('PkgUtilisateurs::formateur.profile_image')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="profile_image"
                type="input"
                class="form-control"
                required
                id="profile_image"
                placeholder="{{ __('PkgUtilisateurs::formateur.profile_image') }}"
                value="{{ $itemFormateur ? $itemFormateur->profile_image : old('profile_image') }}">
            @error('profile_image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        
        <div class="form-group">
            <label for="groupes">
                {{ ucfirst(__('PkgUtilisateurs::Groupe.plural')) }}
            </label>
            <select
                id="groupes"
                name="groupes[]"
                class="form-control select2"
                multiple="multiple">
               
                @foreach ($groupes as $groupe)
                    <option value="{{ $groupe->id }}"
                        {{ (isset($itemFormateur) && $itemFormateur->groupes && $itemFormateur->groupes->contains('id', $groupe->id)) || (is_array(old('groupes')) && in_array($groupe->id, old('groupes'))) ? 'selected' : '' }}>
                        {{ $groupe->code }}
                    </option>
                @endforeach
            </select>
            @error('groupes')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        
        <div class="form-group">
            <label for="specialites">
                {{ ucfirst(__('PkgUtilisateurs::Specialite.plural')) }}
            </label>
            <select
                id="specialites"
                name="specialites[]"
                class="form-control select2"
                multiple="multiple">
               
                @foreach ($specialites as $specialite)
                    <option value="{{ $specialite->id }}"
                        {{ (isset($itemFormateur) && $itemFormateur->specialites && $itemFormateur->specialites->contains('id', $specialite->id)) || (is_array(old('specialites')) && in_array($specialite->id, old('specialites'))) ? 'selected' : '' }}>
                        {{ $specialite->nom }}
                    </option>
                @endforeach
            </select>
            @error('specialites')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        



    </div>


    <div class="card-footer">
        <a href="{{ route('formateurs.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFormateur->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


