{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="projets-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="titre" label="{{ ucfirst(__('PkgCreationProjet::projet.titre')) }}" />
                <x-sortable-column field="TransfertCompetence" label="{{ ucfirst(__('PkgCreationProjet::transfertCompetence.plural')) }}" />

                <x-sortable-column field="AffectationProjet" label="{{ ucfirst(__('PkgRealisationProjets::affectationProjet.plural')) }}" />

                <x-sortable-column field="formateur_id" label="{{ ucfirst(__('PkgFormation::formateur.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projets_data as $projet)
                <tr id="projet-row-{{$projet->id}}">
                    <td>@limit($projet->titre, 80)</td>
                    <td>
                        <ul>
                            @foreach ($projet->transfertCompetences as $transfertCompetence)
                                <li>{{ $transfertCompetence }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul>
                            @foreach ($projet->affectationProjets as $affectationProjet)
                                <li>{{ $affectationProjet }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>@limit($projet->formateur->nom ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-projet')
                            <a href="{{ route('projets.show', ['projet' => $projet->id]) }}" data-id="{{$projet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-projet')
                        @can('update', $projet)
                            <a href="{{ route('projets.edit', ['projet' => $projet->id]) }}" data-id="{{$projet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-projet')
                        @can('delete', $projet)
                            <form class="context-state" action="{{ route('projets.destroy',['projet' => $projet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$projet->id}}">
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
    @section('projet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $projets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>