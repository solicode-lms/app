{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauxScolaire-form')
<form class="crud-form custom-form context-state container" id="niveauxScolaireForm" action="{{ $itemNiveauxScolaire->id ? route('niveauxScolaires.update', $itemNiveauxScolaire->id) : route('niveauxScolaires.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemNiveauxScolaire->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="code">
                {{ ucfirst(__('PkgApprenants::niveauxScolaire.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                required
                
                id="code"
                placeholder="{{ __('PkgApprenants::niveauxScolaire.code') }}"
                value="{{ $itemNiveauxScolaire ? $itemNiveauxScolaire->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="nom">
                {{ ucfirst(__('PkgApprenants::niveauxScolaire.nom')) }}
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                
                
                id="nom"
                placeholder="{{ __('PkgApprenants::niveauxScolaire.nom') }}"
                value="{{ $itemNiveauxScolaire ? $itemNiveauxScolaire->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-12">
            <label for="description">
                {{ ucfirst(__('PkgApprenants::niveauxScolaire.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgApprenants::niveauxScolaire.description') }}">{{ $itemNiveauxScolaire ? $itemNiveauxScolaire->description : old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        

        <!--   Apprenant HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('niveauxScolaires.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNiveauxScolaire->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgApprenants::niveauxScolaire.singular") }} : {{$itemNiveauxScolaire}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
