{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('apprenant-form')
<form 
    class="crud-form custom-form context-state container" 
    id="apprenantForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('apprenants.bulkUpdate') : ($itemApprenant->id ? route('apprenants.update', $itemApprenant->id) : route('apprenants.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemApprenant->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($apprenant_ids))
        @foreach ($apprenant_ids as $id)
            <input type="hidden" name="apprenant_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
      <h5 class="debut-groupe-title text-info">{{ __('État Civil') }}</h5>
      <hr class="debut-groupe-hr">
    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="nom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="nom_arab" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom_arab" id="bulk_field_nom_arab" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="prenom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="prenom" id="bulk_field_prenom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="prenom_arab" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="prenom_arab" id="bulk_field_prenom_arab" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="cin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="cin" id="bulk_field_cin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="date_naissance" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_naissance" id="bulk_field_date_naissance" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_naissance">
            {{ ucfirst(__('PkgApprenants::apprenant.date_naissance')) }}
            
          </label>
                      <input
                name="date_naissance"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_naissance"
                placeholder="{{ __('PkgApprenants::apprenant.date_naissance') }}"
                value="{{ $itemApprenant ? $itemApprenant->date_naissance : old('date_naissance') }}">

          @error('date_naissance')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="sexe" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="sexe" id="bulk_field_sexe" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="nationalite_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nationalite_id" id="bulk_field_nationalite_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="lieu_naissance" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="lieu_naissance" id="bulk_field_lieu_naissance" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="niveaux_scolaire_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-9">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="niveaux_scolaire_id" id="bulk_field_niveaux_scolaire_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>


    </div>
  
    

    
      <h5 class="debut-groupe-title text-info">{{ __('Informations de Contact') }}</h5>
      <hr class="debut-groupe-hr">
    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="tele_num" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="tele_num" id="bulk_field_tele_num" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="user_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="user_id" id="bulk_field_user_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>


    </div>
  
    

    
      <h5 class="debut-groupe-title text-info">{{ __('Informations Académiques') }}</h5>
      <hr class="debut-groupe-hr">
    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="matricule" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="matricule" id="bulk_field_matricule" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="groupes" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="groupes" id="bulk_field_groupes" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="groupes">
            {{ ucfirst(__('PkgApprenants::groupe.plural')) }}
            
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="date_inscription" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_inscription" id="bulk_field_date_inscription" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_inscription">
            {{ ucfirst(__('PkgApprenants::apprenant.date_inscription')) }}
            
          </label>
                      <input
                name="date_inscription"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_inscription"
                placeholder="{{ __('PkgApprenants::apprenant.date_inscription') }}"
                value="{{ $itemApprenant ? $itemApprenant->date_inscription : old('date_inscription') }}">

          @error('date_inscription')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="actif" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="actif" id="bulk_field_actif" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="actif">
            {{ ucfirst(__('PkgApprenants::apprenant.actif')) }}
            
          </label>
                      <input type="hidden" name="actif" value="0">
            <input
                name="actif"
                type="checkbox"
                class="form-control"
                
                
                
                id="actif"
                value="1"
                {{ old('actif', $itemApprenant ? $itemApprenant->actif : 0) ? 'checked' : '' }}>
          @error('actif')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemApprenant" field="sousGroupes" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="sousGroupes" id="bulk_field_sousGroupes" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="sousGroupes">
            {{ ucfirst(__('PkgApprenants::sousGroupe.plural')) }}
            
          </label>
                      <select
                id="sousGroupes"
                name="sousGroupes[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($sousGroupes as $sousGroupe)
                    <option value="{{ $sousGroupe->id }}"
                        {{ (isset($itemApprenant) && $itemApprenant->sousGroupes && $itemApprenant->sousGroupes->contains('id', $sousGroupe->id)) || (is_array(old('sousGroupes')) && in_array($sousGroupe->id, old('sousGroupes'))) ? 'selected' : '' }}>
                        {{ $sousGroupe }}
                    </option>
                @endforeach
            </select>
          @error('sousGroupes')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


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
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprenants::apprenant.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprenants::apprenant.singular") }} : {{$itemApprenant}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
