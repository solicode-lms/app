<div class="form-group col-12 col-md-6" id="container_seuil_live_coding">
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
        {{ ucfirst(__('PkgCreationTache::tache.seuil_live_coding')) }}
    </label>
    <div class="input-group">
        <input
            name="seuil_live_coding"
            type="number"
            class="form-control"
            min="1"
            max="100"
            id="seuil_live_coding"
            placeholder="Hérité de l'affectation (défaut : 50%)"
            value="{{ $entity && !is_null($entity->seuil_live_coding) ? $entity->seuil_live_coding : old('seuil_live_coding') }}">
        <div class="input-group-append">
            <span class="input-group-text">%</span>
        </div>
    </div>
    <small class="form-text text-muted">
        Laissez vide pour hériter du seuil défini sur l'affectation du groupe (ou 50% par défaut).
    </small>
    @error('seuil_live_coding')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<script>
(function() {
    function toggleSeuilLiveCodingVisibility() {
        const checkbox = document.getElementById('is_live_coding_task');
        const container = document.getElementById('container_seuil_live_coding');
        if (!container) return;

        if (checkbox && !checkbox.checked) {
            container.style.display = 'none';
        } else {
            container.style.display = '';
        }
    }

    const checkbox = document.getElementById('is_live_coding_task');
    if (checkbox) {
        checkbox.addEventListener('change', toggleSeuilLiveCodingVisibility);
    }
    toggleSeuilLiveCodingVisibility();
})();
</script>

