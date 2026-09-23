{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('question-show')
<div id="question-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::question.ordre')) }}</small>
                  <span>
                    @if(! is_null($itemQuestion->ordre))
                      {{ $itemQuestion->ordre }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::question.enonce')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemQuestion->enonce) && $itemQuestion->enonce !== '')
                    {!! $itemQuestion->enonce !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::question.type')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemQuestion->type) && $itemQuestion->type !== '')
        {{ $itemQuestion->type }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::question.explication')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemQuestion->explication) && $itemQuestion->explication !== '')
                    {!! $itemQuestion->explication !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::question.is_actif')) }}</small>
                  @if($itemQuestion->is_actif)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::question.bareme')) }}</small>
                  <span>
                  @if(! is_null($itemQuestion->bareme))
                  {{ number_format($itemQuestion->bareme, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::qcm.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemQuestion->qcm)
                  {{ $itemQuestion->qcm }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::uniteApprentissage.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemQuestion->uniteApprentissage)
                  {{ $itemQuestion->uniteApprentissage }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-propositionReponse') && $itemQuestion->propositionReponses->isNotEmpty())  
                  || auth()->user()?->can('create-propositionReponse')
                  || (auth()->user()?->can('edit-propositionReponse')  && $itemQuestion->propositionReponses->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgQcm::propositionReponse.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgQcm::propositionReponse._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'question.show_' . $itemQuestion->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-reponseQcm') && $itemQuestion->reponseQcms->isNotEmpty())  
                  || auth()->user()?->can('create-reponseQcm')
                  || (auth()->user()?->can('edit-reponseQcm')  && $itemQuestion->reponseQcms->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgQcm::reponseQcm.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgQcm::reponseQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'question.show_' . $itemQuestion->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('questions.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-question')
          <x-action-button :entity="$itemQuestion" actionName="edit">
          @can('update', $itemQuestion)
              <a href="{{ route('questions.edit', ['question' => $itemQuestion->id]) }}" data-id="{{$itemQuestion->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgQcm::question.singular") }} : {{ $itemQuestion }}';
    window.showUIId = 'question-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show