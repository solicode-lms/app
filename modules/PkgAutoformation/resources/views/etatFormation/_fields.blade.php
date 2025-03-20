{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatFormation-form')
<form class="crud-form custom-form context-state container" id="etatFormationForm" action="{{ $itemEtatFormation->id ? route('etatFormations.update', $itemEtatFormation->id) : route('etatFormations.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemEtatFormation->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="code">
                {{ ucfirst(__('PkgAutoformation::etatFormation.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                required
                
                id="code"
                placeholder="{{ __('PkgAutoformation::etatFormation.code') }}"
                value="{{ $itemEtatFormation ? $itemEtatFormation->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="nom">
                {{ ucfirst(__('PkgAutoformation::etatFormation.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                id="nom"
                placeholder="{{ __('PkgAutoformation::etatFormation.nom') }}"
                value="{{ $itemEtatFormation ? $itemEtatFormation->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-12">
            <label for="description">
                {{ ucfirst(__('PkgAutoformation::etatFormation.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgAutoformation::etatFormation.description') }}">{{ $itemEtatFormation ? $itemEtatFormation->description : old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="workflow_formation_id">
                {{ ucfirst(__('PkgAutoformation::workflowFormation.singular')) }}
                
            </label>
            <select 
            id="workflow_formation_id" 
            
            
            name="workflow_formation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($workflowFormations as $workflowFormation)
                    <option value="{{ $workflowFormation->id }}"
                        {{ (isset($itemEtatFormation) && $itemEtatFormation->workflow_formation_id == $workflowFormation->id) || (old('workflow_formation_id>') == $workflowFormation->id) ? 'selected' : '' }}>
                        {{ $workflowFormation }}
                    </option>
                @endforeach
            </select>
            @error('workflow_formation_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        

        <!--   RealisationFormation HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('etatFormations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEtatFormation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgAutoformation::etatFormation.singular") }} : {{$itemEtatFormation}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
