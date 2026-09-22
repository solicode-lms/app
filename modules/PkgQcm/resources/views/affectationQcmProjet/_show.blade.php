{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('affectationQcmProjet-show')
<div id="affectationQcmProjet-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemAffectationQcmProjet->affectationProjet)
                  {{ $itemAffectationQcmProjet->affectationProjet }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::qcm.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemAffectationQcmProjet->qcm)
                  {{ $itemAffectationQcmProjet->qcm }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-realisationQcm') && $itemAffectationQcmProjet->realisationQcms->isNotEmpty())  
                  || auth()->user()?->can('create-realisationQcm')
                  || (auth()->user()?->can('edit-realisationQcm')  && $itemAffectationQcmProjet->realisationQcms->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgQcm::realisationQcm.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgQcm::realisationQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'affectationQcmProjet.show_' . $itemAffectationQcmProjet->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('affectationQcmProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-affectationQcmProjet')
          <x-action-button :entity="$itemAffectationQcmProjet" actionName="edit">
          @can('update', $itemAffectationQcmProjet)
              <a href="{{ route('affectationQcmProjets.edit', ['affectationQcmProjet' => $itemAffectationQcmProjet->id]) }}" data-id="{{$itemAffectationQcmProjet->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgQcm::affectationQcmProjet.singular") }} : {{ $itemAffectationQcmProjet }}';
    window.showUIId = 'affectationQcmProjet-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show