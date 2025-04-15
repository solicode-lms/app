{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('commentaireRealisationTache-form')
<form 
    class="crud-form custom-form context-state container" 
    id="commentaireRealisationTacheForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('commentaireRealisationTaches.bulkUpdate') : ($itemCommentaireRealisationTache->id ? route('commentaireRealisationTaches.update', $itemCommentaireRealisationTache->id) : route('commentaireRealisationTaches.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemCommentaireRealisationTache->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($commentaireRealisationTache_ids))
        @foreach ($commentaireRealisationTache_ids as $id)
            <input type="hidden" name="commentaireRealisationTache_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="commentaire" id="bulk_field_commentaire" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="commentaire">
            {{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.commentaire')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="commentaire"
                class="form-control richText"
                required
                
                
                id="commentaire"
                placeholder="{{ __('PkgGestionTaches::commentaireRealisationTache.commentaire') }}">{{ $itemCommentaireRealisationTache ? $itemCommentaireRealisationTache->commentaire : old('commentaire') }}</textarea>
          @error('commentaire')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="dateCommentaire" id="bulk_field_dateCommentaire" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dateCommentaire">
            {{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.dateCommentaire')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="dateCommentaire"
                type="date"
                class="form-control datetimepicker"
                required
                
                
                id="dateCommentaire"
                placeholder="{{ __('PkgGestionTaches::commentaireRealisationTache.dateCommentaire') }}"
                value="{{ $itemCommentaireRealisationTache ? $itemCommentaireRealisationTache->dateCommentaire : old('dateCommentaire') }}">

          @error('dateCommentaire')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_tache_id" id="bulk_field_realisation_tache_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_tache_id">
            {{ ucfirst(__('PkgGestionTaches::realisationTache.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_tache_id" 
            required
            
            
            name="realisation_tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationTaches as $realisationTache)
                    <option value="{{ $realisationTache->id }}"
                        {{ (isset($itemCommentaireRealisationTache) && $itemCommentaireRealisationTache->realisation_tache_id == $realisationTache->id) || (old('realisation_tache_id>') == $realisationTache->id) ? 'selected' : '' }}>
                        {{ $realisationTache }}
                    </option>
                @endforeach
            </select>
          @error('realisation_tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="formateur_id" id="bulk_field_formateur_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="formateur_id">
            {{ ucfirst(__('PkgFormation::formateur.singular')) }}
            
          </label>
                      <select 
            id="formateur_id" 
            
            
            
            name="formateur_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemCommentaireRealisationTache) && $itemCommentaireRealisationTache->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
          @error('formateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="apprenant_id" id="bulk_field_apprenant_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="apprenant_id">
            {{ ucfirst(__('PkgApprenants::apprenant.singular')) }}
            
          </label>
                      <select 
            id="apprenant_id" 
            
            
            
            name="apprenant_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($apprenants as $apprenant)
                    <option value="{{ $apprenant->id }}"
                        {{ (isset($itemCommentaireRealisationTache) && $itemCommentaireRealisationTache->apprenant_id == $apprenant->id) || (old('apprenant_id>') == $apprenant->id) ? 'selected' : '' }}>
                        {{ $apprenant }}
                    </option>
                @endforeach
            </select>
          @error('apprenant_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('commentaireRealisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemCommentaireRealisationTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgGestionTaches::commentaireRealisationTache.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgGestionTaches::commentaireRealisationTache.singular") }} : {{$itemCommentaireRealisationTache}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
