{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-table')
<div class="card-body table-responsive p-0 crud-card-body" id="roles-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" modelname="role" label="{{ ucfirst(__('PkgAutorisation::role.name')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('role-table-tbody')
            @foreach ($roles_data as $role)
                <tr id="role-row-{{$role->id}}">
                    <td>
                     <span @if(strlen($role->name) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $role->name }}" 
                        @endif>
                        {{ Str::limit($role->name, 40) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-role')
                        @can('view', $role)
                            <a href="{{ route('roles.show', ['role' => $role->id]) }}" data-id="{{$role->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-role')
                        @can('update', $role)
                            <a href="{{ route('roles.edit', ['role' => $role->id]) }}" data-id="{{$role->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-role')
                        @can('delete', $role)
                            <form class="context-state" action="{{ route('roles.destroy',['role' => $role->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$role->id}}">
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
    @section('role-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $roles_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>