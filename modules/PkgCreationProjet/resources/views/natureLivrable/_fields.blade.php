{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form id="natureLivrableForm" action="{{ $itemNatureLivrable->id ? route('natureLivrables.update', $itemNatureLivrable->id) : route('natureLivrables.store') }}" method="POST">
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
                id="description"
                placeholder="{{ __('PkgCreationProjet::natureLivrable.description') }}"
                value="{{ $itemNatureLivrable ? $itemNatureLivrable->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        



    </div>


    <div class="card-footer">
        <a href="{{ route('natureLivrables.index') }}" id="natureLivrable_form_cancel" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNatureLivrable->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
    ];
</script>
