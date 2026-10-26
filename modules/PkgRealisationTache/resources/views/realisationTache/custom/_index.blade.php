
@extends('PkgRealisationTache::realisationTache._index')

@section('realisationTache-crud-filters')
<div class="card-header pb-1">
    {{-- Bandeau des situations pédagogiques & multi-états personnalisés (masqué par défaut si aucun filtre actif) --}}
    @php
        $currentSituation = request('filter.realisationTache.situation_pedagogique')
            ?? request('situation_pedagogique')
            ?? (app(\Modules\Core\Services\ViewStateService::class)->get('filter.realisationTache.situation_pedagogique') ?? '');
    @endphp
    <div id="situation-pedagogique-container" class="situation-pedagogique-container mb-2 {{ empty($currentSituation) ? 'd-none' : '' }}">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div class="situation-filter-pills d-flex flex-wrap align-items-center" style="gap: 6px;">
                <span class="text-sm font-weight-bold text-muted mr-1">
                    <i class="fas fa-layer-group text-info"></i> {{ __('Situation :') }}
                </span>
                <button type="button" class="btn btn-xs btn-outline-secondary situation-pill" data-situation="">
                    <i class="fas fa-times"></i> {{ __('Toutes / Masquer') }}
                </button>
                <button type="button" class="btn btn-xs btn-outline-primary situation-pill" data-situation="SITUATION_TRAVAIL">
                    <i class="fas fa-hammer"></i> {{ __('En Travail') }}
                </button>
                <button type="button" class="btn btn-xs btn-outline-danger situation-pill" data-situation="SITUATION_BLOCAGE">
                    <i class="fas fa-exclamation-triangle"></i> {{ __('En Blocage') }}
                </button>
                <button type="button" class="btn btn-xs btn-outline-warning situation-pill" data-situation="SITUATION_ATTENTE">
                    <i class="fas fa-hourglass-half"></i> {{ __('En Attente') }}
                </button>
                <button type="button" class="btn btn-xs btn-outline-success situation-pill" data-situation="SITUATION_TERMINEE">
                    <i class="fas fa-check-circle"></i> {{ __('Terminées') }}
                </button>
                <button type="button" class="btn btn-xs btn-outline-danger situation-pill" data-situation="SITUATION_INACTIF_SEANCE">
                    <i class="fas fa-user-clock"></i> {{ __('Inactifs Séance') }}
                </button>
                <button type="button" class="btn btn-xs btn-outline-secondary situation-pill" data-situation="SITUATION_INACTIF_JOUR">
                    <i class="fas fa-calendar-day"></i> {{ __('Inactifs Journée') }}
                </button>
                <button type="button" class="btn btn-xs btn-outline-dark situation-pill" data-situation="SITUATION_CUSTOM">
                    <i class="fas fa-sliders-h"></i> {{ __('Personnalisée...') }}
                </button>
            </div>

            {{-- Panneau multi-états personnalisés dépliable --}}
            <div id="custom-etats-panel" class="d-none mt-1 mt-md-0">
                <div class="d-flex flex-wrap align-items-center bg-white border rounded px-2 py-1 shadow-sm" style="gap: 6px;">
                    <span class="text-xs text-muted font-weight-bold mr-1">{{ __('États :') }}</span>
                    @foreach($workflowTachesList ?? [] as $wf)
                        <div class="form-check form-check-inline mr-1 mb-0">
                            <input class="form-check-input custom-etat-checkbox" type="checkbox" id="wf_check_{{ $wf->code }}" value="{{ $wf->code }}">
                            <label class="form-check-label text-xs font-weight-normal mb-0" for="wf_check_{{ $wf->code }}" style="cursor: pointer;">
                                {{ $wf->titre ?? $wf->code }}
                            </label>
                        </div>
                    @endforeach
                    <button type="button" id="btn-apply-custom-etats" class="btn btn-xs btn-primary ml-1">
                        <i class="fas fa-filter"></i> {{ __('Appliquer') }}
                    </button>
                </div>
            </div>
        </div>

    </div>

    <form id="realisationTache-crud-filter-form" method="GET" class="row">
        <input type="hidden" id="filter_custom_etats" name="custom_etats" value="{{ request('custom_etats') }}">
        <x-filter-group count="{{count($realisationTaches_filters ?? [])}}">
            @foreach ($realisationTaches_filters as $filter)
                <x-filter-field 
                    :label="$filter['label']" 
                    :type="$filter['type']" 
                    :field="$filter['field']" 
                    :options="$filter['options'] ?? []"
                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" 
                    :targetDynamicDropdown="isset($filter['targetDynamicDropdown']) ? $filter['targetDynamicDropdown'] : null"
                    :targetDynamicDropdownApiUrl="isset($filter['targetDynamicDropdownApiUrl']) ? $filter['targetDynamicDropdownApiUrl'] : null" 
                    :targetDynamicDropdownFilter="isset($filter['targetDynamicDropdownFilter']) ? $filter['targetDynamicDropdownFilter'] : null" />
            @endforeach
        </x-filter-group>
        <div id="realisationTache-crud-search-bar"
            class="{{ count($realisationTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
            <x-search-bar
                :search="request('realisationTaches_search')"
                name="realisationTaches_search"
                id="realisationTaches_search"
                placeholder="Recherche ..."
            />
        </div>
    </form>
