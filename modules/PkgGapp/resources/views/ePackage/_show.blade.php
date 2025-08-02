{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('ePackage-show')
<div id="ePackage-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::ePackage.name')) }}</small>
                              @if(! is_null($itemEPackage->name) && $itemEPackage->name !== '')
        {{ $itemEPackage->name }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::ePackage.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemEPackage->description) && $itemEPackage->description !== '')
    {!! $itemEPackage->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgGapp::eModel.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgGapp::eModel._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'ePackage.show_' . $itemEPackage->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('ePackages.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-ePackage')
          <x-action-button :entity="$itemEPackage" actionName="edit">
          @can('update', $itemEPackage)
              <a href="{{ route('ePackages.edit', ['ePackage' => $itemEPackage->id]) }}" data-id="{{$itemEPackage->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgGapp::ePackage.singular") }} : {{ $itemEPackage }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show