{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="projets-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="titre" label="{{ ucfirst(__('PkgCreationProjet::projet.titre')) }}" />
                <x-sortable-column field="date_debut" label="{{ ucfirst(__('PkgCreationProjet::projet.date_debut')) }}" />
                <x-sortable-column field="date_fin" label="{{ ucfirst(__('PkgCreationProjet::projet.date_fin')) }}" />
                <x-sortable-column field="formateur_id" label="{{ ucfirst(__('PkgUtilisateurs::formateur.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projets_data as $projet)
                <tr>
                    <td>@limit($projet->titre, 80)</td>
                    <td>@limit($projet->date_debut, 80)</td>
                    <td>@limit($projet->date_fin, 80)</td>
                    <td>@limit($projet->formateur->nom ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-projet')
                            <a href="{{ route('projets.show', ['projet' => $projet->id]) }}" data-id="{{$projet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-projet')
                            <a href="{{ route('projets.edit', ['projet' => $projet->id]) }}" data-id="{{$projet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-projet')
                            <form class="context-state" action="{{ route('projets.destroy',['projet' => $projet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$projet->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-footer">
    @section('crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $projets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>