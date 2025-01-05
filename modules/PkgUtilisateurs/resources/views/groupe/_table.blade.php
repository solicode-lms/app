{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="groupes-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" label="{{ ucfirst(__('PkgUtilisateurs::groupe.code')) }}" />
                <x-sortable-column field="nom" label="{{ ucfirst(__('PkgUtilisateurs::groupe.nom')) }}" />
                <x-sortable-column field="filiere_id" label="{{ ucfirst(__('PkgCompetences::filiere.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupes_data as $groupe)
                <tr>
                    <td>@limit($groupe->code, 80)</td>
                    <td>@limit($groupe->nom, 80)</td>
                    <td>@limit($groupe->filiere->code ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-groupe')
                            <a href="{{ route('groupes.show', ['groupe' => $groupe->id]) }}" data-id="{{$groupe->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-groupe')
                            <a href="{{ route('groupes.edit', ['groupe' => $groupe->id]) }}" data-id="{{$groupe->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-groupe')
                            <form class="context-state" action="{{ route('groupes.destroy',['groupe' => $groupe->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$groupe->id}}">
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
        {{ $groupes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>