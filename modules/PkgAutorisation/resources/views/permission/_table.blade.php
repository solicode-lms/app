{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('permission-table')
<div class="card-body table-responsive p-0 crud-card-body" id="permissions-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" modelname="permission" label="{{ ucfirst(__('PkgAutorisation::permission.name')) }}" />
                <x-sortable-column field="controller_id" modelname="permission" label="{{ ucfirst(__('Core::sysController.singular')) }}" />
                <x-sortable-column field="roles" modelname="permission" label="{{ ucfirst(__('PkgAutorisation::role.plural')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('permission-table-tbody')
            @foreach ($permissions_data as $permission)
                <tr id="permission-row-{{$permission->id}}">
                    <td>
                     <span @if(strlen($permission->name) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $permission->name }}" 
                        @endif>
                        {{ Str::limit($permission->name, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($permission->controller) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $permission->controller }}" 
                        @endif>
                        {{ Str::limit($permission->controller, 50) }}
                    </span>
                    </td>
                    <td>
                        <ul>
                            @foreach ($permission->roles as $role)
                                <li @if(strlen($role) > 40) data-toggle="tooltip" title="{{$role}}"  @endif>@limit($role, 40)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">

                        @can('show-permission')
                        @can('view', $permission)
                            <a href="{{ route('permissions.show', ['permission' => $permission->id]) }}" data-id="{{$permission->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-permission')
                        @can('update', $permission)
                            <a href="{{ route('permissions.edit', ['permission' => $permission->id]) }}" data-id="{{$permission->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-permission')
                        @can('delete', $permission)
                            <form class="context-state" action="{{ route('permissions.destroy',['permission' => $permission->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$permission->id}}">
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
    @section('permission-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $permissions_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>