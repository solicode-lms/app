{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('tache-form')
<form 
    class="crud-form custom-form context-state container" 
    id="tacheForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('taches.bulkUpdate') : ($itemTache->id ? route('taches.update', $itemTache->id) : route('taches.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemTache->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($tache_ids))
        @foreach ($tache_ids as $id)
            <input type="hidden" name="tache_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-2">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgGestionTaches::tache.ordre')) }}
            
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                
                
                
                id="ordre"
                placeholder="{{ __('PkgGestionTaches::tache.ordre') }}"
                value="{{ $itemTache ? $itemTache->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-8">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="titre" id="bulk_field_titre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre">
            {{ ucfirst(__('PkgGestionTaches::tache.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgGestionTaches::tache.titre') }}"
                value="{{ $itemTache ? $itemTache->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-3">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="priorite_tache_id" id="bulk_field_priorite_tache_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="priorite_tache_id">
            {{ ucfirst(__('PkgGestionTaches::tache.priorite_tache_id')) }}
            
          </label>
                      <select 
            id="priorite_tache_id" 
            
            
            
            name="priorite_tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($prioriteTaches as $prioriteTache)
                    <option value="{{ $prioriteTache->id }}"
                        {{ (isset($itemTache) && $itemTache->priorite_tache_id == $prioriteTache->id) || (old('priorite_tache_id>') == $prioriteTache->id) ? 'selected' : '' }}>
                        {{ $prioriteTache }}
                    </option>
                @endforeach
            </select>
          @error('priorite_tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-4">
          @if (!empty($bulkEdit))
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
                        {{ (isset($itemTache) && $itemTache->projet_id == $projet->id) || (old('projet_id>') == $projet->id) ? 'selected' : '' }}>
                        {{ $projet }}
                    </option>
                @endforeach
            </select>
          @error('projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgGestionTaches::tache.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgGestionTaches::tache.description') }}">{{ $itemTache ? $itemTache->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="dateDebut" id="bulk_field_dateDebut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dateDebut">
            {{ ucfirst(__('PkgGestionTaches::tache.dateDebut')) }}
            
          </label>
                      <input
                name="dateDebut"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="dateDebut"
                placeholder="{{ __('PkgGestionTaches::tache.dateDebut') }}"
                value="{{ $itemTache ? $itemTache->dateDebut : old('dateDebut') }}">

          @error('dateDebut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="dateFin" id="bulk_field_dateFin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dateFin">
            {{ ucfirst(__('PkgGestionTaches::tache.dateFin')) }}
            
          </label>
                      <input
                name="dateFin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="dateFin"
                placeholder="{{ __('PkgGestionTaches::tache.dateFin') }}"
                value="{{ $itemTache ? $itemTache->dateFin : old('dateFin') }}">

          @error('dateFin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   DependanceTache HasMany --> 


<!--   DependanceTache HasMany --> 


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="livrables" id="bulk_field_livrables" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="livrables">
            {{ ucfirst(__('PkgCreationProjet::livrable.plural')) }}
            
          </label>
                      <select
                id="livrables"
                name="livrables[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($livrables as $livrable)
                    <option value="{{ $livrable->id }}"
                        {{ (isset($itemTache) && $itemTache->livrables && $itemTache->livrables->contains('id', $livrable->id)) || (is_array(old('livrables')) && in_array($livrable->id, old('livrables'))) ? 'selected' : '' }}>
                        {{ $livrable }}
                    </option>
                @endforeach
            </select>
          @error('livrables')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   RealisationTache HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('taches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgGestionTaches::tache.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgGestionTaches::tache.singular") }} : {{$itemTache}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
