{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysModule-table')
<div class="card-body p-0 crud-card-body" id="sysModules-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $sysModules_permissions['edit-sysModule'] || $sysModules_permissions['destroy-sysModule'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="name" modelname="sysModule" label="{{ucfirst(__('Core::sysModule.name'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="is_active" modelname="sysModule" label="{{ucfirst(__('Core::sysModule.is_active'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="sys_color_id" modelname="sysModule" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sysModule-table-tbody')
            @foreach ($sysModules_data as $sysModule)
                @php
                    $isEditable = $sysModules_permissions['edit-sysModule'] && $sysModules_permissionsByItem['update'][$sysModule->id];
                @endphp
                <tr id="sysModule-row-{{$sysModule->id}}" data-id="{{$sysModule->id}}">
                    <x-checkbox-row :item="$sysModule" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysModule->id}}" data-field="name"  data-toggle="tooltip" title="{{ $sysModule->name }}" >
                        {{ $sysModule->name }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysModule->id}}" data-field="is_active"  data-toggle="tooltip" title="{{ $sysModule->is_active }}" >
                        {{ $sysModule->is_active }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysModule->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $sysModule->sysColor }}" >
                        <x-badge 
                        :text="$sysModule->sysColor->name ?? ''" 
                        :background="$sysModule->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($sysModules_permissions['edit-sysModule'])
                        <x-action-button :entity="$sysModule" actionName="edit">
                        @if($sysModules_permissionsByItem['update'][$sysModule->id])
                            <a href="{{ route('sysModules.edit', ['sysModule' => $sysModule->id]) }}" data-id="{{$sysModule->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($sysModules_permissions['show-sysModule'])
                        <x-action-button :entity="$sysModule" actionName="show">
                        @if($sysModules_permissionsByItem['view'][$sysModule->id])
                            <a href="{{ route('sysModules.show', ['sysModule' => $sysModule->id]) }}" data-id="{{$sysModule->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$sysModule" actionName="delete">
                        @if($sysModules_permissions['destroy-sysModule'])
                        @if($sysModules_permissionsByItem['delete'][$sysModule->id])
                            <form class="context-state" action="{{ route('sysModules.destroy',['sysModule' => $sysModule->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$sysModule->id}}">
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
    @section('sysModule-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sysModules_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>