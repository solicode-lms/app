{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="resourceForm" action="{{ $itemResource->id ? route('resources.update', $itemResource->id) : route('resources.store') }}" method="POST">
    @csrf

    @if ($itemResource->id)
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
                value="{{ $itemResource ? $itemResource->nom : old('nom') }}">
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
                value="{{ $itemResource ? $itemResource->lien : old('lien') }}">
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
                value="{{ $itemResource ? $itemResource->description : old('description') }}">
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
        <a href="{{ route('resources.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemResource->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'projet_id',
            fetchUrl: "{{ route('projets.all') }}",
            selectedValue: {{ $itemResource->projet_id ? $itemResource->projet_id : 'undefined' }},
            fieldValue: 'titre'
        }
        
    ];
</script>


