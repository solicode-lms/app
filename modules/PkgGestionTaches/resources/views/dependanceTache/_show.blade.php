{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('dependanceTache-show')
<div id="dependanceTache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::tache.singular')) }}</small>
                              
      @if($itemDependanceTache->tache)
        {{ $itemDependanceTache->tache }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::typeDependanceTache.singular')) }}</small>
                              
      @if($itemDependanceTache->typeDependanceTache)
        {{ $itemDependanceTache->typeDependanceTache }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::tache.singular')) }}</small>
                              
      @if($itemDependanceTache->tache)
        {{ $itemDependanceTache->tache }}
      @else
        —
      @endif

          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('dependanceTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-dependanceTache')
          <x-action-button :entity="$itemDependanceTache" actionName="edit">
          @can('update', $itemDependanceTache)
              <a href="{{ route('dependanceTaches.edit', ['dependanceTache' => $itemDependanceTache->id]) }}" data-id="{{$itemDependanceTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgGestionTaches::dependanceTache.singular") }} : {{ $itemDependanceTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show