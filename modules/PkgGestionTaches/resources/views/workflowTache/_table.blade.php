{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowTache-table')
<div class="card-body table-responsive p-0 crud-card-body" id="workflowTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-workflowTache') || Auth::user()->can('destroy-workflowTache');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="5"  field="ordre" modelname="workflowTache" label="{{ ucfirst(__('PkgGestionTaches::workflowTache.ordre')) }}" />
                <x-sortable-column :sortable="true" width="25.666666666666668"  field="code" modelname="workflowTache" label="{{ ucfirst(__('PkgGestionTaches::workflowTache.code')) }}" />
                <x-sortable-column :sortable="true" width="25.666666666666668"  field="titre" modelname="workflowTache" label="{{ ucfirst(__('PkgGestionTaches::workflowTache.titre')) }}" />
                <x-sortable-column :sortable="true" width="25.666666666666668" field="sys_color_id" modelname="workflowTache" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('workflowTache-table-tbody')
            @foreach ($workflowTaches_data as $workflowTache)
                <tr id="workflowTache-row-{{$workflowTache->id}}" data-id="{{$workflowTache->id}}">
                    <x-checkbox-row :item="$workflowTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 5%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowTache->ordre }}" >
                    <x-field :entity="$workflowTache" field="ordre">
                        <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $workflowTache->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>
                    </x-field>
                    </td>
                    <td style="max-width: 25.666666666666668%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowTache->code }}" >
                    <x-field :entity="$workflowTache" field="code">
                        {{ $workflowTache->code }}
                    </x-field>
                    </td>
                    <td style="max-width: 25.666666666666668%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowTache->titre }}" >
                    <x-field :entity="$workflowTache" field="titre">
                        {{ $workflowTache->titre }}
                    </x-field>
                    </td>
                    <td style="max-width: 25.666666666666668%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowTache->sysColor }}" >
                    <x-field :entity="$workflowTache" field="sysColor">
                        <x-badge 
                        :text="$workflowTache->sysColor->name ?? ''" 
                        :background="$workflowTache->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-workflowTache')
                        @can('update', $workflowTache)
                            <a href="{{ route('workflowTaches.edit', ['workflowTache' => $workflowTache->id]) }}" data-id="{{$workflowTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-workflowTache')
                        @can('view', $workflowTache)
                            <a href="{{ route('workflowTaches.show', ['workflowTache' => $workflowTache->id]) }}" data-id="{{$workflowTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-workflowTache')
                        @can('delete', $workflowTache)
                            <form class="context-state" action="{{ route('workflowTaches.destroy',['workflowTache' => $workflowTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$workflowTache->id}}">
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
    @section('workflowTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $workflowTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>