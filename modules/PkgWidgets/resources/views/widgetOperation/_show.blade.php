{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetOperation-show')
<div id="widgetOperation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widgetOperation.operation')) }}</small>
                              @if(! is_null($itemWidgetOperation->operation) && $itemWidgetOperation->operation !== '')
        {{ $itemWidgetOperation->operation }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widgetOperation.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemWidgetOperation->description) && $itemWidgetOperation->description !== '')
    {!! $itemWidgetOperation->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgWidgets::widget.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgWidgets::widget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'widgetOperation.show_' . $itemWidgetOperation->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('widgetOperations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-widgetOperation')
          <x-action-button :entity="$itemWidgetOperation" actionName="edit">
          @can('update', $itemWidgetOperation)
              <a href="{{ route('widgetOperations.edit', ['widgetOperation' => $itemWidgetOperation->id]) }}" data-id="{{$itemWidgetOperation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgWidgets::widgetOperation.singular") }} : {{ $itemWidgetOperation }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show