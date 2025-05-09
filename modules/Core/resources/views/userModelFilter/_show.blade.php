{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('userModelFilter-show')
<div id="userModelFilter-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.singular')) }}</small>
                              
      @if($itemUserModelFilter->user)
        {{ $itemUserModelFilter->user }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::userModelFilter.model_name')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemUserModelFilter->model_name) && $itemUserModelFilter->model_name !== '')
          {{ $itemUserModelFilter->model_name }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::userModelFilter.filters')) }}</small>
                              @if(! is_null($itemUserModelFilter->filters))
          <pre class="border rounded p-2 bg-light" style="overflow:auto;">
      {!! json_encode($itemUserModelFilter->filters, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) !!}
          </pre>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('userModelFilters.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-userModelFilter')
          <x-action-button :entity="$itemUserModelFilter" actionName="edit">
          @can('update', $itemUserModelFilter)
              <a href="{{ route('userModelFilters.edit', ['userModelFilter' => $itemUserModelFilter->id]) }}" data-id="{{$itemUserModelFilter->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("Core::userModelFilter.singular") }} : {{ $itemUserModelFilter }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show