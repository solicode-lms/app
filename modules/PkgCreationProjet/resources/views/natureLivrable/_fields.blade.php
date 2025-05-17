{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('natureLivrable-form')
<form 
    class="crud-form custom-form context-state container" 
    id="natureLivrableForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('natureLivrables.bulkUpdate') : ($itemNatureLivrable->id ? route('natureLivrables.update', $itemNatureLivrable->id) : route('natureLivrables.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemNatureLivrable->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($natureLivrable_ids))
        @foreach ($natureLivrable_ids as $id)
            <input type="hidden" name="natureLivrable_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemNatureLivrable" field="nom">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgCreationProjet::natureLivrable.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgCreationProjet::natureLivrable.nom') }}"
                value="{{ $itemNatureLivrable ? $itemNatureLivrable->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemNatureLivrable" field="description">

      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgCreationProjet::natureLivrable.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgCreationProjet::natureLivrable.description') }}">{{ $itemNatureLivrable ? $itemNatureLivrable->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('natureLivrables.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNatureLivrable->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgCreationProjet::natureLivrable.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCreationProjet::natureLivrable.singular") }} : {{$itemNatureLivrable}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
