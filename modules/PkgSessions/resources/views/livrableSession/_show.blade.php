{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrableSession-show')
<div id="livrableSession-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::livrableSession.ordre')) }}</small>
                  <span>
                    @if(! is_null($itemLivrableSession->ordre))
                      {{ $itemLivrableSession->ordre }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::livrableSession.titre')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemLivrableSession->titre) && $itemLivrableSession->titre !== '')
        {{ $itemLivrableSession->titre }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::livrableSession.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemLivrableSession->description) && $itemLivrableSession->description !== '')
                    {!! $itemLivrableSession->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemLivrableSession->sessionFormation)
                  {{ $itemLivrableSession->sessionFormation }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::natureLivrable.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemLivrableSession->natureLivrable)
                  {{ $itemLivrableSession->natureLivrable }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('livrableSessions.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-livrableSession')
          <x-action-button :entity="$itemLivrableSession" actionName="edit">
          @can('update', $itemLivrableSession)
              <a href="{{ route('livrableSessions.edit', ['livrableSession' => $itemLivrableSession->id]) }}" data-id="{{$itemLivrableSession->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgSessions::livrableSession.singular") }} : {{ $itemLivrableSession }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show