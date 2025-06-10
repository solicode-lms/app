{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('ville-form')
<form 
    class="crud-form custom-form context-state container" 
    id="villeForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('villes.bulkUpdate') : ($itemVille->id ? route('villes.update', $itemVille->id) : route('villes.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemVille->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($ville_ids))
        @foreach ($ville_ids as $id)
            <input type="hidden" name="ville_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemVille" field="nom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgApprenants::ville.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgApprenants::ville.nom') }}"
                value="{{ $itemVille ? $itemVille->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('villes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemVille->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprenants::ville.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprenants::ville.singular") }} : {{$itemVille}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
