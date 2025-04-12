{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowTache-table')
<div class="card-body table-responsive p-0 crud-card-body" id="workflowTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="42.5"  field="code" modelname="workflowTache" label="{{ ucfirst(__('PkgGestionTaches::workflowTache.code')) }}" />
                <x-sortable-column width="42.5"  field="titre" modelname="workflowTache" label="{{ ucfirst(__('PkgGestionTaches::workflowTache.titre')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('workflowTache-table-tbody')
            @foreach ($workflowTaches_data as $workflowTache)
                <tr id="workflowTache-row-{{$workflowTache->id}}">
                    <td style="max-width: 42.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowTache->code }}" >
                    <x-field :data="$workflowTache" field="code">
                        {{ $workflowTache->code }}
                    </x-field>
                    </td>
                    <td style="max-width: 42.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowTache->titre }}" >
                    <x-field :data="$workflowTache" field="titre">
                        {{ $workflowTache->titre }}
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
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$workflowTache->id}}">
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