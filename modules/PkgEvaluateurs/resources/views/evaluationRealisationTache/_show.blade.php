{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluationRealisationTache-show')
<div id="evaluationRealisationTache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluationRealisationTache.realisation_tache_id')) }}</small>
@include('PkgEvaluateurs::evaluationRealisationTache.custom.fields.realisationTache',['entity' => $itemEvaluationRealisationTache])
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluateur.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemEvaluationRealisationTache->evaluateur)
                  {{ $itemEvaluationRealisationTache->evaluateur }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluationRealisationTache.note')) }}</small>
@include('PkgEvaluateurs::evaluationRealisationTache.custom.fields.note',['entity' => $itemEvaluationRealisationTache])
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluationRealisationTache.message')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemEvaluationRealisationTache->message) && $itemEvaluationRealisationTache->message !== '')
                    {!! $itemEvaluationRealisationTache->message !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluationRealisationProjet.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemEvaluationRealisationTache->evaluationRealisationProjet)
                  {{ $itemEvaluationRealisationTache->evaluationRealisationProjet }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('evaluationRealisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-evaluationRealisationTache')
          <x-action-button :entity="$itemEvaluationRealisationTache" actionName="edit">
          @can('update', $itemEvaluationRealisationTache)
              <a href="{{ route('evaluationRealisationTaches.edit', ['evaluationRealisationTache' => $itemEvaluationRealisationTache->id]) }}" data-id="{{$itemEvaluationRealisationTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgEvaluateurs::evaluationRealisationTache.singular") }} : {{ $itemEvaluationRealisationTache }}';
    window.showUIId = 'evaluationRealisationTache-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show