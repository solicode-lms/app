{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowTache-table')
<div class="card-body p-0 crud-card-body" id="workflowTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $workflowTaches_permissions['edit-workflowTache'] || $workflowTaches_permissions['destroy-workflowTache'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="5"  field="ordre" modelname="workflowTache" label="{{ucfirst(__('PkgGestionTaches::workflowTache.ordre'))}}" />
                <x-sortable-column :sortable="true" width="25.666666666666668"  field="code" modelname="workflowTache" label="{{ucfirst(__('PkgGestionTaches::workflowTache.code'))}}" />
                <x-sortable-column :sortable="true" width="25.666666666666668"  field="titre" modelname="workflowTache" label="{{ucfirst(__('PkgGestionTaches::workflowTache.titre'))}}" />
                <x-sortable-column :sortable="true" width="25.666666666666668" field="sys_color_id" modelname="workflowTache" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('workflowTache-table-tbody')
            @foreach ($workflowTaches_data as $workflowTache)
                @php
                    $isEditable = $workflowTaches_permissions['edit-workflowTache'] && $workflowTaches_permissionsByItem['update'][$workflowTache->id];
                @endphp
                <tr id="workflowTache-row-{{$workflowTache->id}}" data-id="{{$workflowTache->id}}">
                    <x-checkbox-row :item="$workflowTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowTache->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $workflowTache->ordre }}" >
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $workflowTache->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 25.666666666666668%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowTache->id}}" data-field="code"  data-toggle="tooltip" title="{{ $workflowTache->code }}" >
                        {{ $workflowTache->code }}

                    </td>
                    <td style="max-width: 25.666666666666668%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowTache->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $workflowTache->titre }}" >
                        {{ $workflowTache->titre }}

                    </td>
                    <td style="max-width: 25.666666666666668%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowTache->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $workflowTache->sysColor }}" >
                        <x-badge 
                        :text="$workflowTache->sysColor->name ?? ''" 
                        :background="$workflowTache->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($workflowTaches_permissions['edit-workflowTache'])
                        <x-action-button :entity="$workflowTache" actionName="edit">
                        @if($workflowTaches_permissionsByItem['update'][$workflowTache->id])
                            <a href="{{ route('workflowTaches.edit', ['workflowTache' => $workflowTache->id]) }}" data-id="{{$workflowTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($workflowTaches_permissions['show-workflowTache'])
                        <x-action-button :entity="$workflowTache" actionName="show">
                        @if($workflowTaches_permissionsByItem['view'][$workflowTache->id])
                            <a href="{{ route('workflowTaches.show', ['workflowTache' => $workflowTache->id]) }}" data-id="{{$workflowTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$workflowTache" actionName="delete">
                        @if($workflowTaches_permissions['destroy-workflowTache'])
                        @if($workflowTaches_permissionsByItem['delete'][$workflowTache->id])
                            <form class="context-state" action="{{ route('workflowTaches.destroy',['workflowTache' => $workflowTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$workflowTache->id}}">
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
    @section('workflowTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $workflowTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>