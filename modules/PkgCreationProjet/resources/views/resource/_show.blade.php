{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('resource-show')
<div id="resource-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::resource.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemResource->nom) && $itemResource->nom !== '')
        {{ $itemResource->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::resource.lien')) }}</small>
    {{-- Lien cliquable --}}
    @if(!is_null($itemResource->lien) && $itemResource->lien !== '')
        <a href="{{ $itemResource->lien }}" target="_blank">
            <i class="fas fa-link mr-1"></i>
            {{ $itemResource->lien }}
        </a>
    @else
        <span class="text-muted">—</span>
    @endif

                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::resource.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemResource->description) && $itemResource->description !== '')
                    {!! $itemResource->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemResource->projet)
                  {{ $itemResource->projet }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('resources.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-resource')
          <x-action-button :entity="$itemResource" actionName="edit">
          @can('update', $itemResource)
              <a href="{{ route('resources.edit', ['resource' => $itemResource->id]) }}" data-id="{{$itemResource->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCreationProjet::resource.singular") }} : {{ $itemResource }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show