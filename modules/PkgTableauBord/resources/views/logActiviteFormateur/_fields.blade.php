{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('logActiviteFormateur-form')
<form 
    class="crud-form custom-form context-state container" 
    id="logActiviteFormateurForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('logActiviteFormateurs.bulkUpdate') : ($itemLogActiviteFormateur->id ? route('logActiviteFormateurs.update', $itemLogActiviteFormateur->id) : route('logActiviteFormateurs.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemLogActiviteFormateur->id)
        <input type="hidden" name="id" value="{{ $itemLogActiviteFormateur->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($logActiviteFormateur_ids))
        @foreach ($logActiviteFormateur_ids as $id)
            <input type="hidden" name="logActiviteFormateur_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemLogActiviteFormateur" field="formateur_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="formateur_id" 
              id="bulk_field_formateur_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
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
                        {{ (isset($itemLogActiviteFormateur) && $itemLogActiviteFormateur->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
          @error('formateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemLogActiviteFormateur" field="type_activite" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="type_activite" 
              id="bulk_field_type_activite" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="type_activite">
            {{ ucfirst(__('PkgTableauBord::logActiviteFormateur.type_activite')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="type_activite"
                type="input"
                class="form-control"
                required
                
                
                id="type_activite"
                placeholder="{{ __('PkgTableauBord::logActiviteFormateur.type_activite') }}"
                value="{{ $itemLogActiviteFormateur ? $itemLogActiviteFormateur->type_activite : old('type_activite') }}">
          @error('type_activite')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemLogActiviteFormateur" field="duree_secondes" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="duree_secondes" 
              id="bulk_field_duree_secondes" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="duree_secondes">
            {{ ucfirst(__('PkgTableauBord::logActiviteFormateur.duree_secondes')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="duree_secondes"
                type="number"
                class="form-control"
                required
                
                
                id="duree_secondes"
                placeholder="{{ __('PkgTableauBord::logActiviteFormateur.duree_secondes') }}"
                value="{{ $itemLogActiviteFormateur ? $itemLogActiviteFormateur->duree_secondes : old('duree_secondes') }}">
          @error('duree_secondes')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemLogActiviteFormateur" field="date_activite" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="date_activite" 
              id="bulk_field_date_activite" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_activite">
            {{ ucfirst(__('PkgTableauBord::logActiviteFormateur.date_activite')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_activite"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="date_activite"
                placeholder="{{ __('PkgTableauBord::logActiviteFormateur.date_activite') }}"
                value="{{ $itemLogActiviteFormateur ? $itemLogActiviteFormateur->date_activite : old('date_activite') }}">

          @error('date_activite')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemLogActiviteFormateur" field="semaine_annee" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="semaine_annee" 
              id="bulk_field_semaine_annee" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="semaine_annee">
            {{ ucfirst(__('PkgTableauBord::logActiviteFormateur.semaine_annee')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="semaine_annee"
                type="input"
                class="form-control"
                required
                
                
                id="semaine_annee"
                placeholder="{{ __('PkgTableauBord::logActiviteFormateur.semaine_annee') }}"
                value="{{ $itemLogActiviteFormateur ? $itemLogActiviteFormateur->semaine_annee : old('semaine_annee') }}">
          @error('semaine_annee')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('logActiviteFormateurs.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemLogActiviteFormateur->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgTableauBord::logActiviteFormateur.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgTableauBord::logActiviteFormateur.singular") }} : {{$itemLogActiviteFormateur}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
