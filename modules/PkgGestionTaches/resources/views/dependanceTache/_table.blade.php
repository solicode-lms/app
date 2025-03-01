{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="dependanceTaches-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="tache_id" modelname="dependanceTache" label="{{ ucfirst(__('PkgGestionTaches::dependanceTache.tache_id')) }}" />
                <x-sortable-column field="type_dependance_tache_id" modelname="dependanceTache" label="{{ ucfirst(__('PkgGestionTaches::dependanceTache.type_dependance_tache_id')) }}" />
                <x-sortable-column field="tache_cible_id" modelname="dependanceTache" label="{{ ucfirst(__('PkgGestionTaches::dependanceTache.tache_cible_id')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('dependanceTache-table-tbody')
            @foreach ($dependanceTaches_data as $dependanceTache)
                <tr id="dependanceTache-row-{{$dependanceTache->id}}">
                    <td>@limit($dependanceTache->tache, 50)</td>
                    <td>@limit($dependanceTache->typeDependanceTache, 50)</td>
                    <td>@limit($dependanceTache->tacheCible, 50)</td>
                    <td class="text-right">

                        @can('show-dependanceTache')
                        @can('view', $dependanceTache)
                            <a href="{{ route('dependanceTaches.show', ['dependanceTache' => $dependanceTache->id]) }}" data-id="{{$dependanceTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-dependanceTache')
                        @can('update', $dependanceTache)
                            <a href="{{ route('dependanceTaches.edit', ['dependanceTache' => $dependanceTache->id]) }}" data-id="{{$dependanceTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-dependanceTache')
                        @can('delete', $dependanceTache)
                            <form class="context-state" action="{{ route('dependanceTaches.destroy',['dependanceTache' => $dependanceTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$dependanceTache->id}}">
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
    @section('dependanceTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $dependanceTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>