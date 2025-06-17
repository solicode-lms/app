{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowFormation-table')
<div class="card-body p-0 crud-card-body" id="workflowFormations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $workflowFormations_permissions['edit-workflowFormation'] || $workflowFormations_permissions['destroy-workflowFormation'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="code" modelname="workflowFormation" label="{{ucfirst(__('PkgAutoformation::workflowFormation.code'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="titre" modelname="workflowFormation" label="{{ucfirst(__('PkgAutoformation::workflowFormation.titre'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="sys_color_id" modelname="workflowFormation" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('workflowFormation-table-tbody')
            @foreach ($workflowFormations_data as $workflowFormation)
                @php
                    $isEditable = $workflowFormations_permissions['edit-workflowFormation'] && $workflowFormations_permissionsByItem['update'][$workflowFormation->id];
                @endphp
                <tr id="workflowFormation-row-{{$workflowFormation->id}}" data-id="{{$workflowFormation->id}}">
                    <x-checkbox-row :item="$workflowFormation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowFormation->id}}" data-field="code"  data-toggle="tooltip" title="{{ $workflowFormation->code }}" >
                        {{ $workflowFormation->code }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowFormation->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $workflowFormation->titre }}" >
                        {{ $workflowFormation->titre }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowFormation->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $workflowFormation->sysColor }}" >
                        <x-badge 
                        :text="$workflowFormation->sysColor->name ?? ''" 
                        :background="$workflowFormation->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($workflowFormations_permissions['edit-workflowFormation'])
                        <x-action-button :entity="$workflowFormation" actionName="edit">
                        @if($workflowFormations_permissionsByItem['update'][$workflowFormation->id])
                            <a href="{{ route('workflowFormations.edit', ['workflowFormation' => $workflowFormation->id]) }}" data-id="{{$workflowFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($workflowFormations_permissions['show-workflowFormation'])
                        <x-action-button :entity="$workflowFormation" actionName="show">
                        @if($workflowFormations_permissionsByItem['view'][$workflowFormation->id])
                            <a href="{{ route('workflowFormations.show', ['workflowFormation' => $workflowFormation->id]) }}" data-id="{{$workflowFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$workflowFormation" actionName="delete">
                        @if($workflowFormations_permissions['destroy-workflowFormation'])
                        @if($workflowFormations_permissionsByItem['delete'][$workflowFormation->id])
                            <form class="context-state" action="{{ route('workflowFormations.destroy',['workflowFormation' => $workflowFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$workflowFormation->id}}">
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
    @section('workflowFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $workflowFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>