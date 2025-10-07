 @php $canEditremarques_formateur = $bulkEdit ? Auth::user()->hasAnyRole(explode(',', 'formateur')) : (empty($entity->id) || Auth::user()->hasAnyRole(explode(',', 'formateur')) ); @endphp


@if($canEditremarques_formateur)
<!-- Modal d'information -->
<div class="modal fade" id="infoRevisionModal" tabindex="-1" role="dialog" aria-labelledby="infoRevisionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="infoRevisionModalLabel">
          <i class="fas fa-info-circle"></i> Passage automatique en « Révision nécessaire »
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>
          Lorsque vous <strong>modifiez ou ajoutez une remarque</strong>,
          la tâche passe <strong>automatiquement</strong> à l’état 
          <span class="badge bg-warning text-dark">Révision nécessaire</span>,
          sauf si vous avez <strong>changé manuellement l’état</strong>.
        </p>
        <p>
          ⚠️ Les tâches déjà dans un état final 
          (<span class="badge bg-success">Approuvée</span> ou 
          <span class="badge bg-secondary">Non validée</span>)
          ne sont <strong>pas modifiées automatiquement</strong>.
        </p>
      </div>
    </div>
  </div>
</div>
@endif

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
                {{ $canEditremarques_formateur ? '' : 'disabled' }}
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="remarques_formateur" 
              id="bulk_field_remarques_formateur" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="remarques_formateur">
            {{ ucfirst(__('PkgRealisationTache::realisationTache.remarques_formateur')) }}
            
            @if($canEditremarques_formateur)
            <a href="#" data-toggle="modal" data-target="#infoRevisionModal" class="ml-1 text-info">
            <i class="fas fa-info-circle"></i>
            </a>
            @endif

          </label>
                      <textarea rows="" cols=""
                name="remarques_formateur"
                class="form-control richText"
                {{ $canEditremarques_formateur ? '' : 'disabled' }}
                
                
                
                id="remarques_formateur"
                placeholder="{{ __('PkgRealisationTache::realisationTache.remarques_formateur') }}">{{ $entity ? $entity->remarques_formateur : old('remarques_formateur') }}</textarea>
          @error('remarques_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  