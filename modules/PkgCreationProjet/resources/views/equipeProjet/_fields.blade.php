{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('equipeProjet-form')
<form 
    class="crud-form custom-form context-state container" 
    id="equipeProjetForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('equipeProjets.bulkUpdate') : ($itemEquipeProjet->id ? route('equipeProjets.update', $itemEquipeProjet->id) : route('equipeProjets.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEquipeProjet->id)
        <input type="hidden" name="id" value="{{ $itemEquipeProjet->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($equipeProjet_ids))
        @foreach ($equipeProjet_ids as $id)
            <input type="hidden" name="equipeProjet_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemEquipeProjet" field="projet_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="projet_id" 
              id="bulk_field_projet_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="projet_id">
            {{ ucfirst(__('PkgCreationProjet::projet.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="projet_id" 
            required
            
            
            name="projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($projets as $projet)
                    <option value="{{ $projet->id }}"
                        {{ (isset($itemEquipeProjet) && $itemEquipeProjet->projet_id == $projet->id) || (old('projet_id>') == $projet->id) ? 'selected' : '' }}>
                        {{ $projet }}
                    </option>
                @endforeach
            </select>
          @error('projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEquipeProjet" field="nom" :bulkEdit="$bulkEdit">

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
            {{ ucfirst(__('PkgCreationProjet::equipeProjet.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgCreationProjet::equipeProjet.nom') }}"
                value="{{ $itemEquipeProjet ? $itemEquipeProjet->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEquipeProjet" field="apprenants" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemEquipeProjet) && $itemEquipeProjet->apprenants && $itemEquipeProjet->apprenants->contains('id', $apprenant->id)) || (is_array(old('apprenants')) && in_array($apprenant->id, old('apprenants'))) ? 'selected' : '' }}>
                        {{ $apprenant }}
                    </option>
                @endforeach
            </select>
          @error('apprenants')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('equipeProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEquipeProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgCreationProjet::equipeProjet.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCreationProjet::equipeProjet.singular") }} : {{$itemEquipeProjet}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
