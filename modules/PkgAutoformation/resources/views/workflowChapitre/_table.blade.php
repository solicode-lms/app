{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowChapitre-table')
<div class="card-body table-responsive p-0 crud-card-body" id="workflowChapitres-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" modelname="workflowChapitre" label="{{ ucfirst(__('PkgAutoformation::workflowChapitre.code')) }}" />
                <x-sortable-column field="titre" modelname="workflowChapitre" label="{{ ucfirst(__('PkgAutoformation::workflowChapitre.titre')) }}" />
                <x-sortable-column field="sys_color_id" modelname="workflowChapitre" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('workflowChapitre-table-tbody')
            @foreach ($workflowChapitres_data as $workflowChapitre)
                <tr id="workflowChapitre-row-{{$workflowChapitre->id}}">
                    <td>
                     <span @if(strlen($workflowChapitre->code) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $workflowChapitre->code }}" 
                        @endif>
                        {{ Str::limit($workflowChapitre->code, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($workflowChapitre->titre) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $workflowChapitre->titre }}" 
                        @endif>
                        {{ Str::limit($workflowChapitre->titre, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($workflowChapitre->sysColor) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $workflowChapitre->sysColor }}" 
                        @endif>
                        {{ Str::limit($workflowChapitre->sysColor, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-workflowChapitre')
                        @can('view', $workflowChapitre)
                            <a href="{{ route('workflowChapitres.show', ['workflowChapitre' => $workflowChapitre->id]) }}" data-id="{{$workflowChapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-workflowChapitre')
                        @can('update', $workflowChapitre)
                            <a href="{{ route('workflowChapitres.edit', ['workflowChapitre' => $workflowChapitre->id]) }}" data-id="{{$workflowChapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-workflowChapitre')
                        @can('delete', $workflowChapitre)
                            <form class="context-state" action="{{ route('workflowChapitres.destroy',['workflowChapitre' => $workflowChapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$workflowChapitre->id}}">
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
    @section('workflowChapitre-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $workflowChapitres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>