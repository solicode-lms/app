{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('groupe-form')
<form 
    class="crud-form custom-form context-state container" 
    id="groupeForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('groupes.bulkUpdate') : ($itemGroupe->id ? route('groupes.update', $itemGroupe->id) : route('groupes.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemGroupe->id)
        <input type="hidden" name="id" value="{{ $itemGroupe->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($groupe_ids))
        @foreach ($groupe_ids as $id)
            <input type="hidden" name="groupe_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemGroupe" field="code" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="code" 
              id="bulk_field_code" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemGroupe" field="nom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="nom" 
              id="bulk_field_nom" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemGroupe" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="description" 
              id="bulk_field_description" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgApprenants::groupe.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgApprenants::groupe.description') }}">{{ $itemGroupe ? $itemGroupe->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemGroupe" field="filiere_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="filiere_id" 
              id="bulk_field_filiere_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemGroupe" field="annee_formation_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemGroupe) && $itemGroupe->annee_formation_id == $anneeFormation->id) || (old('annee_formation_id>') == $anneeFormation->id) ? 'selected' : '' }}>
                        {{ $anneeFormation }}
                    </option>
                @endforeach
            </select>
          @error('annee_formation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemGroupe" field="apprenants" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="apprenants" 
              id="bulk_field_apprenants" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="apprenants">
            {{ ucfirst(__('PkgApprenants::apprenant.plural')) }}
            
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemGroupe" field="formateurs" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="formateurs" 
              id="bulk_field_formateurs" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="formateurs">
            {{ ucfirst(__('PkgFormation::formateur.plural')) }}
            
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
  
</x-form-field>


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
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprenants::groupe.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprenants::groupe.singular") }} : {{$itemGroupe}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
