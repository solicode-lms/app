{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="typeDependanceTaches-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="titre" modelname="typeDependanceTache" label="{{ ucfirst(__('PkgGestionTaches::typeDependanceTache.titre')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('typeDependanceTache-table-tbody')
            @foreach ($typeDependanceTaches_data as $typeDependanceTache)
                <tr id="typeDependanceTache-row-{{$typeDependanceTache->id}}">
                    <td>@limit($typeDependanceTache->titre, 50)</td>
                    <td class="text-right">

                        @can('show-typeDependanceTache')
                        @can('view', $typeDependanceTache)
                            <a href="{{ route('typeDependanceTaches.show', ['typeDependanceTache' => $typeDependanceTache->id]) }}" data-id="{{$typeDependanceTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-typeDependanceTache')
                        @can('update', $typeDependanceTache)
                            <a href="{{ route('typeDependanceTaches.edit', ['typeDependanceTache' => $typeDependanceTache->id]) }}" data-id="{{$typeDependanceTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-typeDependanceTache')
                        @can('delete', $typeDependanceTache)
                            <form class="context-state" action="{{ route('typeDependanceTaches.destroy',['typeDependanceTache' => $typeDependanceTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$typeDependanceTache->id}}">
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
    @section('typeDependanceTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $typeDependanceTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>