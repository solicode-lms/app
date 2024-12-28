{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="niveauxScolaireForm" action="{{ $itemNiveauxScolaire->id ? route('niveauxScolaires.update', $itemNiveauxScolaire->id) : route('niveauxScolaires.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemNiveauxScolaire->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="code">
                {{ ucfirst(__('PkgUtilisateurs::niveauxScolaire.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                required
                id="code"
                placeholder="{{ __('PkgUtilisateurs::niveauxScolaire.code') }}"
                value="{{ $itemNiveauxScolaire ? $itemNiveauxScolaire->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgUtilisateurs::niveauxScolaire.nom')) }}
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                
                id="nom"
                placeholder="{{ __('PkgUtilisateurs::niveauxScolaire.nom') }}"
                value="{{ $itemNiveauxScolaire ? $itemNiveauxScolaire->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgUtilisateurs::niveauxScolaire.description')) }}
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                
                id="description"
                placeholder="{{ __('PkgUtilisateurs::niveauxScolaire.description') }}"
                value="{{ $itemNiveauxScolaire ? $itemNiveauxScolaire->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        



    </div>


    <div class="card-footer">
        <a href="{{ route('niveauxScolaires.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNiveauxScolaire->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


