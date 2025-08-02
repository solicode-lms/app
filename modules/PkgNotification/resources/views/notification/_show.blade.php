{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('notification-show')
<div id="notification-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgNotification::notification.title')) }}</small>
                              @if(! is_null($itemNotification->title) && $itemNotification->title !== '')
        {{ $itemNotification->title }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgNotification::notification.type')) }}</small>
                              @if(! is_null($itemNotification->type) && $itemNotification->type !== '')
        {{ $itemNotification->type }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgNotification::notification.message')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemNotification->message) && $itemNotification->message !== '')
    {!! $itemNotification->message !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgNotification::notification.sent_at')) }}</small>
                            
    <span>
      @if ($itemNotification->sent_at)
        {{ \Carbon\Carbon::parse($itemNotification->sent_at)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgNotification::notification.is_read')) }}</small>
                              
      @if($itemNotification->is_read)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.singular')) }}</small>
                              
      @if($itemNotification->user)
        {{ $itemNotification->user }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgNotification::notification.data')) }}</small>
                              @if(! is_null($itemNotification->data))
          <pre class="border rounded p-2 bg-light" style="overflow:auto;">
      {!! json_encode($itemNotification->data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) !!}
          </pre>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('notifications.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-notification')
          <x-action-button :entity="$itemNotification" actionName="edit">
          @can('update', $itemNotification)
              <a href="{{ route('notifications.edit', ['notification' => $itemNotification->id]) }}" data-id="{{$itemNotification->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgNotification::notification.singular") }} : {{ $itemNotification }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show