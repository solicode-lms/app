{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('formateurs.update', $item->id) : route('formateurs.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgUtilisateurs::formateur.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                id="nom"
                placeholder="{{ __('Enter PkgUtilisateurs::formateur.nom') }}"
                value="{{ $item ? $item->nom : old('nom') }}">
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
                id="prenom"
                placeholder="{{ __('Enter PkgUtilisateurs::formateur.prenom') }}"
                value="{{ $item ? $item->prenom : old('prenom') }}">
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
                id="prenom_arab"
                placeholder="{{ __('Enter PkgUtilisateurs::formateur.prenom_arab') }}"
                value="{{ $item ? $item->prenom_arab : old('prenom_arab') }}">
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
                id="nom_arab"
                placeholder="{{ __('Enter PkgUtilisateurs::formateur.nom_arab') }}"
                value="{{ $item ? $item->nom_arab : old('nom_arab') }}">
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
                id="tele_num"
                placeholder="{{ __('Enter PkgUtilisateurs::formateur.tele_num') }}"
                value="{{ $item ? $item->tele_num : old('tele_num') }}">
            @error('tele_num')
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
                id="profile_image"
                placeholder="{{ __('Enter PkgUtilisateurs::formateur.profile_image') }}"
                value="{{ $item ? $item->profile_image : old('profile_image') }}">
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
        <a href="{{ route('formateurs.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
    ];
</script>
