
@section('realisationTache-show')

        <div class="card-body">

            {{-- 1️⃣  Informations générales --}}
            <h6 class="text-muted mb-2">
                <i class="fas fa-info-circle mr-1"></i>{{ __('Informations générales') }}
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6 col-lg-6">
                    <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ __('PkgCreationTache::tache.singular') }}</small>
                        {{ $itemRealisationTache->tache?->titre ?? '—' }}
                    </div>
                </div>

                <div class="col-md-6 col-lg-6">
                    <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ __('PkgRealisationProjets::realisationProjet.singular') }}</small>
                        {{ $itemRealisationTache->realisationProjet ?? '—' }}
                    </div>
                </div>

                {{-- ➕  Ajoute ici d’autres attributs généraux --}}
            </div>

            {{-- 2️⃣  Dates de réalisation --}}
            <h6 class="text-muted mb-2">
                <i class="far fa-calendar-alt mr-1"></i>{{ __('Dates de réalisation') }}
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6 col-lg-4">
                    <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ __('PkgRealisationTache::realisationTache.dateDebut') }}</small>
                        {{ optional($itemRealisationTache->dateDebut)->isoFormat('LL') ?? '—' }}
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ __('PkgRealisationTache::realisationTache.dateFin') }}</small>
                        {{ optional($itemRealisationTache->dateFin)->isoFormat('LL') ?? '—' }}
                    </div>
                </div>
            </div>

            {{-- 3️⃣  État --}}
            <h6 class="text-muted mb-2">
                <i class="fas fa-flag-checkered mr-1"></i>{{ __('État') }}
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4 col-lg-3">
                    <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ __('PkgRealisationTache::etatRealisationTache.singular') }}</small>
                        @if($etat = $itemRealisationTache->etatRealisationTache)
                            <span class="badge badge-{{ $etat->sysColor?->class ?? 'info' }} p-2">
                                {{ $etat->nom }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>
                </div>

                {{-- ➕  Place libre pour “Priorité”, “Progression %”, etc. --}}
            </div>


            {{-- 4️⃣ Remarques ---------------------------------------------------- --}}
            <h6 class="text-muted mb-2">
                <i class="fas fa-comments mr-1"></i>{{ __('Remarques') }}
            </h6>

            <div class="row g-3 mb-4">
                {{-- Formateur --}}
                <div class="col-md-6">
                    <div class="callout callout-info m-0 h-100">
                        <h6 class="mb-2">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                            {{ __('PkgRealisationTache::realisationTache.remarques_formateur') }}
                        </h6>
                        {!! nl2br(e($itemRealisationTache->remarques_formateur ?? '—')) !!}
                    </div>
                </div>

                {{-- Apprenant --}}
                <div class="col-md-6">
                    <div class="callout callout-warning m-0 h-100">
                        <h6 class="mb-2">
                            <i class="fas fa-user-graduate mr-1"></i>
                            {{ __('PkgRealisationTache::realisationTache.remarques_apprenant') }}
                        </h6>
                        {!! nl2br(e($itemRealisationTache->remarques_apprenant ?? '—')) !!}
                    </div>
                </div>
            </div>


            {{-- 5️⃣  Historique --}}
            <h6 class="text-muted mt-4 mb-2">
                <i class="fas fa-history mr-1"></i>{{ __('Historique') }}
            </h6>
            <div class="border rounded shadow-sm p-0">
                @include('PkgRealisationTache::historiqueRealisationTache._index', [
                    'isMany'        => true,
                    'edit_has_many' => false,
                    'contextKey'    => 'realisationTache.show_'.$itemRealisationTache->id
                ])
            </div>
        </div>


         {{-- ╔══ En-tête + actions ───────────────────────────────╗ --}}
         <div class="card-footer">
            <div class="btn-group btn-group-sm">
                <a href="{{ route('realisationTaches.index') }}"  class="btn btn-light">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <a href="{{ route('realisationTaches.edit', $itemRealisationTache) }}" class="btn btn-warning text-white">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </div>


<script>
    window.modalTitle   = '{{ __("PkgRealisationTache::realisationTache.singular") }} : {{ $itemRealisationTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show
