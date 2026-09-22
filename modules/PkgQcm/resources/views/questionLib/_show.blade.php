{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('questionLib-show')
<div id="questionLib-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::questionLib.enonce')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemQuestionLib->enonce) && $itemQuestionLib->enonce !== '')
                    {!! $itemQuestionLib->enonce !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::questionLib.type')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemQuestionLib->type) && $itemQuestionLib->type !== '')
        {{ $itemQuestionLib->type }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::questionLib.explication')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemQuestionLib->explication) && $itemQuestionLib->explication !== '')
                    {!! $itemQuestionLib->explication !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::questionLib.is_actif')) }}</small>
                  @if($itemQuestionLib->is_actif)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::uniteApprentissage.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemQuestionLib->uniteApprentissage)
                  {{ $itemQuestionLib->uniteApprentissage }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-propositionReponse') && $itemQuestionLib->propositionReponses->isNotEmpty())  
                  || auth()->user()?->can('create-propositionReponse')
                  || (auth()->user()?->can('edit-propositionReponse')  && $itemQuestionLib->propositionReponses->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgQcm::propositionReponse.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgQcm::propositionReponse._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'questionLib.show_' . $itemQuestionLib->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-questionQcm') && $itemQuestionLib->questionQcms->isNotEmpty())  
                  || auth()->user()?->can('create-questionQcm')
                  || (auth()->user()?->can('edit-questionQcm')  && $itemQuestionLib->questionQcms->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgQcm::questionQcm.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgQcm::questionQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'questionLib.show_' . $itemQuestionLib->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('questionLibs.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-questionLib')
          <x-action-button :entity="$itemQuestionLib" actionName="edit">
          @can('update', $itemQuestionLib)
              <a href="{{ route('questionLibs.edit', ['questionLib' => $itemQuestionLib->id]) }}" data-id="{{$itemQuestionLib->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgQcm::questionLib.singular") }} : {{ $itemQuestionLib }}';
    window.showUIId = 'questionLib-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show