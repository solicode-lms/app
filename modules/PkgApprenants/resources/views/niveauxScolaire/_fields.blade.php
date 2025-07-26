{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauxScolaire-form')
<form 
    class="crud-form custom-form context-state container" 
    id="niveauxScolaireForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('niveauxScolaires.bulkUpdate') : ($itemNiveauxScolaire->id ? route('niveauxScolaires.update', $itemNiveauxScolaire->id) : route('niveauxScolaires.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemNiveauxScolaire->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($niveauxScolaire_ids))
        @foreach ($niveauxScolaire_ids as $id)
            <input type="hidden" name="niveauxScolaire_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemNiveauxScolaire" field="code" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="code" id="bulk_field_code" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="code">
            {{ ucfirst(__('PkgApprenants::niveauxScolaire.code')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="code"
                type="input"
                class="form-control"
                required
                
                
                id="code"
                placeholder="{{ __('PkgApprenants::niveauxScolaire.code') }}"
                value="{{ $itemNiveauxScolaire ? $itemNiveauxScolaire->code : old('code') }}">
          @error('code')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemNiveauxScolaire" field="nom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgApprenants::niveauxScolaire.nom')) }}
            
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                
                
                
                id="nom"
                placeholder="{{ __('PkgApprenants::niveauxScolaire.nom') }}"
                value="{{ $itemNiveauxScolaire ? $itemNiveauxScolaire->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemNiveauxScolaire" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgApprenants::niveauxScolaire.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgApprenants::niveauxScolaire.description') }}">{{ $itemNiveauxScolaire ? $itemNiveauxScolaire->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('niveauxScolaires.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNiveauxScolaire->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprenants::niveauxScolaire.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprenants::niveauxScolaire.singular") }} : {{$itemNiveauxScolaire}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