</div>
@endsection

<script>
// Mapping des situations pédagogiques prédéfinies vers les codes WorkflowTache
const SITUATION_WORKFLOW_MAP = {
  'SITUATION_TRAVAIL': ['IN_PROGRESS', 'REVISION_NECESSAIRE', 'EN_REMEDIATION', 'IN_LIVE_CODING', 'PAUSED'],
  'SITUATION_BLOCAGE': ['EN_REMEDIATION', 'REVISION_NECESSAIRE', 'NOT_VALIDATED', 'LATE', 'PAUSED'],
  'SITUATION_ATTENTE': ['TO_APPROVE', 'READY_FOR_LIVE_CODING', 'A_VALIDER_LIVE_CODING'],
  'SITUATION_TERMINEE': ['APPROVED']
};

// IDs des selects et filtres
const ETAT_ID          = 'filter_etat_realisation_tache_id';
const WORKFLOW_ID      = 'filter_etatRealisationTache.WorkflowTache.Code';
const SITUATION_ID     = 'filter_situation_pedagogique';
const CUSTOM_ETATS_ID  = 'filter_custom_etats';

/**
 * Gère l’affichage/cachage du wrapper parent d’un <select> ou d’un Select2
 * @param {HTMLElement} el      L’élément <select>
 * @param {boolean}     visible true → afficher, false → masquer
 */
function toggleVisibility(el, visible) {
  const wrapper = el?.parentElement;
  if (!wrapper) return;
  wrapper.style.display = visible ? '' : 'none';
}

/**
 * Gère l'affichage des deux <select> en fonction des sélections
 * @param {string} etatSelectId     ID du select “état”
 * @param {string} workflowSelectId ID du select “workflowTache”
 */
function manageSelectVisibility(etatSelectId, workflowSelectId) {
  const etatEl     = document.getElementById(etatSelectId);
  const workflowEl = document.getElementById(workflowSelectId);
  if (!etatEl || !workflowEl) return;

  const etatChoisi     = etatEl.value !== '';
  const workflowChoisi = workflowEl.value !== '';
  const etatVide       = etatEl.options.length === 1;

  if (etatVide) {
    toggleVisibility(etatEl, false);
    toggleVisibility(workflowEl, true);
    return;
  }

  if (etatChoisi || workflowChoisi) {
    toggleVisibility(etatEl, true);
    toggleVisibility(workflowEl, workflowChoisi);
  } else {
    toggleVisibility(etatEl, true);
    toggleVisibility(workflowEl, false);
  }
}

