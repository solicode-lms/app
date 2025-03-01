{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="niveauDifficultes-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" modelname="niveauDifficulte" label="{{ ucfirst(__('PkgCompetences::niveauDifficulte.nom')) }}" />
                <x-sortable-column field="formateur_id" modelname="niveauDifficulte" label="{{ ucfirst(__('PkgCompetences::niveauDifficulte.formateur_id')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('niveauDifficulte-table-tbody')
            @foreach ($niveauDifficultes_data as $niveauDifficulte)
                <tr id="niveauDifficulte-row-{{$niveauDifficulte->id}}">
                    <td>@limit($niveauDifficulte->nom, 50)</td>
                    <td>@limit($niveauDifficulte->formateur, 50)</td>
                    <td class="text-right">

                        @can('show-niveauDifficulte')
                        @can('view', $niveauDifficulte)
                            <a href="{{ route('niveauDifficultes.show', ['niveauDifficulte' => $niveauDifficulte->id]) }}" data-id="{{$niveauDifficulte->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-niveauDifficulte')
                        @can('update', $niveauDifficulte)
                            <a href="{{ route('niveauDifficultes.edit', ['niveauDifficulte' => $niveauDifficulte->id]) }}" data-id="{{$niveauDifficulte->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-niveauDifficulte')
                        @can('delete', $niveauDifficulte)
                            <form class="context-state" action="{{ route('niveauDifficultes.destroy',['niveauDifficulte' => $niveauDifficulte->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$niveauDifficulte->id}}">
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

<div class="card-footer">
    @section('niveauDifficulte-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $niveauDifficultes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>