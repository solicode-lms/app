{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="transfertCompetences-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="competence_id" label="{{ ucfirst(__('PkgCompetences::competence.singular')) }}" />
                <x-sortable-column field="niveau_difficulte_id" label="{{ ucfirst(__('PkgCompetences::niveauDifficulte.singular')) }}" />
                <x-sortable-column field="note" label="{{ ucfirst(__('PkgCreationProjet::transfertCompetence.note')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transfertCompetences_data as $transfertCompetence)
                <tr id="transfertCompetence-row-{{$transfertCompetence->id}}">
                    <td>@limit($transfertCompetence->competence->code ?? '-', 80)</td>
                    <td>@limit($transfertCompetence->niveauDifficulte->nom ?? '-', 80)</td>
                    <td>@limit($transfertCompetence->note, 80)</td>
                    <td class="text-right">
                        @can('show-transfertCompetence')
                            <a href="{{ route('transfertCompetences.show', ['transfertCompetence' => $transfertCompetence->id]) }}" data-id="{{$transfertCompetence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-transfertCompetence')
                        @can('update', $transfertCompetence)
                            <a href="{{ route('transfertCompetences.edit', ['transfertCompetence' => $transfertCompetence->id]) }}" data-id="{{$transfertCompetence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-transfertCompetence')
                        @can('delete', $transfertCompetence)
                            <form class="context-state" action="{{ route('transfertCompetences.destroy',['transfertCompetence' => $transfertCompetence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$transfertCompetence->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-footer">
    @section('transfertCompetence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $transfertCompetences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>