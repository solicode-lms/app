{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('user-table')
<div class="card-body p-0 crud-card-body" id="users-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $users_permissions['edit-user'] || $users_permissions['destroy-user'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="name" modelname="user" label="{!!ucfirst(__('PkgAutorisation::user.name'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="email" modelname="user" label="{!!ucfirst(__('PkgAutorisation::user.email'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="roles" modelname="user" label="{!!ucfirst(__('PkgAutorisation::role.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('user-table-tbody')
            @foreach ($users_data as $user)
                @php
                    $isEditable = $users_permissions['edit-user'] && $users_permissionsByItem['update'][$user->id];
                @endphp
                <tr id="user-row-{{$user->id}}" data-id="{{$user->id}}">
                    <x-checkbox-row :item="$user" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$user->id}}" data-field="name"  data-toggle="tooltip" title="{{ $user->name }}" >
                        {{ $user->name }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$user->id}}" data-field="email"  data-toggle="tooltip" title="{{ $user->email }}" >
                        {{ $user->email }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$user->id}}" data-field="roles"  data-toggle="tooltip" title="{{ $user->roles }}" >
                        <ul>
                            @foreach ($user->roles as $role)
                                <li @if(strlen($role) > 30) data-toggle="tooltip" title="{{$role}}"  @endif>@limit($role, 30)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">
                        @if($users_permissions['initPassword-user'])
                        <x-action-button :entity="$user" actionName="initPassword">
                            <a 
                            data-toggle="tooltip" 
                            title="Initialiser le mot de passe" 
                            href="{{ route('users.initPassword', ['id' => $user->id]) }}" 
                            data-id="{{$user->id}}" 
                            data-url="{{ route('users.initPassword', ['id' => $user->id]) }}" 
                            data-action-type="confirm"
                            class="btn btn-default btn-sm d-none d-md-inline d-lg-inline  context-state actionEntity">
                                <i class="fa-unlock-alt"></i>
                            </a>
                        </x-action-button>
                        @endif
                        

                       

                        @if($users_permissions['edit-user'])
                        <x-action-button :entity="$user" actionName="edit">
                        @if($users_permissionsByItem['update'][$user->id])
                            <a href="{{ route('users.edit', ['user' => $user->id]) }}" data-id="{{$user->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($users_permissions['show-user'])
                        <x-action-button :entity="$user" actionName="show">
                        @if($users_permissionsByItem['view'][$user->id])
                            <a href="{{ route('users.show', ['user' => $user->id]) }}" data-id="{{$user->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$user" actionName="delete">
                        @if($users_permissions['destroy-user'])
                        @if($users_permissionsByItem['delete'][$user->id])
                            <form class="context-state" action="{{ route('users.destroy',['user' => $user->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$user->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                        @endif
                        </x-action-button>
                    </td>
                </tr>
            @endforeach
            @show
        </tbody>
    </table>
</div>
@show

<div class="card-footer">
    @section('user-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $users_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>