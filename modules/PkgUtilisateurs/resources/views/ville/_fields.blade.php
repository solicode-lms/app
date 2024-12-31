{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="villeForm" action="{{ $itemVille->id ? route('villes.update', $itemVille->id) : route('villes.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemVille->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgUtilisateurs::ville.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                id="nom"
                placeholder="{{ __('PkgUtilisateurs::ville.nom') }}"
                value="{{ $itemVille ? $itemVille->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

    </div>

    <div class="card-footer">
        <a href="{{ route('villes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemVille->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


