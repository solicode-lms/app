{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@push('styles')
<style>
    /* Callout plus compact */
    .callout { padding: .9rem 1rem; }

    /* Icône info-box arrondie */
    .info-box-icon {
        border-radius: .25rem;
    }
</style>
@endpush

@section('realisationTache-show')

<div class="container-fluid">

    {{-- ╔═════════════════ En-tête + actions ───────────────────────────╗ --}}
    <div class="card shadow">
        <div class="card-header bg-info bg-gradient text-white d-flex justify-content-between align-items-center py-2">
            <h5 class="mb-0">
                <i class="fas fa-tasks mr-2"></i>{{ __('Détails – Réalisation de Tâche') }}
            </h5>

            <div class="btn-group btn-group-sm">
                <a href="{{ route('realisationTaches.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <a href="{{ route('realisationTaches.edit', $itemRealisationTache) }}" class="btn btn-warning text-white">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </div>

        {{-- ╔═════════════════ Corps ───────────────────────────────────╗ --}}
        <div class="card-body">

            {{-- 1️⃣  Infos principales ——— remplacé par les Info-Boxes Admin-LTE --}}
            <div class="row">
                {{-- Tâche -------------------------------------------------------- --}}
                <div class="col-md-6">
                    <div class="info-box mb-3 shadow-sm">
                        <span class="info-box-icon bg-teal elevation-1">
                            <i class="fas fa-clipboard-check"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                {{ ucfirst(__('PkgGestionTaches::tache.singular')) }}
                            </span>
                            <span class="info-box-number h6 mb-0">
                                {{ $itemRealisationTache->tache?->titre ?? '—' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Réalisation projet ----------------------------------------- --}}
                <div class="col-md-6">
                    <div class="info-box mb-3 shadow-sm">
                        <span class="info-box-icon bg-navy elevation-1">
                            <i class="fas fa-project-diagram"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                {{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}
                            </span>
                            <span class="info-box-number h6 mb-0">
                                {{ $itemRealisationTache->realisationProjet?->titre ?? '—' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2️⃣  Dates ——— passées en callout pour un look « card » fin -------- --}}
            <hr class="my-4">
            <h6 class="text-muted mb-3">
                <i class="far fa-calendar-alt mr-1"></i>{{ __('Dates de réalisation') }}
            </h6>

            <div class="row">
                <div class="col-md-6">
                    <div class="callout callout-info py-3 mb-3">
                        <strong class="d-block mb-1">
                            {{ ucfirst(__('PkgGestionTaches::realisationTache.dateDebut')) }}
                        </strong>
                        {{ optional($itemRealisationTache->dateDebut)->isoFormat('LL') ?? '—' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="callout callout-secondary py-3 mb-3">
                        <strong class="d-block mb-1">
                            {{ ucfirst(__('PkgGestionTaches::realisationTache.dateFin')) }}
                        </strong>
                        {{ optional($itemRealisationTache->dateFin)->isoFormat('LL') ?? '—' }}
                    </div>
                </div>
            </div>

            {{-- 3️⃣  État ——— badge conservé -------------------------------------- --}}
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="callout callout-light py-3 mb-3">
                        <strong class="d-block mb-1">
                            {{ ucfirst(__('PkgGestionTaches::etatRealisationTache.singular')) }}
                        </strong>
                        @if($etat = $itemRealisationTache->etatRealisationTache)
                            <span class="badge badge-{{ $etat->sysColor?->class ?? 'info' }} p-2">
                                {{ $etat->nom }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 4️⃣  Remarques (collapse) — inchangé ------------------------------- --}}
            <hr class="my-4">
            <div id="accordionNotes">
                {{-- Formateur --}}
                <div class="card border-0 mb-2">
                    <div class="card-header bg-light py-2" id="headingTrainer">
                        <h6 class="mb-0">
                            <button class="btn btn-link text-body p-0" data-toggle="collapse"
                                    data-target="#collapseTrainer" aria-expanded="false"
                                    aria-controls="collapseTrainer">
                                <i class="fas fa-comment-dots mr-1"></i>
                                {{ __('PkgGestionTaches::realisationTache.remarques_formateur') }}
                            </button>
                        </h6>
                    </div>
                    <div id="collapseTrainer" class="collapse" aria-labelledby="headingTrainer" data-parent="#accordionNotes">
                        <div class="card-body">
                            {!! nl2br(e($itemRealisationTache->remarques_formateur ?? '—')) !!}
                        </div>
                    </div>
                </div>

                {{-- Apprenant --}}
                <div class="card border-0 mb-2">
                    <div class="card-header bg-light py-2" id="headingStudent">
                        <h6 class="mb-0">
                            <button class="btn btn-link text-body p-0" data-toggle="collapse"
                                    data-target="#collapseStudent" aria-expanded="false"
                                    aria-controls="collapseStudent">
                                <i class="fas fa-comments mr-1"></i>
                                {{ __('PkgGestionTaches::realisationTache.remarques_apprenant') }}
                            </button>
                        </h6>
                    </div>
                    <div id="collapseStudent" class="collapse" aria-labelledby="headingStudent" data-parent="#accordionNotes">
                        <div class="card-body">
                            {!! nl2br(e($itemRealisationTache->remarques_apprenant ?? '—')) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5️⃣  Historique — inchangé ---------------------------------------- --}}
            <hr class="my-4">
            <h6 class="text-muted mb-3">
                <i class="fas fa-history mr-1"></i>{{ __('Historique') }}
            </h6>

            <div class="border rounded shadow-sm p-0">
                @include('PkgGestionTaches::historiqueRealisationTache._index', [
                    'isMany'        => true,
                    'edit_has_many' => false,
                    'contextKey'    => 'realisationTache.show_'.$itemRealisationTache->id
                ])
            </div>
        </div>
    </div>
</div>

{{-- 🔧 Variables JS exportées si besoin ailleurs ------------------------------- --}}
<script>
    window.modalTitle   = '{{ __("PkgGestionTaches::realisationTache.singular") }} : {{ $itemRealisationTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show
