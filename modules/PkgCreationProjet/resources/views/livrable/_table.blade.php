{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="livrables-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="titre" label="{{ ucfirst(__('PkgCreationProjet::livrable.titre')) }}" />
                <x-sortable-column field="nature_livrable_id" label="{{ ucfirst(__('PkgCreationProjet::natureLivrable.singular')) }}" />
                <x-sortable-column field="projet_id" label="{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($livrables_data as $livrable)
                <tr>
                    <td>@limit($livrable->titre, 80)</td>
                    <td>@limit($livrable->natureLivrable->nom ?? '-', 80)</td>
                    <td>@limit($livrable->projet->titre ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-livrable')
                            <a href="{{ route('livrables.show', ['livrable' => $livrable->id]) }}" data-id="{{$livrable->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-livrable')
                            <a href="{{ route('livrables.edit', ['livrable' => $livrable->id]) }}" data-id="{{$livrable->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-livrable')
                            <form class="context-state" action="{{ route('livrables.destroy',['livrable' => $livrable->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$livrable->id}}">
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
        {{ $livrables_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>