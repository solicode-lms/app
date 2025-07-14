{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('uniteApprentissage-form')
<form 
    class="crud-form custom-form context-state container" 
    id="uniteApprentissageForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('uniteApprentissages.bulkUpdate') : ($itemUniteApprentissage->id ? route('uniteApprentissages.update', $itemUniteApprentissage->id) : route('uniteApprentissages.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemUniteApprentissage->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($uniteApprentissage_ids))
        @foreach ($uniteApprentissage_ids as $id)
            <input type="hidden" name="uniteApprentissage_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemUniteApprentissage" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgCompetences::uniteApprentissage.ordre')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                required
                
                
                id="ordre"
                placeholder="{{ __('PkgCompetences::uniteApprentissage.ordre') }}"
                value="{{ $itemUniteApprentissage ? $itemUniteApprentissage->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemUniteApprentissage" field="nom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgCompetences::uniteApprentissage.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgCompetences::uniteApprentissage.nom') }}"
                value="{{ $itemUniteApprentissage ? $itemUniteApprentissage->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemUniteApprentissage" field="lien" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="lien" id="bulk_field_lien" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="lien">
            {{ ucfirst(__('PkgCompetences::uniteApprentissage.lien')) }}
            
          </label>
           <input
                name="lien"
                type="input"
                class="form-control"
                
                
                
                id="lien"
                placeholder="{{ __('PkgCompetences::uniteApprentissage.lien') }}"
                value="{{ $itemUniteApprentissage ? $itemUniteApprentissage->lien : old('lien') }}">
          @error('lien')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemUniteApprentissage" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgCompetences::uniteApprentissage.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgCompetences::uniteApprentissage.description') }}">{{ $itemUniteApprentissage ? $itemUniteApprentissage->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemUniteApprentissage" field="micro_competence_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="micro_competence_id" id="bulk_field_micro_competence_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="micro_competence_id">
            {{ ucfirst(__('PkgCompetences::microCompetence.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="micro_competence_id" 
            required
            
            
            name="micro_competence_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($microCompetences as $microCompetence)
                    <option value="{{ $microCompetence->id }}"
                        {{ (isset($itemUniteApprentissage) && $itemUniteApprentissage->micro_competence_id == $microCompetence->id) || (old('micro_competence_id>') == $microCompetence->id) ? 'selected' : '' }}>
                        {{ $microCompetence }}
                    </option>
                @endforeach
            </select>
          @error('micro_competence_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('uniteApprentissages.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemUniteApprentissage->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgCompetences::uniteApprentissage.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCompetences::uniteApprentissage.singular") }} : {{$itemUniteApprentissage}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
