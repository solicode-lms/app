{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-table')
<div class="card-body p-0 crud-card-body" id="roles-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $roles_permissions['edit-role'] || $roles_permissions['destroy-role'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="82"  field="name" modelname="role" label="{!!ucfirst(__('PkgAutorisation::role.name'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('role-table-tbody')
            @foreach ($roles_data as $role)
                @php
                    $isEditable = $roles_permissions['edit-role'] && $roles_permissionsByItem['update'][$role->id];
                @endphp
                <tr id="role-row-{{$role->id}}" data-id="{{$role->id}}">
                    <x-checkbox-row :item="$role" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$role->id}}" data-field="name">
                        {{ $role->name }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($roles_permissions['edit-role'])
                        <x-action-button :entity="$role" actionName="edit">
                        @if($roles_permissionsByItem['update'][$role->id])
                            <a href="{{ route('roles.edit', ['role' => $role->id]) }}" data-id="{{$role->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($roles_permissions['show-role'])
                        <x-action-button :entity="$role" actionName="show">
                        @if($roles_permissionsByItem['view'][$role->id])
                            <a href="{{ route('roles.show', ['role' => $role->id]) }}" data-id="{{$role->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$role" actionName="delete">
                        @if($roles_permissions['destroy-role'])
                        @if($roles_permissionsByItem['delete'][$role->id])
                            <form class="context-state" action="{{ route('roles.destroy',['role' => $role->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$role->id}}">
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
    @section('role-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $roles_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>