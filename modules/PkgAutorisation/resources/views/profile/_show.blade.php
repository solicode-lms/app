{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('profile-show')
<div id="profile-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ 'Ancien mot de passe' }}</small>
                                <!-- Valeur masquée -->
        @if(! empty($itemProfile->old_password))
          <span>••••••••</span>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

        <!-- Valeur masquée -->
        @if(! empty($itemProfile->password))
          <span>••••••••</span>
        @else
          <span class="text-muted">—</span>
        @endif

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.singular')) }}</small>
                              
      @if($itemProfile->user)
        {{ $itemProfile->user }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::profile.phone')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemProfile->phone) && $itemProfile->phone !== '')
          {{ $itemProfile->phone }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('profiles.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-profile')
          <x-action-button :entity="$itemProfile" actionName="edit">
          @can('update', $itemProfile)
              <a href="{{ route('profiles.edit', ['profile' => $itemProfile->id]) }}" data-id="{{$itemProfile->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgAutorisation::profile.singular") }} : {{ $itemProfile }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show