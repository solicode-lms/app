{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrablesRealisation-form')
<form class="crud-form custom-form context-state container" id="livrablesRealisationForm" action="{{ $itemLivrablesRealisation->id ? route('livrablesRealisations.update', $itemLivrablesRealisation->id) : route('livrablesRealisations.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemLivrablesRealisation->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
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
  


      <div class="form-group col-12 col-md-6">
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
  


      <div class="form-group col-12 col-md-6">
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
  


      <div class="form-group col-12 col-md-12">
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
  


      <div class="form-group col-12 col-md-6">
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
     window.modalTitle = '{{__("PkgRealisationProjets::livrablesRealisation.singular") }} : {{$itemLivrablesRealisation}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
