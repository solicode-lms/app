{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('typeDependanceTache-form')
<form 
    class="crud-form custom-form context-state container" 
    id="typeDependanceTacheForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('typeDependanceTaches.bulkUpdate') : ($itemTypeDependanceTache->id ? route('typeDependanceTaches.update', $itemTypeDependanceTache->id) : route('typeDependanceTaches.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemTypeDependanceTache->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($typeDependanceTache_ids))
        @foreach ($typeDependanceTache_ids as $id)
            <input type="hidden" name="typeDependanceTache_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemTypeDependanceTache" field="titre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="titre" id="bulk_field_titre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre">
            {{ ucfirst(__('PkgGestionTaches::typeDependanceTache.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgGestionTaches::typeDependanceTache.titre') }}"
                value="{{ $itemTypeDependanceTache ? $itemTypeDependanceTache->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemTypeDependanceTache" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgGestionTaches::typeDependanceTache.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgGestionTaches::typeDependanceTache.description') }}">{{ $itemTypeDependanceTache ? $itemTypeDependanceTache->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('typeDependanceTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemTypeDependanceTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgGestionTaches::typeDependanceTache.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgGestionTaches::typeDependanceTache.singular") }} : {{$itemTypeDependanceTache}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
