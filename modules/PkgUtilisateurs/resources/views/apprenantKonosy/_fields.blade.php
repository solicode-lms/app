{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('apprenantKonosies.update', $item->id) : route('apprenantKonosies.store') }}" method="POST">
    @csrf

    @if ($item->id)
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
                id="MatriculeEtudiant"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.MatriculeEtudiant') }}"
                value="{{ $item ? $item->MatriculeEtudiant : old('MatriculeEtudiant') }}">
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
                id="Nom"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Nom') }}"
                value="{{ $item ? $item->Nom : old('Nom') }}">
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
                id="Prenom"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Prenom') }}"
                value="{{ $item ? $item->Prenom : old('Prenom') }}">
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
                id="Sexe"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Sexe') }}"
                value="{{ $item ? $item->Sexe : old('Sexe') }}">
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
                id="EtudiantActif"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.EtudiantActif') }}"
                value="{{ $item ? $item->EtudiantActif : old('EtudiantActif') }}">
            @error('EtudiantActif')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Diplome">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Diplome')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="Diplome"
                type="input"
                class="form-control"
                id="Diplome"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Diplome') }}"
                value="{{ $item ? $item->Diplome : old('Diplome') }}">
            @error('Diplome')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Principale">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Principale')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="Principale"
                type="input"
                class="form-control"
                id="Principale"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Principale') }}"
                value="{{ $item ? $item->Principale : old('Principale') }}">
            @error('Principale')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="LibelleLong">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.LibelleLong')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="LibelleLong"
                type="input"
                class="form-control"
                id="LibelleLong"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.LibelleLong') }}"
                value="{{ $item ? $item->LibelleLong : old('LibelleLong') }}">
            @error('LibelleLong')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="CodeDiplome">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.CodeDiplome')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="CodeDiplome"
                type="input"
                class="form-control"
                id="CodeDiplome"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.CodeDiplome') }}"
                value="{{ $item ? $item->CodeDiplome : old('CodeDiplome') }}">
            @error('CodeDiplome')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="DateNaissance">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.DateNaissance')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="DateNaissance"
                type="input"
                class="form-control"
                id="DateNaissance"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.DateNaissance') }}"
                value="{{ $item ? $item->DateNaissance : old('DateNaissance') }}">
            @error('DateNaissance')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="DateInscription">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.DateInscription')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="DateInscription"
                type="input"
                class="form-control"
                id="DateInscription"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.DateInscription') }}"
                value="{{ $item ? $item->DateInscription : old('DateInscription') }}">
            @error('DateInscription')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="LieuNaissance">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.LieuNaissance')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="LieuNaissance"
                type="input"
                class="form-control"
                id="LieuNaissance"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.LieuNaissance') }}"
                value="{{ $item ? $item->LieuNaissance : old('LieuNaissance') }}">
            @error('LieuNaissance')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="CIN">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.CIN')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="CIN"
                type="input"
                class="form-control"
                id="CIN"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.CIN') }}"
                value="{{ $item ? $item->CIN : old('CIN') }}">
            @error('CIN')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="NTelephone">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.NTelephone')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="NTelephone"
                type="input"
                class="form-control"
                id="NTelephone"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.NTelephone') }}"
                value="{{ $item ? $item->NTelephone : old('NTelephone') }}">
            @error('NTelephone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Adresse">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Adresse')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="Adresse"
                type="input"
                class="form-control"
                id="Adresse"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Adresse') }}"
                value="{{ $item ? $item->Adresse : old('Adresse') }}">
            @error('Adresse')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Nationalite">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Nationalite')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="Nationalite"
                type="input"
                class="form-control"
                id="Nationalite"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Nationalite') }}"
                value="{{ $item ? $item->Nationalite : old('Nationalite') }}">
            @error('Nationalite')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Nom_Arabe">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Nom_Arabe')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="Nom_Arabe"
                type="input"
                class="form-control"
                id="Nom_Arabe"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Nom_Arabe') }}"
                value="{{ $item ? $item->Nom_Arabe : old('Nom_Arabe') }}">
            @error('Nom_Arabe')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Prenom_Arabe">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Prenom_Arabe')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="Prenom_Arabe"
                type="input"
                class="form-control"
                id="Prenom_Arabe"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.Prenom_Arabe') }}"
                value="{{ $item ? $item->Prenom_Arabe : old('Prenom_Arabe') }}">
            @error('Prenom_Arabe')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="NiveauScolaire">
                {{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.NiveauScolaire')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="NiveauScolaire"
                type="input"
                class="form-control"
                id="NiveauScolaire"
                placeholder="{{ __('PkgUtilisateurs::apprenantKonosy.NiveauScolaire') }}"
                value="{{ $item ? $item->NiveauScolaire : old('NiveauScolaire') }}">
            @error('NiveauScolaire')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        



    </div>

    <div class="card-footer">
        <a href="{{ route('apprenantKonosies.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
    ];
</script>