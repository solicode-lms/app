{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationUaProjet-show')
<div id="realisationUaProjet-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUaProjet.realisation_tache_id')) }}</small>
@include('PkgApprentissage::realisationUaProjet.custom.fields.realisationTache',['entity' => $itemRealisationUaProjet])
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUa.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationUaProjet->realisationUa)
                  {{ $itemRealisationUaProjet->realisationUa }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUaProjet.note')) }}</small>
@include('PkgApprentissage::realisationUaProjet.custom.fields.note',['entity' => $itemRealisationUaProjet])
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUaProjet.bareme')) }}</small>
                  <span>
                  @if(! is_null($itemRealisationUaProjet->bareme))
                  {{ number_format($itemRealisationUaProjet->bareme, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUaProjet.remarque_formateur')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemRealisationUaProjet->remarque_formateur) && $itemRealisationUaProjet->remarque_formateur !== '')
                    {!! $itemRealisationUaProjet->remarque_formateur !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUaProjet.date_debut')) }}</small>
                  <span>
                    @if ($itemRealisationUaProjet->date_debut)
                    {{ \Carbon\Carbon::parse($itemRealisationUaProjet->date_debut)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUaProjet.date_fin')) }}</small>
                  <span>
                    @if ($itemRealisationUaProjet->date_fin)
                    {{ \Carbon\Carbon::parse($itemRealisationUaProjet->date_fin)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationUaProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationUaProjet')
          <x-action-button :entity="$itemRealisationUaProjet" actionName="edit">
          @can('update', $itemRealisationUaProjet)
              <a href="{{ route('realisationUaProjets.edit', ['realisationUaProjet' => $itemRealisationUaProjet->id]) }}" data-id="{{$itemRealisationUaProjet->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::realisationUaProjet.singular") }} : {{ $itemRealisationUaProjet }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show