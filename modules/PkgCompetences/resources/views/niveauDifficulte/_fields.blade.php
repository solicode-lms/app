{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauDifficulte-form')
<form 
    class="crud-form custom-form context-state container" 
    id="niveauDifficulteForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('niveauDifficultes.bulkUpdate') : ($itemNiveauDifficulte->id ? route('niveauDifficultes.update', $itemNiveauDifficulte->id) : route('niveauDifficultes.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemNiveauDifficulte->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($niveauDifficulte_ids))
        @foreach ($niveauDifficulte_ids as $id)
            <input type="hidden" name="niveauDifficulte_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemNiveauDifficulte" field="nom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgCompetences::niveauDifficulte.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgCompetences::niveauDifficulte.nom') }}"
                value="{{ $itemNiveauDifficulte ? $itemNiveauDifficulte->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemNiveauDifficulte" field="noteMin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="noteMin" id="bulk_field_noteMin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="noteMin">
            {{ ucfirst(__('PkgCompetences::niveauDifficulte.noteMin')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="noteMin"
        type="number"
        class="form-control"
        required
        
        
        id="noteMin"
        step="0.01"
        placeholder="{{ __('PkgCompetences::niveauDifficulte.noteMin') }}"
        value="{{ $itemNiveauDifficulte ? number_format($itemNiveauDifficulte->noteMin, 2, '.', '') : old('noteMin') }}">
          @error('noteMin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemNiveauDifficulte" field="noteMax" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="noteMax" id="bulk_field_noteMax" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="noteMax">
            {{ ucfirst(__('PkgCompetences::niveauDifficulte.noteMax')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="noteMax"
        type="number"
        class="form-control"
        required
        
        
        id="noteMax"
        step="0.01"
        placeholder="{{ __('PkgCompetences::niveauDifficulte.noteMax') }}"
        value="{{ $itemNiveauDifficulte ? number_format($itemNiveauDifficulte->noteMax, 2, '.', '') : old('noteMax') }}">
          @error('noteMax')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemNiveauDifficulte" field="formateur_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="formateur_id" id="bulk_field_formateur_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="formateur_id">
            {{ ucfirst(__('PkgFormation::formateur.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="formateur_id" 
            required
            
            
            name="formateur_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemNiveauDifficulte) && $itemNiveauDifficulte->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
          @error('formateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemNiveauDifficulte" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgCompetences::niveauDifficulte.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgCompetences::niveauDifficulte.description') }}">{{ $itemNiveauDifficulte ? $itemNiveauDifficulte->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('niveauDifficultes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNiveauDifficulte->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgCompetences::niveauDifficulte.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCompetences::niveauDifficulte.singular") }} : {{$itemNiveauDifficulte}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
