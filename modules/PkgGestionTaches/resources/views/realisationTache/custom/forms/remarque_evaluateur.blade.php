@php
    // Vérifie si l'utilisateur peut voir (évaluateur ou formateur assigné)
    $canView = $entity->currentEvaluateurId() !== null;

    if (! $canView) {
        return;
    }

    // Récupère le message à afficher via méthode utilitaire
    $displayMessage = $entity->getDisplayMessage();
@endphp

<div class="form-group col-12 col-md-6">
    @if (!empty($bulkEdit))
        <div class="bulk-check">
            <input type="checkbox"
                   class="check-input"
                   name="fields_modifiables[]"
                   value="remarque_evaluateur"
                   id="bulk_field_remarque_evaluateur"
                   title="Appliquer ce champ à tous les éléments sélectionnés"
                   data-toggle="tooltip">
        </div>
    @endif

    <label for="remarque_evaluateur">
        {{ ucfirst(__('PkgGestionTaches::realisationTache.remarque_evaluateur')) }}
    </label>

    <textarea
        name="remarque_evaluateur"
        id="remarque_evaluateur"
        class="form-control richText"
        placeholder="{{ __('PkgGestionTaches::realisationTache.remarque_evaluateur') }}"
    >{{ old('remarque_evaluateur', $displayMessage) }}</textarea>

    @error('remarque_evaluateur')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
