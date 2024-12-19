{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('apprenants.update', $item->id) : route('apprenants.store') }}" method="POST">
    @csrf

    @if ($item->id)
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
                id="nom"
                placeholder="{{ __('Enter PkgUtilisateurs::apprenant.nom') }}"
                value="{{ $item ? $item->nom : old('nom') }}">
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
                id="prenom"
                placeholder="{{ __('Enter PkgUtilisateurs::apprenant.prenom') }}"
                value="{{ $item ? $item->prenom : old('prenom') }}">
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
                id="prenom_arab"
                placeholder="{{ __('Enter PkgUtilisateurs::apprenant.prenom_arab') }}"
                value="{{ $item ? $item->prenom_arab : old('prenom_arab') }}">
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
                id="nom_arab"
                placeholder="{{ __('Enter PkgUtilisateurs::apprenant.nom_arab') }}"
                value="{{ $item ? $item->nom_arab : old('nom_arab') }}">
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
                id="tele_num"
                placeholder="{{ __('Enter PkgUtilisateurs::apprenant.tele_num') }}"
                value="{{ $item ? $item->tele_num : old('tele_num') }}">
            @error('tele_num')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="profile_image">
                {{ ucfirst(__('PkgUtilisateurs::apprenant.profile_image')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="profile_image"
                type="input"
                class="form-control"
                id="profile_image"
                placeholder="{{ __('Enter PkgUtilisateurs::apprenant.profile_image') }}"
                value="{{ $item ? $item->profile_image : old('profile_image') }}">
            @error('profile_image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="groupe_id">
                {{ ucfirst(__('PkgUtilisateurs::groupe.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="groupe_id" name="groupe_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('groupe_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="niveaux_scolaires_id">
                {{ ucfirst(__('PkgUtilisateurs::niveauxScolaire.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="niveaux_scolaires_id" name="niveaux_scolaires_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('niveaux_scolaires_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="ville_id">
                {{ ucfirst(__('PkgUtilisateurs::ville.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="ville_id" name="ville_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('ville_id')
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
                        {{ (isset($item) && $item->groupes && $item->groupes->contains('id', $groupe->id)) || (is_array(old('groupes')) && in_array($groupe->id, old('groupes'))) ? 'selected' : '' }}>
                        {{ $groupe->nom }}
                    </option>
                @endforeach
            </select>
            @error('groupes')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        



    </div>

    <div class="card-footer">
        <a href="{{ route('apprenants.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'groupe_id',
            fetchUrl: "{{ route('groupes.all') }}",
            selectedValue: {{ $item->groupe_id ? $item->groupe_id : 'undefined' }},
            fieldValue: 'nom'
        },
        
        {
            fieldId: 'niveaux_scolaires_id',
            fetchUrl: "{{ route('niveauxScolaires.all') }}",
            selectedValue: {{ $item->niveaux_scolaires_id ? $item->niveaux_scolaires_id : 'undefined' }},
            fieldValue: 'nom'
        },
        
        {
            fieldId: 'ville_id',
            fetchUrl: "{{ route('villes.all') }}",
            selectedValue: {{ $item->ville_id ? $item->ville_id : 'undefined' }},
            fieldValue: 'nom'
        }
        
    ];
</script>
