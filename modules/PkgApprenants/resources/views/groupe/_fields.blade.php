{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('groupe-form')
<form class="crud-form custom-form context-state" id="groupeForm" action="{{ $itemGroupe->id ? route('groupes.update', $itemGroupe->id) : route('groupes.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemGroupe->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="code">
                {{ ucfirst(__('PkgApprenants::groupe.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                required
                id="code"
                placeholder="{{ __('PkgApprenants::groupe.code') }}"
                value="{{ $itemGroupe ? $itemGroupe->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgApprenants::groupe.nom')) }}
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                
                id="nom"
                placeholder="{{ __('PkgApprenants::groupe.nom') }}"
                value="{{ $itemGroupe ? $itemGroupe->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgApprenants::groupe.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgApprenants::groupe.description') }}">
                {{ $itemGroupe ? $itemGroupe->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        
    <div class="form-group">
            <label for="filiere_id">
                {{ ucfirst(__('PkgFormation::filiere.singular')) }}
                
            </label>
            <select 
            id="filiere_id" 
            
            name="filiere_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($filieres as $filiere)
                    <option value="{{ $filiere->id }}"
                        {{ (isset($itemGroupe) && $itemGroupe->filiere_id == $filiere->id) || (old('filiere_id>') == $filiere->id) ? 'selected' : '' }}>
                        {{ $filiere }}
                    </option>
                @endforeach
            </select>
            @error('filiere_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        

        <!--   Apprenant HasMany --> 

        
                <div class="form-group">
            <label for="formateurs">
                {{ ucfirst(__('PkgFormation::Formateur.plural')) }}
            </label>
            <select
                id="formateurs"
                name="formateurs[]"
                class="form-control select2"
                multiple="multiple">
               
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemGroupe) && $itemGroupe->formateurs && $itemGroupe->formateurs->contains('id', $formateur->id)) || (is_array(old('formateurs')) && in_array($formateur->id, old('formateurs'))) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
            @error('formateurs')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>


    </div>

    <div class="card-footer">
        <a href="{{ route('groupes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemGroupe->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>

