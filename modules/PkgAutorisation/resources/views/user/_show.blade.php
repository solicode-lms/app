{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('user-show')
<div id="user-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.name')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemUser->name) && $itemUser->name !== '')
          {{ $itemUser->name }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.email')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemUser->email) && $itemUser->email !== '')
          {{ $itemUser->email }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.email_verified_at')) }}</small>
                            
    <span>
      @if ($itemUser->email_verified_at)
        {{ \Carbon\Carbon::parse($itemUser->email_verified_at)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.password')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemUser->password) && $itemUser->password !== '')
          {{ $itemUser->password }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.must_change_password')) }}</small>
                              
      @if($itemUser->must_change_password)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.remember_token')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemUser->remember_token) && $itemUser->remember_token !== '')
          {{ $itemUser->remember_token }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::role.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemUser->roles->isNotEmpty())
          <div>
            @foreach($itemUser->roles as $role)
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
          <a href="{{ route('users.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-user')
          <x-action-button :entity="$itemUser" actionName="edit">
          @can('update', $itemUser)
              <a href="{{ route('users.edit', ['user' => $itemUser->id]) }}" data-id="{{$itemUser->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgAutorisation::user.singular") }} : {{ $itemUser }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show