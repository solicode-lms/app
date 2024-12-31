{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="livrableForm" action="{{ $itemLivrable->id ? route('livrables.update', $itemLivrable->id) : route('livrables.store') }}" method="POST" novalidate>
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
                required
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
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgCreationProjet::livrable.description') }}">
                {{ $itemLivrable ? $itemLivrable->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
    <div class="form-group" style="display: {{ isset($page['scop_entity']) && $page['scop_entity'] == 'projet' ? 'none' : 'block' }}">
            <label for="projet_id">
                {{ ucfirst(__('PkgCreationProjet::projet.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="projet_id" 
            required
            name="projet_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($projets as $projet)
                    <option value="{{ $projet->id }}"
                        {{ (isset($itemLivrable) && $itemLivrable->projet_id == $projet->id) || (old('projet_id>') == $projet->id) ? 'selected' : '' }}>
                        {{ $projet }}
                    </option>
                @endforeach
            </select>
            @error('projet_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
    <div class="form-group">
            <label for="nature_livrable_id">
                {{ ucfirst(__('PkgCreationProjet::natureLivrable.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="nature_livrable_id" 
            required
            name="nature_livrable_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($natureLivrables as $natureLivrable)
                    <option value="{{ $natureLivrable->id }}"
                        {{ (isset($itemLivrable) && $itemLivrable->nature_livrable_id == $natureLivrable->id) || (old('nature_livrable_id>') == $natureLivrable->id) ? 'selected' : '' }}>
                        {{ $natureLivrable }}
                    </option>
                @endforeach
            </select>
            @error('nature_livrable_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


    </div>

    <div class="card-footer">
        <a href="{{ route('livrables.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemLivrable->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


