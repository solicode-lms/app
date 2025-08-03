{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetType-show')
<div id="widgetType-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widgetType.type')) }}</small>
                              @if(! is_null($itemWidgetType->type) && $itemWidgetType->type !== '')
        {{ $itemWidgetType->type }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widgetType.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemWidgetType->description) && $itemWidgetType->description !== '')
    {!! $itemWidgetType->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgWidgets::widget.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgWidgets::widget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'widgetType.show_' . $itemWidgetType->id])
            </div>
          </div>
      </div>
   


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('widgetTypes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-widgetType')
          <x-action-button :entity="$itemWidgetType" actionName="edit">
          @can('update', $itemWidgetType)
              <a href="{{ route('widgetTypes.edit', ['widgetType' => $itemWidgetType->id]) }}" data-id="{{$itemWidgetType->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgWidgets::widgetType.singular") }} : {{ $itemWidgetType }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show