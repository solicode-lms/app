{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluationRealisationProjet-show')
<div id="evaluationRealisationProjet-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemEvaluationRealisationProjet->realisationProjet)
                  {{ $itemEvaluationRealisationProjet->realisationProjet }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluateur.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemEvaluationRealisationProjet->evaluateur)
                  {{ $itemEvaluationRealisationProjet->evaluateur }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluationRealisationProjet.date_evaluation')) }}</small>
                  <span>
                    @if ($itemEvaluationRealisationProjet->date_evaluation)
                    {{ \Carbon\Carbon::parse($itemEvaluationRealisationProjet->date_evaluation)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.singular')) }}</small>

                {{-- Affichage sous forme de badge --}}
                @if($itemEvaluationRealisationProjet->etatEvaluationProjet)
                  <x-badge 
                    :text="$itemEvaluationRealisationProjet->etatEvaluationProjet" 
                    :background="$itemEvaluationRealisationProjet->etatEvaluationProjet->sysColor->hex ?? '#6c757d'" 
                  />
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-evaluationRealisationTache') && $itemEvaluationRealisationProjet->evaluationRealisationTaches->isNotEmpty())  
                  || auth()->user()?->can('create-evaluationRealisationTache')
                  || (auth()->user()?->can('edit-evaluationRealisationTache')  && $itemEvaluationRealisationProjet->evaluationRealisationTaches->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgEvaluateurs::evaluationRealisationTache.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgEvaluateurs::evaluationRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'evaluationRealisationProjet.show_' . $itemEvaluationRealisationProjet->id])
                  </div>
                  </div>
            </div>
            @endif

            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluationRealisationProjet.remarques')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemEvaluationRealisationProjet->remarques) && $itemEvaluationRealisationProjet->remarques !== '')
                    {!! $itemEvaluationRealisationProjet->remarques !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('evaluationRealisationProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-evaluationRealisationProjet')
          <x-action-button :entity="$itemEvaluationRealisationProjet" actionName="edit">
          @can('update', $itemEvaluationRealisationProjet)
              <a href="{{ route('evaluationRealisationProjets.edit', ['evaluationRealisationProjet' => $itemEvaluationRealisationProjet->id]) }}" data-id="{{$itemEvaluationRealisationProjet->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgEvaluateurs::evaluationRealisationProjet.singular") }} : {{ $itemEvaluationRealisationProjet }}';
    window.showUIId = 'evaluationRealisationProjet-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show