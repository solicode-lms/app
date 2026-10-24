<div class="form-group col-12 col-md-6">
    @if ($bulkEdit)
    <div class="bulk-check">
        <input 
            type="checkbox" 
            class="check-input" 
            name="fields_modifiables[]" 
            value="seuil_blocage_minutes" 
            id="bulk_field_seuil_blocage_minutes" 
            title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
    </div>
    @endif
    <label for="seuil_blocage_minutes">
        {{ ucfirst(__('PkgRealisationProjets::affectationProjet.seuil_blocage_minutes')) }}
        <span class="text-danger">*</span>
    </label>
    <div class="input-group">
        <input
            name="seuil_blocage_minutes"
            type="number"
            class="form-control"
            required
            min="1"
            id="seuil_blocage_minutes"
            placeholder="{{ __('PkgRealisationProjets::affectationProjet.seuil_blocage_minutes') }}"
            value="{{ $entity ? $entity->seuil_blocage_minutes : old('seuil_blocage_minutes') }}">
        <div class="input-group-append">
            <span class="input-group-text">min</span>
        </div>
    </div>
    @php
        $moyen = $seuil_blocage_moyen ?? \Modules\PkgRealisationProjets\Models\AffectationProjet::getMoyenneSeuilBlocage();
    @endphp
    <small class="form-text text-muted mt-1 d-flex justify-content-between align-items-center">
        <span>
            Moyenne du système : <strong id="valeur_seuil_moyen">{{ $moyen }}</strong> min
        </span>
        <button type="button" class="btn btn-xs btn-outline-secondary ml-2" id="btn_appliquer_moyenne" title="Appliquer la moyenne du système">
            <i class="fas fa-undo-alt"></i> Utiliser la moyenne
        </button>
    </small>
    @error('seuil_blocage_minutes')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<script>
(function() {
    const STORAGE_KEY = 'affectation_projet_seuil_blocage_minutes';
    const input = document.getElementById('seuil_blocage_minutes');
    const btnMoyenne = document.getElementById('btn_appliquer_moyenne');
    const moyenVal = "{{ $moyen }}";
    const isCreate = {{ empty($entity->id) ? 'true' : 'false' }};

    if (input) {
        // En création, priorité à la valeur enregistrée par l'utilisateur dans son localStorage
        if (isCreate) {
            const storedValue = localStorage.getItem(STORAGE_KEY);
            if (storedValue !== null && storedValue.trim() !== '') {
                input.value = storedValue;
            }
        }

        // Sauvegarder dans localStorage dès modification
        input.addEventListener('input', function() {
            if (this.value) {
                localStorage.setItem(STORAGE_KEY, this.value);
            }
        });

        // Bouton de réinitialisation vers la moyenne système
        if (btnMoyenne) {
            btnMoyenne.addEventListener('click', function() {
                input.value = moyenVal;
                localStorage.setItem(STORAGE_KEY, moyenVal);
                $(input).trigger('change');
            });
        }
    }
})();
</script>
