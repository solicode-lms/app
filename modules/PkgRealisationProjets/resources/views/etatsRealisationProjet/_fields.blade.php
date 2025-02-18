{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatsRealisationProjet-form')
<form class="crud-form custom-form context-state container" id="etatsRealisationProjetForm" action="{{ $itemEtatsRealisationProjet->id ? route('etatsRealisationProjets.update', $itemEtatsRealisationProjet->id) : route('etatsRealisationProjets.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemEtatsRealisationProjet->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        
    <div class="form-group col-12 col-md-6">
            <label for="formateur_id">
                {{ ucfirst(__('PkgFormation::formateur.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="formateur_id" 
            required
            
            name="formateur_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemEtatsRealisationProjet) && $itemEtatsRealisationProjet->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
            @error('formateur_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-6">
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

        
        <div class="form-group col-12 col-md-12">
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
<script>
     window.modalTitle = '{{__("PkgRealisationProjets::etatsRealisationProjet.singular") }} : {{$itemEtatsRealisationProjet}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
