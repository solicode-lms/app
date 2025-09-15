{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysController-table')
<div class="card-body p-0 crud-card-body" id="sysControllers-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $sysControllers_permissions['edit-sysController'] || $sysControllers_permissions['destroy-sysController'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="20.5" field="sys_module_id" modelname="sysController" label="{!!ucfirst(__('Core::sysModule.singular'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="name" modelname="sysController" label="{!!ucfirst(__('Core::sysController.name'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="is_active" modelname="sysController" label="{!!ucfirst(__('Core::sysController.is_active'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="Permission" modelname="sysController" label="{!!ucfirst(__('PkgAutorisation::permission.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sysController-table-tbody')
            @foreach ($sysControllers_data as $sysController)
                @php
                    $isEditable = $sysControllers_permissions['edit-sysController'] && $sysControllers_permissionsByItem['update'][$sysController->id];
                @endphp
                <tr id="sysController-row-{{$sysController->id}}" data-id="{{$sysController->id}}">
                    <x-checkbox-row :item="$sysController" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysController->id}}" data-field="sys_module_id">
                        {{  $sysController->sysModule }}

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysController->id}}" data-field="name">
                        {{ $sysController->name }}

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysController->id}}" data-field="is_active">
                        <span class="{{ $sysController->is_active ? 'text-success' : 'text-danger' }}">
                            {{ $sysController->is_active ? 'Oui' : 'Non' }}
                        </span>

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class=" text-truncate" data-id="{{$sysController->id}}" data-field="Permission">
                        <ul>
                            @foreach ($sysController->controllerIdPermissions as $permission)
                                <li>{{$permission}} </li>
                            @endforeach
                        </ul>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($sysControllers_permissions['edit-sysController'])
                        <x-action-button :entity="$sysController" actionName="edit">
                        @if($sysControllers_permissionsByItem['update'][$sysController->id])
                            <a href="{{ route('sysControllers.edit', ['sysController' => $sysController->id]) }}" data-id="{{$sysController->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($sysControllers_permissions['show-sysController'])
                        <x-action-button :entity="$sysController" actionName="show">
                        @if($sysControllers_permissionsByItem['view'][$sysController->id])
                            <a href="{{ route('sysControllers.show', ['sysController' => $sysController->id]) }}" data-id="{{$sysController->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$sysController" actionName="delete">
                        @if($sysControllers_permissions['destroy-sysController'])
                        @if($sysControllers_permissionsByItem['delete'][$sysController->id])
                            <form class="context-state" action="{{ route('sysControllers.destroy',['sysController' => $sysController->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$sysController->id}}">
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
    @section('sysController-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sysControllers_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>