{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowProjet-table')
<div class="card-body p-0 crud-card-body" id="workflowProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $workflowProjets_permissions['edit-workflowProjet'] || $workflowProjets_permissions['destroy-workflowProjet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="5"  field="ordre" modelname="workflowProjet" label="{{ucfirst(__('PkgRealisationProjets::workflowProjet.ordre'))}}" />
                <x-sortable-column :sortable="true" width="25.666666666666668"  field="code" modelname="workflowProjet" label="{{ucfirst(__('PkgRealisationProjets::workflowProjet.code'))}}" />
                <x-sortable-column :sortable="true" width="25.666666666666668"  field="titre" modelname="workflowProjet" label="{{ucfirst(__('PkgRealisationProjets::workflowProjet.titre'))}}" />
                <x-sortable-column :sortable="true" width="25.666666666666668" field="sys_color_id" modelname="workflowProjet" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('workflowProjet-table-tbody')
            @foreach ($workflowProjets_data as $workflowProjet)
                @php
                    $isEditable = $workflowProjets_permissions['edit-workflowProjet'] && $workflowProjets_permissionsByItem['update'][$workflowProjet->id];
                @endphp
                <tr id="workflowProjet-row-{{$workflowProjet->id}}" data-id="{{$workflowProjet->id}}">
                    <x-checkbox-row :item="$workflowProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowProjet->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $workflowProjet->ordre }}" >
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $workflowProjet->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 25.666666666666668%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowProjet->id}}" data-field="code"  data-toggle="tooltip" title="{{ $workflowProjet->code }}" >
                        {{ $workflowProjet->code }}

                    </td>
                    <td style="max-width: 25.666666666666668%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowProjet->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $workflowProjet->titre }}" >
                        {{ $workflowProjet->titre }}

                    </td>
                    <td style="max-width: 25.666666666666668%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowProjet->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $workflowProjet->sysColor }}" >
                        <x-badge 
                        :text="$workflowProjet->sysColor->name ?? ''" 
                        :background="$workflowProjet->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($workflowProjets_permissions['edit-workflowProjet'])
                        <x-action-button :entity="$workflowProjet" actionName="edit">
                        @if($workflowProjets_permissionsByItem['update'][$workflowProjet->id])
                            <a href="{{ route('workflowProjets.edit', ['workflowProjet' => $workflowProjet->id]) }}" data-id="{{$workflowProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($workflowProjets_permissions['show-workflowProjet'])
                        <x-action-button :entity="$workflowProjet" actionName="show">
                        @if($workflowProjets_permissionsByItem['view'][$workflowProjet->id])
                            <a href="{{ route('workflowProjets.show', ['workflowProjet' => $workflowProjet->id]) }}" data-id="{{$workflowProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$workflowProjet" actionName="delete">
                        @if($workflowProjets_permissions['destroy-workflowProjet'])
                        @if($workflowProjets_permissionsByItem['delete'][$workflowProjet->id])
                            <form class="context-state" action="{{ route('workflowProjets.destroy',['workflowProjet' => $workflowProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$workflowProjet->id}}">
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
    @section('workflowProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $workflowProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>