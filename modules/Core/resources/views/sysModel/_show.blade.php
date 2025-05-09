{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysModel-show')
<div id="sysModel-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModel.name')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemSysModel->name) && $itemSysModel->name !== '')
          {{ $itemSysModel->name }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModel.model')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemSysModel->model) && $itemSysModel->model !== '')
          {{ $itemSysModel->model }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModule.singular')) }}</small>
                              
      @if($itemSysModel->sysModule)
        {{ $itemSysModel->sysModule }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemSysModel->sysColor)
        @php
          $related = $itemSysModel->sysColor;
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
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModel.icone')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemSysModel->icone) && $itemSysModel->icone !== '')
          {{ $itemSysModel->icone }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModel.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemSysModel->description) && $itemSysModel->description !== '')
    {!! $itemSysModel->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('sysModels.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-sysModel')
          <x-action-button :entity="$itemSysModel" actionName="edit">
          @can('update', $itemSysModel)
              <a href="{{ route('sysModels.edit', ['sysModel' => $itemSysModel->id]) }}" data-id="{{$itemSysModel->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("Core::sysModel.singular") }} : {{ $itemSysModel }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show