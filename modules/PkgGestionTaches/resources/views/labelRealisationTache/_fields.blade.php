{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('labelRealisationTache-form')
<form class="crud-form custom-form context-state container" id="labelRealisationTacheForm" action="{{ $itemLabelRealisationTache->id ? route('labelRealisationTaches.update', $itemLabelRealisationTache->id) : route('labelRealisationTaches.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemLabelRealisationTache->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="nom">
                {{ ucfirst(__('PkgGestionTaches::labelRealisationTache.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                id="nom"
                placeholder="{{ __('PkgGestionTaches::labelRealisationTache.nom') }}"
                value="{{ $itemLabelRealisationTache ? $itemLabelRealisationTache->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-12">
            <label for="description">
                {{ ucfirst(__('PkgGestionTaches::labelRealisationTache.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgGestionTaches::labelRealisationTache.description') }}">{{ $itemLabelRealisationTache ? $itemLabelRealisationTache->description : old('description') }}</textarea>
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
                        {{ (isset($itemLabelRealisationTache) && $itemLabelRealisationTache->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
            @error('formateur_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-6">
            <label for="sys_color_id">
                {{ ucfirst(__('Core::sysColor.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="sys_color_id" 
            required
            
            name="sys_color_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysColors as $sysColor)
                    <option value="{{ $sysColor->id }}"
                        {{ (isset($itemLabelRealisationTache) && $itemLabelRealisationTache->sys_color_id == $sysColor->id) || (old('sys_color_id>') == $sysColor->id) ? 'selected' : '' }}>
                        {{ $sysColor }}
                    </option>
                @endforeach
            </select>
            @error('sys_color_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


    </div>

    <div class="card-footer">
        <a href="{{ route('labelRealisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemLabelRealisationTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgGestionTaches::labelRealisationTache.singular") }} : {{$itemLabelRealisationTache}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
