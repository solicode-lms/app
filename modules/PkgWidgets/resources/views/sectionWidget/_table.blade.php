{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sectionWidget-table')
<div class="card-body p-0 crud-card-body" id="sectionWidgets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $sectionWidgets_permissions['edit-sectionWidget'] || $sectionWidgets_permissions['destroy-sectionWidget'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="8"  field="ordre" modelname="sectionWidget" label="{!!ucfirst(__('PkgWidgets::sectionWidget.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="10"  field="icone" modelname="sectionWidget" label="{!!ucfirst(__('PkgWidgets::sectionWidget.icone'))!!}" />
                <x-sortable-column :sortable="true" width="54"  field="titre" modelname="sectionWidget" label="{!!ucfirst(__('PkgWidgets::sectionWidget.titre'))!!}" />
                <x-sortable-column :sortable="true" width="10" field="sys_color_id" modelname="sectionWidget" label="{!!ucfirst(__('Core::sysColor.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sectionWidget-table-tbody')
            @foreach ($sectionWidgets_data as $sectionWidget)
                @php
                    $isEditable = $sectionWidgets_permissions['edit-sectionWidget'] && $sectionWidgets_permissionsByItem['update'][$sectionWidget->id];
                @endphp
                <tr id="sectionWidget-row-{{$sectionWidget->id}}" data-id="{{$sectionWidget->id}}">
                    <x-checkbox-row :item="$sectionWidget" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 8%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sectionWidget->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $sectionWidget->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 10%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sectionWidget->id}}" data-field="icone">
                        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                            <i class="{{ $sectionWidget->icone }}" ></i>
                        </div>

                    </td>
                    <td style="max-width: 54%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sectionWidget->id}}" data-field="titre">
                        {{ $sectionWidget->titre }}

                    </td>
                    <td style="max-width: 10%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sectionWidget->id}}" data-field="sys_color_id">
                        <x-badge 
                        :text="$sectionWidget->sysColor->name ?? ''" 
                        :background="$sectionWidget->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($sectionWidgets_permissions['edit-sectionWidget'])
                        <x-action-button :entity="$sectionWidget" actionName="edit">
                        @if($sectionWidgets_permissionsByItem['update'][$sectionWidget->id])
                            <a href="{{ route('sectionWidgets.edit', ['sectionWidget' => $sectionWidget->id]) }}" data-id="{{$sectionWidget->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($sectionWidgets_permissions['show-sectionWidget'])
                        <x-action-button :entity="$sectionWidget" actionName="show">
                        @if($sectionWidgets_permissionsByItem['view'][$sectionWidget->id])
                            <a href="{{ route('sectionWidgets.show', ['sectionWidget' => $sectionWidget->id]) }}" data-id="{{$sectionWidget->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$sectionWidget" actionName="delete">
                        @if($sectionWidgets_permissions['destroy-sectionWidget'])
                        @if($sectionWidgets_permissionsByItem['delete'][$sectionWidget->id])
                            <form class="context-state" action="{{ route('sectionWidgets.destroy',['sectionWidget' => $sectionWidget->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$sectionWidget->id}}">
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
    @section('sectionWidget-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sectionWidgets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>