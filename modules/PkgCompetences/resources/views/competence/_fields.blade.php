{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('competence-form')
<form class="crud-form custom-form context-state container" id="competenceForm" action="{{ $itemCompetence->id ? route('competences.update', $itemCompetence->id) : route('competences.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemCompetence->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="code">
                {{ ucfirst(__('PkgCompetences::competence.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                required
                
                id="code"
                placeholder="{{ __('PkgCompetences::competence.code') }}"
                value="{{ $itemCompetence ? $itemCompetence->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="mini_code">
                {{ ucfirst(__('PkgCompetences::competence.mini_code')) }}
                
            </label>
            <input
                name="mini_code"
                type="input"
                class="form-control"
                
                
                id="mini_code"
                placeholder="{{ __('PkgCompetences::competence.mini_code') }}"
                value="{{ $itemCompetence ? $itemCompetence->mini_code : old('mini_code') }}">
            @error('mini_code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="nom">
                {{ ucfirst(__('PkgCompetences::competence.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                id="nom"
                placeholder="{{ __('PkgCompetences::competence.nom') }}"
                value="{{ $itemCompetence ? $itemCompetence->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="module_id">
                {{ ucfirst(__('PkgFormation::module.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="module_id" 
            required
            
            name="module_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($modules as $module)
                    <option value="{{ $module->id }}"
                        {{ (isset($itemCompetence) && $itemCompetence->module_id == $module->id) || (old('module_id>') == $module->id) ? 'selected' : '' }}>
                        {{ $module }}
                    </option>
                @endforeach
            </select>
            @error('module_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
                    <div class="form-group col-12 col-md-6">
            <label for="technologies">
                {{ ucfirst(__('PkgCompetences::Technology.plural')) }}
            </label>
            <select
                id="technologies"
                name="technologies[]"
                class="form-control select2"
                
                multiple="multiple">
               
                @foreach ($technologies as $technology)
                    <option value="{{ $technology->id }}"
                        {{ (isset($itemCompetence) && $itemCompetence->technologies && $itemCompetence->technologies->contains('id', $technology->id)) || (is_array(old('technologies')) && in_array($technology->id, old('technologies'))) ? 'selected' : '' }}>
                        {{ $technology }}
                    </option>
                @endforeach
            </select>
            @error('technologies')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>


        

        <!--   NiveauCompetence HasMany --> 

        

        <!--   TransfertCompetence HasMany --> 

        
        <div class="form-group col-12 col-md-12">
            <label for="description">
                {{ ucfirst(__('PkgCompetences::competence.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgCompetences::competence.description') }}">{{ $itemCompetence ? $itemCompetence->description : old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

    </div>

    <div class="card-footer">
        <a href="{{ route('competences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemCompetence->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgCompetences::competence.singular") }} : {{$itemCompetence}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
