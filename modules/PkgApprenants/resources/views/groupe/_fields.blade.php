{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('groupe-form')
<form class="crud-form custom-form context-state container" id="groupeForm" action="{{ $itemGroupe->id ? route('groupes.update', $itemGroupe->id) : route('groupes.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemGroupe->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
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

        
        <div class="form-group col-12 col-md-6">
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

        
        <div class="form-group col-12 col-md-12">
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

        
        <div class="form-group col-12 col-md-6">
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


        
        <div class="form-group col-12 col-md-6">
            <label for="annee_formation_id">
                {{ ucfirst(__('PkgFormation::anneeFormation.singular')) }}
                
            </label>
            <select 
            id="annee_formation_id" 
            
            
            name="annee_formation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($anneeFormations as $anneeFormation)
                    <option value="{{ $anneeFormation->id }}"
                        {{ (isset($itemGroupe) && $itemGroupe->annee_formation_id == $anneeFormation->id) || (old('annee_formation_id>') == $anneeFormation->id) ? 'selected' : '' }}>
                        {{ $anneeFormation }}
                    </option>
                @endforeach
            </select>
            @error('annee_formation_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        

        <!--   AffectationProjet HasMany --> 

        
                    <div class="form-group col-12 col-md-6">
            <label for="apprenants">
                {{ ucfirst(__('PkgApprenants::Apprenant.plural')) }}
            </label>
            <select
                id="apprenants"
                name="apprenants[]"
                class="form-control select2"
                
                multiple="multiple">
               
                @foreach ($apprenants as $apprenant)
                    <option value="{{ $apprenant->id }}"
                        {{ (isset($itemGroupe) && $itemGroupe->apprenants && $itemGroupe->apprenants->contains('id', $apprenant->id)) || (is_array(old('apprenants')) && in_array($apprenant->id, old('apprenants'))) ? 'selected' : '' }}>
                        {{ $apprenant }}
                    </option>
                @endforeach
            </select>
            @error('apprenants')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>


        
                    <div class="form-group col-12 col-md-6">
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
<script>
     window.modalTitle = '{{__("PkgApprenants::groupe.singular") }} : {{$itemGroupe}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
