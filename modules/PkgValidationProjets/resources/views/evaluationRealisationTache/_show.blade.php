{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluationRealisationTache-show')
<div id="evaluationRealisationTache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgValidationProjets::evaluationRealisationTache.evaluation_realisation_projet_id')) }}</small>
                              
      <span>
        @if(! is_null($itemEvaluationRealisationTache->evaluation_realisation_projet_id))
          {{ $itemEvaluationRealisationTache->evaluation_realisation_projet_id }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgValidationProjets::evaluationRealisationTache.note')) }}</small>
                              
      <span>
        @if(! is_null($itemEvaluationRealisationTache->note))
          {{ number_format($itemEvaluationRealisationTache->note, 2, '.', '') }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgValidationProjets::evaluationRealisationTache.message')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemEvaluationRealisationTache->message) && $itemEvaluationRealisationTache->message !== '')
    {!! $itemEvaluationRealisationTache->message !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgValidationProjets::evaluateur.singular')) }}</small>
                              
      @if($itemEvaluationRealisationTache->evaluateur)
        {{ $itemEvaluationRealisationTache->evaluateur }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::realisationTache.singular')) }}</small>
                              
      @if($itemEvaluationRealisationTache->realisationTache)
        {{ $itemEvaluationRealisationTache->realisationTache }}
      @else
        —
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
    window.modalTitle   = '{{ __("PkgValidationProjets::evaluationRealisationTache.singular") }} : {{ $itemEvaluationRealisationTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show