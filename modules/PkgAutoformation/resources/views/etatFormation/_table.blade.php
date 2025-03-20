{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatFormation-table')
<div class="card-body table-responsive p-0 crud-card-body" id="etatFormations-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" modelname="etatFormation" label="{{ ucfirst(__('PkgAutoformation::etatFormation.code')) }}" />
                <x-sortable-column field="nom" modelname="etatFormation" label="{{ ucfirst(__('PkgAutoformation::etatFormation.nom')) }}" />
                <x-sortable-column field="workflow_formation_id" modelname="etatFormation" label="{{ ucfirst(__('PkgAutoformation::workflowFormation.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatFormation-table-tbody')
            @foreach ($etatFormations_data as $etatFormation)
                <tr id="etatFormation-row-{{$etatFormation->id}}">
                    <td>
                     <span @if(strlen($etatFormation->code) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $etatFormation->code }}" 
                        @endif>
                        {{ Str::limit($etatFormation->code, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($etatFormation->nom) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $etatFormation->nom }}" 
                        @endif>
                        {{ Str::limit($etatFormation->nom, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($etatFormation->workflowFormation) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $etatFormation->workflowFormation }}" 
                        @endif>
                        {{ Str::limit($etatFormation->workflowFormation, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-etatFormation')
                        @can('view', $etatFormation)
                            <a href="{{ route('etatFormations.show', ['etatFormation' => $etatFormation->id]) }}" data-id="{{$etatFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-etatFormation')
                        @can('update', $etatFormation)
                            <a href="{{ route('etatFormations.edit', ['etatFormation' => $etatFormation->id]) }}" data-id="{{$etatFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-etatFormation')
                        @can('delete', $etatFormation)
                            <form class="context-state" action="{{ route('etatFormations.destroy',['etatFormation' => $etatFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$etatFormation->id}}">
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
    @section('etatFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>