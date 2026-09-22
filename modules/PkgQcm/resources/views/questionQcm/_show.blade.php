{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('questionQcm-show')
<div id="questionQcm-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::questionQcm.ordre')) }}</small>
                  <span>
                    @if(! is_null($itemQuestionQcm->ordre))
                      {{ $itemQuestionQcm->ordre }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::questionQcm.bareme')) }}</small>
                  <span>
                  @if(! is_null($itemQuestionQcm->bareme))
                  {{ number_format($itemQuestionQcm->bareme, 2, '.', '') }}
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
                @if($itemQuestionQcm->qcm)
                  {{ $itemQuestionQcm->qcm }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::questionLib.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemQuestionQcm->questionLib)
                  {{ $itemQuestionQcm->questionLib }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-reponseQcm') && $itemQuestionQcm->reponseQcms->isNotEmpty())  
                  || auth()->user()?->can('create-reponseQcm')
                  || (auth()->user()?->can('edit-reponseQcm')  && $itemQuestionQcm->reponseQcms->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgQcm::reponseQcm.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgQcm::reponseQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'questionQcm.show_' . $itemQuestionQcm->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('questionQcms.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-questionQcm')
          <x-action-button :entity="$itemQuestionQcm" actionName="edit">
          @can('update', $itemQuestionQcm)
              <a href="{{ route('questionQcms.edit', ['questionQcm' => $itemQuestionQcm->id]) }}" data-id="{{$itemQuestionQcm->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgQcm::questionQcm.singular") }} : {{ $itemQuestionQcm }}';
    window.showUIId = 'questionQcm-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show