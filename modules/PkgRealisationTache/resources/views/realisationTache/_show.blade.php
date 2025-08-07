{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationTache-show')
<div id="realisationTache-crud-show">
        <div class="card-body">
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('Informations générales') }}
            </h6>
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::tache.singular')) }}</small>
@include('PkgRealisationTache::realisationTache.custom.fields.tache',['entity' => $itemRealisationTache])
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationTache->realisationProjet)
                  {{ $itemRealisationTache->realisationProjet }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('Dates de réalisation') }}
            </h6>
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.dateDebut')) }}</small>
                  <span>
                    @if ($itemRealisationTache->dateDebut)
                    {{ \Carbon\Carbon::parse($itemRealisationTache->dateDebut)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.dateFin')) }}</small>
                  <span>
                    @if ($itemRealisationTache->dateFin)
                    {{ \Carbon\Carbon::parse($itemRealisationTache->dateFin)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('État') }}
            </h6>
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.etat_realisation_tache_id')) }}</small>
@include('PkgRealisationTache::realisationTache.custom.fields.etatRealisationTache',['entity' => $itemRealisationTache])
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.note')) }}</small>
@include('PkgRealisationTache::realisationTache.custom.fields.note',['entity' => $itemRealisationTache])
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.is_live_coding')) }}</small>
                  @if($itemRealisationTache->is_live_coding)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('Suivi et évaluation') }}
            </h6>
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationChapitre.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.show_' . $itemRealisationTache->id])
                  </div>
                  </div>
            </div>

            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationUaProjet.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationUaProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.show_' . $itemRealisationTache->id])
                  </div>
                  </div>
            </div>

            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationUaPrototype.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationUaPrototype._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.show_' . $itemRealisationTache->id])
                  </div>
                  </div>
            </div>

            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('Remarques') }}
            </h6>
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.remarques_formateur')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemRealisationTache->remarques_formateur) && $itemRealisationTache->remarques_formateur !== '')
                    {!! $itemRealisationTache->remarques_formateur !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.remarques_apprenant')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemRealisationTache->remarques_apprenant) && $itemRealisationTache->remarques_apprenant !== '')
                    {!! $itemRealisationTache->remarques_apprenant !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.remarque_evaluateur')) }}</small>
@include('PkgRealisationTache::realisationTache.custom.fields.remarque_evaluateur',['entity' => $itemRealisationTache])
                </div>
            </div>
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgEvaluateurs::evaluationRealisationTache.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgEvaluateurs::evaluationRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.show_' . $itemRealisationTache->id])
                  </div>
                  </div>
            </div>

            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationTache::historiqueRealisationTache.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgRealisationTache::historiqueRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.show_' . $itemRealisationTache->id])
                  </div>
                  </div>
            </div>

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationTache')
          <x-action-button :entity="$itemRealisationTache" actionName="edit">
          @can('update', $itemRealisationTache)
              <a href="{{ route('realisationTaches.edit', ['realisationTache' => $itemRealisationTache->id]) }}" data-id="{{$itemRealisationTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationTache::realisationTache.singular") }} : {{ $itemRealisationTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show