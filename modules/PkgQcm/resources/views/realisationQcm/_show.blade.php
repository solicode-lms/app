{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationQcm-show')
<div id="realisationQcm-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::realisationQcm.date_debut')) }}</small>
                  <span>
                    @if ($itemRealisationQcm->date_debut)
                    {{ \Carbon\Carbon::parse($itemRealisationQcm->date_debut)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::realisationQcm.date_fin')) }}</small>
                  <span>
                    @if ($itemRealisationQcm->date_fin)
                    {{ \Carbon\Carbon::parse($itemRealisationQcm->date_fin)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::realisationQcm.date_soumission')) }}</small>
                  <span>
                    @if ($itemRealisationQcm->date_soumission)
                    {{ \Carbon\Carbon::parse($itemRealisationQcm->date_soumission)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::realisationQcm.statut')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemRealisationQcm->statut) && $itemRealisationQcm->statut !== '')
        {{ $itemRealisationQcm->statut }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::realisationQcm.date_validation')) }}</small>
                  <span>
                    @if ($itemRealisationQcm->date_validation)
                    {{ \Carbon\Carbon::parse($itemRealisationQcm->date_validation)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::affectationQcmProjet.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationQcm->affectationQcmProjet)
                  {{ $itemRealisationQcm->affectationQcmProjet }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::etatRealisationQcm.singular')) }}</small>

                {{-- Affichage sous forme de badge --}}
                @if($itemRealisationQcm->etatRealisationQcm)
                  <x-badge 
                    :text="$itemRealisationQcm->etatRealisationQcm" 
                    :background="$itemRealisationQcm->etatRealisationQcm->sysColor->hex ?? '#6c757d'" 
                  />
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::qcm.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationQcm->qcm)
                  {{ $itemRealisationQcm->qcm }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationQcm->apprenant)
                  {{ $itemRealisationQcm->apprenant }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-reponseQcm') && $itemRealisationQcm->reponseQcms->isNotEmpty())  
                  || auth()->user()?->can('create-reponseQcm')
                  || (auth()->user()?->can('edit-reponseQcm')  && $itemRealisationQcm->reponseQcms->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgQcm::reponseQcm.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgQcm::reponseQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationQcm.show_' . $itemRealisationQcm->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationQcms.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationQcm')
          <x-action-button :entity="$itemRealisationQcm" actionName="edit">
          @can('update', $itemRealisationQcm)
              <a href="{{ route('realisationQcms.edit', ['realisationQcm' => $itemRealisationQcm->id]) }}" data-id="{{$itemRealisationQcm->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgQcm::realisationQcm.singular") }} : {{ $itemRealisationQcm }}';
    window.showUIId = 'realisationQcm-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show