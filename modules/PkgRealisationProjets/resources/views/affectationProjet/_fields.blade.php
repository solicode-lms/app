{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('affectationProjet-form')
<form class="crud-form custom-form context-state container" id="affectationProjetForm" action="{{ $itemAffectationProjet->id ? route('affectationProjets.update', $itemAffectationProjet->id) : route('affectationProjets.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemAffectationProjet->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="projet_id">
                {{ ucfirst(__('PkgCreationProjet::projet.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="projet_id" 
            required
            
            name="projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($projets as $projet)
                    <option value="{{ $projet->id }}"
                        {{ (isset($itemAffectationProjet) && $itemAffectationProjet->projet_id == $projet->id) || (old('projet_id>') == $projet->id) ? 'selected' : '' }}>
                        {{ $projet }}
                    </option>
                @endforeach
            </select>
            @error('projet_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-6">
            <label for="groupe_id">
                {{ ucfirst(__('PkgApprenants::groupe.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="groupe_id" 
            required
            
            name="groupe_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($groupes as $groupe)
                    <option value="{{ $groupe->id }}"
                        {{ (isset($itemAffectationProjet) && $itemAffectationProjet->groupe_id == $groupe->id) || (old('groupe_id>') == $groupe->id) ? 'selected' : '' }}>
                        {{ $groupe }}
                    </option>
                @endforeach
            </select>
            @error('groupe_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-6">
            <label for="date_debut">
                {{ ucfirst(__('PkgRealisationProjets::affectationProjet.date_debut')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="date_debut"
                type="date"
                class="form-control datetimepicker"
                required
                
                id="date_debut"
                placeholder="{{ __('PkgRealisationProjets::affectationProjet.date_debut') }}"
                value="{{ $itemAffectationProjet ? $itemAffectationProjet->date_debut : old('date_debut') }}">
            @error('date_debut')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>





        
        <div class="form-group col-12 col-md-6">
            <label for="date_fin">
                {{ ucfirst(__('PkgRealisationProjets::affectationProjet.date_fin')) }}
                
            </label>
            <input
                name="date_fin"
                type="date"
                class="form-control datetimepicker"
                
                
                id="date_fin"
                placeholder="{{ __('PkgRealisationProjets::affectationProjet.date_fin') }}"
                value="{{ $itemAffectationProjet ? $itemAffectationProjet->date_fin : old('date_fin') }}">
            @error('date_fin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>





        
        <div class="form-group col-12 col-md-12">
            <label for="description">
                {{ ucfirst(__('PkgRealisationProjets::affectationProjet.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgRealisationProjets::affectationProjet.description') }}">{{ $itemAffectationProjet ? $itemAffectationProjet->description : old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="annee_formation_id">
                {{ ucfirst(__('PkgFormation::anneeFormation.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="annee_formation_id" 
            required
            
            name="annee_formation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($anneeFormations as $anneeFormation)
                    <option value="{{ $anneeFormation->id }}"
                        {{ (isset($itemAffectationProjet) && $itemAffectationProjet->annee_formation_id == $anneeFormation->id) || (old('annee_formation_id>') == $anneeFormation->id) ? 'selected' : '' }}>
                        {{ $anneeFormation }}
                    </option>
                @endforeach
            </select>
            @error('annee_formation_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        

        <!--   RealisationProjet HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('affectationProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemAffectationProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgRealisationProjets::affectationProjet.singular") }} : {{$itemAffectationProjet}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
