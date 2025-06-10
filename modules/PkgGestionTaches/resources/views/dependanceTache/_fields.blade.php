{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('dependanceTache-form')
<form 
    class="crud-form custom-form context-state container" 
    id="dependanceTacheForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('dependanceTaches.bulkUpdate') : ($itemDependanceTache->id ? route('dependanceTaches.update', $itemDependanceTache->id) : route('dependanceTaches.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemDependanceTache->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($dependanceTache_ids))
        @foreach ($dependanceTache_ids as $id)
            <input type="hidden" name="dependanceTache_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemDependanceTache" field="tache_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="tache_id" id="bulk_field_tache_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="tache_id">
            {{ ucfirst(__('PkgGestionTaches::tache.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="tache_id" 
            required
            
            
            name="tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($taches as $tache)
                    <option value="{{ $tache->id }}"
                        {{ (isset($itemDependanceTache) && $itemDependanceTache->tache_id == $tache->id) || (old('tache_id>') == $tache->id) ? 'selected' : '' }}>
                        {{ $tache }}
                    </option>
                @endforeach
            </select>
          @error('tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemDependanceTache" field="type_dependance_tache_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="type_dependance_tache_id" id="bulk_field_type_dependance_tache_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="type_dependance_tache_id">
            {{ ucfirst(__('PkgGestionTaches::typeDependanceTache.singular')) }}
            
          </label>
                      <select 
            id="type_dependance_tache_id" 
            
            
            
            name="type_dependance_tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($typeDependanceTaches as $typeDependanceTache)
                    <option value="{{ $typeDependanceTache->id }}"
                        {{ (isset($itemDependanceTache) && $itemDependanceTache->type_dependance_tache_id == $typeDependanceTache->id) || (old('type_dependance_tache_id>') == $typeDependanceTache->id) ? 'selected' : '' }}>
                        {{ $typeDependanceTache }}
                    </option>
                @endforeach
            </select>
          @error('type_dependance_tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemDependanceTache" field="tache_cible_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="tache_cible_id" id="bulk_field_tache_cible_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="tache_cible_id">
            {{ ucfirst(__('PkgGestionTaches::tache.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="tache_cible_id" 
            required
            
            
            name="tache_cible_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($taches as $tache)
                    <option value="{{ $tache->id }}"
                        {{ (isset($itemDependanceTache) && $itemDependanceTache->tache_cible_id == $tache->id) || (old('tache_cible_id>') == $tache->id) ? 'selected' : '' }}>
                        {{ $tache }}
                    </option>
                @endforeach
            </select>
          @error('tache_cible_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('dependanceTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemDependanceTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgGestionTaches::dependanceTache.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgGestionTaches::dependanceTache.singular") }} : {{$itemDependanceTache}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
