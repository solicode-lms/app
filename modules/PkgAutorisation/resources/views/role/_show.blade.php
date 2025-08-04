{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-show')
<div id="role-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::role.name')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemRole->name) && $itemRole->name !== '')
        {{ $itemRole->name }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::role.guard_name')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemRole->guard_name) && $itemRole->guard_name !== '')
        {{ $itemRole->guard_name }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::permission.plural')) }}</small>
                  <!-- Valeurs many-to-many -->
                  @if($itemRole->permissions->isNotEmpty())
                  <div>
                    @foreach($itemRole->permissions as $permission)
                      <span class="badge badge-info mr-1">
                        {{ $permission }}
                      </span>
                    @endforeach
                  </div>
                  @else
                  <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgWidgets::widget.plural')) }}</small>
                  <!-- Valeurs many-to-many -->
                  @if($itemRole->widgets->isNotEmpty())
                  <div>
                    @foreach($itemRole->widgets as $widget)
                      <span class="badge badge-info mr-1">
                        {{ $widget }}
                      </span>
                    @endforeach
                  </div>
                  @else
                  <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.plural')) }}</small>
                  <!-- Valeurs many-to-many -->
                  @if($itemRole->users->isNotEmpty())
                  <div>
                    @foreach($itemRole->users as $user)
                      <span class="badge badge-info mr-1">
                        {{ $user }}
                      </span>
                    @endforeach
                  </div>
                  @else
                  <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('roles.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-role')
          <x-action-button :entity="$itemRole" actionName="edit">
          @can('update', $itemRole)
              <a href="{{ route('roles.edit', ['role' => $itemRole->id]) }}" data-id="{{$itemRole->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgAutorisation::role.singular") }} : {{ $itemRole }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show