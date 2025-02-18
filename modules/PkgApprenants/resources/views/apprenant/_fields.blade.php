{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('apprenant-form')
<form class="crud-form custom-form context-state container" id="apprenantForm" action="{{ $itemApprenant->id ? route('apprenants.update', $itemApprenant->id) : route('apprenants.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemApprenant->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="nom">
                {{ ucfirst(__('PkgApprenants::apprenant.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                id="nom"
                placeholder="{{ __('PkgApprenants::apprenant.nom') }}"
                value="{{ $itemApprenant ? $itemApprenant->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="prenom">
                {{ ucfirst(__('PkgApprenants::apprenant.prenom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="prenom"
                type="input"
                class="form-control"
                required
                
                id="prenom"
                placeholder="{{ __('PkgApprenants::apprenant.prenom') }}"
                value="{{ $itemApprenant ? $itemApprenant->prenom : old('prenom') }}">
            @error('prenom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="prenom_arab">
                {{ ucfirst(__('PkgApprenants::apprenant.prenom_arab')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="prenom_arab"
                type="input"
                class="form-control"
                required
                
                id="prenom_arab"
                placeholder="{{ __('PkgApprenants::apprenant.prenom_arab') }}"
                value="{{ $itemApprenant ? $itemApprenant->prenom_arab : old('prenom_arab') }}">
            @error('prenom_arab')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="nom_arab">
                {{ ucfirst(__('PkgApprenants::apprenant.nom_arab')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom_arab"
                type="input"
                class="form-control"
                required
                
                id="nom_arab"
                placeholder="{{ __('PkgApprenants::apprenant.nom_arab') }}"
                value="{{ $itemApprenant ? $itemApprenant->nom_arab : old('nom_arab') }}">
            @error('nom_arab')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="sexe">
                {{ ucfirst(__('PkgApprenants::apprenant.sexe')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="sexe"
                type="input"
                class="form-control"
                required
                
                id="sexe"
                placeholder="{{ __('PkgApprenants::apprenant.sexe') }}"
                value="{{ $itemApprenant ? $itemApprenant->sexe : old('sexe') }}">
            @error('sexe')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="tele_num">
                {{ ucfirst(__('PkgApprenants::apprenant.tele_num')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="tele_num"
                type="input"
                class="form-control"
                required
                
                id="tele_num"
                placeholder="{{ __('PkgApprenants::apprenant.tele_num') }}"
                value="{{ $itemApprenant ? $itemApprenant->tele_num : old('tele_num') }}">
            @error('tele_num')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="date_naissance">
                {{ ucfirst(__('PkgApprenants::apprenant.date_naissance')) }}
                
            </label>
            <input
                name="date_naissance"
                type="date"
                class="form-control datetimepicker"
                
                
                id="date_naissance"
                placeholder="{{ __('PkgApprenants::apprenant.date_naissance') }}"
                value="{{ $itemApprenant ? $itemApprenant->date_naissance : old('date_naissance') }}">
            @error('date_naissance')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>





        
        <div class="form-group col-12 col-md-6">
            <label for="lieu_naissance">
                {{ ucfirst(__('PkgApprenants::apprenant.lieu_naissance')) }}
                
            </label>
            <input
                name="lieu_naissance"
                type="input"
                class="form-control"
                
                
                id="lieu_naissance"
                placeholder="{{ __('PkgApprenants::apprenant.lieu_naissance') }}"
                value="{{ $itemApprenant ? $itemApprenant->lieu_naissance : old('lieu_naissance') }}">
            @error('lieu_naissance')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="cin">
                {{ ucfirst(__('PkgApprenants::apprenant.cin')) }}
                
            </label>
            <input
                name="cin"
                type="input"
                class="form-control"
                
                
                id="cin"
                placeholder="{{ __('PkgApprenants::apprenant.cin') }}"
                value="{{ $itemApprenant ? $itemApprenant->cin : old('cin') }}">
            @error('cin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-12">
            <label for="adresse">
                {{ ucfirst(__('PkgApprenants::apprenant.adresse')) }}
                
            </label>
            <textarea rows="" cols=""
                name="adresse"
                class="form-control richText"
                
                
                id="adresse"
                placeholder="{{ __('PkgApprenants::apprenant.adresse') }}">
                {{ $itemApprenant ? $itemApprenant->adresse : old('adresse') }}
            </textarea>
            @error('adresse')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        
    <div class="form-group col-12 col-md-6">
            <label for="niveaux_scolaire_id">
                {{ ucfirst(__('PkgApprenants::niveauxScolaire.singular')) }}
                
            </label>
            <select 
            id="niveaux_scolaire_id" 
            
            
            name="niveaux_scolaire_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($niveauxScolaires as $niveauxScolaire)
                    <option value="{{ $niveauxScolaire->id }}"
                        {{ (isset($itemApprenant) && $itemApprenant->niveaux_scolaire_id == $niveauxScolaire->id) || (old('niveaux_scolaire_id>') == $niveauxScolaire->id) ? 'selected' : '' }}>
                        {{ $niveauxScolaire }}
                    </option>
                @endforeach
            </select>
            @error('niveaux_scolaire_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-6">
            <label for="matricule">
                {{ ucfirst(__('PkgApprenants::apprenant.matricule')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="matricule"
                type="input"
                class="form-control"
                required
                
                id="matricule"
                placeholder="{{ __('PkgApprenants::apprenant.matricule') }}"
                value="{{ $itemApprenant ? $itemApprenant->matricule : old('matricule') }}">
            @error('matricule')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        
    <div class="form-group col-12 col-md-6">
            <label for="nationalite_id">
                {{ ucfirst(__('PkgApprenants::nationalite.singular')) }}
                
            </label>
            <select 
            id="nationalite_id" 
            
            
            name="nationalite_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($nationalites as $nationalite)
                    <option value="{{ $nationalite->id }}"
                        {{ (isset($itemApprenant) && $itemApprenant->nationalite_id == $nationalite->id) || (old('nationalite_id>') == $nationalite->id) ? 'selected' : '' }}>
                        {{ $nationalite }}
                    </option>
                @endforeach
            </select>
            @error('nationalite_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-6">
            <label for="actif">
                {{ ucfirst(__('PkgApprenants::apprenant.actif')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input type="hidden" name="actif" value="0">
            <input
                name="actif"
                type="checkbox"
                class="form-control"
                required
                
                id="actif"
                value="1"
                {{ old('actif', $itemApprenant ? $itemApprenant->actif : 0) ? 'checked' : '' }}>
            @error('actif')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="date_inscription">
                {{ ucfirst(__('PkgApprenants::apprenant.date_inscription')) }}
                
            </label>
            <input
                name="date_inscription"
                type="date"
                class="form-control datetimepicker"
                
                
                id="date_inscription"
                placeholder="{{ __('PkgApprenants::apprenant.date_inscription') }}"
                value="{{ $itemApprenant ? $itemApprenant->date_inscription : old('date_inscription') }}">
            @error('date_inscription')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>





        
                <div class="form-group col-12 col-md-6">
            <label for="groupes">
                {{ ucfirst(__('PkgApprenants::Groupe.plural')) }}
            </label>
            <select
                id="groupes"
                name="groupes[]"
                class="form-control select2"
                
                multiple="multiple">
               
                @foreach ($groupes as $groupe)
                    <option value="{{ $groupe->id }}"
                        {{ (isset($itemApprenant) && $itemApprenant->groupes && $itemApprenant->groupes->contains('id', $groupe->id)) || (is_array(old('groupes')) && in_array($groupe->id, old('groupes'))) ? 'selected' : '' }}>
                        {{ $groupe }}
                    </option>
                @endforeach
            </select>
            @error('groupes')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>


        

        <!--   RealisationProjet HasMany --> 

        
        
    <div class="form-group col-12 col-md-6">
            <label for="user_id">
                {{ ucfirst(__('PkgAutorisation::user.singular')) }}
                
            </label>
            <select 
            id="user_id" 
            
            
            name="user_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ (isset($itemApprenant) && $itemApprenant->user_id == $user->id) || (old('user_id>') == $user->id) ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


    </div>

    <div class="card-footer">
        <a href="{{ route('apprenants.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemApprenant->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgApprenants::apprenant.singular") }} : {{$itemApprenant}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
