{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('resource-form')
<form class="crud-form custom-form context-state" id="resourceForm" action="{{ $itemResource->id ? route('resources.update', $itemResource->id) : route('resources.store') }}" method="POST" novalidate>
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
                required
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
                required
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
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgCreationProjet::resource.description') }}">
                {{ $itemResource ? $itemResource->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        
    <div class="form-group">
            <label for="projet_id">
                {{ ucfirst(__('PkgCreationProjet::projet.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="projet_id" 
            required
            name="projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($projets as $projet)
                    <option value="{{ $projet->id }}"
                        {{ (isset($itemResource) && $itemResource->projet_id == $projet->id) || (old('projet_id>') == $projet->id) ? 'selected' : '' }}>
                        {{ $projet }}
                    </option>
                @endforeach
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
@show


<script>

</script>

