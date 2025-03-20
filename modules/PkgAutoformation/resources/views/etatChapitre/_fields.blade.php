{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatChapitre-form')
<form class="crud-form custom-form context-state container" id="etatChapitreForm" action="{{ $itemEtatChapitre->id ? route('etatChapitres.update', $itemEtatChapitre->id) : route('etatChapitres.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemEtatChapitre->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="code">
                {{ ucfirst(__('PkgAutoformation::etatChapitre.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                required
                
                id="code"
                placeholder="{{ __('PkgAutoformation::etatChapitre.code') }}"
                value="{{ $itemEtatChapitre ? $itemEtatChapitre->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="nom">
                {{ ucfirst(__('PkgAutoformation::etatChapitre.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                id="nom"
                placeholder="{{ __('PkgAutoformation::etatChapitre.nom') }}"
                value="{{ $itemEtatChapitre ? $itemEtatChapitre->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="workflow_chapitre_id">
                {{ ucfirst(__('PkgAutoformation::workflowChapitre.singular')) }}
                
            </label>
            <select 
            id="workflow_chapitre_id" 
            
            
            name="workflow_chapitre_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($workflowChapitres as $workflowChapitre)
                    <option value="{{ $workflowChapitre->id }}"
                        {{ (isset($itemEtatChapitre) && $itemEtatChapitre->workflow_chapitre_id == $workflowChapitre->id) || (old('workflow_chapitre_id>') == $workflowChapitre->id) ? 'selected' : '' }}>
                        {{ $workflowChapitre }}
                    </option>
                @endforeach
            </select>
            @error('workflow_chapitre_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-12">
            <label for="description">
                {{ ucfirst(__('PkgAutoformation::etatChapitre.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgAutoformation::etatChapitre.description') }}">{{ $itemEtatChapitre ? $itemEtatChapitre->description : old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        

        <!--   RealisationChapitre HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('etatChapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEtatChapitre->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgAutoformation::etatChapitre.singular") }} : {{$itemEtatChapitre}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
