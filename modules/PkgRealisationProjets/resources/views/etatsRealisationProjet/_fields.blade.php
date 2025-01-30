{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatsRealisationProjet-form')
<form class="crud-form custom-form context-state" id="etatsRealisationProjetForm" action="{{ $itemEtatsRealisationProjet->id ? route('etatsRealisationProjets.update', $itemEtatsRealisationProjet->id) : route('etatsRealisationProjets.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemEtatsRealisationProjet->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="titre">
                {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.titre')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="titre"
                type="input"
                class="form-control"
                required
                id="titre"
                placeholder="{{ __('PkgRealisationProjets::etatsRealisationProjet.titre') }}"
                value="{{ $itemEtatsRealisationProjet ? $itemEtatsRealisationProjet->titre : old('titre') }}">
            @error('titre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgRealisationProjets::etatsRealisationProjet.description') }}">
                {{ $itemEtatsRealisationProjet ? $itemEtatsRealisationProjet->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        

        <!--   RealisationProjet HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('etatsRealisationProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEtatsRealisationProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>

