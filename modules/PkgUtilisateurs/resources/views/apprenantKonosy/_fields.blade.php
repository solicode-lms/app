{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="apprenantKonosyForm" action="{{ $itemApprenantKonosy->id ? route('apprenantKonosies.update', $itemApprenantKonosy->id) : route('apprenantKonosies.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemApprenantKonosy->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="MatriculeEtudiant">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.MatriculeEtudiant')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="MatriculeEtudiant"
                type="input"
                class="form-control"
                required
                id="MatriculeEtudiant"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.MatriculeEtudiant') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->MatriculeEtudiant : old('MatriculeEtudiant') }}">
            @error('MatriculeEtudiant')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Nom">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="Nom"
                type="input"
                class="form-control"
                required
                id="Nom"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Nom') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Nom : old('Nom') }}">
            @error('Nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Prenom">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Prenom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="Prenom"
                type="input"
                class="form-control"
                required
                id="Prenom"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Prenom') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Prenom : old('Prenom') }}">
            @error('Prenom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Sexe">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Sexe')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="Sexe"
                type="input"
                class="form-control"
                required
                id="Sexe"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Sexe') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Sexe : old('Sexe') }}">
            @error('Sexe')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="EtudiantActif">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.EtudiantActif')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="EtudiantActif"
                type="input"
                class="form-control"
                required
                id="EtudiantActif"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.EtudiantActif') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->EtudiantActif : old('EtudiantActif') }}">
            @error('EtudiantActif')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Diplome">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Diplome')) }}
                
            </label>
            <input
                name="Diplome"
                type="input"
                class="form-control"
                
                id="Diplome"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Diplome') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Diplome : old('Diplome') }}">
            @error('Diplome')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Principale">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Principale')) }}
                
            </label>
            <input
                name="Principale"
                type="input"
                class="form-control"
                
                id="Principale"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Principale') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Principale : old('Principale') }}">
            @error('Principale')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="LibelleLong">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.LibelleLong')) }}
                
            </label>
            <input
                name="LibelleLong"
                type="input"
                class="form-control"
                
                id="LibelleLong"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.LibelleLong') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->LibelleLong : old('LibelleLong') }}">
            @error('LibelleLong')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="CodeDiplome">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.CodeDiplome')) }}
                
            </label>
            <input
                name="CodeDiplome"
                type="input"
                class="form-control"
                
                id="CodeDiplome"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.CodeDiplome') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->CodeDiplome : old('CodeDiplome') }}">
            @error('CodeDiplome')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="DateNaissance">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.DateNaissance')) }}
                
            </label>
            <input
                name="DateNaissance"
                type="input"
                class="form-control"
                
                id="DateNaissance"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.DateNaissance') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->DateNaissance : old('DateNaissance') }}">
            @error('DateNaissance')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="DateInscription">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.DateInscription')) }}
                
            </label>
            <input
                name="DateInscription"
                type="input"
                class="form-control"
                
                id="DateInscription"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.DateInscription') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->DateInscription : old('DateInscription') }}">
            @error('DateInscription')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="LieuNaissance">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.LieuNaissance')) }}
                
            </label>
            <input
                name="LieuNaissance"
                type="input"
                class="form-control"
                
                id="LieuNaissance"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.LieuNaissance') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->LieuNaissance : old('LieuNaissance') }}">
            @error('LieuNaissance')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="CIN">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.CIN')) }}
                
            </label>
            <input
                name="CIN"
                type="input"
                class="form-control"
                
                id="CIN"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.CIN') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->CIN : old('CIN') }}">
            @error('CIN')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="NTelephone">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.NTelephone')) }}
                
            </label>
            <input
                name="NTelephone"
                type="input"
                class="form-control"
                
                id="NTelephone"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.NTelephone') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->NTelephone : old('NTelephone') }}">
            @error('NTelephone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Adresse">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Adresse')) }}
                
            </label>
            <input
                name="Adresse"
                type="input"
                class="form-control"
                
                id="Adresse"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Adresse') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Adresse : old('Adresse') }}">
            @error('Adresse')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Nationalite">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Nationalite')) }}
                
            </label>
            <input
                name="Nationalite"
                type="input"
                class="form-control"
                
                id="Nationalite"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Nationalite') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Nationalite : old('Nationalite') }}">
            @error('Nationalite')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Nom_Arabe">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Nom_Arabe')) }}
                
            </label>
            <input
                name="Nom_Arabe"
                type="input"
                class="form-control"
                
                id="Nom_Arabe"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Nom_Arabe') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Nom_Arabe : old('Nom_Arabe') }}">
            @error('Nom_Arabe')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Prenom_Arabe">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Prenom_Arabe')) }}
                
            </label>
            <input
                name="Prenom_Arabe"
                type="input"
                class="form-control"
                
                id="Prenom_Arabe"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Prenom_Arabe') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Prenom_Arabe : old('Prenom_Arabe') }}">
            @error('Prenom_Arabe')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="NiveauScolaire">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.NiveauScolaire')) }}
                
            </label>
            <input
                name="NiveauScolaire"
                type="input"
                class="form-control"
                
                id="NiveauScolaire"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.NiveauScolaire') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->NiveauScolaire : old('NiveauScolaire') }}">
            @error('NiveauScolaire')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        



    </div>


    <div class="card-footer">
        <a href="{{ route('apprenantKonosies.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemApprenantKonosy->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