// Initialisation et branchements
document.addEventListener('DOMContentLoaded', () => {
  const etatEl             = document.getElementById(ETAT_ID);
  const wfEl               = document.getElementById(WORKFLOW_ID);
  const situationEl        = document.getElementById(SITUATION_ID);
  const customEtatsEl      = document.getElementById(CUSTOM_ETATS_ID);
  const situationContainer = document.getElementById('situation-pedagogique-container');
  const customPanel        = document.getElementById('custom-etats-panel');

  if (etatEl && wfEl) {
    manageSelectVisibility(ETAT_ID, WORKFLOW_ID);

    const observer = new MutationObserver(() => {
      manageSelectVisibility(ETAT_ID, WORKFLOW_ID);
    });
    observer.observe(etatEl, {
      childList: true,
      subtree: true,
      attributes: true,
      attributeFilter: ['value', 'label', 'disabled'] 
    });

    const $etat = $("#" + ETAT_ID);
    function handleEtatFocus() {
      if (wfEl.value !== '') {
        wfEl.selectedIndex = 0;
        $(wfEl).trigger('change');
      }
      manageSelectVisibility(ETAT_ID, WORKFLOW_ID);
    }
    $etat.on('focus', handleEtatFocus);
    if ($etat.hasClass('select2-hidden-accessible')) {
      $etat.on('select2:select', handleEtatFocus);
    }

    $(etatEl).on('select2:select', function () {
      if (wfEl.value !== '') {
        $(wfEl).val(null).trigger('change');
      }
      // Si l'utilisateur choisit un état unitaire, réinitialiser et masquer la situation
      if (situationEl && $(situationEl).val() !== '') {
        $(situationEl).val('').trigger('change.select2');
        if (situationContainer) situationContainer.classList.add('d-none');
        if (customPanel) customPanel.classList.add('d-none');
        if (customEtatsEl) customEtatsEl.value = '';
        updateActivePill('');
      }
      manageSelectVisibility(ETAT_ID, WORKFLOW_ID);
    });

    wfEl.addEventListener('change', () => {
      manageSelectVisibility(ETAT_ID, WORKFLOW_ID);
    });
  }

  // --- Fonctions utilitaires situations & pills ---
  function updateActivePill(val) {
    document.querySelectorAll('.situation-pill').forEach(btn => {
      const btnSit = btn.getAttribute('data-situation') || '';
      if (val && btnSit === val) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });
  }

  function syncCheckboxesForSituation(situation) {
    const targetCodes = SITUATION_WORKFLOW_MAP[situation] || [];
    document.querySelectorAll('.custom-etat-checkbox').forEach(cb => {
      cb.checked = targetCodes.includes(cb.value);
    });
  }

  // Fonction pour afficher/masquer la barre de situation et synchroniser les composants
  function handleSituationChange() {
    const val = (situationEl ? $(situationEl).val() : '') || '';
    if (val) {
      // 1. Afficher la barre de situation dès qu'une option est choisie
      if (situationContainer) situationContainer.classList.remove('d-none');
      updateActivePill(val);

      if (val === 'SITUATION_CUSTOM') {
        if (customPanel) customPanel.classList.remove('d-none');
      } else {
        if (customPanel) customPanel.classList.add('d-none');
        if (customEtatsEl) customEtatsEl.value = '';
        if (SITUATION_WORKFLOW_MAP[val]) {
          syncCheckboxesForSituation(val);
        }
      }
    } else {
      // 2. Masquer la barre de situation après nettoyage du select
      if (situationContainer) situationContainer.classList.add('d-none');
      if (customPanel) customPanel.classList.add('d-none');
      if (customEtatsEl) customEtatsEl.value = '';
      updateActivePill('');
      document.querySelectorAll('.custom-etat-checkbox').forEach(cb => { cb.checked = false; });
    }
  }

  // Initialisation au chargement
  const urlParams = new URLSearchParams(window.location.search);
  const initialSituation = (situationEl ? $(situationEl).val() : '') 
    || urlParams.get('filter.realisationTache.situation_pedagogique')
    || urlParams.get('situation_pedagogique')
    || '';

  if (initialSituation) {
    if (situationEl && $(situationEl).val() !== initialSituation) {
      $(situationEl).val(initialSituation);
    }
    if (situationContainer) situationContainer.classList.remove('d-none');
    updateActivePill(initialSituation);
    if (initialSituation === 'SITUATION_CUSTOM') {
      if (customPanel) customPanel.classList.remove('d-none');
      const initialCustom = (customEtatsEl ? customEtatsEl.value : '') || '';
      const customCodes = initialCustom.split(',').map(s => s.trim()).filter(Boolean);
      document.querySelectorAll('.custom-etat-checkbox').forEach(cb => {
        cb.checked = customCodes.includes(cb.value);
      });
    } else if (SITUATION_WORKFLOW_MAP[initialSituation]) {
      syncCheckboxesForSituation(initialSituation);
    }
  } else {
    if (situationContainer) situationContainer.classList.add('d-none');
  }

  // Écouter le changement et le nettoyage sur le select situation
  if (situationEl) {
    $(situationEl).on('change select2:select select2:clear', handleSituationChange);
  }

  // Clic sur un bouton de la barre de situation (Pills)
  $(document).on('click', '.situation-pill', function(e) {
    e.preventDefault();
    const situation = $(this).attr('data-situation') || '';

    // Nettoyer les paramètres URL résiduels non préfixés (ex: situation_pedagogique=...)
    try {
      const currentUrl = new URL(window.location.href);
      if (currentUrl.searchParams.has('situation_pedagogique')) {
        currentUrl.searchParams.delete('situation_pedagogique');
        window.history.replaceState({}, '', currentUrl.toString());
      }
    } catch (err) {}

    if (!situation) {
      // Clic sur "Toutes / Masquer" : nettoyer le select et masquer la barre
      if (situationEl) {
        $(situationEl).val('').trigger('change.select2');
      }
      if (situationContainer) situationContainer.classList.add('d-none');
      if (customPanel) customPanel.classList.add('d-none');
      if (customEtatsEl) customEtatsEl.value = '';
      updateActivePill('');
      document.querySelectorAll('.custom-etat-checkbox').forEach(cb => { cb.checked = false; });

      // Lancer le filtrage
      if (situationEl) {
        $(situationEl).trigger('change').trigger('input');
      }
      return;
    }

    updateActivePill(situation);

    if (situation === 'SITUATION_CUSTOM') {
      if (customPanel) customPanel.classList.remove('d-none');
      if (situationEl) {
        $(situationEl).val('SITUATION_CUSTOM').trigger('change.select2');
      }
    } else {
      if (customPanel) customPanel.classList.add('d-none');
      if (customEtatsEl) customEtatsEl.value = '';

      if (SITUATION_WORKFLOW_MAP[situation]) {
        syncCheckboxesForSituation(situation);
      }

      // Réinitialiser les filtres unitaires d'états
      if (etatEl && $(etatEl).val() !== '') {
        $(etatEl).val(null).trigger('change.select2');
      }
      if (wfEl && $(wfEl).val() !== '') {
        $(wfEl).val(null).trigger('change.select2');
      }

      // Mettre à jour la valeur du select et lancer l'événement change pour déclencher le filtrage
      if (situationEl) {
        $(situationEl).val(situation).trigger('change.select2').trigger('change').trigger('input');
      }
    }
  });

  // Interaction avec les cases à cocher custom
  $(document).on('change', '.custom-etat-checkbox', function() {
    updateActivePill('SITUATION_CUSTOM');
    if (situationEl) {
      $(situationEl).val('SITUATION_CUSTOM').trigger('change.select2');
    }
    const checked = $('.custom-etat-checkbox:checked').map(function() { return this.value; }).get();
    if (customEtatsEl) {
      customEtatsEl.value = checked.join(',');
    }
  });

  // Bouton Appliquer du filtre personnalisé multi-états
  $(document).on('click', '#btn-apply-custom-etats', function(e) {
    e.preventDefault();
    const checked = $('.custom-etat-checkbox:checked').map(function() { return this.value; }).get();
    if (customEtatsEl) {
      customEtatsEl.value = checked.join(',');
    }
    if (situationEl) {
      $(situationEl).val('SITUATION_CUSTOM').trigger('change.select2').trigger('change').trigger('input');
    }
  });
});
</script>