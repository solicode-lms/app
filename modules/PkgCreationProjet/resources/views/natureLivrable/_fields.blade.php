{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="natureLivrableForm" action="{{ $itemNatureLivrable->id ? route('natureLivrables.update', $itemNatureLivrable->id) : route('natureLivrables.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemNatureLivrable->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgCreationProjet::natureLivrable.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                id="nom"
                placeholder="{{ __('PkgCreationProjet::natureLivrable.nom') }}"
                value="{{ $itemNatureLivrable ? $itemNatureLivrable->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCreationProjet::natureLivrable.description')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                required
                id="description"
                placeholder="{{ __('PkgCreationProjet::natureLivrable.description') }}"
                value="{{ $itemNatureLivrable ? $itemNatureLivrable->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        
        
        <!--   Livrable_HasMany HasMany --> 
        
        
    </div>

    <div class="card-footer">
        <a href="{{ route('natureLivrables.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNatureLivrable->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


