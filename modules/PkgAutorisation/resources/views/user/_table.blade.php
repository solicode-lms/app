{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('user-table')
<div class="card-body table-responsive p-0 crud-card-body" id="users-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" modelname="user" label="{{ ucfirst(__('PkgAutorisation::user.name')) }}" />
                <x-sortable-column field="email" modelname="user" label="{{ ucfirst(__('PkgAutorisation::user.email')) }}" />
                <x-sortable-column field="roles" modelname="user" label="{{ ucfirst(__('PkgAutorisation::role.plural')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('user-table-tbody')
            @foreach ($users_data as $user)
                <tr id="user-row-{{$user->id}}">
                    <td>
                     <span @if(strlen($user->name) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $user->name }}" 
                        @endif>
                        {{ Str::limit($user->name, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($user->email) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $user->email }}" 
                        @endif>
                        {{ Str::limit($user->email, 40) }}
                    </span>
                    </td>
                    <td>
                        <ul>
                            @foreach ($user->roles as $role)
                                <li @if(strlen($role) > 40) data-toggle="tooltip" title="{{$role}}"  @endif>@limit($role, 40)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">
                       @can('initPassword-user')
                        <a 
                        data-toggle="tooltip" 
                        title="Initialiser le mot de passe" 
                        href="{{ route('users.initPassword', ['id' => $user->id]) }}" 
                        data-id="{{$user->id}}" 
                        data-url="{{ route('users.initPassword', ['id' => $user->id]) }}" 
                        data-action-type="confirm"
                        class="btn btn-default btn-sm context-state actionEntity">
                            <i class="fas fa-unlock-alt"></i>
                        </a>
                        @endcan
                        
                        @can('show-user')
                        @can('view', $user)
                            <a href="{{ route('users.show', ['user' => $user->id]) }}" data-id="{{$user->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-user')
                        @can('update', $user)
                            <a href="{{ route('users.edit', ['user' => $user->id]) }}" data-id="{{$user->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-user')
                        @can('delete', $user)
                            <form class="context-state" action="{{ route('users.destroy',['user' => $user->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$user->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
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