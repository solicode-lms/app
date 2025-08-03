{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widget-show')
<div id="widget-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-2 col-lg-2 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widget.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemWidget->ordre))
          {{ $itemWidget->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widget.icon')) }}</small>
                              @if(! is_null($itemWidget->icon) && $itemWidget->icon !== '')
        {{ $itemWidget->icon }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-4 col-lg-4 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widget.name')) }}</small>
                              @if(! is_null($itemWidget->name) && $itemWidget->name !== '')
        {{ $itemWidget->name }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widget.label')) }}</small>
                              @if(! is_null($itemWidget->label) && $itemWidget->label !== '')
        {{ $itemWidget->label }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widgetType.singular')) }}</small>
                              
      @if($itemWidget->widgetType)
        {{ $itemWidget->widgetType }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysModel.singular')) }}</small>
                              
      @if($itemWidget->sysModel)
        {{ $itemWidget->sysModel }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widgetOperation.singular')) }}</small>
                              
      @if($itemWidget->widgetOperation)
        {{ $itemWidget->widgetOperation }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-4 col-lg-4 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widget.color')) }}</small>
                              @if(! is_null($itemWidget->color) && $itemWidget->color !== '')
        {{ $itemWidget->color }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemWidget->sysColor)
        @php
          $related = $itemWidget->sysColor;
        @endphp
        <span 
          class="badge" 
          style="background-color: {{ $related->hex }}; color: #fff;"
        >
          {{ $related }}
        </span>
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::role.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemWidget->roles->isNotEmpty())
          <div>
            @foreach($itemWidget->roles as $role)
              <span class="badge badge-info mr-1">
                {{ $role }}
              </span>
            @endforeach
          </div>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::sectionWidget.singular')) }}</small>
                              
      @if($itemWidget->sectionWidget)
        {{ $itemWidget->sectionWidget }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgWidgets::widgetUtilisateur.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgWidgets::widgetUtilisateur._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'widget.show_' . $itemWidget->id])
            </div>
          </div>
      </div>
   

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widget.parameters')) }}</small>
                              @if(! is_null($itemWidget->parameters))
          <pre class="border rounded p-2 bg-light" style="overflow:auto;">
      {!! json_encode($itemWidget->parameters, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) !!}
          </pre>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('widgets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-widget')
          <x-action-button :entity="$itemWidget" actionName="edit">
          @can('update', $itemWidget)
              <a href="{{ route('widgets.edit', ['widget' => $itemWidget->id]) }}" data-id="{{$itemWidget->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgWidgets::widget.singular") }} : {{ $itemWidget }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show