{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('ville-show')
<div id="ville-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::ville.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemVille->nom) && $itemVille->nom !== '')
          {{ $itemVille->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('villes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-ville')
          <x-action-button :entity="$itemVille" actionName="edit">
          @can('update', $itemVille)
              <a href="{{ route('villes.edit', ['ville' => $itemVille->id]) }}" data-id="{{$itemVille->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprenants::ville.singular") }} : {{ $itemVille }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show