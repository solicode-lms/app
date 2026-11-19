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
        {{ ucfirst(__('PkgRealisationProjets::affectationProjet.seuil_live_coding')) }}
        <span class="text-danger">*</span>
    </label>
    <div class="input-group">
        <input
            name="seuil_live_coding"
            type="number"
            class="form-control"
            required
            min="1"
            max="100"
            id="seuil_live_coding"
            placeholder="{{ __('PkgRealisationProjets::affectationProjet.seuil_live_coding') }}"
            value="{{ $entity && !is_null($entity->seuil_live_coding) ? $entity->seuil_live_coding : old('seuil_live_coding', 50) }}">
        <div class="input-group-append">
            <span class="input-group-text">%</span>
        </div>
    </div>
    <small class="form-text text-muted mt-1">
        Pourcentage par défaut de réalisation requis pour lancer le Live coding sur ce groupe (défaut : 50%).
    </small>
    @error('seuil_live_coding')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<script>
(function() {
    const STORAGE_KEY = 'affectation_projet_seuil_live_coding';
    const input = document.getElementById('seuil_live_coding');
    const isCreate = {{ empty($entity->id) ? 'true' : 'false' }};

    if (input) {
        if (isCreate) {
            const storedValue = localStorage.getItem(STORAGE_KEY);
            if (storedValue !== null && storedValue.trim() !== '') {
                input.value = storedValue;
            }
        }

        input.addEventListener('input', function() {
            if (this.value) {
                localStorage.setItem(STORAGE_KEY, this.value);
            }
        });
    }
})();
</script>

