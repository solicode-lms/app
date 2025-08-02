{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sectionWidget-show')
<div id="sectionWidget-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::sectionWidget.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemSectionWidget->ordre))
          {{ $itemSectionWidget->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::sectionWidget.icone')) }}</small>
                              @if(! is_null($itemSectionWidget->icone) && $itemSectionWidget->icone !== '')
        {{ $itemSectionWidget->icone }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::sectionWidget.titre')) }}</small>
                              @if(! is_null($itemSectionWidget->titre) && $itemSectionWidget->titre !== '')
        {{ $itemSectionWidget->titre }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::sectionWidget.sous_titre')) }}</small>
                              @if(! is_null($itemSectionWidget->sous_titre) && $itemSectionWidget->sous_titre !== '')
        {{ $itemSectionWidget->sous_titre }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemSectionWidget->sysColor)
        @php
          $related = $itemSectionWidget->sysColor;
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
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgWidgets::widget.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgWidgets::widget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sectionWidget.show_' . $itemSectionWidget->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('sectionWidgets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-sectionWidget')
          <x-action-button :entity="$itemSectionWidget" actionName="edit">
          @can('update', $itemSectionWidget)
              <a href="{{ route('sectionWidgets.edit', ['sectionWidget' => $itemSectionWidget->id]) }}" data-id="{{$itemSectionWidget->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgWidgets::sectionWidget.singular") }} : {{ $itemSectionWidget }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show