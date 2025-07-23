{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('critereEvaluation-show')
<div id="critereEvaluation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::critereEvaluation.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemCritereEvaluation->ordre))
          {{ $itemCritereEvaluation->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::critereEvaluation.intitule')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemCritereEvaluation->intitule) && $itemCritereEvaluation->intitule !== '')
    {!! $itemCritereEvaluation->intitule !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::critereEvaluation.bareme')) }}</small>
                              
      <span>
        @if(! is_null($itemCritereEvaluation->bareme))
          {{ number_format($itemCritereEvaluation->bareme, 2, '.', '') }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::phaseEvaluation.singular')) }}</small>
                              
      @if($itemCritereEvaluation->phaseEvaluation)
        {{ $itemCritereEvaluation->phaseEvaluation }}
      @else
        —
      @endif

          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('critereEvaluations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-critereEvaluation')
          <x-action-button :entity="$itemCritereEvaluation" actionName="edit">
          @can('update', $itemCritereEvaluation)
              <a href="{{ route('critereEvaluations.edit', ['critereEvaluation' => $itemCritereEvaluation->id]) }}" data-id="{{$itemCritereEvaluation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCompetences::critereEvaluation.singular") }} : {{ $itemCritereEvaluation }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show