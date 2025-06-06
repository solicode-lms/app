{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('permission-show')
<div id="permission-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::permission.name')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemPermission->name) && $itemPermission->name !== '')
          {{ $itemPermission->name }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::permission.guard_name')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemPermission->guard_name) && $itemPermission->guard_name !== '')
          {{ $itemPermission->guard_name }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysController.singular')) }}</small>
                              
      @if($itemPermission->sysController)
        {{ $itemPermission->sysController }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::feature.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemPermission->features->isNotEmpty())
          <div>
            @foreach($itemPermission->features as $feature)
              <span class="badge badge-info mr-1">
                {{ $feature }}
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
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::role.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemPermission->roles->isNotEmpty())
          <div>
            @foreach($itemPermission->roles as $role)
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
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('permissions.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-permission')
          <x-action-button :entity="$itemPermission" actionName="edit">
          @can('update', $itemPermission)
              <a href="{{ route('permissions.edit', ['permission' => $itemPermission->id]) }}" data-id="{{$itemPermission->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgAutorisation::permission.singular") }} : {{ $itemPermission }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show