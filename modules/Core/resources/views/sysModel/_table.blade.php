{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysModel-table')
<div class="card-body p-0 crud-card-body" id="sysModels-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $sysModels_permissions['edit-sysModel'] || $sysModels_permissions['destroy-sysModel'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="20.5"  field="name" modelname="sysModel" label="{!!ucfirst(__('Core::sysModel.name'))!!}" />
                <x-sortable-column :sortable="true" width="20.5" field="sys_module_id" modelname="sysModel" label="{!!ucfirst(__('Core::sysModule.singular'))!!}" />
                <x-sortable-column :sortable="true" width="20.5" field="sys_color_id" modelname="sysModel" label="{!!ucfirst(__('Core::sysColor.singular'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="icone" modelname="sysModel" label="{!!ucfirst(__('Core::sysModel.icone'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sysModel-table-tbody')
            @foreach ($sysModels_data as $sysModel)
                @php
                    $isEditable = $sysModels_permissions['edit-sysModel'] && $sysModels_permissionsByItem['update'][$sysModel->id];
                @endphp
                <tr id="sysModel-row-{{$sysModel->id}}" data-id="{{$sysModel->id}}">
                    <x-checkbox-row :item="$sysModel" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysModel->id}}" data-field="name"  data-toggle="tooltip" title="{{ $sysModel->name }}" >
                        {{ $sysModel->name }}

                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysModel->id}}" data-field="sys_module_id"  data-toggle="tooltip" title="{{ $sysModel->sysModule }}" >
                        {{  $sysModel->sysModule }}

                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysModel->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $sysModel->sysColor }}" >
                        <x-badge 
                        :text="$sysModel->sysColor->name ?? ''" 
                        :background="$sysModel->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysModel->id}}" data-field="icone"  data-toggle="tooltip" title="{{ $sysModel->icone }}" >
                        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                            <i class="{{ $sysModel->icone }}" ></i>
                        </div>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($sysModels_permissions['edit-sysModel'])
                        <x-action-button :entity="$sysModel" actionName="edit">
                        @if($sysModels_permissionsByItem['update'][$sysModel->id])
                            <a href="{{ route('sysModels.edit', ['sysModel' => $sysModel->id]) }}" data-id="{{$sysModel->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($sysModels_permissions['show-sysModel'])
                        <x-action-button :entity="$sysModel" actionName="show">
                        @if($sysModels_permissionsByItem['view'][$sysModel->id])
                            <a href="{{ route('sysModels.show', ['sysModel' => $sysModel->id]) }}" data-id="{{$sysModel->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$sysModel" actionName="delete">
                        @if($sysModels_permissions['destroy-sysModel'])
                        @if($sysModels_permissionsByItem['delete'][$sysModel->id])
                            <form class="context-state" action="{{ route('sysModels.destroy',['sysModel' => $sysModel->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$sysModel->id}}">
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
    @section('sysModel-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sysModels_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>