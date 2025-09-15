{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('permission-table')
<div class="card-body p-0 crud-card-body" id="permissions-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $permissions_permissions['edit-permission'] || $permissions_permissions['destroy-permission'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="name" modelname="permission" label="{!!ucfirst(__('PkgAutorisation::permission.name'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="controller_id" modelname="permission" label="{!!ucfirst(__('Core::sysController.singular'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="roles" modelname="permission" label="{!!ucfirst(__('PkgAutorisation::role.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('permission-table-tbody')
            @foreach ($permissions_data as $permission)
                @php
                    $isEditable = $permissions_permissions['edit-permission'] && $permissions_permissionsByItem['update'][$permission->id];
                @endphp
                <tr id="permission-row-{{$permission->id}}" data-id="{{$permission->id}}">
                    <x-checkbox-row :item="$permission" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$permission->id}}" data-field="name">
                        {{ $permission->name }}

                    </td>
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$permission->id}}" data-field="controller_id">
                        {{  $permission->controller }}

                    </td>
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$permission->id}}" data-field="roles">
                        <ul>
                            @foreach ($permission->roles as $role)
                                <li @if(strlen($role) > 30) data-toggle="tooltip" title="{{$role}}"  @endif>@limit($role, 30)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($permissions_permissions['edit-permission'])
                        <x-action-button :entity="$permission" actionName="edit">
                        @if($permissions_permissionsByItem['update'][$permission->id])
                            <a href="{{ route('permissions.edit', ['permission' => $permission->id]) }}" data-id="{{$permission->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($permissions_permissions['show-permission'])
                        <x-action-button :entity="$permission" actionName="show">
                        @if($permissions_permissionsByItem['view'][$permission->id])
                            <a href="{{ route('permissions.show', ['permission' => $permission->id]) }}" data-id="{{$permission->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$permission" actionName="delete">
                        @if($permissions_permissions['destroy-permission'])
                        @if($permissions_permissionsByItem['delete'][$permission->id])
                            <form class="context-state" action="{{ route('permissions.destroy',['permission' => $permission->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$permission->id}}">
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
    @section('permission-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $permissions_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>