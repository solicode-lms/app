{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('resources.update', $item->id) : route('resources.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgCreationProjet::resource.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                id="nom"
                placeholder="{{ __('PkgCreationProjet::resource.nom') }}"
                value="{{ $item ? $item->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="lien">
                {{ ucfirst(__('PkgCreationProjet::resource.lien')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="lien"
                type="input"
                class="form-control"
                id="lien"
                placeholder="{{ __('PkgCreationProjet::resource.lien') }}"
                value="{{ $item ? $item->lien : old('lien') }}">
            @error('lien')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCreationProjet::resource.description')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                id="description"
                placeholder="{{ __('PkgCreationProjet::resource.description') }}"
                value="{{ $item ? $item->description : old('description') }}">
            @error('description')
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
        <a href="{{ route('resources.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'projet_id',
            fetchUrl: "{{ route('projets.all') }}",
            selectedValue: {{ $item->projet_id ? $item->projet_id : 'undefined' }},
            fieldValue: 'titre'
        }
        
    ];
</script>
