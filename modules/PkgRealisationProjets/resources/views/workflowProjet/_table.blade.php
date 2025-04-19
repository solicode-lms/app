{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowProjet-table')
<div class="card-body table-responsive p-0 crud-card-body" id="workflowProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-workflowProjet') || Auth::user()->can('destroy-workflowProjet');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="25.666666666666668"  field="code" modelname="workflowProjet" label="{{ ucfirst(__('PkgRealisationProjets::workflowProjet.code')) }}" />
                <x-sortable-column :sortable="true" width="25.666666666666668"  field="titre" modelname="workflowProjet" label="{{ ucfirst(__('PkgRealisationProjets::workflowProjet.titre')) }}" />
                <x-sortable-column :sortable="true" width="5"  field="ordre" modelname="workflowProjet" label="{{ ucfirst(__('PkgRealisationProjets::workflowProjet.ordre')) }}" />
                <x-sortable-column :sortable="true" width="25.666666666666668" field="sys_color_id" modelname="workflowProjet" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('workflowProjet-table-tbody')
            @foreach ($workflowProjets_data as $workflowProjet)
                <tr id="workflowProjet-row-{{$workflowProjet->id}}">
                    <x-checkbox-row :item="$workflowProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 25.666666666666668%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowProjet->code }}" >
                    <x-field :entity="$workflowProjet" field="code">
                        {{ $workflowProjet->code }}
                    </x-field>
                    </td>
                    <td style="max-width: 25.666666666666668%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowProjet->titre }}" >
                    <x-field :entity="$workflowProjet" field="titre">
                        {{ $workflowProjet->titre }}
                    </x-field>
                    </td>
                    <td style="max-width: 5%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowProjet->ordre }}" >
                    <x-field :entity="$workflowProjet" field="ordre">
                        {{ $workflowProjet->ordre }}
                    </x-field>
                    </td>
                    <td style="max-width: 25.666666666666668%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowProjet->sysColor }}" >
                    <x-field :entity="$workflowProjet" field="sysColor">
                        <x-badge 
                        :text="$workflowProjet->sysColor->name ?? ''" 
                        :background="$workflowProjet->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-workflowProjet')
                        @can('update', $workflowProjet)
                            <a href="{{ route('workflowProjets.edit', ['workflowProjet' => $workflowProjet->id]) }}" data-id="{{$workflowProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-workflowProjet')
                        @can('view', $workflowProjet)
                            <a href="{{ route('workflowProjets.show', ['workflowProjet' => $workflowProjet->id]) }}" data-id="{{$workflowProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-workflowProjet')
                        @can('delete', $workflowProjet)
                            <form class="context-state" action="{{ route('workflowProjets.destroy',['workflowProjet' => $workflowProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$workflowProjet->id}}">
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
    @section('workflowProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $workflowProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>