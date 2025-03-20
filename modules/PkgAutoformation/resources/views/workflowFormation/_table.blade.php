{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowFormation-table')
<div class="card-body table-responsive p-0 crud-card-body" id="workflowFormations-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" modelname="workflowFormation" label="{{ ucfirst(__('PkgAutoformation::workflowFormation.code')) }}" />
                <x-sortable-column field="titre" modelname="workflowFormation" label="{{ ucfirst(__('PkgAutoformation::workflowFormation.titre')) }}" />
                <x-sortable-column field="sys_color_id" modelname="workflowFormation" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('workflowFormation-table-tbody')
            @foreach ($workflowFormations_data as $workflowFormation)
                <tr id="workflowFormation-row-{{$workflowFormation->id}}">
                    <td>
                     <span @if(strlen($workflowFormation->code) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $workflowFormation->code }}" 
                        @endif>
                        {{ Str::limit($workflowFormation->code, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($workflowFormation->titre) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $workflowFormation->titre }}" 
                        @endif>
                        {{ Str::limit($workflowFormation->titre, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($workflowFormation->sysColor) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $workflowFormation->sysColor }}" 
                        @endif>
                        {{ Str::limit($workflowFormation->sysColor, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-workflowFormation')
                        @can('view', $workflowFormation)
                            <a href="{{ route('workflowFormations.show', ['workflowFormation' => $workflowFormation->id]) }}" data-id="{{$workflowFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-workflowFormation')
                        @can('update', $workflowFormation)
                            <a href="{{ route('workflowFormations.edit', ['workflowFormation' => $workflowFormation->id]) }}" data-id="{{$workflowFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-workflowFormation')
                        @can('delete', $workflowFormation)
                            <form class="context-state" action="{{ route('workflowFormations.destroy',['workflowFormation' => $workflowFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$workflowFormation->id}}">
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
    @section('workflowFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $workflowFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>