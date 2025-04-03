{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('dependanceTache-form')
<form class="crud-form custom-form context-state container" id="dependanceTacheForm" action="{{ $itemDependanceTache->id ? route('dependanceTaches.update', $itemDependanceTache->id) : route('dependanceTaches.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemDependanceTache->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
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
  


      <div class="form-group col-12 col-md-6">
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
  


      <div class="form-group col-12 col-md-6">
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
     window.modalTitle = '{{__("PkgGestionTaches::dependanceTache.singular") }} : {{$itemDependanceTache}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
