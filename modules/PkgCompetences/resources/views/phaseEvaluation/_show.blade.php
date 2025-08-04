{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('phaseEvaluation-show')
<div id="phaseEvaluation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::phaseEvaluation.ordre')) }}</small>
                  <span>
                    @if(! is_null($itemPhaseEvaluation->ordre))
                      {{ $itemPhaseEvaluation->ordre }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::phaseEvaluation.code')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemPhaseEvaluation->code) && $itemPhaseEvaluation->code !== '')
        {{ $itemPhaseEvaluation->code }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::phaseEvaluation.libelle')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemPhaseEvaluation->libelle) && $itemPhaseEvaluation->libelle !== '')
        {{ $itemPhaseEvaluation->libelle }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::phaseEvaluation.coefficient')) }}</small>
                  <span>
                  @if(! is_null($itemPhaseEvaluation->coefficient))
                  {{ number_format($itemPhaseEvaluation->coefficient, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::phaseEvaluation.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemPhaseEvaluation->description) && $itemPhaseEvaluation->description !== '')
                    {!! $itemPhaseEvaluation->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgCompetences::critereEvaluation.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgCompetences::critereEvaluation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'phaseEvaluation.show_' . $itemPhaseEvaluation->id])
                  </div>
                  </div>
            </div>

            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgCreationTache::tache.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgCreationTache::tache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'phaseEvaluation.show_' . $itemPhaseEvaluation->id])
                  </div>
                  </div>
            </div>

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('phaseEvaluations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-phaseEvaluation')
          <x-action-button :entity="$itemPhaseEvaluation" actionName="edit">
          @can('update', $itemPhaseEvaluation)
              <a href="{{ route('phaseEvaluations.edit', ['phaseEvaluation' => $itemPhaseEvaluation->id]) }}" data-id="{{$itemPhaseEvaluation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCompetences::phaseEvaluation.singular") }} : {{ $itemPhaseEvaluation }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show