{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('historiqueRealisationTache-show')
<div id="historiqueRealisationTache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::historiqueRealisationTache.changement')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemHistoriqueRealisationTache->changement) && $itemHistoriqueRealisationTache->changement !== '')
                    {!! $itemHistoriqueRealisationTache->changement !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::historiqueRealisationTache.dateModification')) }}</small>
@include('PkgRealisationTache::historiqueRealisationTache.custom.fields.dateModification',['entity' => $itemHistoriqueRealisationTache])
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemHistoriqueRealisationTache->realisationTache)
                  {{ $itemHistoriqueRealisationTache->realisationTache }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemHistoriqueRealisationTache->user)
                  {{ $itemHistoriqueRealisationTache->user }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::historiqueRealisationTache.isFeedback')) }}</small>
                  @if($itemHistoriqueRealisationTache->isFeedback)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('historiqueRealisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-historiqueRealisationTache')
          <x-action-button :entity="$itemHistoriqueRealisationTache" actionName="edit">
          @can('update', $itemHistoriqueRealisationTache)
              <a href="{{ route('historiqueRealisationTaches.edit', ['historiqueRealisationTache' => $itemHistoriqueRealisationTache->id]) }}" data-id="{{$itemHistoriqueRealisationTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationTache::historiqueRealisationTache.singular") }} : {{ $itemHistoriqueRealisationTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show