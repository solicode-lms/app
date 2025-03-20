{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatChapitre-table')
<div class="card-body table-responsive p-0 crud-card-body" id="etatChapitres-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" modelname="etatChapitre" label="{{ ucfirst(__('PkgAutoformation::etatChapitre.code')) }}" />
                <x-sortable-column field="nom" modelname="etatChapitre" label="{{ ucfirst(__('PkgAutoformation::etatChapitre.nom')) }}" />
                <x-sortable-column field="workflow_chapitre_id" modelname="etatChapitre" label="{{ ucfirst(__('PkgAutoformation::workflowChapitre.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatChapitre-table-tbody')
            @foreach ($etatChapitres_data as $etatChapitre)
                <tr id="etatChapitre-row-{{$etatChapitre->id}}">
                    <td>
                     <span @if(strlen($etatChapitre->code) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $etatChapitre->code }}" 
                        @endif>
                        {{ Str::limit($etatChapitre->code, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($etatChapitre->nom) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $etatChapitre->nom }}" 
                        @endif>
                        {{ Str::limit($etatChapitre->nom, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($etatChapitre->workflowChapitre) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $etatChapitre->workflowChapitre }}" 
                        @endif>
                        {{ Str::limit($etatChapitre->workflowChapitre, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-etatChapitre')
                        @can('view', $etatChapitre)
                            <a href="{{ route('etatChapitres.show', ['etatChapitre' => $etatChapitre->id]) }}" data-id="{{$etatChapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-etatChapitre')
                        @can('update', $etatChapitre)
                            <a href="{{ route('etatChapitres.edit', ['etatChapitre' => $etatChapitre->id]) }}" data-id="{{$etatChapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-etatChapitre')
                        @can('delete', $etatChapitre)
                            <form class="context-state" action="{{ route('etatChapitres.destroy',['etatChapitre' => $etatChapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$etatChapitre->id}}">
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
    @section('etatChapitre-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatChapitres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>