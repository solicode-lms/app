{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('bordereauxOfficiel-show')
<div id="bordereauxOfficiel-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::module.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemBordereauxOfficiel->module)
                  {{ $itemBordereauxOfficiel->module }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::groupe.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemBordereauxOfficiel->groupe)
                  {{ $itemBordereauxOfficiel->groupe }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::anneeFormation.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemBordereauxOfficiel->anneeFormation)
                  {{ $itemBordereauxOfficiel->anneeFormation }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgTableauBord::bordereauxOfficiel.nb_apprenants')) }}</small>
                  <span>
                    @if(! is_null($itemBordereauxOfficiel->nb_apprenants))
                      {{ $itemBordereauxOfficiel->nb_apprenants }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgTableauBord::bordereauxOfficiel.taux_conformite_calcul')) }}</small>
                  <span>
                  @if(! is_null($itemBordereauxOfficiel->taux_conformite_calcul))
                  {{ number_format($itemBordereauxOfficiel->taux_conformite_calcul, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgTableauBord::bordereauxOfficiel.statut')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemBordereauxOfficiel->statut) && $itemBordereauxOfficiel->statut !== '')
        {{ $itemBordereauxOfficiel->statut }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgTableauBord::bordereauxOfficiel.date_scellement')) }}</small>
                  <span>
                    @if ($itemBordereauxOfficiel->date_scellement)
                    {{ \Carbon\Carbon::parse($itemBordereauxOfficiel->date_scellement)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgTableauBord::bordereauxOfficiel.hash_signature')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemBordereauxOfficiel->hash_signature) && $itemBordereauxOfficiel->hash_signature !== '')
        {{ $itemBordereauxOfficiel->hash_signature }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemBordereauxOfficiel->genereParUser)
                  {{ $itemBordereauxOfficiel->genereParUser }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('bordereauxOfficiels.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-bordereauxOfficiel')
          <x-action-button :entity="$itemBordereauxOfficiel" actionName="edit">
          @can('update', $itemBordereauxOfficiel)
              <a href="{{ route('bordereauxOfficiels.edit', ['bordereauxOfficiel' => $itemBordereauxOfficiel->id]) }}" data-id="{{$itemBordereauxOfficiel->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgTableauBord::bordereauxOfficiel.singular") }} : {{ $itemBordereauxOfficiel }}';
    window.showUIId = 'bordereauxOfficiel-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show