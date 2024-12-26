{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('livrables.update', $item->id) : route('livrables.store') }}" method="POST">
    @csrf

    @if ($item->id)
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
                value="{{ $item ? $item->titre : old('titre') }}">
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
                value="{{ $item ? $item->description : old('description') }}">
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
        <a href="{{ route('livrables.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'nature_livrable_id',
            fetchUrl: "{{ route('natureLivrables.all') }}",
            selectedValue: {{ $item->nature_livrable_id ? $item->nature_livrable_id : 'undefined' }},
            fieldValue: 'nom'
        },
        
        {
            fieldId: 'projet_id',
            fetchUrl: "{{ route('projets.all') }}",
            selectedValue: {{ $item->projet_id ? $item->projet_id : 'undefined' }},
            fieldValue: 'titre'
        }
        
    ];
</script>
