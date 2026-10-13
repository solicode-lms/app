{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('bordereauxOfficiel-form')
<form 
    class="crud-form custom-form context-state container" 
    id="bordereauxOfficielForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('bordereauxOfficiels.bulkUpdate') : ($itemBordereauxOfficiel->id ? route('bordereauxOfficiels.update', $itemBordereauxOfficiel->id) : route('bordereauxOfficiels.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemBordereauxOfficiel->id)
        <input type="hidden" name="id" value="{{ $itemBordereauxOfficiel->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($bordereauxOfficiel_ids))
        @foreach ($bordereauxOfficiel_ids as $id)
            <input type="hidden" name="bordereauxOfficiel_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemBordereauxOfficiel" field="module_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="module_id" 
              id="bulk_field_module_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
                        {{ (isset($itemBordereauxOfficiel) && $itemBordereauxOfficiel->module_id == $module->id) || (old('module_id>') == $module->id) ? 'selected' : '' }}>
                        {{ $module }}
                    </option>
                @endforeach
            </select>
          @error('module_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemBordereauxOfficiel" field="groupe_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="groupe_id" 
              id="bulk_field_groupe_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
                        {{ (isset($itemBordereauxOfficiel) && $itemBordereauxOfficiel->groupe_id == $groupe->id) || (old('groupe_id>') == $groupe->id) ? 'selected' : '' }}>
                        {{ $groupe }}
                    </option>
                @endforeach
            </select>
          @error('groupe_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemBordereauxOfficiel" field="annee_formation_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="annee_formation_id" 
              id="bulk_field_annee_formation_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
                        {{ (isset($itemBordereauxOfficiel) && $itemBordereauxOfficiel->annee_formation_id == $anneeFormation->id) || (old('annee_formation_id>') == $anneeFormation->id) ? 'selected' : '' }}>
                        {{ $anneeFormation }}
                    </option>
                @endforeach
            </select>
          @error('annee_formation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemBordereauxOfficiel" field="nb_apprenants" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="nb_apprenants" 
              id="bulk_field_nb_apprenants" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nb_apprenants">
            {{ ucfirst(__('PkgTableauBord::bordereauxOfficiel.nb_apprenants')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="nb_apprenants"
                type="number"
                class="form-control"
                required
                
                
                id="nb_apprenants"
                placeholder="{{ __('PkgTableauBord::bordereauxOfficiel.nb_apprenants') }}"
                value="{{ $itemBordereauxOfficiel ? $itemBordereauxOfficiel->nb_apprenants : old('nb_apprenants') }}">
          @error('nb_apprenants')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemBordereauxOfficiel" field="taux_conformite_calcul" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="taux_conformite_calcul" 
              id="bulk_field_taux_conformite_calcul" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="taux_conformite_calcul">
            {{ ucfirst(__('PkgTableauBord::bordereauxOfficiel.taux_conformite_calcul')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="taux_conformite_calcul"
        type="number"
        class="form-control"
        required
        
        
        id="taux_conformite_calcul"
        step="0.01"
        placeholder="{{ __('PkgTableauBord::bordereauxOfficiel.taux_conformite_calcul') }}"
        value="{{ $itemBordereauxOfficiel ? number_format($itemBordereauxOfficiel->taux_conformite_calcul, 2, '.', '') : old('taux_conformite_calcul') }}">
          @error('taux_conformite_calcul')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemBordereauxOfficiel" field="statut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="statut" 
              id="bulk_field_statut" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="statut">
            {{ ucfirst(__('PkgTableauBord::bordereauxOfficiel.statut')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="statut"
                type="input"
                class="form-control"
                required
                
                
                id="statut"
                placeholder="{{ __('PkgTableauBord::bordereauxOfficiel.statut') }}"
                value="{{ $itemBordereauxOfficiel ? $itemBordereauxOfficiel->statut : old('statut') }}">
          @error('statut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemBordereauxOfficiel" field="date_scellement" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="date_scellement" 
              id="bulk_field_date_scellement" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_scellement">
            {{ ucfirst(__('PkgTableauBord::bordereauxOfficiel.date_scellement')) }}
            
          </label>
                      <input
                name="date_scellement"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_scellement"
                placeholder="{{ __('PkgTableauBord::bordereauxOfficiel.date_scellement') }}"
                value="{{ $itemBordereauxOfficiel ? $itemBordereauxOfficiel->date_scellement : old('date_scellement') }}">

          @error('date_scellement')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemBordereauxOfficiel" field="hash_signature" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="hash_signature" 
              id="bulk_field_hash_signature" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="hash_signature">
            {{ ucfirst(__('PkgTableauBord::bordereauxOfficiel.hash_signature')) }}
            
          </label>
           <input
                name="hash_signature"
                type="input"
                class="form-control"
                
                
                
                id="hash_signature"
                placeholder="{{ __('PkgTableauBord::bordereauxOfficiel.hash_signature') }}"
                value="{{ $itemBordereauxOfficiel ? $itemBordereauxOfficiel->hash_signature : old('hash_signature') }}">
          @error('hash_signature')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemBordereauxOfficiel" field="genere_par_user_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="genere_par_user_id" 
              id="bulk_field_genere_par_user_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="genere_par_user_id">
            {{ ucfirst(__('PkgAutorisation::user.singular')) }}
            
          </label>
                      <select 
            id="genere_par_user_id" 
            
            
            
            name="genere_par_user_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($genereParUsers as $user)
                    <option value="{{ $user->id }}" 
                        {{ (isset($itemBordereauxOfficiel) && $itemBordereauxOfficiel->genere_par_user_id == $user->id) || (old('genere_par_user_id>') == $user->id) ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
          @error('genere_par_user_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('bordereauxOfficiels.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemBordereauxOfficiel->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgTableauBord::bordereauxOfficiel.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgTableauBord::bordereauxOfficiel.singular") }} : {{$itemBordereauxOfficiel}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
