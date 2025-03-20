{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowChapitre-form')
<form class="crud-form custom-form context-state container" id="workflowChapitreForm" action="{{ $itemWorkflowChapitre->id ? route('workflowChapitres.update', $itemWorkflowChapitre->id) : route('workflowChapitres.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemWorkflowChapitre->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="code">
                {{ ucfirst(__('PkgAutoformation::workflowChapitre.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                required
                
                id="code"
                placeholder="{{ __('PkgAutoformation::workflowChapitre.code') }}"
                value="{{ $itemWorkflowChapitre ? $itemWorkflowChapitre->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="titre">
                {{ ucfirst(__('PkgAutoformation::workflowChapitre.titre')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                id="titre"
                placeholder="{{ __('PkgAutoformation::workflowChapitre.titre') }}"
                value="{{ $itemWorkflowChapitre ? $itemWorkflowChapitre->titre : old('titre') }}">
            @error('titre')
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
            class="form-control select2Color">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysColors as $sysColor)
                    <option value="{{ $sysColor->id }}" data-color="{{ $sysColor->hex }}" 
                        {{ (isset($itemWorkflowChapitre) && $itemWorkflowChapitre->sys_color_id == $sysColor->id) || (old('sys_color_id>') == $sysColor->id) ? 'selected' : '' }}>
                        {{ $sysColor }}
                    </option>
                @endforeach
            </select>
            @error('sys_color_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-12">
            <label for="description">
                {{ ucfirst(__('PkgAutoformation::workflowChapitre.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgAutoformation::workflowChapitre.description') }}">{{ $itemWorkflowChapitre ? $itemWorkflowChapitre->description : old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        

        <!--   EtatChapitre HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('workflowChapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemWorkflowChapitre->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgAutoformation::workflowChapitre.singular") }} : {{$itemWorkflowChapitre}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
