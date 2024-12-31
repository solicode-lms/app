{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="competenceForm" action="{{ $itemCompetence->id ? route('competences.update', $itemCompetence->id) : route('competences.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemCompetence->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
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

        <div class="form-group">
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

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCompetences::competence.description')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                required
                id="description"
                placeholder="{{ __('PkgCompetences::competence.description') }}"
                value="{{ $itemCompetence ? $itemCompetence->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
    <div class="form-group">
            <label for="module_id">
                {{ ucfirst(__('PkgCompetences::module.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="module_id" 
            name="module_id" 
            class="form-control">
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


                <div class="form-group">
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



        <!--   NiveauCompetence_HasMany HasMany --> 


        <!--   TransfertCompetence_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('competences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemCompetence->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


