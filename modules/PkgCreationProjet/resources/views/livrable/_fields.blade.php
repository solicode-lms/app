{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form id="livrableForm" action="{{ $itemLivrable->id ? route('livrables.update', $itemLivrable->id) : route('livrables.store') }}" method="POST">
    @csrf

    @if ($itemLivrable->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="titre">
                {{ ucfirst(__('PkgCreationProjet::livrable.titre')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="titre"
                type="input"
                class="form-control"
                id="titre"
                placeholder="{{ __('PkgCreationProjet::livrable.titre') }}"
                value="{{ $itemLivrable ? $itemLivrable->titre : old('titre') }}">
            @error('titre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCreationProjet::livrable.description')) }}
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                id="description"
                placeholder="{{ __('PkgCreationProjet::livrable.description') }}"
                value="{{ $itemLivrable ? $itemLivrable->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="nature_livrable_id">
                {{ ucfirst(__('PkgCreationProjet::natureLivrable.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="nature_livrable_id" name="nature_livrable_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('nature_livrable_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="projet_id">
                {{ ucfirst(__('PkgCreationProjet::projet.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="projet_id" name="projet_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('projet_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        



    </div>


    <div class="card-footer">
        <a href="{{ route('livrables.index') }}" id="livrable_form_cancel" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemLivrable->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'nature_livrable_id',
            fetchUrl: "{{ route('natureLivrables.all') }}",
            selectedValue: {{ $itemLivrable->nature_livrable_id ? $itemLivrable->nature_livrable_id : 'undefined' }},
            fieldValue: 'nom'
        },
        
        {
            fieldId: 'projet_id',
            fetchUrl: "{{ route('projets.all') }}",
            selectedValue: {{ $itemLivrable->projet_id ? $itemLivrable->projet_id : 'undefined' }},
            fieldValue: 'titre'
        }
        
    ];
</script>
