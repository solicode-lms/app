{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('qcm-show')
<div id="qcm-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::qcm.titre')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemQcm->titre) && $itemQcm->titre !== '')
        {{ $itemQcm->titre }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::qcm.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemQcm->description) && $itemQcm->description !== '')
                    {!! $itemQcm->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::qcm.duree_minutes')) }}</small>
                  <span>
                    @if(! is_null($itemQcm->duree_minutes))
                      {{ $itemQcm->duree_minutes }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::qcm.is_duree_limitee')) }}</small>
                  @if($itemQcm->is_duree_limitee)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::qcm.is_publie')) }}</small>
                  @if($itemQcm->is_publie)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemQcm->formateur)
                  {{ $itemQcm->formateur }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-affectationQcmProjet') && $itemQcm->affectationQcmProjets->isNotEmpty())  
                  || auth()->user()?->can('create-affectationQcmProjet')
                  || (auth()->user()?->can('edit-affectationQcmProjet')  && $itemQcm->affectationQcmProjets->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgQcm::affectationQcmProjet.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgQcm::affectationQcmProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'qcm.show_' . $itemQcm->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-questionQcm') && $itemQcm->questionQcms->isNotEmpty())  
                  || auth()->user()?->can('create-questionQcm')
                  || (auth()->user()?->can('edit-questionQcm')  && $itemQcm->questionQcms->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgQcm::questionQcm.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgQcm::questionQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'qcm.show_' . $itemQcm->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-realisationQcm') && $itemQcm->realisationQcms->isNotEmpty())  
                  || auth()->user()?->can('create-realisationQcm')
                  || (auth()->user()?->can('edit-realisationQcm')  && $itemQcm->realisationQcms->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgQcm::realisationQcm.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgQcm::realisationQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'qcm.show_' . $itemQcm->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('qcms.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-qcm')
          <x-action-button :entity="$itemQcm" actionName="edit">
          @can('update', $itemQcm)
              <a href="{{ route('qcms.edit', ['qcm' => $itemQcm->id]) }}" data-id="{{$itemQcm->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgQcm::qcm.singular") }} : {{ $itemQcm }}';
    window.showUIId = 'qcm-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show