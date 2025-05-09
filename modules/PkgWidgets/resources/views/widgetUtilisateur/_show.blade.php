{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetUtilisateur-show')
<div id="widgetUtilisateur-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widgetUtilisateur.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemWidgetUtilisateur->ordre))
          {{ $itemWidgetUtilisateur->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widgetUtilisateur.sys_module_id')) }}</small>
                              
      <span>
        @if(! is_null($itemWidgetUtilisateur->sys_module_id))
          {{ number_format($itemWidgetUtilisateur->sys_module_id, 2, '.', '') }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.singular')) }}</small>
                              
      @if($itemWidgetUtilisateur->user)
        {{ $itemWidgetUtilisateur->user }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widget.singular')) }}</small>
                              
      @if($itemWidgetUtilisateur->widget)
        {{ $itemWidgetUtilisateur->widget }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widgetUtilisateur.visible')) }}</small>
                              
      @if($itemWidgetUtilisateur->visible)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('widgetUtilisateurs.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-widgetUtilisateur')
          <x-action-button :entity="$itemWidgetUtilisateur" actionName="edit">
          @can('update', $itemWidgetUtilisateur)
              <a href="{{ route('widgetUtilisateurs.edit', ['widgetUtilisateur' => $itemWidgetUtilisateur->id]) }}" data-id="{{$itemWidgetUtilisateur->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgWidgets::widgetUtilisateur.singular") }} : {{ $itemWidgetUtilisateur }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show