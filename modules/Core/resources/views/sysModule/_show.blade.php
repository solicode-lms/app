{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysModule-show')
<div id="sysModule-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModule.name')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemSysModule->name) && $itemSysModule->name !== '')
          {{ $itemSysModule->name }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModule.slug')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemSysModule->slug) && $itemSysModule->slug !== '')
          {{ $itemSysModule->slug }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModule.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemSysModule->description) && $itemSysModule->description !== '')
    {!! $itemSysModule->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModule.is_active')) }}</small>
                              
      <span>
        @if(! is_null($itemSysModule->is_active))
          {{ $itemSysModule->is_active }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModule.order')) }}</small>
                              
      <span>
        @if(! is_null($itemSysModule->order))
          {{ $itemSysModule->order }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModule.version')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemSysModule->version) && $itemSysModule->version !== '')
          {{ $itemSysModule->version }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemSysModule->sysColor)
        @php
          $related = $itemSysModule->sysColor;
        @endphp
        <span 
          class="badge" 
          style="background-color: {{ $related->hex }}; color: #fff;"
        >
          {{ $related->sysColor }}
        </span>
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('sysModules.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-sysModule')
          <x-action-button :entity="$itemSysModule" actionName="edit">
          @can('update', $itemSysModule)
              <a href="{{ route('sysModules.edit', ['sysModule' => $itemSysModule->id]) }}" data-id="{{$itemSysModule->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("Core::sysModule.singular") }} : {{ $itemSysModule }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show