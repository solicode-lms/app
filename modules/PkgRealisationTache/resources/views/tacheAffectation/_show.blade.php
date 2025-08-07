{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('tacheAffectation-show')
<div id="tacheAffectation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::tache.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemTacheAffectation->tache)
                  {{ $itemTacheAffectation->tache }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemTacheAffectation->affectationProjet)
                  {{ $itemTacheAffectation->affectationProjet }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::tacheAffectation.pourcentage_realisation_cache')) }}</small>
                  <span>
                  @if(! is_null($itemTacheAffectation->pourcentage_realisation_cache))
                  {{ number_format($itemTacheAffectation->pourcentage_realisation_cache, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationTache::realisationTache.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgRealisationTache::realisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'tacheAffectation.show_' . $itemTacheAffectation->id])
                  </div>
                  </div>
            </div>

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('tacheAffectations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-tacheAffectation')
          <x-action-button :entity="$itemTacheAffectation" actionName="edit">
          @can('update', $itemTacheAffectation)
              <a href="{{ route('tacheAffectations.edit', ['tacheAffectation' => $itemTacheAffectation->id]) }}" data-id="{{$itemTacheAffectation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationTache::tacheAffectation.singular") }} : {{ $itemTacheAffectation }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show