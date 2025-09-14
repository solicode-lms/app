{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sessionFormation-form')
<form 
    class="crud-form custom-form context-state container" 
    id="sessionFormationForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('sessionFormations.bulkUpdate') : ($itemSessionFormation->id ? route('sessionFormations.update', $itemSessionFormation->id) : route('sessionFormations.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemSessionFormation->id)
        <input type="hidden" name="id" value="{{ $itemSessionFormation->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($sessionFormation_ids))
        @foreach ($sessionFormation_ids as $id)
            <input type="hidden" name="sessionFormation_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-2">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgSessions::sessionFormation.ordre')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                required
                
                
                id="ordre"
                placeholder="{{ __('PkgSessions::sessionFormation.ordre') }}"
                value="{{ $itemSessionFormation ? $itemSessionFormation->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="titre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-10">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="titre" id="bulk_field_titre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre">
            {{ ucfirst(__('PkgSessions::sessionFormation.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgSessions::sessionFormation.titre') }}"
                value="{{ $itemSessionFormation ? $itemSessionFormation->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="code" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="code" id="bulk_field_code" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="code">
            {{ ucfirst(__('PkgSessions::sessionFormation.code')) }}
            
          </label>
           <input
                name="code"
                type="input"
                class="form-control"
                
                
                
                id="code"
                placeholder="{{ __('PkgSessions::sessionFormation.code') }}"
                value="{{ $itemSessionFormation ? $itemSessionFormation->code : old('code') }}">
          @error('code')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="thematique" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="thematique" id="bulk_field_thematique" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="thematique">
            {{ ucfirst(__('PkgSessions::sessionFormation.thematique')) }}
            
          </label>
           <input
                name="thematique"
                type="input"
                class="form-control"
                
                
                
                id="thematique"
                placeholder="{{ __('PkgSessions::sessionFormation.thematique') }}"
                value="{{ $itemSessionFormation ? $itemSessionFormation->thematique : old('thematique') }}">
          @error('thematique')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="filiere_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="filiere_id" id="bulk_field_filiere_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
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
                        {{ (isset($itemSessionFormation) && $itemSessionFormation->filiere_id == $filiere->id) || (old('filiere_id>') == $filiere->id) ? 'selected' : '' }}>
                        {{ $filiere }}
                    </option>
                @endforeach
            </select>
          @error('filiere_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="objectifs_pedagogique" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="objectifs_pedagogique" id="bulk_field_objectifs_pedagogique" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="objectifs_pedagogique">
            {{ ucfirst(__('PkgSessions::sessionFormation.objectifs_pedagogique')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="objectifs_pedagogique"
                class="form-control richText"
                required
                
                
                id="objectifs_pedagogique"
                placeholder="{{ __('PkgSessions::sessionFormation.objectifs_pedagogique') }}">{{ $itemSessionFormation ? $itemSessionFormation->objectifs_pedagogique : old('objectifs_pedagogique') }}</textarea>
          @error('objectifs_pedagogique')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="titre_prototype" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="titre_prototype" id="bulk_field_titre_prototype" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre_prototype">
            {{ ucfirst(__('PkgSessions::sessionFormation.titre_prototype')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre_prototype"
                type="input"
                class="form-control"
                required
                
                
                id="titre_prototype"
                placeholder="{{ __('PkgSessions::sessionFormation.titre_prototype') }}"
                value="{{ $itemSessionFormation ? $itemSessionFormation->titre_prototype : old('titre_prototype') }}">
          @error('titre_prototype')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="description_prototype" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="description_prototype" id="bulk_field_description_prototype" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description_prototype">
            {{ ucfirst(__('PkgSessions::sessionFormation.description_prototype')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="description_prototype"
                class="form-control richText"
                required
                
                
                id="description_prototype"
                placeholder="{{ __('PkgSessions::sessionFormation.description_prototype') }}">{{ $itemSessionFormation ? $itemSessionFormation->description_prototype : old('description_prototype') }}</textarea>
          @error('description_prototype')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="contraintes_prototype" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="contraintes_prototype" id="bulk_field_contraintes_prototype" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="contraintes_prototype">
            {{ ucfirst(__('PkgSessions::sessionFormation.contraintes_prototype')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="contraintes_prototype"
                class="form-control richText"
                
                
                
                id="contraintes_prototype"
                placeholder="{{ __('PkgSessions::sessionFormation.contraintes_prototype') }}">{{ $itemSessionFormation ? $itemSessionFormation->contraintes_prototype : old('contraintes_prototype') }}</textarea>
          @error('contraintes_prototype')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="titre_projet" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="titre_projet" id="bulk_field_titre_projet" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre_projet">
            {{ ucfirst(__('PkgSessions::sessionFormation.titre_projet')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre_projet"
                type="input"
                class="form-control"
                required
                
                
                id="titre_projet"
                placeholder="{{ __('PkgSessions::sessionFormation.titre_projet') }}"
                value="{{ $itemSessionFormation ? $itemSessionFormation->titre_projet : old('titre_projet') }}">
          @error('titre_projet')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="description_projet" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="description_projet" id="bulk_field_description_projet" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description_projet">
            {{ ucfirst(__('PkgSessions::sessionFormation.description_projet')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="description_projet"
                class="form-control richText"
                required
                
                
                id="description_projet"
                placeholder="{{ __('PkgSessions::sessionFormation.description_projet') }}">{{ $itemSessionFormation ? $itemSessionFormation->description_projet : old('description_projet') }}</textarea>
          @error('description_projet')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="contraintes_projet" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="contraintes_projet" id="bulk_field_contraintes_projet" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="contraintes_projet">
            {{ ucfirst(__('PkgSessions::sessionFormation.contraintes_projet')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="contraintes_projet"
                class="form-control richText"
                
                
                
                id="contraintes_projet"
                placeholder="{{ __('PkgSessions::sessionFormation.contraintes_projet') }}">{{ $itemSessionFormation ? $itemSessionFormation->contraintes_projet : old('contraintes_projet') }}</textarea>
          @error('contraintes_projet')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="remarques" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="remarques" id="bulk_field_remarques" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="remarques">
            {{ ucfirst(__('PkgSessions::sessionFormation.remarques')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="remarques"
                class="form-control richText"
                
                
                
                id="remarques"
                placeholder="{{ __('PkgSessions::sessionFormation.remarques') }}">{{ $itemSessionFormation ? $itemSessionFormation->remarques : old('remarques') }}</textarea>
          @error('remarques')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="date_debut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="date_debut" id="bulk_field_date_debut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgSessions::sessionFormation.date_debut')) }}
            
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_debut"
                placeholder="{{ __('PkgSessions::sessionFormation.date_debut') }}"
                value="{{ $itemSessionFormation ? $itemSessionFormation->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="date_fin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="date_fin" id="bulk_field_date_fin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgSessions::sessionFormation.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgSessions::sessionFormation.date_fin') }}"
                value="{{ $itemSessionFormation ? $itemSessionFormation->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="jour_feries_vacances" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="jour_feries_vacances" id="bulk_field_jour_feries_vacances" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="jour_feries_vacances">
            {{ ucfirst(__('PkgSessions::sessionFormation.jour_feries_vacances')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="jour_feries_vacances"
                class="form-control richText"
                
                
                
                id="jour_feries_vacances"
                placeholder="{{ __('PkgSessions::sessionFormation.jour_feries_vacances') }}">{{ $itemSessionFormation ? $itemSessionFormation->jour_feries_vacances : old('jour_feries_vacances') }}</textarea>
          @error('jour_feries_vacances')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSessionFormation" field="annee_formation_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="annee_formation_id" id="bulk_field_annee_formation_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
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
                        {{ (isset($itemSessionFormation) && $itemSessionFormation->annee_formation_id == $anneeFormation->id) || (old('annee_formation_id>') == $anneeFormation->id) ? 'selected' : '' }}>
                        {{ $anneeFormation }}
                    </option>
                @endforeach
            </select>
          @error('annee_formation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('sessionFormations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSessionFormation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgSessions::sessionFormation.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgSessions::sessionFormation.singular") }} : {{$itemSessionFormation}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
