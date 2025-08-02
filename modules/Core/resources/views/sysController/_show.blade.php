{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysController-show')
<div id="sysController-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModule.singular')) }}</small>
                              
      @if($itemSysController->sysModule)
        {{ $itemSysController->sysModule }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysController.name')) }}</small>
                              @if(! is_null($itemSysController->name) && $itemSysController->name !== '')
        {{ $itemSysController->name }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysController.slug')) }}</small>
                              @if(! is_null($itemSysController->slug) && $itemSysController->slug !== '')
        {{ $itemSysController->slug }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysController.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemSysController->description) && $itemSysController->description !== '')
    {!! $itemSysController->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysController.is_active')) }}</small>
                              
      @if($itemSysController->is_active)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgAutorisation::permission.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgAutorisation::permission._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysController.show_' . $itemSysController->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('sysControllers.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-sysController')
          <x-action-button :entity="$itemSysController" actionName="edit">
          @can('update', $itemSysController)
              <a href="{{ route('sysControllers.edit', ['sysController' => $itemSysController->id]) }}" data-id="{{$itemSysController->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("Core::sysController.singular") }} : {{ $itemSysController }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show