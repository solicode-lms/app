{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('anneeFormation-form')
<form 
    class="crud-form custom-form context-state container" 
    id="anneeFormationForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('anneeFormations.bulkUpdate') : ($itemAnneeFormation->id ? route('anneeFormations.update', $itemAnneeFormation->id) : route('anneeFormations.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemAnneeFormation->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($anneeFormation_ids))
        @foreach ($anneeFormation_ids as $id)
            <input type="hidden" name="anneeFormation_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemAnneeFormation" field="titre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="titre" id="bulk_field_titre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre">
            {{ ucfirst(__('PkgFormation::anneeFormation.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgFormation::anneeFormation.titre') }}"
                value="{{ $itemAnneeFormation ? $itemAnneeFormation->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemAnneeFormation" field="date_debut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_debut" id="bulk_field_date_debut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgFormation::anneeFormation.date_debut')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="date_debut"
                placeholder="{{ __('PkgFormation::anneeFormation.date_debut') }}"
                value="{{ $itemAnneeFormation ? $itemAnneeFormation->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemAnneeFormation" field="date_fin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_fin" id="bulk_field_date_fin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgFormation::anneeFormation.date_fin')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="date_fin"
                placeholder="{{ __('PkgFormation::anneeFormation.date_fin') }}"
                value="{{ $itemAnneeFormation ? $itemAnneeFormation->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('anneeFormations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemAnneeFormation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgFormation::anneeFormation.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgFormation::anneeFormation.singular") }} : {{$itemAnneeFormation}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
