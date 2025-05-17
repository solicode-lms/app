{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrablesRealisation-form')
<form 
    class="crud-form custom-form context-state container" 
    id="livrablesRealisationForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('livrablesRealisations.bulkUpdate') : ($itemLivrablesRealisation->id ? route('livrablesRealisations.update', $itemLivrablesRealisation->id) : route('livrablesRealisations.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemLivrablesRealisation->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($livrablesRealisation_ids))
        @foreach ($livrablesRealisation_ids as $id)
            <input type="hidden" name="livrablesRealisation_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemLivrablesRealisation" field="livrable_id">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="livrable_id" id="bulk_field_livrable_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="livrable_id">
            {{ ucfirst(__('PkgCreationProjet::livrable.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="livrable_id" 
            required
            
            
            name="livrable_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($livrables as $livrable)
                    <option value="{{ $livrable->id }}"
                        {{ (isset($itemLivrablesRealisation) && $itemLivrablesRealisation->livrable_id == $livrable->id) || (old('livrable_id>') == $livrable->id) ? 'selected' : '' }}>
                        {{ $livrable }}
                    </option>
                @endforeach
            </select>
          @error('livrable_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemLivrablesRealisation" field="lien">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="lien" id="bulk_field_lien" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="lien">
            {{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.lien')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="lien"
                type="input"
                class="form-control"
                required
                
                
                id="lien"
                placeholder="{{ __('PkgRealisationProjets::livrablesRealisation.lien') }}"
                value="{{ $itemLivrablesRealisation ? $itemLivrablesRealisation->lien : old('lien') }}">
          @error('lien')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemLivrablesRealisation" field="titre">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="titre" id="bulk_field_titre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre">
            {{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgRealisationProjets::livrablesRealisation.titre') }}"
                value="{{ $itemLivrablesRealisation ? $itemLivrablesRealisation->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemLivrablesRealisation" field="description">

      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgRealisationProjets::livrablesRealisation.description') }}">{{ $itemLivrablesRealisation ? $itemLivrablesRealisation->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemLivrablesRealisation" field="realisation_projet_id">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_projet_id" id="bulk_field_realisation_projet_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_projet_id">
            {{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_projet_id" 
            required
            
            
            name="realisation_projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationProjets as $realisationProjet)
                    <option value="{{ $realisationProjet->id }}"
                        {{ (isset($itemLivrablesRealisation) && $itemLivrablesRealisation->realisation_projet_id == $realisationProjet->id) || (old('realisation_projet_id>') == $realisationProjet->id) ? 'selected' : '' }}>
                        {{ $realisationProjet }}
                    </option>
                @endforeach
            </select>
          @error('realisation_projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('livrablesRealisations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemLivrablesRealisation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgRealisationProjets::livrablesRealisation.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgRealisationProjets::livrablesRealisation.singular") }} : {{$itemLivrablesRealisation}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
