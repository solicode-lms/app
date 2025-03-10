{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('prioriteTache-form')
<form class="crud-form custom-form context-state container" id="prioriteTacheForm" action="{{ $itemPrioriteTache->id ? route('prioriteTaches.update', $itemPrioriteTache->id) : route('prioriteTaches.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemPrioriteTache->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="nom">
                {{ ucfirst(__('PkgGestionTaches::prioriteTache.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                id="nom"
                placeholder="{{ __('PkgGestionTaches::prioriteTache.nom') }}"
                value="{{ $itemPrioriteTache ? $itemPrioriteTache->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="ordre">
                {{ ucfirst(__('PkgGestionTaches::prioriteTache.ordre')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="ordre"
                type="number"
                class="form-control"
                required
                
                id="ordre"
                placeholder="{{ __('PkgGestionTaches::prioriteTache.ordre') }}"
                value="{{ $itemPrioriteTache ? $itemPrioriteTache->ordre : old('ordre') }}">
            @error('ordre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-12">
            <label for="description">
                {{ ucfirst(__('PkgGestionTaches::prioriteTache.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgGestionTaches::prioriteTache.description') }}">{{ $itemPrioriteTache ? $itemPrioriteTache->description : old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
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
                        {{ (isset($itemPrioriteTache) && $itemPrioriteTache->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
            @error('formateur_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        

        <!--   Tache HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('prioriteTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemPrioriteTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgGestionTaches::prioriteTache.singular") }} : {{$itemPrioriteTache}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
