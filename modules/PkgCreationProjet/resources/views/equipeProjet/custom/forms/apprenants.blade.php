<div class="form-group col-12 col-md-6">
    @if ($bulkEdit)
    <div class="bulk-check">
        <input 
        type="checkbox" 
        class="check-input" 
        name="fields_modifiables[]" 
        value="apprenants" 
        id="bulk_field_apprenants" 
        title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
    </div>
    @endif
    <label for="apprenants">
      {{ ucfirst(__('PkgApprenants::apprenant.plural')) }}
    </label>
    <select
        id="apprenants"
        name="apprenants[]"
        class="form-control select2"
        data-initial-members='@json(isset($entity) && $entity->apprenants ? $entity->apprenants->pluck("id") : [])'
        multiple="multiple">
        @foreach ($apprenants as $apprenant)
            <option value="{{ $apprenant->id }}" 
                {{ (isset($entity) && $entity->apprenants && $entity->apprenants->contains('id', $apprenant->id)) || (is_array(old('apprenants')) && in_array($apprenant->id, old('apprenants'))) ? 'selected' : '' }}>
                {{ $apprenant }}
            </option>
        @endforeach
    </select>
    
    <div id="regle-gestion-taches-equipe-alert" class="alert alert-warning py-2 px-3 mt-2 mb-1 d-none" style="font-size: 0.82rem; border-left: 3px solid #ffc107;">
        <i class="fas fa-exclamation-triangle mr-1 text-warning"></i>
        <strong>Règle de gestion des tâches d'équipe :</strong>
        <div class="mt-1">
            Seules les réalisations de tâches à l'état « À faire » (TODO) seront supprimées lors du retrait d'un apprenant. Si des tâches sont déjà en cours ou validées, vous devez d'abord remettre leur état à TODO pour pouvoir retirer l'apprenant.
        </div>
    </div>

    @error('apprenants')
      <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<script>
(function() {
    const selectElem = document.getElementById('apprenants');
    const alertElem = document.getElementById('regle-gestion-taches-equipe-alert');
    if (!selectElem) return;

    let initialMembers = [];
    try {
        initialMembers = JSON.parse(selectElem.getAttribute('data-initial-members') || '[]').map(String);
    } catch (e) {
        initialMembers = [];
    }

    function updateAlertVisibility(currentValues) {
        if (!alertElem) return;
        const currentVals = (currentValues || []).map(String);
        // Vérifier si au moins un membre initial a été retiré
        const hasRemovedMember = initialMembers.some(id => !currentVals.includes(id));
        if (hasRemovedMember) {
            alertElem.classList.remove('d-none');
        } else {
            alertElem.classList.add('d-none');
        }
    }

    if (window.jQuery) {
        const $select = jQuery(selectElem);

        // Mise à jour de la visibilité au chargement si besoin
        updateAlertVisibility($select.val());

        $select.on('change', function() {
            updateAlertVisibility($select.val());
        });

        $select.on('select2:unselecting', function(e) {
            const removedId = String(e.params.args.data.id);

            // Ne déclencher le message que si l'apprenant était déjà membre enregistré de l'équipe
            if (initialMembers.includes(removedId)) {
                if (window.Swal) {
                    e.preventDefault(); // Suspend la désélection en attente de confirmation
                    Swal.fire({
                        title: "Règle de gestion des tâches d'équipe :",
                        html: "Seules les réalisations de tâches à l'état « À faire » (TODO) seront supprimées lors du retrait d'un apprenant. Si des tâches sont déjà en cours ou validées, vous devez d'abord remettre leur état à TODO pour pouvoir retirer l'apprenant.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Continuer le retrait',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const currentVals = $select.val() || [];
                            const newVals = currentVals.filter(id => String(id) !== removedId);
                            $select.val(newVals).trigger('change');
                        }
                    });
                }
            }
        });
    }
})();
</script>
