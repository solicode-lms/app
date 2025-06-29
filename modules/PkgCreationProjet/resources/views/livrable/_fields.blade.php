{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrable-form')
<form 
    class="crud-form custom-form context-state container" 
    id="livrableForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('livrables.bulkUpdate') : ($itemLivrable->id ? route('livrables.update', $itemLivrable->id) : route('livrables.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemLivrable->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($livrable_ids))
        @foreach ($livrable_ids as $id)
            <input type="hidden" name="livrable_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemLivrable" field="nature_livrable_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nature_livrable_id" id="bulk_field_nature_livrable_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nature_livrable_id">
            {{ ucfirst(__('PkgCreationProjet::natureLivrable.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="nature_livrable_id" 
            required
            
            
            name="nature_livrable_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($natureLivrables as $natureLivrable)
                    <option value="{{ $natureLivrable->id }}"
                        {{ (isset($itemLivrable) && $itemLivrable->nature_livrable_id == $natureLivrable->id) || (old('nature_livrable_id>') == $natureLivrable->id) ? 'selected' : '' }}>
                        {{ $natureLivrable }}
                    </option>
                @endforeach
            </select>
          @error('nature_livrable_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemLivrable" field="titre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="titre" id="bulk_field_titre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre">
            {{ ucfirst(__('PkgCreationProjet::livrable.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgCreationProjet::livrable.titre') }}"
                value="{{ $itemLivrable ? $itemLivrable->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemLivrable" field="projet_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="projet_id" id="bulk_field_projet_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
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
                        {{ (isset($itemLivrable) && $itemLivrable->projet_id == $projet->id) || (old('projet_id>') == $projet->id) ? 'selected' : '' }}>
                        {{ $projet }}
                    </option>
                @endforeach
            </select>
          @error('projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemLivrable" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgCreationProjet::livrable.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgCreationProjet::livrable.description') }}">{{ $itemLivrable ? $itemLivrable->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemLivrable" field="is_affichable_seulement_par_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="is_affichable_seulement_par_formateur" id="bulk_field_is_affichable_seulement_par_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_affichable_seulement_par_formateur">
            {{ ucfirst(__('PkgCreationProjet::livrable.is_affichable_seulement_par_formateur')) }}
            
          </label>
                      <input type="hidden" name="is_affichable_seulement_par_formateur" value="0">
            <input
                name="is_affichable_seulement_par_formateur"
                type="checkbox"
                class="form-control"
                
                
                 data-store-key="Livrable_is_affichable_seulement_par_formateur" 
                id="is_affichable_seulement_par_formateur"
                value="1"
                {{ old('is_affichable_seulement_par_formateur', $itemLivrable ? $itemLivrable->is_affichable_seulement_par_formateur : 0) ? 'checked' : '' }}>
          @error('is_affichable_seulement_par_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemLivrable" field="taches" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="taches" id="bulk_field_taches" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="taches">
            {{ ucfirst(__('PkgGestionTaches::tache.plural')) }}
            
          </label>
                      <select
                id="taches"
                name="taches[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($taches as $tache)
                    <option value="{{ $tache->id }}"
                        {{ (isset($itemLivrable) && $itemLivrable->taches && $itemLivrable->taches->contains('id', $tache->id)) || (is_array(old('taches')) && in_array($tache->id, old('taches'))) ? 'selected' : '' }}>
                        {{ $tache }}
                    </option>
                @endforeach
            </select>
          @error('taches')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('livrables.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemLivrable->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgCreationProjet::livrable.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCreationProjet::livrable.singular") }} : {{$itemLivrable}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
