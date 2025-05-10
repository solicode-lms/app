{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('typeDependanceTache-show')
<div id="typeDependanceTache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::typeDependanceTache.titre')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemTypeDependanceTache->titre) && $itemTypeDependanceTache->titre !== '')
          {{ $itemTypeDependanceTache->titre }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::typeDependanceTache.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemTypeDependanceTache->description) && $itemTypeDependanceTache->description !== '')
    {!! $itemTypeDependanceTache->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgGestionTaches::dependanceTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgGestionTaches::dependanceTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'typeDependanceTache.show_' . $itemTypeDependanceTache->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('typeDependanceTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-typeDependanceTache')
          <x-action-button :entity="$itemTypeDependanceTache" actionName="edit">
          @can('update', $itemTypeDependanceTache)
              <a href="{{ route('typeDependanceTaches.edit', ['typeDependanceTache' => $itemTypeDependanceTache->id]) }}" data-id="{{$itemTypeDependanceTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgGestionTaches::typeDependanceTache.singular") }} : {{ $itemTypeDependanceTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show