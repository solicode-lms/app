

@section('realisationTache-show')
<div class="card container">
    <div class="card-header">
        {{ __('Détails de la Réalisation de Tâche') }}
    </div>

    <div class="card-body row">

        <div class="form-group col-12 col-md-6">
            <label>{{ ucfirst(__('PkgGestionTaches::tache.singular')) }}</label>
            <div class="form-control-plaintext">
                {{ $itemRealisationTache->tache ?? '-' }}
            </div>
        </div>

        <div class="form-group col-12 col-md-6">
            <label>{{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}</label>
            <div class="form-control-plaintext">
                {{ $itemRealisationTache->realisationProjet ?? '-' }}
            </div>
        </div>

        <div class="form-group col-12 col-md-3">
            <label>{{ ucfirst(__('PkgGestionTaches::realisationTache.dateDebut')) }}</label>
            <div class="form-control-plaintext">
                {{ $itemRealisationTache->dateDebut ? $itemRealisationTache->dateDebut : '-' }}
            </div>
        </div>

        <div class="form-group col-12 col-md-3">
            <label>{{ ucfirst(__('PkgGestionTaches::realisationTache.dateFin')) }}</label>
            <div class="form-control-plaintext">
                {{ $itemRealisationTache->dateFin ? $itemRealisationTache->dateFin : '-' }}
            </div>
        </div>

        <div class="form-group col-12 col-md-6">
            <label>{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.singular')) }}</label>
            <div class="form-control-plaintext">
                {{ $itemRealisationTache->etatRealisationTache ?? '-' }}
            </div>
        </div>

        <div class="form-group col-12 col-md-6">
            <label>{{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_formateur')) }}</label>
            <div class="form-control-plaintext">
                {!! nl2br(e($itemRealisationTache->remarques_formateur)) ?? '-' !!}
            </div>
        </div>

        <div class="form-group col-12 col-md-6">
            <label>{{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_apprenant')) }}</label>
            <div class="form-control-plaintext">
                {!! nl2br(e($itemRealisationTache->remarques_apprenant)) ?? '-' !!}
            </div>
        </div>

        {{-- Historique si disponible --}}
        <div class="col-12">
            <label>{{ ucfirst(__('PkgGestionTaches::historiqueRealisationTache.plural')) }}</label>

            @include('PkgGestionTaches::historiqueRealisationTache._index', [
                'isMany' => true,
                'edit_has_many' => false,
                'contextKey' => 'realisationTache.show_' . $itemRealisationTache->id
            ])
        </div>

    </div>

    <div class="card-footer">
        <a href="{{ route('realisationTaches.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <a href="{{ route('realisationTaches.edit', $itemRealisationTache->id) }}" class="btn btn-info ml-2">{{ __('Core::msg.edit') }}</a>
    </div>
</div>

<script>
    window.modalTitle = '{{ __("PkgGestionTaches::realisationTache.singular") }} : {{$itemRealisationTache}}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
@show
