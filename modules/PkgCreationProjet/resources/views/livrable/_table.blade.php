{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="livrables-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nature_livrable_id" modelname="livrable" label="{{ ucfirst(__('PkgCreationProjet::natureLivrable.singular')) }}" />
                <x-sortable-column field="titre" modelname="livrable" label="{{ ucfirst(__('PkgCreationProjet::livrable.titre')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('livrable-table-tbody')
            @foreach ($livrables_data as $livrable)
                <tr id="livrable-row-{{$livrable->id}}">
                    <td>@limit($livrable->natureLivrable, 50)</td>
                    <td>@limit($livrable->titre, 50)</td>
                    <td class="text-right">

                        @can('show-livrable')
                        @can('view', $livrable)
                            <a href="{{ route('livrables.show', ['livrable' => $livrable->id]) }}" data-id="{{$livrable->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-livrable')
                        @can('update', $livrable)
                            <a href="{{ route('livrables.edit', ['livrable' => $livrable->id]) }}" data-id="{{$livrable->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-livrable')
                        @can('delete', $livrable)
                            <form class="context-state" action="{{ route('livrables.destroy',['livrable' => $livrable->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$livrable->id}}">
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
    @section('livrable-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $livrables_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>