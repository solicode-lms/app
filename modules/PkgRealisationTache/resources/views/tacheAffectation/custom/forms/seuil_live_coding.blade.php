<div class="form-group col-12 col-md-6">
    @if ($bulkEdit)
    <div class="bulk-check">
        <input 
            type="checkbox" 
            class="check-input" 
            name="fields_modifiables[]" 
            value="seuil_live_coding" 
            id="bulk_field_seuil_live_coding" 
            title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
    </div>
    @endif
    <label for="seuil_live_coding">
        {{ ucfirst(__('PkgRealisationTache::tacheAffectation.seuil_live_coding')) }}
    </label>
    <div class="input-group">
        <input
            name="seuil_live_coding"
            type="number"
            class="form-control"
            min="1"
            max="100"
            id="seuil_live_coding"
            placeholder="{{ __('PkgRealisationTache::tacheAffectation.seuil_live_coding') }}"
            value="{{ $entity && !is_null($entity->seuil_live_coding) ? $entity->seuil_live_coding : old('seuil_live_coding', 50) }}">
        <div class="input-group-append">
            <span class="input-group-text">%</span>
        </div>
    </div>
    <small class="form-text text-muted mt-1">
        Seuil effectif pour déclencher le Live coding sur cette tâche pour cette affectation.
    </small>
    @error('seuil_live_coding')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

