{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationTache-show')
<div class="card container shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">{{ __('Détails de la Réalisation de Tâche') }}</h5>
    </div>

    <div class="card-body">

        <h6 class="text-muted mb-3">📋 Informations Générales</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>{{ ucfirst(__('PkgGestionTaches::tache.singular')) }} :</strong>
                <div class="text-primary">{{ $itemRealisationTache->tache ?? '-' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <strong>{{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }} :</strong>
                <div class="text-primary">{{ $itemRealisationTache->realisationProjet ?? '-' }}</div>
            </div>
        </div>

        <hr>

        <h6 class="text-muted mb-3">📅 Dates</h6>
        <div class="row">
            <div class="col-md-3 mb-3">
                <strong>🕑 {{ ucfirst(__('PkgGestionTaches::realisationTache.dateDebut')) }} :</strong>
                <div>{{ $itemRealisationTache->dateDebut ?? '-' }}</div>
            </div>

            <div class="col-md-3 mb-3">
                <strong>⏰ {{ ucfirst(__('PkgGestionTaches::realisationTache.dateFin')) }} :</strong>
                <div>{{ $itemRealisationTache->dateFin ?? '-' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <strong>{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.singular')) }} :</strong>
                @if (!empty($itemRealisationTache->etatRealisationTache))
                    <span class="badge badge-info p-2">{{ $itemRealisationTache->etatRealisationTache }}</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </div>
        </div>

        <hr>

        <h6 class="text-muted mb-3">📝 Remarques</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card bg-light p-3">
                    <strong>{{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_formateur')) }} :</strong>
                    <div class="mt-2">{!! nl2br(e($itemRealisationTache->remarques_formateur)) ?? '-' !!}</div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card bg-light p-3">
                    <strong>{{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_apprenant')) }} :</strong>
                    <div class="mt-2">{!! nl2br(e($itemRealisationTache->remarques_apprenant)) ?? '-' !!}</div>
                </div>
            </div>
        </div>

        <hr>

        <h6 class="text-muted mb-3">🕘 Historique</h6>
        <div class="col-12">
            @include('PkgGestionTaches::historiqueRealisationTache._index', [
                'isMany' => true,
                'edit_has_many' => false,
                'contextKey' => 'realisationTache.show_' . $itemRealisationTache->id
            ])
        </div>

    </div>

    <div class="card-footer text-right">
        <a href="{{ route('realisationTaches.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Core::msg.cancel') }}
        </a>
        <a href="{{ route('realisationTaches.edit', $itemRealisationTache->id) }}" class="btn btn-primary ml-2">
            <i class="fas fa-edit"></i> {{ __('Core::msg.edit') }}
        </a>
    </div>
</div>

<script>
    window.modalTitle = '{{ __("PkgGestionTaches::realisationTache.singular") }} : {{$itemRealisationTache}}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
@show