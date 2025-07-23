{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('chapitre-form')
<form 
    class="crud-form custom-form context-state container" 
    id="chapitreForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('chapitres.bulkUpdate') : ($itemChapitre->id ? route('chapitres.update', $itemChapitre->id) : route('chapitres.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemChapitre->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($chapitre_ids))
        @foreach ($chapitre_ids as $id)
            <input type="hidden" name="chapitre_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemChapitre" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgCompetences::chapitre.ordre')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                required
                
                
                id="ordre"
                placeholder="{{ __('PkgCompetences::chapitre.ordre') }}"
                value="{{ $itemChapitre ? $itemChapitre->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="code" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="code" id="bulk_field_code" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="code">
            {{ ucfirst(__('PkgCompetences::chapitre.code')) }}
            
          </label>
           <input
                name="code"
                type="input"
                class="form-control"
                
                
                
                id="code"
                placeholder="{{ __('PkgCompetences::chapitre.code') }}"
                value="{{ $itemChapitre ? $itemChapitre->code : old('code') }}">
          @error('code')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="nom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgCompetences::chapitre.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgCompetences::chapitre.nom') }}"
                value="{{ $itemChapitre ? $itemChapitre->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="lien" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="lien" id="bulk_field_lien" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="lien">
            {{ ucfirst(__('PkgCompetences::chapitre.lien')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="lien"
                type="input"
                class="form-control"
                required
                
                
                id="lien"
                placeholder="{{ __('PkgCompetences::chapitre.lien') }}"
                value="{{ $itemChapitre ? $itemChapitre->lien : old('lien') }}">
          @error('lien')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgCompetences::chapitre.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgCompetences::chapitre.description') }}">{{ $itemChapitre ? $itemChapitre->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="duree_en_heure" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="duree_en_heure" id="bulk_field_duree_en_heure" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="duree_en_heure">
            {{ ucfirst(__('PkgCompetences::chapitre.duree_en_heure')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="duree_en_heure"
        type="number"
        class="form-control"
        required
        
        
        id="duree_en_heure"
        step="0.01"
        placeholder="{{ __('PkgCompetences::chapitre.duree_en_heure') }}"
        value="{{ $itemChapitre ? number_format($itemChapitre->duree_en_heure, 2, '.', '') : old('duree_en_heure') }}">
          @error('duree_en_heure')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="isOfficiel" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="isOfficiel" id="bulk_field_isOfficiel" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="isOfficiel">
            {{ ucfirst(__('PkgCompetences::chapitre.isOfficiel')) }}
            
          </label>
                      <input type="hidden" name="isOfficiel" value="0">
            <input
                name="isOfficiel"
                type="checkbox"
                class="form-control"
                
                
                
                id="isOfficiel"
                value="1"
                {{ old('isOfficiel', $itemChapitre ? $itemChapitre->isOfficiel : 0) ? 'checked' : '' }}>
          @error('isOfficiel')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="unite_apprentissage_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="unite_apprentissage_id" id="bulk_field_unite_apprentissage_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="unite_apprentissage_id">
            {{ ucfirst(__('PkgCompetences::uniteApprentissage.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="unite_apprentissage_id" 
            required
            
            
            name="unite_apprentissage_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($uniteApprentissages as $uniteApprentissage)
                    <option value="{{ $uniteApprentissage->id }}"
                        {{ (isset($itemChapitre) && $itemChapitre->unite_apprentissage_id == $uniteApprentissage->id) || (old('unite_apprentissage_id>') == $uniteApprentissage->id) ? 'selected' : '' }}>
                        {{ $uniteApprentissage }}
                    </option>
                @endforeach
            </select>
          @error('unite_apprentissage_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemChapitre" field="formateur_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="formateur_id" id="bulk_field_formateur_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="formateur_id">
            {{ ucfirst(__('PkgFormation::formateur.singular')) }}
            
          </label>
                      <select 
            id="formateur_id" 
            
            
            
            name="formateur_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemChapitre) && $itemChapitre->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
          @error('formateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('chapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemChapitre->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgCompetences::chapitre.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCompetences::chapitre.singular") }} : {{$itemChapitre}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
