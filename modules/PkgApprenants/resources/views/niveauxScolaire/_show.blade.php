{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauxScolaire-show')
<div id="niveauxScolaire-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::niveauxScolaire.code')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemNiveauxScolaire->code) && $itemNiveauxScolaire->code !== '')
          {{ $itemNiveauxScolaire->code }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::niveauxScolaire.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemNiveauxScolaire->nom) && $itemNiveauxScolaire->nom !== '')
          {{ $itemNiveauxScolaire->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::niveauxScolaire.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemNiveauxScolaire->description) && $itemNiveauxScolaire->description !== '')
    {!! $itemNiveauxScolaire->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('niveauxScolaires.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-niveauxScolaire')
          <x-action-button :entity="$itemNiveauxScolaire" actionName="edit">
          @can('update', $itemNiveauxScolaire)
              <a href="{{ route('niveauxScolaires.edit', ['niveauxScolaire' => $itemNiveauxScolaire->id]) }}" data-id="{{$itemNiveauxScolaire->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprenants::niveauxScolaire.singular") }} : {{ $itemNiveauxScolaire }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show